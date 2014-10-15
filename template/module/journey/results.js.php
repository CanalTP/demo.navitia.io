<script type="text/javascript" src="<?php js_link('OpenLayers/OpenLayers.js'); ?>"></script>
<script type="text/javascript">
<!--

var sliderWidth = 0;
var sliderCursorWidth = 0;
var sliderSliding = false;

var from_id = '<?php echo addslashes(urldecode($journeySummary['from_id'])); ?>';
var from_name = '<?php echo addslashes(urldecode($journeySummary['from_name'])); ?>';
var to_id = '<?php echo addslashes(urldecode($journeySummary['to_id'])); ?>';
var to_name = '<?php echo addslashes(urldecode($journeySummary['to_name'])); ?>';
var datetime_represents = '<?php echo $journeySummary['datetime_represents']; ?>';

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
        if ($(this).parent().find('.avoid').hasClass('hide')) {
            $('.avoid').addClass('hide');
            $(this).parent().find('.avoid').removeClass('hide');
        } else {
            $(this).parent().find('.avoid').addClass('hide');
        }
    });
    $('.section_options .avoid a').live('click', function() {
        var id = $(this).attr('class').split(';');
        id = id[1];
        addAvoidParameter(id);
    });
});

function addAvoidParameter(id)
{
    this.avoidUris.push(id);
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
    'stop_area': '<?php echo escape(translate('object_identifiers', 'stop_area')); ?>',
    'stop_point': '<?php echo escape(translate('object_identifiers', 'stop_point')); ?>',
    'admin': '<?php echo escape(translate('object_identifiers', 'admin')); ?>',
    'address': '<?php echo escape(translate('object_identifiers', 'address')); ?>',
    'poi': '<?php echo escape(translate('object_identifiers', 'poi')); ?>'
};

// FirstLetter depart
var fletter_from = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo 'coverage/';
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_from.setPlaceTypeLabels(itemTypeLabels);
fletter_from.setCallbackFunctions({
    onResult: null,
    onClick: updateFromAutocomplete,
    onErase: null
});
fletter_from.bind('journey_search_from', 'FLFromDivId');

// Firstletter arrivée
var fletter_to = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo 'coverage/';
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_to.setPlaceTypeLabels(itemTypeLabels);
fletter_to.setCallbackFunctions({
    onResult: null,
    onClick: updateFromAutocomplete,
    onErase: null
});
fletter_to.bind('journey_search_to', 'FLToDivId');

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
    'public_transport': { strokeColor: '#ff5500', strokeOpacity: 1, strokeWidth: 4 },
    'street_network': { strokeColor: '#7476c4', strokeOpacity: 1, strokeWidth: 4 },
    'transfer': { strokeColor: '#5cb23b', strokeOpacity: 1, strokeWidth: 4 }
};
var outer_line_style = { strokeColor: '#ffffff', strokeOpacity: 1, strokeWidth: 9 };

var from_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_origin.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};
var to_marker_style = {
    externalGraphic: "<?php img_src('/img/marker_destination.png'); ?>",
    graphicWidth: 28,
    graphicHeight: 32,
    graphicXOffset: -12,
    graphicYOffset: -32
};

<?php
if (is_array($journeyResult['data']->journeys)) {
    foreach ($journeyResult['data']->journeys as $journeyIndex => $journey) {
        // Création des itinéraires
        echo "journey_points.push(new Array());\n";

        foreach ($journey->sections as $sectionIndex => $section) {
            // Création des sections
            echo "journey_points[" . $journeyIndex . "].push({
                type: '". $section->type ."',
                points: new Array()
            });";

            if ($section->type == 'public_transport') {
                // Création du point de départ TC
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->from->coord->lon . ", " . $section->from->coord->lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

                // Création des points intermédiaires TC
                if (is_array($section->stopDateTimes)) {
                    foreach ($section->stopDateTimes as $stopDateTime) {
                        echo "var pointPosition = new OpenLayers.LonLat(" . $stopDateTime->stopPoint->coord->lon . ", " . $stopDateTime->stopPoint->coord->lat . ").transform(wgsProjection, smeProjection);\n";
                        echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                        echo "map_bounds.extend(pointPosition);";
                    }
                }

                // Création du point d'arrivée TC
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->to->coord->lon . ", " . $section->to->coord->lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

            } else if ($section->type == 'street_network') {
                // Création des points marche à pied
                foreach ($section->geojson->coordinates as $coordItem) {
                    echo "var pointPosition = new OpenLayers.LonLat(" . $coordItem->lon . ", " . $coordItem->lat . ").transform(wgsProjection, smeProjection);\n";
                    echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                    echo "map_bounds.extend(pointPosition);";
                }
            } else if ($section->type == 'transfer') {
                // Création du point de départ de la correspondance
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->from->coord->lon . ", " . $section->from->coord->lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";

                // Création du point d'arrivée de la correspondance
                echo "var pointPosition = new OpenLayers.LonLat(" . $section->to->coord->lon . ", " . $section->to->coord->lat . ").transform(wgsProjection, smeProjection);\n";
                echo "journey_points[" . $journeyIndex . "][" . $sectionIndex . "].points.push(new OpenLayers.Geometry.Point(pointPosition.lon, pointPosition.lat));\n";
                echo "map_bounds.extend(pointPosition);";
            }
        }
    }
} else {
    echo "var from_id_data = from_id.split(':');\n";
    echo "var to_id_data = to_id.split(':');\n";
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
    var from_point = journey_points[current_journey_index][0].points[0];
    var last_section = journey_points[current_journey_index].length - 1;
    var last_point = journey_points[current_journey_index][last_section].points.length - 1;
    var to_point = journey_points[current_journey_index][last_section].points[last_point];
} else {
    if (from_id_data[0] == 'coord') {
        var from_position = new OpenLayers.LonLat(parseFloat(from_id_data[1], 10), parseFloat(from_id_data[2], 10)).transform(wgsProjection, smeProjection);
        map_bounds.extend(from_position);
        var from_point = new OpenLayers.Geometry.Point(from_position.lon, from_position.lat);
    }
    if (to_id_data[0] == 'coord') {
        var to_position = new OpenLayers.LonLat(parseFloat(to_id_data[1], 10), parseFloat(to_id_data[2], 10)).transform(wgsProjection, smeProjection);
        map_bounds.extend(to_position);
        var to_point = new OpenLayers.Geometry.Point(to_position.lon, to_position.lat);
    }
}
if (from_point) {
    var from_marker_feature = new OpenLayers.Feature.Vector(from_point, {type: 'from'}, from_marker_style);
    marker_layer.addFeatures(from_marker_feature);
}
if (to_point) {
    var to_marker_feature = new OpenLayers.Feature.Vector(to_point, {type: 'to'}, to_marker_style);
    marker_layer.addFeatures(to_marker_feature);
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
        if (feature.attributes.type == 'from') {
            from_id = 'coord:' + feature_position.lon + ':' + feature_position.lat;
        } else if (feature.attributes.type == 'to') {
            to_id = 'coord:' + feature_position.lon + ':' + feature_position.lat;
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

        if (draggedFeature.attributes.type == 'from') {
            navitia_url += '/journeys?from=coord:' + feature_position.lon + ':' + feature_position.lat;
            navitia_url += '&to=' + to_id;
        } else if (draggedFeature.attributes.type == 'to') {
            navitia_url += '/journeys?from=' + from_id;
            navitia_url += '&to=coord:' + feature_position.lon + ':' + feature_position.lat;
        }
        navitia_url += '&datetime=<?php echo urldecode($journeySummary['datetime']); ?>';
    } else {
        navitia_url += '/journeys?from=' + from_id + '&to=' + to_id;
        navitia_url += '&datetime=' + currentJourneySearchTime;
    }

    navitia_url += '&datetime_represents=' + datetime_represents;

    for (var id_index in this.avoidUris) {
        navitia_url += '&forbidden_uris[]=' + this.avoidUris[id_index];
    }

    $.ajax({
        url: '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia'); ?>' + encodeURIComponent(navitia_url),
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
                if (section.type == 'street_network') {
                    for (coord_index in section.geojson.coordinates) {
                        var coord = section.geojson.coordinates[coord_index];
                        var point_lonlat = new OpenLayers.LonLat(coord[0], coord[1]).transform(wgsProjection, smeProjection);
                        journey_points[journey_index][section_index].points.push(new OpenLayers.Geometry.Point(point_lonlat.lon, point_lonlat.lat));
                    }
                } else if (section.type == 'public_transport') {
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
        if (journey_points[current_journey_index][section_index].type != 'crow_fly') {
            var inner_journey_line = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
            var outer_journey_line = new OpenLayers.Geometry.LineString(journey_points[current_journey_index][section_index].points);
            var outer_line_feature = new OpenLayers.Feature.Vector(outer_journey_line, null, outer_line_style);
            line_layer.addFeatures(outer_line_feature);
            var inner_line_feature = new OpenLayers.Feature.Vector(inner_journey_line, null, line_styles[journey_points[current_journey_index][section_index].type]);
            line_layer.addFeatures(inner_line_feature);
            // TODO : extend on each point
            //map_bounds.extend(new OpenLayers.LonLat(from_point.x, from_point.y));
        }
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
        var from_point = getFirstSectionPoint(journey_points[current_journey_index]);
        var to_point = getLastSectionPoint(journey_points[current_journey_index]);;
        var from_marker_feature = new OpenLayers.Feature.Vector(from_point, {type: 'from'}, from_marker_style);
        var to_marker_feature = new OpenLayers.Feature.Vector(to_point, {type: 'to'}, to_marker_style);
        marker_layer.addFeatures(from_marker_feature);
        marker_layer.addFeatures(to_marker_feature);
        map_bounds.extend(new OpenLayers.LonLat(from_point.x, from_point.y));
        map_bounds.extend(new OpenLayers.LonLat(to_point.x, to_point.y));
        map.zoomToExtent(map_bounds);
    }
}

function getFirstSectionPoint(journeySections)
{
    if (journeySections[0].type != 'crow_fly') {
        return journeySections[0].points[0];
    } else {
        return journeySections[1].points[0];
    }
}

function getLastSectionPoint(journeySections)
{
    var last_section = journeySections.length - 1;
    var section_points = new Array();
    if (journeySections[last_section].type != 'crow_fly') {
        section_points = journeySections[last_section].points;
    } else {
        section_points = journeySections[last_section - 1].points;
    }
    return section_points[section_points.length - 1];
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
    var url = '<?php echo url_link('journey/results'); ?>/' + from_name + '/' + from_id + '/' + to_name + '/' + to_id + '/' + datetime_represents + '/' + currentJourneySearchTime + '/?outputType=ajaxResult';

    for (var id_index in this.avoidUris) {
        url += '&avoidUri[]=' + escape(this.avoidUris[id_index]);
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
    $('#journey_title_from_label').html(from_name);
    $('#journey_title_to_label').html(to_name);
}

/**
 * Met à jour l'itinéraire (solution + feuille de route + carte) quand le point
 * de départ ou d'arrivée a été modifié grâce à l'autocomplétion
 */
function updateFromAutocomplete()
{
    resetCenter();
    current_journey_index = 0;
    from_id = $('#journey_search_from_id').val();
    from_name = $('#journey_search_from_name').val();
    to_id = $('#journey_search_to_id').val();
    to_name = $('#journey_search_to_name').val();
    drawJourneyLinePoints(current_journey_index);
    updateJourneyDetail();
    showJourneyLine();
}

//-->
</script>