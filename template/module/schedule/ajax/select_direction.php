<div id="<?php echo $type; ?>_route_container">
    <div class="field_text_container">
        <div class="field_padding">
            <label>Direction</label>
            <select name="route_id" id="<?php echo $type; ?>_route_selection">
                <option value=""></option>
                <?php foreach ($route_list as $route) { ?>
                    <option value="<?php echo $route->id; ?>"><?php echo $route->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="clear"></div>
    </div>
</div>