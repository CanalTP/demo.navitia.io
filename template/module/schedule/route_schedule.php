<?php
    $lineCode = '';
    if (isset($route_schedule[0]->Route->Line->Code)) {
        $lineCode = $route_schedule[0]->Route->Line->Code;
    }
?>
<div class="schedule_container route_schedule">
    <div class="window_title">Horaires</div>

    <?php if (is_object($route_schedule[0])) {  ?>
    
        <h1 class="schedule_title">
            Horaires de la ligne <?php echo $lineCode; ?>
        </h1>
        
        <div class="schedule_page_container clearfix">
        
            <div class="board_result">
                <div class="board_summary clearfix">
                    <?php if (is_object($route_schedule[0]->Route)) { ?>
                        <ul>
                            <?php if (is_object($route_schedule[0]->Route->Line)) { ?>
                                <li class="network">
                                    <?php echo $route_schedule[0]->Route->Line->Network->Name; ?>
                                </li>
                                <li class="mode">
                                    <img src="<?php echo img_src('/img/mode/' . strtolower($route_schedule[0]->Route->Line->PhysicalModeList[0]->Name) . '.png'); ?>" alt="<?php echo $route_schedule[0]->Route->Line->PhysicalModeList[0]->Name; ?>" />
                                </li>
                                <li class="line">
                                    <span class="code"><?php echo $route_schedule[0]->Route->Line->Code; ?> :</span>
                                    <span class="name"><?php echo $route_schedule[0]->Route->Line->Name; ?></span>
                                </li>
                            <?php } ?>
                            <li class="direction">Direction : <?php echo $route_schedule[0]->Route->Name; ?></li>
                        </ul>
                    <?php } ?>
                </div>
            
                <h2>Horaires de départ le <?php echo date('d/m/Y', $board_summary['timestamp']); ?></h2>
                
                <!--
                <div class="pagination">
                    <ul>
                        <li><a href="#">Horaires précédents</a></li>
                        <li><a href="#">Horaires suivants</a></li>
                    </ul>
                </div>
                -->
                
                <div class="schedule_table_container">
                    <ul class="pagination">
                        <li class="prev"><a id="pagination_prev" href="javascript:void(0);">Horaires précédents</a></li>
                        <li class="next"><a id="pagination_next" href="javascript:void(0);">Horaires suivants</a></li>
                    </ul>
                    <div class="clear"></div>
                    <?php if ($route_schedule[0]->TableRowList != null) { ?>
                        <table id="route_schedule_table" class="route_schedule">
                            <?php foreach ($route_schedule[0]->TableRowList as $r => $row) { ?>
                                <tr>
                                    <th scope="row">
                                        <a href="<?php echo url_link('schedule/departure_board/' . $board_summary['line_id'] . '/' . $board_summary['route_uri'] . '/' . $row->StopPoint->Uri . '/' . $board_summary['datetime']); ?>">
                                            <?php echo $row->StopPoint->Name; ?>
                                        </a>
                                    </th>
                                    <?php if (!empty($row->StopTimeList)) { ?>
                                        <?php foreach ($row->StopTimeList as $s => $time) { ?>
                                            <td class="col_<?php echo $s; ?>" id="schedule_<?php echo $r . '_' . $s; ?>"><?php echo date('H:i', $time); ?></td>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <td class="col_0">Aucun horaire</td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                    
                    <?php } else { ?>
                        <div class="generic_error">
                            <p>Aucun horaire disponible pour cette ligne ce sens.</p>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="other_info_group clearfix">
                    <!--
                    <h2>Equipements sur cette ligne</h2>
                    <ul class="equipement_list">
                        <li>Parking vélo</li>
                        <li>Annonces sonores</li>
                        <li>Annonces visuelles</li>
                    </ul>
                    -->
                
                    <?php if (count($other_line_route_list) > 0) { ?>
                        <div class="other_info_container other_line">
                            <h2>Changer la direction</h2>
                            
                            <div class="other_line_list">
                                <ul>
                                    <?php foreach ($other_line_route_list as $route) { ?>
                                        <li>
                                            <a title="Voir les horaires en direction de <?php echo $route->Name; ?>" href="<?php url_link('schedule/line/' . $board_summary['line_id'] . '/' . $route->Uri . '/' . $board_summary['datetime']); ?>">
                                                <span class="direction">Direction : <?php echo $route->Name; ?></span>
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

<?php include(TEMPLATE_DIR . '/module/schedule/route_schedule.js.php'); ?>