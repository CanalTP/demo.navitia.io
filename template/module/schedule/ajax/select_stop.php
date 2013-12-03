<div id="<?php echo $type; ?>_stop_container">
    <div class="field_text_container">
        <div class="field_padding">
            <label>Arrêt</label>
            <select name="stop_point_uri" id="<?php echo $type; ?>_stop_selection">
                <option value=""></option>
                <?php foreach ($stop_list as $stop) { ?>
                    <option id="coord:<?php echo $stop->StopPoint->Coord->Lon; ?>,<?php echo $stop->StopPoint->Coord->Lat; ?>" value="<?php echo $stop->StopPoint->Uri; ?>">
                        <?php echo $stop->StopPoint->Name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="clear"></div>
    </div>
</div>