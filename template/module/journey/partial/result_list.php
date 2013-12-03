<?php use Nv2\Model\Entity\Journey\Journey; ?>

<?php if (!$journeyResult['hasError']) { ?>
    <h2>Solutions trouvées</h2>
    <div class="previous">
        <a href="<?php url_link($journeySummary['links']['sooner']); ?>">
            Précédentes
        </a>
    </div>
    <ul>
        <?php foreach ($journeyResult['data']->JourneyList as $journeyIndex => $journey) { ?>
            <li class="<?php if ($journeyIndex == 0) echo 'current'; ?> journey_number_<?php echo $journeyIndex; ?>">
                <a href="javascript:void(0);" class="journey_detail_link" id="journey_number:<?php echo $journeyIndex; ?>">
                    <?php if ($journey->Type == Journey::TYPE_PUBLIC_TRANSPORT) { ?>
                        <span class="hours">
                            <?php echo date('H:i', $journey->DepartureTimestamp); ?>
                                <span class="icon-to"><span>à</span></span>
                            <?php echo date('H:i', $journey->ArrivalTimestamp); ?>
                        </span>
                    <?php } else if ($journey->Type == Journey::TYPE_WALK) { ?>
                        <span class="hours">
                            Trajet à pied
                        </span>
                    <?php } ?>
                    <br />
                    <span>Durée : <strong><?php echo gmdate('H:i', ($journey->Duration)); ?></strong></span><br />
                    <?php if ($journey->NbTransfers == 0) { ?>
                        <span><strong>Trajet direct</strong></span>
                    <?php } else { ?>
                        <span>Correspondances : <strong><?php echo $journey->NbTransfers; ?></strong></span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>
    </ul>
    <div class="next">
        <a href="<?php url_link($journeySummary['links']['later']); ?>">
            Suivantes
        </a>
    </div>
<?php } else { ?>
    <h2>Pas de solution</h2>
<?php }?>