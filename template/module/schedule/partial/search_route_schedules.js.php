<script type="text/javascript">
<!--

$(document).ready(function() {
    $('#rs_network_selection').bind('change', function() {
        $('#rs_route_container').html('');
        eraseLinesOnMap();
        if ($(this).val() != '') {
            $.ajax({
                url: '<?php url_link('schedule/select_line/'); ?>' + $(this).val() + '/rs',
                success: function(data) {
                    $('#rs_line_container').html(data);
                }
            });
        }
    });
    $('#rs_line_selection').live('change', function() {
        eraseLinesOnMap();
        if ($(this).val() != '') {
            $.ajax({
                url: '<?php url_link('schedule/select_direction/'); ?>' + $(this).val() + '/rs',
                success: function(data) {
                    $('#rs_route_container').html(data);
                    $('#line_schedule_search_submit').html('<input type="submit" value="Valider" />');
                }
            });
        }
    });
    $('#rs_route_selection').live('change', function() {
        // Route sélectionnée, affichage sur la carte
        navitia_url = '/journey_pattern_points.json?filter=route.uri = ' + $(this).val();
        $.ajax({
            url: '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia'); ?><?php echo request_get('RegionName'); ?>' + navitia_url,
            dataType: 'json',
            success: function(data) {
                drawRouteOnMap(data.ptref.journey_pattern_points);
            }
        });
    });
});

//-->
</script>