<form id="departure_boards_form" method="post" action="<?php url_link('schedule/stop_execute'); ?>">
    <fieldset>
        <legend>Rechercher des horaires</legend>
        <?php if (isset($network_list) && count($network_list) > 1) { ?>
            <div id="db_network_container">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label>Réseau</label>
                        <select name="network_uri" id="db_network_selection">
                            <option value=""></option>
                            <?php foreach ($network_list as $network) { ?>
                                <option value="<?php echo $network->id; ?>"><?php echo $network->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div id="db_line_container"></div>
        <?php } else { ?>
            <div id="db_line_container">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label>Ligne</label>
                        <select name="line_id" id="db_line_selection">
                            <option value=""></option>
                            <?php foreach ($line_list as $line) { ?>
                                <option value="<?php echo $line->id; ?>">
                                    <?php echo $line->code; ?> :
                                    <?php echo $line->name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        <?php } ?>
        <div id="db_route_container"></div>
        <div id="db_stop_container"></div>
        <div class="button_line" id="stop_schedule_search_submit"></div>
    </fieldset>
</form>
<form id="schedule_search_coord_form" class="hidden" action="<?php url_link('schedule/coord_execute'); ?>" method="post">
    <div>
        <input type="hidden" id="schedule_search_stop_coords" name="coords" value="" />
    </div>
</form>