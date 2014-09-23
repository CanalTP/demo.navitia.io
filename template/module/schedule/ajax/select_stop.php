<div id="<?php echo $type; ?>_stop_container">
    <div class="field_text_container">
        <div class="field_padding">
            <label>Arrêt</label>
            <select name="stop_point_id" id="<?php echo $type; ?>_stop_selection">
                <option value=""></option>
                <?php foreach ($stop_list as $stop) { ?>
                    <option id="coord:<?php echo $stop->stopPoint->coord->lon; ?>,<?php echo $stop->stopPoint->coord->lat; ?>" value="<?php echo $stop->stopPoint->id; ?>">
                        <?php echo $stop->stopPoint->name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="clear"></div>
    </div>
</div>