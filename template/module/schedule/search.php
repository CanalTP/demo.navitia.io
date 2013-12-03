<div class="schedule_container">
    <div class="schedule_search">
        <h1 class="window_title">Horaires</h1>
        
        <div class="search_form">
            <div class="schedule_form_container">
                <?php include(TEMPLATE_DIR . '/module/schedule/partial/search_departure_boards.php'); ?>
            </div>
            <div class="schedule_map_container">
                <p>Pour rechercher des horaires à proximité d'un point, cliquez sur la carte</p>
                <div id="schedule_search_map"></div>
                <div>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/schedule/search.js.php'); ?>