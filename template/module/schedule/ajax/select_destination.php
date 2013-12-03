<div>
    <div class="field_text_container">
        <div class="field_padding">
            <label>Arrivée</label>
            <input id="schedule_search_destination_name" type="text" name="destination[name]" value="" disabled="disabled" />
            <input id="schedule_search_destination_extcode" type="hidden" name="destination[uri]" value="" />
        </div>
        <div class="clear"></div>
    </div>
    <div id="FLDestinationDivId"></div>
</div>
<div>
    <div class="field_text_container">
        <div class="field_padding">
            <label>Date</label>
            <input type="text" size="10" name="date" value="<?php echo $current_date_human; ?>" />
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="button_line" id="train_schedule_search_submit">
    <input type="submit" value="Valider" />
</div>