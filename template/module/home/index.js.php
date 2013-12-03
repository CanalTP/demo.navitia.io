<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

$(document).ready(function() {
    $('.region_choice').bind('click', function() {
        $('#region_form').submit();
    });
    $('.region_choice').bind('change', function() {
        hide_region_edges();
        show_region_edges($(this).attr('id'));
    });
    /*
    $('.region_line').bind('mouseout', function() {
        hide_region_edges();
    });
    */
});

//----------------------------------------------------------------------------------------------------------------------
// OpenLayerMap
map = new OpenLayers.Map("open_layer_map_container");

var savedInitPosition = {
    'lon': <?php echo config_get('map', 'Init', 'CoordLon'); ?>,
    'lat': <?php echo config_get('map', 'Init', 'CoordLat'); ?>,
    'zoom': <?php echo config_get('map', 'Init', 'Zoom'); ?>
};

var originSelected = false;

// Création d'objets de projection (conversion de coordonnées)
var wgsProjection = new OpenLayers.Projection("EPSG:4326"); // WGS84 projection
var smeProjection = new OpenLayers.Projection("EPSG:900913"); // Spherical Mercator projection

// Objet de position initiale de la carte
var initPosition = new OpenLayers.LonLat(savedInitPosition.lon, savedInitPosition.lat).transform(wgsProjection, smeProjection);

// Ajout d'une couche de carte (carto MapQuest)
var arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                "http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg",
                "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
var baseOSM = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM);
map.addLayer(baseOSM);

var kmlLayers = new Array();
var regionData = new Array();

<?php $region_data = data_get('region_coords', 'coords'); ?>

<?php foreach ($region_data as $region_id => $data) { ?>

    <?php /*
    kmlLayers['<?php echo $region_id; ?>'] = new OpenLayers.Layer.Vector("KML", {
        strategies: [new OpenLayers.Strategy.Fixed()],
        protocol: new OpenLayers.Protocol.HTTP({
            url: "data/region_edge/<?php echo $region_id; ?>.kml",
            format: new OpenLayers.Format.KML({
                extractStyles: true,
                extractAttributes: true,
                maxDepth: 2
            })
        })
    });
    
    map.addLayer(kmlLayers['<?php echo $region_id; ?>']);
    */?>
    
    regionData['<?php echo $region_id; ?>'] = {
        'lon': <?php echo $data['init_coords']['lon']; ?>,
        'lat': <?php echo $data['init_coords']['lat']; ?>,
        'zoom': <?php echo $data['init_coords']['zoom']; ?>
    };

<?php } ?>

hide_region_edges();

// Centre de la carte
map.setCenter(initPosition, savedInitPosition.zoom);

function show_region_edges(region_id)
{
    
    region_id = region_id.split('_');
    region_id = region_id[1];
    //this.kmlLayers[region_id].setVisibility(true);
    
    var initPosition = new OpenLayers.LonLat(this.regionData[region_id].lon, this.regionData[region_id].lat).transform(this.wgsProjection, this.smeProjection);
    this.map.setCenter(initPosition, this.regionData[region_id].zoom);
}

function hide_region_edges()
{
    /*
    for (var index in this.kmlLayers) {
        this.kmlLayers[index].setVisibility(false);
    }
    */
}

//-->
</script>