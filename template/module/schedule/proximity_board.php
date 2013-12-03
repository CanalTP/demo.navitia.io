<div class="schedule_container departure_board proximity_board">
    <div class="window_title">Horaires</div>

    <?php if (count($departure_boards) > 0) {  ?>
    
        <h1 class="schedule_title">
            Horaires à proximité
        </h1>
        
        <div class="schedule_page_container clearfix">
            <div class="board_result">
                <p>Cliquez sur les symboles "+" pour déplier les grilles horaires</p>
                <?php foreach ($departure_boards as $board_id => $board) { ?>
                    <?php if ($board->BoardItems != null) { ?>
                        <div class="board_summary clearfix">
                            <?php if (is_object($board->Route)) { ?>
                                <ul>
                                    <li class="stop">
                                        <a class="show_timing" id="boardidlink_<?php echo $board_id; ?>" href="javascript:void(0);"><img src="<?php img_src('/img/schedule_plus_button.png'); ?>" alt="+" width="16" height="16" /></a>
                                        <?php echo $board->StopPoint->Name; ?> (<?php echo round($board->StopPoint->Distance); ?> <abbr title="mètres">m</abbr>)
                                    </li>
                                    <?php if (is_object($board->Route->Line)) { ?>
                                        <li class="network">
                                            <?php echo $board->Route->Line->Network->Name; ?>
                                        </li>
                                        <li class="mode">
                                            <img src="<?php echo img_src('/img/mode/' . strtolower($board->Route->Line->PhysicalModeList[0]->Name) . '.png'); ?>" alt="$board->Route->Line->PhysicalModeList[0]->Name" />
                                        </li>
                                        <li class="line">
                                            <a title="Voir les horaires de la ligne <?php echo $board->Route->Line->Code; ?>" href="<?php echo url_link('schedule/line/' . $board->Route->Line->Uri . '/' . $board->Route->Uri . '/' . request_get(1)); ?>">
                                                <span class="code"><?php echo $board->Route->Line->Code; ?> :</span>
                                                <span class="name"><?php echo $board->Route->Line->Name; ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="direction">Direction : <?php echo $board->Route->Name; ?></li>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="schedule_table_container hidden" id="boardid_<?php echo $board_id; ?>">
                            <h2>Horaires de départ le <?php echo date_format(new \DateTime(request_get(1)), 'd/m/Y'); ?></h2>
                            <table>
                                <thead>
                                    <tr>
                                        <?php foreach ($board->BoardItems as $item) { ?>
                                            <th scope="col"><?php echo str_pad($item->Hour, 2, '0', STR_PAD_LEFT); ?>h</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php foreach ($board->BoardItems as $item) { ?>
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
                            <ul class="board_more">
                                <li class="journey_link">
                                    <a href="<?php url_link('journey/results/-/' . $current_coords->getUri() . '/' . $board->StopPoint->Name . '/' . $board->StopPoint->Uri . '/departure/' . $current_date); ?>">Calculer un itinéraire pour s'y rendre</a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="board_map" id="open_layer_map_container"></div>
        </div>
        
    <?php } else { ?>
    
        <div class="schedule_page_container">            
            <div class="generic_error">
                <p>Aucun horaire n'a été trouvé à proximité de votre recherche.</p>
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

<?php include(TEMPLATE_DIR . '/module/schedule/proximity_board.js.php'); ?>