<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

//----------------------------------------------------------------------------------------------------------------------
// OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

<?php $search_coords = explode(';', urldecode(request_get(1))); ?>

var savedInitPosition = {
    'lon': <?php echo $search_coords[0]; ?>,
    'lat': <?php echo $search_coords[1]; ?>,
    'zoom': 15
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

//Limites de la carte visible
var map_bounds = new OpenLayers.Bounds();

// Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

// Layer des lignes
var line_layer = new OpenLayers.Layer.Vector("Line Layer");
map.addLayer(line_layer);

// Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");
map.addLayer(marker_layer);

// Styles lignes et marqueurs
var inner_line_style = { strokeColor: '#ff5500', strokeOpacity: 1, strokeWidth: 4 };
var outer_line_style = { strokeColor: '#ffffff', strokeOpacity: 1, strokeWidth: 9 };

// Style des marqueurs
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

var line_points = new Array();
var line_points_data = new Array();
var pointPosition = null;

// Définition des points et lignes à tracer sur la carte 
<?php
    foreach ($line_point_list as $lineId => $line) {
        echo "line_points['" . $lineId . "'] = new Array();\n";
        echo "line_points_data['" . $lineId . "'] = new Array();\n";
        foreach ($line as $stop) {
            echo "pointPosition = new OpenLayers.LonLat(" . $stop->stopPoint->coord->lon . ", " . $stop->stopPoint->coord->lat . ").transform(wgsProjection, smeProjection);\n";
            echo "line_points['" . $lineId . "'].push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
            echo "line_points_data['" . $lineId . "'].push({id: '" . addslashes($stop->stopPoint->id) . "', name: '" . addslashes($stop->stopPoint->name) ."'});\n";
        }
    }
?>

for (var i = 0 in line_points) {
    var outer_journey_lines = new OpenLayers.Geometry.LineString(line_points[i]);
    var outer_line_features = new OpenLayers.Feature.Vector(outer_journey_lines, null, outer_line_style);
    line_layer.addFeatures(outer_line_features);
}

for (var i = 0 in line_points) {
    var inner_journey_lines = new OpenLayers.Geometry.LineString(line_points[i]);
    var inner_line_features = new OpenLayers.Feature.Vector(inner_journey_lines, null, inner_line_style);
    line_layer.addFeatures(inner_line_features);
    //var stop_marker_feature = new OpenLayers.Feature.Vector(line_points[stop_index], line_points_data[stop_index], stop_marker_style);
    //marker_layer.addFeatures(stop_marker_feature);
}

// Création du marqueur de l'arrêt en cours
var search_position = new OpenLayers.LonLat(<?php echo $search_coords[0]; ?>, <?php echo $search_coords[1]; ?>).transform(wgsProjection, smeProjection);
var search_point = new OpenLayers.Geometry.Point(search_position.lon, search_position.lat)
var search_feature = new OpenLayers.Feature.Vector(search_point, null, coord_marker_style);
marker_layer.addFeatures(search_feature);

//-->
</script>