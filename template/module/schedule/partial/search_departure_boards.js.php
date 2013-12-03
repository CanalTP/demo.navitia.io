<script type="text/javascript">
<!--

$(document).ready(function() {
    $('#db_network_selection').bind('change', function() {
        $('#db_route_container').html('');
        $('#db_stop_container').html('');
        eraseLinesOnMap();
        if ($(this).val() != '') {
            $.ajax({
                url: '<?php url_link('schedule/select_line/'); ?>' + $(this).val() + '/db',
                success: function(data) {
                    $('#db_line_container').html(data);
                }
            });
        }
    });
    $('#db_line_selection').live('change', function() {
        $('#db_stop_container').html('');
        eraseLinesOnMap();
        if ($(this).val() != '') {
            $.ajax({
                url: '<?php url_link('schedule/select_direction/'); ?>' + $(this).val() + '/db',
                success: function(data) {
                    $('#db_route_container').html(data);
                }
            });
        }
    });
    $('#db_route_selection').live('change', function() {
        // Route sélectionnée, affichage sur la carte
        navitia_url = '/journey_pattern_points.json?filter=route.uri = ' + $(this).val();
        $.ajax({
            url: '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia'); ?><?php echo request_get('RegionName'); ?>' + navitia_url,
            dataType: 'json',
            success: function(data) {
                drawRouteOnMap(data.ptref.journey_pattern_points);
            }
        });
        
        if ($(this).val() != '') {
            $.ajax({
                url: '<?php url_link('schedule/select_stop/'); ?>' + $(this).val() + '/db',
                success: function(data) {
                    $('#db_stop_container').html(data);
                    $('#stop_schedule_search_submit').html('<input type="submit" value="Horaires de cette ligne" name="line_schedule_submit" />');
                }
            });
        }
    });
    $('#db_stop_selection').live('change', function() {
        $('#db_stop_selection option:selected').each(function() {
            var coord = $(this).attr('id').split(':');
            coord = coord[1].split(',');
            drawStopOnMap(coord[0], coord[1]);
        });
        $('#stop_schedule_search_submit').html('<input type="submit" value="Horaires de cette ligne" name="line_schedule_submit" />&nbsp;<input type="submit" value="Horaires à cet arrêt" name="stop_schedule_submit" />');
    });
});

//-->
</script>