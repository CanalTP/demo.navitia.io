<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

var sliderWidth = 0;
var sliderCursorWidth = 0;
var sliderSliding = false;

var origin_uri = '<?php echo addslashes(urldecode($journeySummary['origin_uri'])); ?>';
var origin_name = '<?php echo addslashes(urldecode($journeySummary['origin_name'])); ?>';
var destination_uri = '<?php echo addslashes(urldecode($journeySummary['destination_uri'])); ?>';
var destination_name = '<?php echo addslashes(urldecode($journeySummary['destination_name'])); ?>';
var clockwise = '<?php echo $journeySummary['clockwise']; ?>';

var avoidUris = new Array();

var current_journey_index = 0;
var currentJourneySearchTime = '<?php echo $journeySummary['datetime']; ?>';

$(document).ready(function() {
    // Clic sur voir tous les arrêts
    $('#show_all_stops_link').live('click', function() {
        $('.journey_section_stop').slideToggle('fast');
    });

    // Clic sur un onglet solution : affichage de la feuille de route
    $('.journey_detail_link').live('click', function() {
        $('.journey_section_stop').hide();
        $('.journey_block').addClass('hidden');

        var journey_data = $(this).attr('id').split(':');
        current_journey_index = parseInt(journey_data[1], 10);
        $('#journey_detail_number_' + journey_data[1]).removeClass('hidden');

        $('.journey_result_list li').removeClass('current');
        $(this).parent().addClass('current');

        showJourneyLine();
    });

    // Option clockwise
    $('#journey_clockwise').bind('change', function() {
        clockwise = $(this).val();
        updateJourneyDetail();
        drawJourneyLinePoints(0);
    });

    // Slider horaire
    sliderWidth = parseInt($('#journey_hour_slider').parent().css('width'), 10);
    sliderCursorWidth = parseInt($('#journey_hour_slider').css('width'), 10);
    sliderWidth -= sliderCursorWidth;

    var sliderLeftPos = $('#journey_hour_slider').offset().left;

    // Place le cuseur sur l'heure de la recherche au démarrage
    $('#journey_hour_slider').css('left', getHourPercentageValue($('#journey_hour_slider span').html()) + 'px');

    // Cache le tooltip de l'heure au démarrage
    $('#journey_hour_slider span').hide();
    // Affiche le tooltip au survol
    $('.journey_change_time_clockwise').bind('mouseover', function() {
        $('#journey_hour_slider span').show();
    });
    // Cache le tooltip
    $('.journey_change_time_clockwise').bind('mouseout', function() {
        $('#journey_hour_slider span').hide();
    });

    // Active le drag du curseur quand on appuie sur le bouton de la souris
    $('#journey_hour_slider').parent().bind('mousedown', function(e) {
        e.preventDefault();
        current_journey_index = 0;
        sliderSliding = true;
    });
    // Evenement lors du drag du curseur
    $('#journey_hour_slider').parent().bind('mousemove', function(e) {
        if (sliderSliding) {
            var newPos = (e.pageX - sliderLeftPos);
            if (newPos < (sliderCursorWidth / 2)) newPos = (sliderCursorWidth / 2);
            if (newPos > (sliderWidth + (sliderCursorWidth / 2))) newPos = (sliderWidth + (sliderCursorWidth / 2));
            newPos = newPos - (sliderCursorWidth / 2);

            $('#journey_hour_slider').css('left', newPos + 'px');
            $('#journey_hour_slider span').html(getHourFromPosition(newPos));
            currentJourneySearchTime = getIsoTimeFromValue(newPos);
        }
    });
    // Désactive le drag du curseur quand on lache le bouton de la souris
    $(document).bind('mouseup', function() {
        if (sliderSliding == true) {
            sliderSliding = false;
            updateJourneyDetail();
        }
    });

    $('#journey_hour_next').bind('click', function() {
        const minutesInADay = 60 * 24;
        var pos = parseInt($('#journey_hour_slider').css('left'), 10);
        pos += (sliderWidth / 24);
        if (pos > sliderWidth) pos = sliderWidth;
        currentJourneySearchTime = getIsoTimeFromValue(pos);
        $('#journey_hour_slider span').html(getHourFromPosition(pos));
        $('#journey_hour_slider').css('left', pos + 'px');
        showJourneyLine();
        updateJourneyDetail();
    });

    $('#journey_hour_prev').bind('click', function() {
        const minutesInADay = 60 * 24;
        var pos = parseInt($('#journey_hour_slider').css('left'), 10);
        pos -= (sliderWidth / 24);
        if (pos < 0) pos = 0;
        currentJourneySearchTime = getIsoTimeFromValue(pos);
        $('#journey_hour_slider span').html(getHourFromPosition(pos));
        $('#journey_hour_slider').css('left', pos + 'px');
        showJourneyLine();
        updateJourneyDetail();
    });

    $('.option_link').live('click', function() {
        $(this).parent().find('.avoid').toggleClass('hide');
    });
    $('.section_options .avoid a').live('click', function() {
        var uri = $(this).attr('class').split(';');
        uri = uri[1];
        addAvoidParameter(uri);
    });
});

function addAvoidParameter(uri)
{
    this.avoidUris.push(uri);
    resetCenter();
    current_journey_index = 0;
    drawJourneyLinePoints(current_journey_index);
    updateJourneyDetail();
    showJourneyLine();
}

function getHourPercentageValue(value)
{
    const minutesInADay = 60 * 24;
    value = value.split(':');
    var minutes = parseInt(value[0], 10) * 60 + parseInt(value[1], 10);
    var returnValue = (parseInt(minutes, 10) / minutesInADay) * sliderWidth;
    return returnValue;
}

function getHourFromPosition(pos)
{
    const minutesInADay = 60 * 24;
    var hour = parseInt(minutesInADay * pos / sliderWidth / 60, 10);
    var minute = parseInt(minutesInADay * pos / sliderWidth, 10) - (hour * 60);
    if (hour < 10) hour = "0" + hour;
    if (minute < 10) minute = "0" + minute;
    if (hour == "24") hour = "00";
    return hour + ':' + minute;
}

function getIsoTimeFromValue(value)
{
    var dateTimeData = currentJourneySearchTime.split('T');
    var hour = getHourFromPosition(value).split(':');

    return dateTimeData[0] + 'T' + hour[0] + hour[1] + '00';
}

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
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_origin.setPlaceTypeLabels(itemTypeLabels);
fletter_origin.setCallbackFunctions({
    onResult: null,
    onClick: updateFromAutocomplete,
    onErase: null
});
fletter_origin.bind('journey_search_origin', 'FLOriginDivId');

// Firstletter arrivée
var fletter_destination = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_destination.setPlaceTypeLabels(itemTypeLabels);
fletter_destination.setCallbackFunctions({
    onResult: null,
    onClick: updateFromAutocomplete,
    onErase: null
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

// Création des points de l'itinéraire (par itinéraire puis section)
var journey_points = new Array();

// Limites de la carte visible
var map_bounds = new OpenLayers.Bounds();

// Layer des lignes
var line_layer = new OpenLayers.Layer.Vector("Line Layer");
map.addLayer(line_layer);

// Layer des marqueurs
var marker_layer = new OpenLayers.Layer.Vector("Marker Layer");

// Styles lignes et marqueurs
var line_styles = {
    'PUBLIC_TRANSPORT': { strokeColor: '#ff5500', strokeOpacity: 1, strokeWidth: 4 },
    'STREET_NETWORK': { strokeColor: '#7476c4', strokeOpacity: 1, strokeWidth: 4 },
    'TRANSFER': { strokeColor: '#5cb23b', strokeOpacity: 1, strokeWidth: 4 }
};
var outer_line_style = { strokeColor: '#ffffff', strokeOpacity: 1, strokeWidth: 9 };

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

<?php
if (is_array($journeyResult['data']->JourneyList)) {
    foreach ($journeyResult['data']->JourneyList as $journeyIndex => $journey) {
        // Création des itinéraires
        echo "journey_points.push(new Array());\n";

        foreach ($journey->SectionList as $sectionIndex => $section) {
            // Création des sections
            echo "journey_points[" . $journeyIndex . "].push({
                type: '". $section->Type ."',
                points: new Array()
            });";

            if ($section->Type == 'PUBLIC_TRANSPORT') {
                // Création du point de départ TC
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->Origin->Coord->Lon . ", " . $section->Origin->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

                // Création des points intermédiaires TC
                if (is_array($section->IntermediateStopList)) {
                    foreach ($section->IntermediateStopList as $stopTime) {
                        echo "var pointPosition = new OpenLayers.LonLat(" . $stopTime->StopPoint->Coord->Lon . ", " . $stopTime->StopPoint->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
                        echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                        echo "map_bounds.extend(pointPosition);";
                    }
                }

                // Création du point d'arrivée TC
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->Destination->Coord->Lon . ", " . $section->Destination->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

            } else if ($section->Type == 'STREET_NETWORK') {
                // Création des points marche à pied
                foreach ($section->StreetNetwork->CoordinateList as $coordItem) {
                    echo "var pointPosition = new OpenLayers.LonLat(" . $coordItem->Lon . ", " . $coordItem->Lat . ").transform(wgsProjection, smeProjection);\n";
                    echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                    echo "map_bounds.extend(pointPosition);";
                }
            } else if ($section->Type == 'TRANSFER') {
                // Création du point de départ de la correspondance
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->Origin->Coord->Lon . ", " . $section->Origin->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

                // Création du point d'arrivée de la correspondance
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->Destination->Coord->Lon . ", " . $section->Destination->Coord->Lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";
            }
        }
    }
} else {
    echo "var origin_uri_data = origin_uri.split(':');\n";
    echo "var destination_uri_data = destination_uri.split(':');\n";
}
?>

// Création des tracés
for (section_index in journey_points[current_journey_index]) {
    var inner_journey_lines = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
    var outer_journey_lines = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
    var outer_line_features = new OpenLayers.Feature.Vector(inner_journey_lines, null, outer_line_style);
    line_layer.addFeatures(outer_line_features);

    var inner_line_features = new OpenLayers.Feature.Vector(outer_journey_lines, null, line_styles[journey_points[current_journey_index][section_index].type]);
    line_layer.addFeatures(inner_line_features);
}

// Création des marqueurs départ et arrivée
if (typeof(journey_points[current_journey_index]) != 'undefined') {
    var origin_point = journey_points[current_journey_index][0].points[0];
    var last_section = journey_points[current_journey_index].length - 1;
    var last_point = journey_points[current_journey_index][last_section].points.length - 1;
    var destination_point = journey_points[current_journey_index][last_section].points[last_point];
} else {
    if (origin_uri_data[0] == 'coord') {
        var origin_position = new OpenLayers.LonLat(parseFloat(origin_uri_data[1], 10), parseFloat(origin_uri_data[2], 10)).transform(wgsProjection, smeProjection);
        map_bounds.extend(origin_position);
        var origin_point = new OpenLayers.Geometry.Point(origin_position.lon, origin_position.lat);
    }
    if (destination_uri_data[0] == 'coord') {
        var destination_position = new OpenLayers.LonLat(parseFloat(destination_uri_data[1], 10), parseFloat(destination_uri_data[2], 10)).transform(wgsProjection, smeProjection);
        map_bounds.extend(destination_position);
        var destination_point = new OpenLayers.Geometry.Point(destination_position.lon, destination_position.lat);
    }
}
if (origin_point) {
    var origin_marker_feature = new OpenLayers.Feature.Vector(origin_point, {type: 'origin'}, origin_marker_style);
    marker_layer.addFeatures(origin_marker_feature);
}
if (destination_point) {
    var destination_marker_feature = new OpenLayers.Feature.Vector(destination_point, {type: 'destination'}, destination_marker_style);
    marker_layer.addFeatures(destination_marker_feature);
}

// Zoom adapté aux points
map.zoomToExtent(map_bounds);
map.addLayer(marker_layer);

// Détermine si les points sont actuellement déplacés
var draggedFeature = null;

// Ecouteur de déplacement des points
window.setInterval(dragListener, 125);

// Controleur de glisse des marqueurs
var drag_control = new OpenLayers.Control.DragFeature(marker_layer, {
    onComplete: function(feature) {
        draggedFeature = null;

        // Mise à jour des coordonnées des points
        var feature_position = new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y);
        feature_position.transform(smeProjection, wgsProjection);
        if (feature.attributes.type == 'origin') {
            origin_uri = 'coord:' + feature_position.lon + ':' + feature_position.lat;
        } else if (feature.attributes.type == 'destination') {
            destination_uri = 'coord:' + feature_position.lon + ':' + feature_position.lat;
        }

        // Recalcul de la feuille de route
        updateJourneyDetail();
    },

    onDrag: function(feature) {
        draggedFeature = feature;
    }
});
map.addControl(drag_control);
drag_control.activate();

/**
 * Effectue une requête AJAX pour récupérer le flux JSON Navitia permettant de mettre à jour
 * le tracé sur la carte pendant le déplacement des points ou du slider horaire
 */
function drawJourneyLinePoints(journey_index)
{
    var navitia_url = '';

    if (draggedFeature != null) {
        var feature_position = new OpenLayers.LonLat(draggedFeature.geometry.x, draggedFeature.geometry.y);
        feature_position.transform(smeProjection, wgsProjection);

        if (draggedFeature.attributes.type == 'origin') {
            navitia_url += '/journeys.json?origin=coord:' + feature_position.lon + ':' + feature_position.lat;
            navitia_url += '&destination=' + destination_uri;
        } else if (draggedFeature.attributes.type == 'destination') {
            navitia_url += '/journeys.json?origin=' + origin_uri;
            navitia_url += '&destination=coord:' + feature_position.lon + ':' + feature_position.lat;
        }
        navitia_url += '&datetime=<?php echo urldecode($journeySummary['datetime']); ?>';
    } else {
        navitia_url += '/journeys.json?origin=' + origin_uri + '&destination=' + destination_uri;
        navitia_url += '&datetime=' + currentJourneySearchTime;
    }

    if (clockwise == 'arrival') {
        navitia_url += '&clockwise=false';
    } else {
        navitia_url += '&clockwise=true';
    }

    for (var uri_index in this.avoidUris) {
        navitia_url += '&forbidden_uris[]=' + this.avoidUris[uri_index];
    }

    $.ajax({
        url: '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia'); ?><?php echo request_get('RegionName'); ?>' + encodeURIComponent(navitia_url),
        dataType: 'json',
        success: fillJourneyLinePoints
    });
}

/**
 *
 */
function fillJourneyLinePoints(data)
{
    if (data != null && typeof(data.journeys) != 'undefined') {
        // Solutions trouvées
        journey_points = new Array();
        for (journey_index in data.journeys) {
            journey_points.push(new Array());
            for (section_index in data.journeys[journey_index].sections) {
                var section = data.journeys[journey_index].sections[section_index];
                journey_points[journey_index].push({
                    type: section.type,
                    points: new Array()
                });
                if (section.type == 'STREET_NETWORK') {
                    for (coord_index in section.street_network.coordinates) {
                        var coord = section.street_network.coordinates[coord_index];
                        var point_lonlat = new OpenLayers.LonLat(coord.lon, coord.lat).transform(wgsProjection, smeProjection);
                        journey_points[journey_index][section_index].points.push(new OpenLayers.Geometry.Point(point_lonlat.lon, point_lonlat.lat));
                    }
                } else if (section.type == 'PUBLIC_TRANSPORT') {
                    for (stop_time_index in section.stop_date_times) {
                        var stop_time = section.stop_date_times[stop_time_index];
                        var point_lonlat = new OpenLayers.LonLat(stop_time.stop_point.coord.lon, stop_time.stop_point.coord.lat).transform(wgsProjection, smeProjection);
                        journey_points[journey_index][section_index].points.push(new OpenLayers.Geometry.Point(point_lonlat.lon, point_lonlat.lat));
                    }
                }
            }
        }
        drawLine();
    } else {
        // Pas de solution
        line_layer.removeAllFeatures();
    }
}

/**
 * Trace l'itinéraire sur la carte en fonction des points contenus dans journey_points
 */
function drawLine()
{
    line_layer.removeAllFeatures();
    for (section_index in journey_points[current_journey_index]) {
        var inner_journey_line = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
        var outer_journey_line = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
        var outer_line_feature = new OpenLayers.Feature.Vector(outer_journey_line, null, outer_line_style);
        line_layer.addFeatures(outer_line_feature);
        var inner_line_feature = new OpenLayers.Feature.Vector(inner_journey_line, null, line_styles[journey_points[current_journey_index][section_index].type]);
        line_layer.addFeatures(inner_line_feature);
        // TODO : extend on each point
        //map_bounds.extend(new OpenLayers.LonLat(origin_point.x, origin_point.y));
    }
    //map.zoomToExtent(map_bounds);
}

/**
 * Dessine les marqueurs de départ et d'arrivée en fonction des points contenus dans journeys_points
 */
function drawMarkers()
{
    if (typeof(journey_points[current_journey_index]) != 'undefined') {
        marker_layer.removeAllFeatures();
        var origin_point = journey_points[current_journey_index][0].points[0];
        var last_section = journey_points[current_journey_index].length - 1;
        var last_point = journey_points[current_journey_index][last_section].points.length - 1;
        var destination_point = journey_points[current_journey_index][last_section].points[last_point];
        var origin_marker_feature = new OpenLayers.Feature.Vector(origin_point, {type: 'origin'}, origin_marker_style);
        var destination_marker_feature = new OpenLayers.Feature.Vector(destination_point, {type: 'destination'}, destination_marker_style);
        marker_layer.addFeatures(origin_marker_feature);
        marker_layer.addFeatures(destination_marker_feature);
        map_bounds.extend(new OpenLayers.LonLat(origin_point.x, origin_point.y));
        map_bounds.extend(new OpenLayers.LonLat(destination_point.x, destination_point.y));
        map.zoomToExtent(map_bounds);
    }
}

function resetCenter()
{
    map_bounds = new OpenLayers.Bounds();
}

/**
 * Dessine les marqueurs et le tracé de l'itinéraire
 */
function showJourneyLine()
{
    drawLine();
    drawMarkers();
}

/**
 * Fonction appelée périodiquement pour mettre à jour le tracé sur la carte
 * si le slider (sliderSliding) ou les points (draggedFearture) sont déplacés
 */
function dragListener()
{
    if (draggedFeature != null || sliderSliding) {
        drawJourneyLinePoints(current_journey_index);
    }
}

/**
 * Effectue une requête AJAX pour récupérer et mettre à jour la feuille de route
 * quand le slider ou les points ont été déplacés
 */
function updateJourneyDetail()
{
    var url = '<?php echo url_link('journey/results'); ?>/' + origin_name + '/' + origin_uri + '/' + destination_name + '/' + destination_uri + '/' + clockwise + '/' + currentJourneySearchTime + '/?outputType=ajaxResult';

    for (var uri_index in this.avoidUris) {
        url += '&avoidUri[]=' + escape(this.avoidUris[uri_index]);
    }

    $.ajax({
        url: url,
        success: function(data) {
            $('#journey_result_container').html(data);
            resetCenter();
            drawMarkers();
            updateJourneyTitle();
        }
    });
}

function updateJourneyTitle()
{
    $('#journey_title_origin_label').html(origin_name);
    $('#journey_title_destination_label').html(destination_name);
}

/**
 * Met à jour l'itinéraire (solution + feuille de route + carte) quand le point
 * de départ ou d'arrivée a été modifié grâce à l'autocomplétion
 */
function updateFromAutocomplete()
{
    resetCenter();
    current_journey_index = 0;
    origin_uri = $('#journey_search_origin_uri').val();
    origin_name = $('#journey_search_origin_name').val();
    destination_uri = $('#journey_search_destination_uri').val();
    destination_name = $('#journey_search_destination_name').val();
    drawJourneyLinePoints(current_journey_index);
    updateJourneyDetail();
    showJourneyLine();
}

//-->
</script>