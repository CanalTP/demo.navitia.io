<form id="stops_schedules_form" method="post" action="<?php url_link('schedule/train_execute'); ?>">
    <fieldset>
        <legend>Rechercher les horaires entre deux arrêts</legend>
        <div>
            <div class="field_text_container">
                <div class="field_padding">
                    <label>Départ</label>
                    <input id="schedule_search_origin_name" type="text" name="origin[name]" value="" />
                    <input id="schedule_search_origin_extcode" type="hidden" name="origin[uri]" value="" />
                </div>
                <div class="clear"></div>
            </div>
            <div id="FLFromDivId"></div>
        </div>
        <div id="ss_destination_container"></div>
        <div class="button_line" id="stops_schedule_search_submit"></div>
    </fieldset>
</form>