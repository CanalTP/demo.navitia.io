<?php

use Nv2\Model\Entity\Journey\Journey;
use Nv2\Model\Entity\Journey\Section;

$allowed_sections = array(
    Section::TYPE_PUBLIC_TRANSPORT,
    Section::TYPE_STREET_NETWORK,
    Section::TYPE_TRANSFER
)

?>

<?php if (!$journeyResult['hasError']) { ?>
    <?php foreach ($journeyResult['data']->journeys as $journeyIndex => $journey) { ?>
        <div id="journey_detail_number_<?php echo $journeyIndex; ?>" class="journey_block journey_number_<?php echo $journeyIndex; ?><?php if ($journeyIndex > 0) echo ' hidden'; ?>">
            <div class="journey_summary">
                <?php include(TEMPLATE_DIR . '/module/journey/partial/summary.php'); ?>
                <div class="clear"></div>
            </div>
            <div class="journey_start">
                <div class="field_line">
                    <div class="field_text_container">
                        <div class="field_padding">
                            <label for="journey_search_from_name">Départ</label>
                            <input type="text" value="<?php echo ($journeySummary['from_name'] != '-') ? $journeySummary['from_name'] : $roadMapTitle['from']; ?>" name="from[name]" id="journey_search_from_name" autocomplete="off">
                            <input type="hidden" value="<?php echo $journeySummary['from_id']; ?>" name="from[id]" id="journey_search_from_id">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="FLFromDivId"></div>
                </div>
            </div>
            <ol class="journey_section_list">
                <?php foreach ($journey->sections as $sectionIndex => $section) { ?>
                    <?php if (in_array($section->type, $allowed_sections)) { ?>
                        <li class="section_<?php echo $section->type; ?>">
                            <?php include(TEMPLATE_DIR . '/module/journey/partial/section_' . $section->type . '.php'); ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ol>
            <div class="journey_end">
                <div class="field_line">
                    <div class="field_text_container">
                        <div class="field_padding">
                            <label for="journey_search_destination_name">Arrivée</label>
                            <input type="text" value="<?php echo $roadMapTitle['to']; ?>" name="to[name]" id="journey_search_to_name" autocomplete="off">
                            <input type="hidden" value="<?php echo $journeySummary['to_id']; ?>" name="to[id]" id="journey_search_to_id">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="FLToDivId"></div>
                </div>
            </div>
            <?php if ($journey->description == Journey::DESCR_PUBLIC_TRANSPORT) { ?>
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
                        <label for="journey_search_from_name">Départ</label>
                        <input type="text" value="<?php echo $roadMapTitle['from']; ?>" name="from[name]" id="journey_search_from_name" autocomplete="off">
                        <input type="hidden" value="<?php echo $journeySummary['from_id']; ?>" name="from[id]" id="journey_search_from_id">
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="FLFromDivId"></div>
            </div>
        </div>
        <p class="error">Aucune solution n'a été trouvée entre ces deux points. Veuillez modifier vos critères de recherche.</p>
        <div class="journey_end">
            <div class="field_line">
                <div class="field_text_container">
                    <div class="field_padding">
                        <label for="journey_search_to_name">Arrivée</label>
                        <input type="text" value="<?php echo $roadMapTitle['to']; ?>" name="to[name]" id="journey_search_to_name" autocomplete="off">
                        <input type="hidden" value="<?php echo $journeySummary['to_id']; ?>" name="to[id]" id="journey_search_to_id">
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="FLToDivId"></div>
            </div>
        </div>
    </div>
<?php } ?>
