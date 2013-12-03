<form id="route_schedules_form" method="post" action="<?php url_link('schedule/line_execute'); ?>">
    <fieldset>
        <legend>Rechercher les horaires d'une ligne</legend>
        <?php if (isset($network_list) && count($network_list) > 1) { ?>
            <div id="rs_network_container">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label>Réseau</label>
                        <select name="network_uri" id="rs_network_selection">
                            <option value=""></option>
                            <?php foreach ($network_list as $network) { ?>
                                <option value="<?php echo $network->Uri; ?>"><?php echo $network->Name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div id="rs_line_container"></div>
        <?php } else { ?>
            <div id="rs_line_container">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label>Ligne</label>
                        <select name="line_uri" id="rs_line_selection">
                            <option value=""></option>
                            <?php foreach ($line_list as $line) { ?>
                                <option value="<?php echo $line->Uri; ?>">
                                    <?php echo $line->Code; ?> :
                                    <?php echo $line->Name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        <?php } ?>
        <div id="rs_route_container"></div>
        <div class="button_line" id="line_schedule_search_submit"></div>
    </fieldset>
</form>