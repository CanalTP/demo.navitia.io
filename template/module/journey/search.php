<div class="journey_container">
    <div class="journey_search">
        <h1 class="window_title"><?php echo translate('journey', 'section_title'); ?></h1>
        <div class="search_form">
            <form method="post" id="journey_search_form" action="<?php url_link('journey/execute'); ?>">
                <fieldset>
                    <legend><?php echo translate('journey.search', 'form_title'); ?></legend>
                    <div class="field_line">
                        <div class="field_text_container">
                            <div class="field_padding">
                                <label for="journey_search_from_name"><?php echo translate('transport', 'from_label'); ?></label>
                                <input id="journey_search_from_name" type="text" name="from[name]" value="" />
                                <input id="journey_search_from_uri" type="hidden" name="from[uri]" value="" />
                                <input id="journey_search_from_coords" type="hidden" name="from[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLFromDivId"></div>
                    </div>
                    <div class="field_line">
                        <div class="field_text_container">
                            <div class="field_padding">
                                <label for="journey_search_to_name"><?php echo translate('transport', 'to_label'); ?></label>
                                <input id="journey_search_to_name" type="text" name="to[name]" value="" />
                                <input id="journey_search_to_uri" type="hidden" name="to[uri]" value="" />
                                <input id="journey_search_to_coords" type="hidden" name="to[coords]" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="FLToDivId"></div>
                    </div>
                </fieldset>
            
                <div class="button_more">
                    <a href="javascript:void(0);" id="button_advanced_options"><?php echo translate('journey.search', 'more_option'); ?></a>
                </div>
            
                <fieldset class="advanced_options" id="fieldset_advanced_options">
                    <?php /*<legend><?php echo translate('journey.search', 'advanced_option'); ?></legend>*/ ?>
                
                    <fieldset>
                        <legend>Date et heure</legend>
                        
                        <div class="field_line field_line_wa">
                            <div class="field_text_container">
                                <div class="field_padding">
                                    <label><?php echo translate('journey.search', 'date_label'); ?></label>
                                    <input type="text" size="10" name="option[date]" value="<?php echo $current_date_human; ?>" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    
                        <div class="field_line field_line_wa">
                            <div class="field_text_container">
                                <div class="field_padding">
                                    <label><?php echo translate('journey.search', 'hour_label'); ?></label>
                                    <select name="option[datetime_represents]">
                                        <option value="departure"><?php echo translate('journey.search', 'datetime_represents_departure'); ?></option>
                                        <option value="arrival"><?php echo translate('journey.search', 'datetime_represents_arrival'); ?></option>
                                    </select>
                                    <input type="text" size="5" name="option[time]" value="<?php echo $current_time_human; ?>" />
                                    <input type="hidden" name="option[time_type]" value="french" />
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </fieldset>
                
                    <?php /*
                    <!-- A intégrer quand l'evitement de mode fonctionnera (demande NAVITIAII-305)
                    
                    
                    <fieldset>
                        <legend>Modes</legend>
                        <div class="mode_list">
                            <?php foreach ($mode_list as $mode) { ?>
                                <div class="mode_element">
                                    <input type="checkbox" name="modes[]" id="option_mode_<?php echo $mode['name']; ?>" value="<?php echo $mode['name']; ?>" <?php if ($mode['checked']) { ?>checked="checked"<?php } ?> />
                                    <label for="option_mode_<?php echo $mode['name']; ?>"><?php echo translate('transport.media_mode', $mode['name']); ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    </fieldset>
                    */ ?>
                    
                    <?php  /*
                    <fieldset>
                        <legend>Autres options</legend>
                        
                        <div>
                            <label for="option_walk_speed">Vitesse de marche à pied</label>
                            <select id="option_walk_speed" name="option[walk_speed]">
                                <option value="slow">Lente (2 km/h)</option>
                                <option value="normal" selected="selected">Normale (5 km/h)</option>
                                <option value="fast">Rapide (8 km/h)</option>
                            </select>
                        </div>
                    </fieldset>
                    */ ?>
                </fieldset>
            
                <div class="button_line">
                    <input type="submit" id="button_valid_search" value="<?php echo translate('journey.search', 'go_now'); ?>" />
                </div>
            </form>
          
            <div class="search_map">
                <p><?php echo translate('journey.search', 'map_explanation'); ?></p>
                <div class="search_map_container" id="open_layer_map_container"></div>
                <div>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/journey/search.js.php'); ?>