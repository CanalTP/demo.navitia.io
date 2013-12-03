<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

$(document).ready(function() {

    $('#departure_boards_form').show();

    $('#search_form_menu a').bind('click', function() {
        $('#search_form_menu li').removeClass('current');
        $(this).parent().addClass('current');
        $('.search_form form').hide();
        var id = $(this).attr('id');
        switch (id) {
            case 'dp_menu_item':
                $('#departure_boards_form').show();
                break;
            case 'rs_menu_item':
                $('#route_schedules_form').show();
                break;
            case 'ss_menu_item':
                $('#stops_schedules_form').show();
                break;
        }
    });
});

//Libellé des types de points (pour traduction)
var itemTypeLabels = {
    'STOP_AREA': 'Zone d\'arrêt',
    'STOP_POINT': 'Point d\'arrêt',
};

//FirstLetter depart
var fletter_origin = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_origin.setPlaceTypeLabels(itemTypeLabels);
fletter_origin.setObjectFilter(['stop_area']);
fletter_origin.bind('schedule_search_origin_name', 'schedule_search_origin_extcode', 'FLOriginDivId');

// Firstletter arrivée
/*
var fletter_destination = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_destination.setItemTypeLabels(itemTypeLabels);
fletter_origin.setObjectFilter(['stop_area']);
fletter_destination.bind('schedule_search_destination_name', 'schedule_search_destination_extcode', 'FLDestinationDivId');
*/

//----------------------------------------------------------------------------------------------------------------------
//OpenLayerMap
map = new OpenLayers.Map("schedule_search_map");

<?php $region_data = data_get('region_coords', 'coords', request_get('RegionName')); ?>
var savedInitPosition = {
    'lon': <?php echo $region_data['init_coords']['lon']; ?>,
    'lat': <?php echo $region_data['init_coords']['lat']; ?>,
    'zoom': <?php echo $region_data['init_coords']['zoom']; ?>,
};

var originSelected = false;

// Création d'objets de projection (conversion de coordonnées)
var wgsProjection = new OpenLayers.Projection("EPSG:4326"); // WGS84 projection
var smeProjection = new OpenLayers.Projection("EPSG:900913"); // Spherical Mercator projection

// Objet de position initiale de la carte
var initPosition = new OpenLayers.LonLat(savedInitPosition.lon, savedInitPosition.lat).transform(wgsProjection, smeProjection);

// Ajout d'une couche de carte (carto MapQuest)
arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM);
map.addLayer(baseOSM);

// Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

// Limites de la carte visible
var map_bounds = new OpenLayers.Bounds();

// Layer des lignes
var line_layer = new OpenLayers.Layer.Vector("Line Layer");
map.addLayer(line_layer);

// Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");
map.addLayer(marker_layer);
var current_marker_layer = new OpenLayers.Layer.Vector("Current Marker Layer");
map.addLayer(current_marker_layer);

// Styles lignes et marqueurs
var inner_line_style = { strokeColor: '#ff5500', strokeOpacity: 1, strokeWidth: 4 };
var outer_line_style = { strokeColor: '#ffffff', strokeOpacity: 1, strokeWidth: 9 };

var point_position = null;
var line_points = new Array();

// Marqueurs d'arrêts
var stop_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_line_point.png'); ?>",
    graphicWidth: 16,
    graphicHeight: 16,
    graphicXOffset: -8,
    graphicYOffset: -8
};
//Style des marqueurs
var current_stop_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_current_stop.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -10,
    graphicYOffset: -32
};

//Handler d'événement Click
OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
    defaultHandlerOptions: {
        'single': true,
        'double': false,
        'pixelTolerance': 0,
        'stopSingle': false,
        'stopDouble': false
    },

    initialize: function(options) {
        this.handlerOptions = OpenLayers.Util.extend({}, this.defaultHandlerOptions);
        OpenLayers.Control.prototype.initialize.apply(this, arguments);
        this.handler = new OpenLayers.Handler.Click(this, {'click': this.trigger}, this.handlerOptions);
    },

    trigger: function(e) {
        var clickedPosition = map.getLonLatFromViewPortPx(e.xy);
        var point = new OpenLayers.Geometry.Point(clickedPosition.lon, clickedPosition.lat);
        var marker_feature = new OpenLayers.Feature.Vector(point, null, current_stop_marker_style);
        current_marker_layer.removeAllFeatures();
        current_marker_layer.addFeatures(marker_feature);
        clickedPosition.transform(smeProjection, wgsProjection);
        $('#schedule_search_stop_coords').val(clickedPosition.lon + ':' + clickedPosition.lat);
        $('#schedule_search_coord_form').submit();
    }
});

// Ajout du handler à la map
var click = new OpenLayers.Control.Click();
map.addControl(click);
click.activate();

function eraseLinesOnMap()
{
    line_points = new Array();
    line_layer.removeAllFeatures();
    marker_layer.removeAllFeatures();
    current_marker_layer.removeAllFeatures();
    map_bounds = new OpenLayers.Bounds();
    map.setCenter(initPosition, savedInitPosition.zoom);
}

function drawRouteOnMap(point_list)
{
    eraseLinesOnMap();
    
    for (var i in point_list) {
        point_position = new OpenLayers.LonLat(point_list[i].stop_point.coord.lon, point_list[i].stop_point.coord.lat).transform(wgsProjection, smeProjection);
        line_points.push(new OpenLayers.Geometry.Point(point_position.lon, point_position.lat));
        map_bounds.extend(point_position);

        var stop_marker_feature = new OpenLayers.Feature.Vector(line_points[i], null, stop_marker_style);
        marker_layer.addFeatures(stop_marker_feature);
    }

    var inner_journey_lines = new OpenLayers.Geometry.LineString(line_points);
    var outer_journey_lines = new OpenLayers.Geometry.LineString(line_points);
    
    var outer_line_features = new OpenLayers.Feature.Vector(outer_journey_lines, null, outer_line_style);
    line_layer.addFeatures(outer_line_features);
    
    var inner_line_features = new OpenLayers.Feature.Vector(inner_journey_lines, null, inner_line_style);
    line_layer.addFeatures(inner_line_features);

    map.zoomToExtent(map_bounds);
}

function drawStopOnMap(lon, lat)
{
    current_marker_layer.removeAllFeatures();
    var position = new OpenLayers.LonLat(lon, lat).transform(wgsProjection, smeProjection);
    var point = new OpenLayers.Geometry.Point(position.lon, position.lat);
    var stop_marker_feature = new OpenLayers.Feature.Vector(point, null, current_stop_marker_style);
    current_marker_layer.addFeatures(stop_marker_feature);
    map.setCenter(position, 15);
}

-->
</script>

<?php include(TEMPLATE_DIR . '/module/schedule/partial/search_departure_boards.js.php'); ?>
<?php include(TEMPLATE_DIR . '/module/schedule/partial/search_route_schedules.js.php'); ?>