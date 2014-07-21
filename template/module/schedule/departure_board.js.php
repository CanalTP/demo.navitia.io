<?php

$current_stop_point_coords = Nv2\Model\Entity\Geo\Coord::create();
if (is_object($departure_board[0])) {
    $current_stop_point_coords = $departure_board[0]->StopPoint->Coord;
}

?>
<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

//----------------------------------------------------------------------------------------------------------------------
// OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

var savedInitPosition = {
    'lon': <?php echo config_get('map', 'Init', 'CoordLon'); ?>,
    'lat': <?php echo config_get('map', 'Init', 'CoordLat'); ?>,
    'zoom': <?php echo config_get('map', 'Init', 'Zoom'); ?>
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

// Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

// Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");

// Style des marqueurs
var stop_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_current_stop.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};

// Création du marqueur de l'arrêt en cours
var current_stop_position = new OpenLayers.LonLat(<?php echo $current_stop_point_coords->Lon; ?>, <?php echo $current_stop_point_coords->Lat; ?>).transform(wgsProjection, smeProjection);
var current_stop_point = new OpenLayers.Geometry.Point(current_stop_position.lon, current_stop_position.lat);
var current_stop_feature = new OpenLayers.Feature.Vector(current_stop_point, null, stop_marker_style);
marker_layer.addFeatures(current_stop_feature);

// Centrage sur l'arrêt en cours
map.setCenter(current_stop_position, <?php echo config_get('map', 'Schedule', 'DepartureBoardZoom'); ?>);

// Ajout du layer des marqueurs
map.addLayer(marker_layer);

//-->
</script>