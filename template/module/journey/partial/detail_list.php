<?php use Nv2\Model\Entity\Journey\Journey; ?>

<?php if (!$journeyResult['hasError']) { ?>
    <?php foreach ($journeyResult['data']->JourneyList as $journeyIndex => $journey) { ?>
        <div id="journey_detail_number_<?php echo $journeyIndex; ?>" class="journey_block journey_number_<?php echo $journeyIndex; ?><?php if ($journeyIndex > 0) echo ' hidden'; ?>">
            <div class="journey_summary">
                <?php include(TEMPLATE_DIR . '/module/journey/partial/summary.php'); ?>
                <div class="clear"></div>
            </div>
            <div class="journey_start">
                <div class="field_line">
                    <div class="field_text_container">
                        <div class="field_padding">
                            <label for="journey_search_origin_name">Départ</label>
                            <input type="text" value="<?php echo ($journeySummary['origin_name'] != '-') ? $journeySummary['origin_name'] : $roadMapTitle['origin']; ?>" name="origin[name]" id="journey_search_origin_name" autocomplete="off">
                            <input type="hidden" value="<?php echo $journeySummary['origin_uri']; ?>" name="origin[uri]" id="journey_search_origin_uri">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="FLOriginDivId"></div>
                </div>
            </div>
            <ol class="journey_section_list">
                <?php foreach ($journey->SectionList as $sectionIndex => $section) { ?>
                    <?php if ($section->Type != 'WAITING') { ?>
                        <li class="section_<?php echo strtolower($section->Type); ?>">
                            <?php if ($section->Type == 'PUBLIC_TRANSPORT') { ?>
                                <?php include(TEMPLATE_DIR . '/module/journey/partial/section_public_transport.php'); ?>
                            <?php } else if ($section->Type == 'STREET_NETWORK') { ?>
                                <?php include(TEMPLATE_DIR . '/module/journey/partial/section_street_network.php'); ?>
                            <?php } else if ($section->Type == 'TRANSFER') { ?>
                                <?php include(TEMPLATE_DIR . '/module/journey/partial/section_transfer.php'); ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ol>
            <div class="journey_end">
                <div class="field_line">
                    <div class="field_text_container">
                        <div class="field_padding">
                            <label for="journey_search_destination_name">Arrivée</label>
                            <input type="text" value="<?php echo $roadMapTitle['destination']; ?>" name="destination[name]" id="journey_search_destination_name" autocomplete="off">
                            <input type="hidden" value="<?php echo $journeySummary['destination_uri']; ?>" name="destination[uri]" id="journey_search_destination_uri">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="FLDestinationDivId"></div>
                </div>
            </div>
            <?php if ($journey->Type == Journey::TYPE_PUBLIC_TRANSPORT) { ?>
                <ul class="show_stops">
                    <li><a href="<?php echo url_link($journeySummary['links']['reverse_route']); ?>">Trajet retour</a></li>
                    <li><a href="javascript:void(0);" id="show_all_stops_link" class="option_link">Montrer tous les arrêts</a></li>
                </ul>
                <div class="clear"></div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="journey_block">
        <div class="journey_summary">
            <?php include(TEMPLATE_DIR . '/module/journey/partial/summary.php'); ?>
            <div class="clear"></div>
        </div>
        <div class="journey_start">
            <div class="field_line">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label for="journey_search_origin_name">Départ</label>
                        <input type="text" value="<?php echo $roadMapTitle['origin']; ?>" name="origin[name]" id="journey_search_origin_name" autocomplete="off">
                        <input type="hidden" value="<?php echo $journeySummary['origin_uri']; ?>" name="origin[uri]" id="journey_search_origin_extcode">
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="FLOriginDivId"></div>
            </div>
        </div>
        <p class="error">Aucune solution n'a été trouvée entre ces deux points. Veuillez modifier vos critères de recherche.</p>
        <div class="journey_end">
            <div class="field_line">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label for="journey_search_destination_name">Arrivée</label>
                        <input type="text" value="<?php echo $roadMapTitle['destination']; ?>" name="destination[name]" id="journey_search_destination_name" autocomplete="off">
                        <input type="hidden" value="<?php echo $journeySummary['destination_uri']; ?>" name="destination[uri]" id="journey_search_destination_extcode">
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="FLDestinationDivId"></div>
            </div>
        </div>
    </div>
<?php } ?>
