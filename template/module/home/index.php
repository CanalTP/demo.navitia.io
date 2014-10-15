<div class="journey_container home_container">
    <div class="journey_search">
        <div class="search_form">
            <div class="region_list">
                <h1 class="general_title"><?php echo translate('home', 'welcome_prefix'); ?> <strong>IHM<sup>nv2</sup></strong></h1>
                <form id="region_form" method="post" action="<?php url_link('home/select_region'); ?>">
                    <fieldset>
                        <legend><?php echo translate('home', 'question'); ?></legend>
                        <?php foreach ($region_list as $index => $region) { ?>
                            <div>
                                <label class="region_line" for="rid_<?php echo $region->id; ?>">
                                    <input class="region_choice" type="radio" name="region" value="<?php echo $region->id; ?>" id="rid_<?php echo $region->id; ?>" />
                                    <?php echo translate('region.name', strtolower($region->id)); ?>
                                </label>
                            </div>
                        <?php } ?>
                    </fieldset>
                    <noscript>
                        <div class="button_line">
                            <input type="submit" value="<?php echo translate('forms', 'validate_button'); ?>" />
                        </div>
                    </noscript>
                </form>
                <div class="disclaimer">
                    <p>Note : ce site est encore en cours de développement, il se peut qu'il contienne quelques bugs. Merci de votre compréhension.</p>
                    <p><strong>Développeurs :</strong> vous pouvez contribuer au projet en vous rendant sur <a href="https://github.com/CanalTP/demo.navitia.io">l'espace GitHub de ce site</a>
                </div>
            </div>
            <div id="open_layer_map_container" class="region_map"></div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/home/index.js.php'); ?>