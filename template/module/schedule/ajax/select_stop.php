<div id="<?php echo $type; ?>_stop_container">
    <div class="field_text_container">
        <div class="field_padding">
            <label>Arrêt</label>
            <?php if (count($stops) > 0) { ?>
                <select name="stop_point_id" id="<?php echo $type; ?>_stop_selection">
                    <option value=""></option>
                    <?php foreach ($stops as $stop) { ?>
                        <option id="coord:<?php echo $stop->stopPoint->coord->lon; ?>,<?php echo $stop->stopPoint->coord->lat; ?>" value="<?php echo $stop->stopPoint->id; ?>">
                            <?php echo $stop->stopPoint->name; ?>
                        </option>
                    <?php } ?>
                </select>
            <?php } else { ?>
                <div>
                    Pas d'arrêts pour cette ligne dans cette direction, essayez une autre ligne.
                </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>
</div>