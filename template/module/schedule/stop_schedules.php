<div class="schedule_container departure_board">
    <div class="window_title">Horaires</div>
    
    <?php if (count($schedules) > 0) {  ?>
    
        <h1 class="schedule_title">
            Horaires à l'arrêt <?php echo $schedules[0]->stopPoint->name; ?>
        </h1>
        
        <div class="schedule_page_container clearfix">
        
            <div class="board_result">
                <div class="board_summary clearfix">
                    <?php if (is_object($schedules[0]->route)) { ?>
                        <ul>
                            <?php if (is_object($schedules[0]->route->line)) { ?>
                                <!--    
                                <li class="network">
                                    <?php //echo $schedules[0]->route->line->network->name; ?>
                                </li>
                                <li class="mode">
                                    <img src="<?php //echo img_src('/img/mode/' . strtolower($schedules[0]->route->line->physicalModeList[0]->name) . '.png'); ?>"
                                         alt="<?php //echo $schedules[0]->route->line->physicalModeList[0]->name; ?>" />
                                </li>
                                -->
                                <li class="line">
                                    <a title="Voir les horaires de la ligne <?php echo $schedules[0]->route->line->code; ?>"
                                       href="<?php echo url_link('schedule/line/' . $schedules[0]->route->line->id . '/' . $schedules[0]->route->id . '/' . $summary['datetime']); ?>">
                                        <span class="code"><?php echo $schedules[0]->route->line->code; ?> :</span>
                                        <span class="name"><?php echo $schedules[0]->route->line->name; ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="direction">Direction : <?php echo $schedules[0]->route->name; ?></li>
                        </ul>
                    <?php } ?>
                </div>
            
                <h2>Horaires de départ le <?php echo date('d/m/Y', $summary['timestamp']); ?></h2>
            
                <div class="schedule_table_container">
                    <?php if ($schedules[0]->schedules != null) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <?php foreach ($schedules[0]->schedules as $hour => $minutes) { ?>
                                        <th scope="col"><?php echo $hour; ?>h</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($schedules[0]->schedules as $hour => $minutes) { ?>
                                        <td>
                                            <ul class="minute_list">
                                                <?php foreach ($minutes as $minute) { ?>
                                                    <li><?php echo $minute; ?></li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="generic_error">
                            <p>Aucun horaire disponible pour cet arrêt dans ce sens.</p>
                        </div>
                    <?php } ?>
                </div>
            
                <div class="other_info_group clearfix">
                    <!--
                    <h2>Equipements à cet arrêt</h2>
                    <ul class="equipement_list">
                        <li>Parking vélo</li>
                        <li>Annonces sonores</li>
                        <li>Annonces visuelles</li>
                    </ul>
                    -->
                
                    <?php if (!empty($other_line_route_list)) { ?>
                        <div class="other_info_container other_line">
                            <h2>Autres lignes à cet arrêt</h2>
                            <div class="other_line_list">
                                <ul>
                                    <?php foreach ($other_line_route_list as $route) { ?>
                                        <li>
                                            <a title="Voir les horaires de l'arrêt sur la ligne <?php echo $route->Line->Code; ?>" href="<?php echo url_link('schedule/departure_board/' . $route->Line->Uri . '/' . $route->Uri . '/' . $board_summary['stop_point_uri'] . '/' . $board_summary['datetime']); ?>">
                                                <span class="mode"><img width="20" height="20" src="<?php echo img_src('/img/mode/' . strtolower($route->Line->PhysicalModeList[0]->Name) . '.png'); ?>" alt="<?php //echo $route->Line->PhysicalModeList[0]->Name; ?>" /></span>
                                                <span class="code"><?php echo $route->Line->Code; ?> :</span>
                                                <span class="name"><?php echo $route->Line->Name; ?></span>
                                                <span class="direction">(Direction : <?php echo $route->Name; ?>)</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                
                    <?php if (!empty($other_stop_list)) { ?>
                        <div class="other_info_container other_stop">
                            <h2>Autres arrêts sur cette ligne</h2>
                            <div class="other_stop_list">
                                <ul>
                                    <?php foreach ($other_stop_list as $stop) { ?>
                                        <li id="stoppointuri|<?php echo $stop->Uri; ?>">
                                            <a title="Voir les horaires de l'arrêt <?php echo $stop->Name; ?> sur cette ligne" href="<?php echo url_link('schedule/departure_board/' . $board_summary['line_id'] . '/' . $board_summary['route_uri'] . '/' . $stop->Uri . '/' . $board_summary['datetime']); ?>">
                                                <?php echo $stop->Name; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="board_map" id="open_layer_map_container"></div>
        </div>
    
    <?php } else { ?>
    
        <div class="schedule_page_container">
            <div class="generic_error">
                <p>Une erreur technique s'est produite. Le service est momentanément indisponible. Veuillez réessayer ultérieurement.</p>
                <ul class="message_buttons">
                    <li>
                        <a href="<?php url_link('schedule/search'); ?>">Retour à la recherche horaire</a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    
    <?php } ?>
</div>

<?php include(TEMPLATE_DIR . '/module/schedule/stop_schedules.js.php'); ?>