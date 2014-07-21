<?php
    $paginationCount = 0;
    $tableRowList = array();
    if (is_object($route_schedule[0])) {
        $tableRowList = $route_schedule[0]->TableRowList;
        if (is_object(is_object($route_schedule[0]->TableRowList[0]))) {
            $paginationCount = count($route_schedule[0]->TableRowList[0]->StopTimeList);
        }
    }
?>

<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

//----------------------------------------------------------------------------------------------------------------------
// Pagination des résultats

var resultByPage = 10;
var paginationCurrentIndex = 0;
var paginationCount = <?php echo $paginationCount ?>;

function updatePagination()
{
    $('#route_schedule_table td').hide();
    for (var i = paginationCurrentIndex; i < (paginationCurrentIndex + resultByPage); i++) {
        $('#route_schedule_table .col_' + i).show();
    }
}

$(document).ready(function() {
    updatePagination();
    $('#pagination_prev').hide();
    if (paginationCount <= resultByPage) {
        $('#pagination_next').hide();    
    }
    $('#pagination_next').bind('click', function() {
        paginationCurrentIndex += resultByPage;
        $('#pagination_prev').show();
        if (paginationCurrentIndex > (paginationCount - resultByPage)) {
            paginationCurrentIndex = (paginationCount - resultByPage);
            $('#pagination_next').hide();
        }
        updatePagination();
    });
    $('#pagination_prev').bind('click', function() {
        paginationCurrentIndex -= resultByPage;
        $('#pagination_next').show();
        if (paginationCurrentIndex < 0) {
            paginationCurrentIndex = 0;
            $('#pagination_prev').hide();
        }
        updatePagination();
    });
});

//----------------------------------------------------------------------------------------------------------------------
// OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

<?php $region_data = data_get('region_coords', 'coords', request_get('RegionName')); ?>
var savedInitPosition = {
    'lon': <?php echo $region_data['init_coords']['lon']; ?>,
    'lat': <?php echo $region_data['init_coords']['lat']; ?>,
    'zoom': <?php echo $region_data['init_coords']['zoom']; ?>,
};

// Création d'objets de projection (conversion de coordonnées)
var wgsProjection = new OpenLayers.Projection("EPSG:4326"); // WGS84 projection
var smeProjection = new OpenLayers.Projection("EPSG:900913"); // Spherical Mercator projection

// Objet de position initiale de la carte
var initPosition = new OpenLayers.LonLat(savedInitPosition.lon, savedInitPosition.lat).transform(wgsProjection, smeProjection);

// Ajout d'une couche de carte (carto OpenStreetMap)
arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM);
map.addLayer(baseOSM);

// Limites de la carte visible
var map_bounds = new OpenLayers.Bounds();

// Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

// Layer des marqueurs
//var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");

// Layer des lignes
var line_layer = new OpenLayers.Layer.Vector("Line Layer");
map.addLayer(line_layer);

// Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");
map.addLayer(marker_layer);

// Styles lignes et marqueurs
var inner_line_style = { strokeColor: '#ff5500', strokeOpacity: 1, strokeWidth: 4 };
var outer_line_style = { strokeColor: '#ffffff', strokeOpacity: 1, strokeWidth: 9 };

var line_points = new Array();
var line_points_data = new Array();
var pointPosition = null;

// Marqueurs d'arrêts
var stop_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_line_point.png'); ?>",
    graphicWidth: 16,
    graphicHeight: 16,
    graphicXOffset: -8,
    graphicYOffset: -8
};

<?php
    foreach ($tableRowList as $row) {
        echo "pointPosition = new OpenLayers.LonLat(" . $row->StopPoint->Coord->Lon . ", " . $row->StopPoint->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
        echo "line_points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
        echo "line_points_data.push({uri: '" . $row->StopPoint->Uri . "', name: '" . addslashes($row->StopPoint->Name) ."'});\n";
        echo "map_bounds.extend(pointPosition);\n\n";
    }
?>

// Création du tracé et des points sur la ligne
for (stop_index in line_points) {
    var inner_journey_lines = new OpenLayers.Geometry.LineString(line_points);
    var outer_journey_lines = new OpenLayers.Geometry.LineString(line_points);
    
    var outer_line_features = new OpenLayers.Feature.Vector(outer_journey_lines, null, outer_line_style);
    line_layer.addFeatures(outer_line_features);
    
    var inner_line_features = new OpenLayers.Feature.Vector(inner_journey_lines, null, inner_line_style);
    line_layer.addFeatures(inner_line_features);
    
    var stop_marker_feature = new OpenLayers.Feature.Vector(line_points[stop_index], line_points_data[stop_index], stop_marker_style);
    marker_layer.addFeatures(stop_marker_feature);
}

map.zoomToExtent(map_bounds);

// Survol d'un arrêt pour en voir le nom
var hoverMarkerControl = new OpenLayers.Control.SelectFeature(
    marker_layer, {
        hover: true,
        highlightOnly: true,
        overFeature: function(feature) {
            var popup = new OpenLayers.Popup("stop_popup",
                feature.geometry.getBounds().getCenterLonLat(),
                new OpenLayers.Size(200, 100), feature.attributes.name, null, false, null);
            popup.panMapIfOutOfView = true;
            popup.autoSize = true;
            feature.popup = popup;
            map.addPopup(popup);
            var width = $('#stop_popup').css('width').replace('px', '');
            $('#stop_popup').css('margin-left', ((width / 2) * -1) + 'px');
        },
        outFeature: function(feature) {
            map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
        }
    }
);
map.addControl(hoverMarkerControl);
hoverMarkerControl.activate();

// Clic sur un arrêt pour en voir la grille horaire
var clickMarkerControl = new OpenLayers.Control.SelectFeature(
    marker_layer, {
        onSelect: function(feature) {
            window.location.href = "<?php url_link('schedule/departure_board'); ?>/<?php echo $board_summary['line_uri']; ?>/<?php echo $board_summary['route_uri']; ?>/"
                + feature.attributes.uri + "/<?php echo $board_summary['datetime']; ?>";
        }
    }
);
map.addControl(clickMarkerControl);
clickMarkerControl.activate();


//-->
</script>