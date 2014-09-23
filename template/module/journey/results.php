<div class="journey_container">
    <!-- Titre -->
    <div class="window_title">Itinéraires</div>
  
    <!-- Sous-Titre -->
    <?php if ($roadMapTitle['from'] && $roadMapTitle['to']) { ?>
        <h1 class="journey_title">
            <span class="hidden">De </span>
            <span id="journey_title_origin_label"><?php echo $roadMapTitle['from']; ?></span>
            <span class="journey_to_icon"><span>&Agrave;</span></span>
            <span id="journey_title_destination_label"><?php echo $roadMapTitle['to']; ?></span>
        </h1>
    <?php } ?>
  
    <!-- Feuille de route -->
    <div id="journey_result_container">
        <?php include(TEMPLATE_DIR . '/module/journey/ajax-results.php'); ?>
        <div class="clear"></div>
    </div>
    
    <!-- Carte -->
    <div class="journey_map">
        <div class="journey_change_time_clockwise">
            <!-- Clockwise -->
            <div class="journey_change_clockwise">
                <select name="clockwise" id="journey_clockwise">
                    <option value="departure">Partir après</option>
                    <option value="arrival">Arriver avant</option>
                </select>
            </div>
            <!-- Slider horaire -->
            <ul class="journey_change_time">
                <li class="prev">
                    <a id="journey_hour_prev" href="javascript:void(0);">
                        <span>Itinéraires précédents</span>
                    </a>
                </li>
                <li class="slider">
                    <a id="journey_hour_slider" href="javascript:void(0);">
                        <span><?php echo date('H:i', $journeySummary['timestamp']); ?></span>
                    </a>
                </li>
                <li class="next">
                    <a id="journey_hour_next" href="javascript:void(0);">
                        <span>Itinéraires suivants</span>
                    </a>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="journey_map_spacer"></div>
        <div class="journey_map_container" id="open_layer_map_container"></div>
    </div>
    
    <div class="clear"></div>
</div>

<?php include(TEMPLATE_DIR . '/module/journey/results.js.php'); ?>