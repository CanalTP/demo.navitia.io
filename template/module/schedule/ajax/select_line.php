<div id="<?php echo $type; ?>_line_container">
    <div class="field_text_container">
        <div class="field_padding">
            <?php if ($line_list != null) { ?>
                <label>Ligne</label>
                <select name="line_uri" id="<?php echo $type; ?>_line_selection">
                    <option value=""></option>
                    <?php foreach ($line_list as $line) { ?>
                        <option value="<?php echo $line->Uri; ?>">
                            <?php echo $line->Code; ?> :
                            <?php echo $line->Name; ?>
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