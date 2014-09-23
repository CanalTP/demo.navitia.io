<div id="<?php echo $type; ?>_line_container">
    <div class="field_text_container">
        <div class="field_padding">
            <?php if ($line_list != null) { ?>
                <label>Ligne</label>
                <select name="line_id" id="<?php echo $type; ?>_line_selection">
                    <option value=""></option>
                    <?php foreach ($line_list as $line) { ?>
                        <option value="<?php echo $line->id; ?>">
                            <?php echo $line->code; ?> :
                            <?php echo $line->name; ?>
                        </option>
                    <?php } ?>
                </select>
            <?php } else { ?>
                Aucune ligne pour ce réseau
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>
</div>