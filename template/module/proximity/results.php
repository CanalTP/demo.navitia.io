<div class="schedule_container">
    <div class="schedule_search">
        <h1 class="schedule_title">A proximité de <?php echo urldecode($search_place); ?></h1>
        
        <div class="search_form">
            <div class="schedule_form_container">
                
            </div>
            <div class="schedule_map_container">
                <div id="open_layer_map_container" style="width: 844px; height:500px;"></div>
                <div>Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/proximity/results.js.php'); ?>