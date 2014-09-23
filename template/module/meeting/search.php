<div class="journey_container">
    <div class="journey_search">
        <h1 class="window_title"><?php echo translate('journey', 'section_title'); ?></h1>
        <div class="search_form">
            <form method="post" id="journey_search_form" action="<?php url_link('meeting/execute'); ?>">
                <fieldset>
                    <legend><?php echo translate('journey.search', 'form_title'); ?></legend>
                    <div class="field_line">
                        <div class="field_text_container">
                            <div class="field_padding">
                                <label for="meeting_first_origin_person">Nom</label>
                                <input id="meeting_first_origin_person" type="text" name="first_origin[person]" value="" />
                            </div>
                            <div class="field_padding">
                                <label for="meeting_first_origin_name">Adresse</label>
                                <input id="meeting_first_origin_name" type="text" name="first_origin[name]" value="" />
                                <input id="meeting_first_origin_id" type="hidden" name="first_origin[id]" value="" />
                                <input id="meeting_first_origin_coords" type="hidden" name="first_origin[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLFirstOriginDivId"></div>
                    </div>
                    <div class="field_line">
                        <div class="field_text_container">
                            <div class="field_padding">
                                <label for="meeting_second_origin_person">Nom</label>
                                <input id="meeting_second_origin_person" type="text" name="second_origin[person]" value="" />
                            </div>
                            <div class="field_padding">
                                <label for="meeting_second_origin_name">Adresse</label>
                                <input id="meeting_second_origin_name" type="text" name="second_origin[name]" value="" />
                                <input id="meeting_second_origin_uri" type="hidden" name="second_origin[uri]" value="" />
                                <input id="meeting_second_origin_coords" type="hidden" name="second_origin[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLSecondOriginDivId"></div>
                    </div>
                    <div class="field_line">
                        <div class="field_text_container">
                            <div class="field_padding">
                                <label for="meeting_third_origin_person">Nom</label>
                                <input id="meeting_third_origin_person" type="text" name="third_origin[person]" value="" />
                            </div>
                            <div class="field_padding">
                                <label for="meeting_third_origin_name">Adresse</label>
                                <input id="meeting_third_origin_name" type="text" name="third_origin[name]" value="" />
                                <input id="meeting_third_origin_uri" type="hidden" name="third_origin[uri]" value="" />
                                <input id="meeting_third_origin_coords" type="hidden" name="third_origin[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLThirdOriginDivId"></div>
                    </div>
                </fieldset>
            
                <fieldset>
                    <?php /*<legend><?php echo translate('journey.search', 'advanced_option'); ?></legend>*/ ?>
                
                    <fieldset>                        
                        <div class="field_line field_line_wa">
                            <div class="field_text_container">
                                <div class="field_padding">
                                    <label><?php echo translate('journey.search', 'date_label'); ?></label>
                                    <input type="text" size="10" name="option[date]" value="<?php echo $current_date_human; ?>" />
                                    <label><?php echo translate('journey.search', 'hour_label'); ?></label>
                                    <input type="text" size="5" name="option[time]" value="<?php echo $current_time_human; ?>" />
                                    <input type="hidden" name="option[time_type]" value="french" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </fieldset>
                </fieldset>
            
                <div class="button_line">
                    <input type="submit" id="button_valid_search" value="Lancer la recherche" />
                </div>
            </form>
          
            <div class="search_map">
                <p>Vous pouvez cliquer sur la carte pour localiser vos participants.</p>
                <div class="search_map_container" id="open_layer_map_container"></div>
                <div>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/meeting/search.js.php'); ?>