<p class="section_mode">
    <img src="<?php img_src('/img/mode/marche.png'); ?>" alt="" />
</p>
                
<?php if (isset($section->path)) { ?>
    <div class="section_content">
        <p class="section_title">
            <span class="headsign">Marche à pied</span>
        </p>
        <ol class="journey_sn_list">
            <?php foreach ($section->path as $pathItemIndex => $pathItem) { ?>
                <li><span>
                    <?php echo translate('journey.directions', $pathItem->direction); ?>
                    <?php echo $pathItem->name; ?>
                    sur <?php echo $pathItem->length; ?> <abbr title="mètres">m</abbr>
                </span></li>
            <?php } ?>
        </ol>
    </div>
<?php } ?>

<div class="clear"></div>