<p class="section_mode">
    <img src="<?php img_src('/img/mode/marche.png'); ?>" alt="" />
</p>
                
<?php if (isset($section->StreetNetwork->PathItemList)) { ?>
    <div class="section_content">
        <p class="section_title">
            <span class="headsign">Marche à pied</span>
        </p>
        <ol class="journey_sn_list">
            <?php foreach ($section->StreetNetwork->PathItemList as $pathItemIndex => $pathItem) { ?>
                <li><span>
                    <?php echo $pathItem->Name; ?>
                    sur <?php echo $pathItem->Length; ?> <abbr title="mètres">m</abbr>
                </span></li>
            <?php } ?>
        </ol>
    </div>
<?php } ?>

<div class="clear"></div>