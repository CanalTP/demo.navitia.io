<div class="schedule_container departure_board">
    <div class="window_title">Horaires</div>

    <?php if (is_object($departure_board[0])) {  ?>
    
        <h1 class="schedule_title">
            Horaires à l'arrêt <?php echo $departure_board[0]->StopPoint->Name; ?>
        </h1>
        
        <div class="schedule_page_container clearfix">
        
            <div class="board_result">
                <div class="board_summary clearfix">
                    <?php if (is_object($departure_board[0]->Route)) { ?>
                        <ul>
                            <?php if (is_object($departure_board[0]->Route->Line)) { ?>
                                <li class="network">
                                    <?php echo $departure_board[0]->Route->Line->Network->Name; ?>
                                </li>
                                <li class="mode">
                                    <img src="<?php //echo img_src('/img/mode/' . strtolower($departure_board[0]->Route->Line->PhysicalModeList[0]->Name) . '.png'); ?>" alt="<?php //echo $departure_board[0]->Route->Line->PhysicalModeList[0]->Name; ?>" />
                                </li>
                                <li class="line">
                                    <a title="Voir les horaires de la ligne <?php echo $departure_board[0]->Route->Line->Code; ?>" href="<?php echo url_link('schedule/line/' . $departure_board[0]->Route->Line->Uri . '/' . $departure_board[0]->Route->Uri . '/' . $board_summary['datetime']); ?>">
                                        <span class="code"><?php echo $departure_board[0]->Route->Line->Code; ?> :</span>
                                        <span class="name"><?php echo $departure_board[0]->Route->Line->Name; ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="direction">Direction : <?php echo $departure_board[0]->Route->Name; ?></li>
                        </ul>
                    <?php } ?>
                </div>
            
                <h2>Horaires de départ le <?php echo date('d/m/Y', $board_summary['timestamp']); ?></h2>
            
                <div class="schedule_table_container">
                    <?php if ($departure_board[0]->BoardItems != null) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <?php foreach ($departure_board[0]->BoardItems as $item) { ?>
                                        <th scope="col"><?php echo str_pad($item->Hour, 2, '0', STR_PAD_LEFT); ?>h</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($departure_board[0]->BoardItems as $item) { ?>
                                        <td>
                                            <ul class="minute_list">
                                                <?php foreach ($item->Minutes as $minute) { ?>
                                                    <li><?php echo str_pad($minute, 2, '0', STR_PAD_LEFT); ?></li>
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
                                            <a title="Voir les horaires de l'arrêt <?php echo $stop->Name; ?> sur cette ligne" href="<?php echo url_link('schedule/departure_board/' . $board_summary['line_uri'] . '/' . $board_summary['route_uri'] . '/' . $stop->Uri . '/' . $board_summary['datetime']); ?>">
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

<?php include(TEMPLATE_DIR . '/module/schedule/departure_board.js.php'); ?>