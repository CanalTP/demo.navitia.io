<div class="journey_container">
    <div class="journey_search">
        <h1 class="window_title"><?php echo translate('proximity', 'section_title'); ?></h1>
        <div class="search_form">
            <form method="post" id="journey_search_form" action="<?php url_link('proximity/execute'); ?>">
                <fieldset>
                    <legend><?php echo translate('proximity.search', 'form_title'); ?></legend>
                    <div class="field_line">
                        <div class="field_text_container field_long_label">
                            <div class="field_padding">
                                <label for="proximity_search_point_name">Addresse, arrêt, lieu...</label>
                                <input id="proximity_search_point_name" type="text" name="point[name]" value="" />
                                <input id="proximity_search_point_id" type="hidden" name="point[id]" value="" />
                                <input id="proximity_search_point_coords" type="hidden" name="point[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLPointDivId"></div>
                    </div>
                </fieldset>
            
                <div class="button_line">
                    <input type="submit" id="button_valid_search" value="Rechercher" />
                </div>
            </form>
          
            <div class="search_map">
                <p>Ou cliquez sur la carte pour lancer la recherche</p>
                <div class="search_map_container" id="open_layer_map_container"></div>
                <div>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/proximity/search.js.php'); ?>