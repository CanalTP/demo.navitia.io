<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

$(document).ready(function() {
    $('#button_advanced_options').bind('click', function() {
        $('#fieldset_advanced_options').toggle('fast', function() {
            if ($('#fieldset_advanced_options').is(":visible")) {
                $('#button_advanced_options').html('<?php echo escape(translate('journey.search', 'hide_advanced_option')); ?>');
                $('#button_advanced_options').parent().css('float', 'none');
                $('#button_valid_search').val('<?php echo escape(translate('journey.search', 'go_now')); ?>');
                $('#button_valid_search').parent().css('float', 'none');
            } else {
                $('#button_advanced_options').html('<?php echo escape(translate('journey.search', 'more_option')); ?>');
                $('#button_advanced_options').parent().css('float', 'left');
                $('#button_valid_search').val('<?php echo escape(translate('journey.search', 'go_now')); ?>');
                $('#button_valid_search').parent().css('float', 'right');
            }
        });
    });
});

//----------------------------------------------------------------------------------------------------------------------
// FirstLetter

// Libellé des types de points (pour traduction)
var itemTypeLabels = {
    'STOP_AREA': '<?php echo escape(translate('object_identifiers', 'stop_area')); ?>',
    'STOP_POINT': '<?php echo escape(translate('object_identifiers', 'stop_point')); ?>',
    'ADMIN': '<?php echo escape(translate('object_identifiers', 'admin')); ?>',
    'ADDRESS': '<?php echo escape(translate('object_identifiers', 'address')); ?>',
    'POI': '<?php echo escape(translate('object_identifiers', 'poi')); ?>'
};

// FirstLetter depart
var fletter_origin = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo 'coverage/';
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_origin.setPlaceTypeLabels(itemTypeLabels);
fletter_origin.setCallbackFunctions({
    onResult: locateAutocompleteItemOnMap,
    onClick: locateAutocompleteItemOnMap,
    onErase: null
});
fletter_origin.setCallbackAttributes({
    markerType: 'origin'
});
fletter_origin.bind('journey_search_origin', 'FLOriginDivId');

// Firstletter arrivée
var fletter_destination = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo 'coverage/';
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_destination.setPlaceTypeLabels(itemTypeLabels);
fletter_destination.setCallbackFunctions({
    onResult: locateAutocompleteItemOnMap,
    onClick: locateAutocompleteItemOnMap,
    onErase: null
});
fletter_destination.setCallbackAttributes({
    markerType: 'destination'
});
fletter_destination.bind('journey_search_destination', 'FLDestinationDivId');

//----------------------------------------------------------------------------------------------------------------------
// OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

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

// Layer des marqueurs
var origin_marker_layer = new OpenLayers.Layer.Vector("Origin Marker Layer");
var destination_marker_layer = new OpenLayers.Layer.Vector("Destination Marker Layer");
var origin_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_origin.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};
var destination_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_destination.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};
map.addLayer(origin_marker_layer);
map.addLayer(destination_marker_layer);

// Handler d'événement Click
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
        
        if (originSelected == false) {
            var origin_point = new OpenLayers.Geometry.Point(clickedPosition.lon, clickedPosition.lat);
            var origin_marker_feature = new OpenLayers.Feature.Vector(origin_point, null, origin_marker_style);
            origin_marker_layer.removeAllFeatures();
            origin_marker_layer.addFeatures(origin_marker_feature);
            clickedPosition.transform(smeProjection, wgsProjection);
            $('#journey_search_origin_coords').val(clickedPosition.lon + ':' + clickedPosition.lat);
            originSelected = true;
        } else {
            var destination_point = new OpenLayers.Geometry.Point(clickedPosition.lon, clickedPosition.lat);
            var destination_marker_feature = new OpenLayers.Feature.Vector(destination_point, null, destination_marker_style);
            destination_marker_layer.removeAllFeatures();
            destination_marker_layer.addFeatures(destination_marker_feature);
            clickedPosition.transform(smeProjection, wgsProjection);
            $('#journey_search_destination_coords').val(clickedPosition.lon + ':' + clickedPosition.lat);
            $('#journey_search_form').submit();
        }
    }
});

// Ajout du handler à la map
var click = new OpenLayers.Control.Click();
map.addControl(click);
click.activate();

//! Permet de placer un marqueur sur la carte quand l'autocomplétion est utilisée
function locateAutocompleteItemOnMap(place, attributes)
{
    var coords = null;
    switch (place.type) {
        case 'ADDRESS':
            coords = place.address.coord;
            break;
        case 'STOP_POINT':
            coords = place.stop_point.coord;
            break;
        case 'STOP_AREA':
            coords = place.stop_area.coord;
            break;
        default:
            coords = null;
            break;    
    }
    if (coords != null) {
        var latlon = new OpenLayers.LonLat(coords.lon, coords.lat).transform(wgsProjection, smeProjection);
        var point = new OpenLayers.Geometry.Point(latlon.lon, latlon.lat);
        var marker_style = origin_marker_style;
        if (attributes.markerType == 'destination') {
            marker_style = destination_marker_style;
        }
        var feature = new OpenLayers.Feature.Vector(point, null, marker_style);
        
        if (attributes.markerType == 'destination') {
            destination_marker_layer.removeAllFeatures();
            destination_marker_layer.addFeatures(feature);
        } else {
            origin_marker_layer.removeAllFeatures();
            origin_marker_layer.addFeatures(feature);
        }

        map.setCenter(latlon, 16);
    }
}

//-->
</script>