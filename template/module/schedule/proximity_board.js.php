<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

$(document).ready(function() {
    $('#boardid_0').removeClass('hidden');
    $('#boardidlink_0 img').attr('src', '<?php img_src('/img/schedule_minus_button.png'); ?>');
    $('.show_timing').bind('click', function() {
        var id = $(this).attr('id').split('_');
        id = id[1];
        $('#boardid_' + id).toggleClass('hidden');
        if ($('#boardid_' + id).hasClass('hidden')) {
            $('#boardidlink_' + id + ' img').attr('src', '<?php img_src('/img/schedule_plus_button.png'); ?>');
        } else {
            $('#boardidlink_' + id + ' img').attr('src', '<?php img_src('/img/schedule_minus_button.png'); ?>');
        }
    });
});

//----------------------------------------------------------------------------------------------------------------------
//OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

var savedInitPosition = {
    'lon': <?php echo config_get('map', 'Init', 'CoordLon'); ?>,
    'lat': <?php echo config_get('map', 'Init', 'CoordLat'); ?>,
    'zoom': <?php echo config_get('map', 'Init', 'Zoom'); ?>
};

//Création d'objets de projection (conversion de coordonnées)
var wgsProjection = new OpenLayers.Projection("EPSG:4326"); // WGS84 projection
var smeProjection = new OpenLayers.Projection("EPSG:900913"); // Spherical Mercator projection

//Objet de position initiale de la carte
var initPosition = new OpenLayers.LonLat(savedInitPosition.lon, savedInitPosition.lat).transform(wgsProjection, smeProjection);

//Ajout d'une couche de carte (carto OpenStreetMap)
arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
            "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM);
map.addLayer(baseOSM);

//Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

//Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");

//Style des marqueurs
var coord_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_coord.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};
var stop_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_current_stop.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};

<?php $current_stop_point_coords = explode(':', urldecode(request_get('param', 0))); ?>

//Création du marqueur de l'arrêt en cours
var current_stop_position = new OpenLayers.LonLat(<?php echo $current_stop_point_coords[0]; ?>, <?php echo $current_stop_point_coords[1]; ?>).transform(wgsProjection, smeProjection);
var current_stop_point = new OpenLayers.Geometry.Point(current_stop_position.lon, current_stop_position.lat)
var current_stop_feature = new OpenLayers.Feature.Vector(current_stop_point, null, coord_marker_style);
marker_layer.addFeatures(current_stop_feature);

var proximity_markers = new Array();

<?php

$proximity_points = array();
foreach ($departure_boards as $board) {
    if (!in_array($board->StopPoint->Uri, $proximity_points)) {
        echo 'proximity_markers["' . $board->StopPoint->Uri . '"] = {name: "' . $board->StopPoint->Name . '", lon: ' . $board->StopPoint->Coord->Lon . ', lat: ' . $board->StopPoint->Coord->Lat . '}' . "\n";
        $proximity_points[] = $board->StopPoint->Uri;
    }
}
 
?>

for (var i in proximity_markers) {
    var proximity_position = new OpenLayers.LonLat(proximity_markers[i].lon, proximity_markers[i].lat).transform(wgsProjection, smeProjection);
    var proximity_point = new OpenLayers.Geometry.Point(proximity_position.lon, proximity_position.lat);
    var proximity_feature = new OpenLayers.Feature.Vector(proximity_point, null, stop_marker_style);
    marker_layer.addFeatures(proximity_feature);  
}

//Centrage sur l'arrêt en cours
map.setCenter(current_stop_position, <?php echo config_get('map', 'Schedule', 'DepartureBoardZoom'); ?>);

//Ajout du layer des marqueurs
map.addLayer(marker_layer);

//-->
</script>