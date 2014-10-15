<?php

use Nv2\Controller\Journey\JourneyPrecisionController;
use Nv2\Model\Entity\Places\Place;

?>

<div class="journey_container">
    <div class="window_title">Itinéraires</div>
    
    <h1 class="journey_title">Demande de précision</h1>

    <div class="precision_container journey_precision">
        <form method="post" action="<?php url_link('journey/execute'); ?>">
            <fieldset>
                <legend>Point de départ</legend>
                <div class="point_container">
                    <?php if ($from_point_response['type'] == JourneyPrecisionController::RESPONSE_MULTIPLE_SOLUTION) { ?>
                        <p class="response_type">Vous avez saisi "<?php echo $from_point_response['entry']; ?>". Plusieurs points possibles :</p>
                        <ul class="point_list">
                            <?php foreach ($from_point_response['places'] as $pointIndex => $point) { ?>
                                <li class="<?php echo strtolower($point->objectType); ?>">
                                    <input id="from_<?php echo $pointIndex; ?>" type="radio" name="from[data]" value="<?php echo $point->name . ';' . $point->id; ?>" />
                                    <label for="from_<?php echo $pointIndex; ?>">
                                        <span class="point_name"><?php echo $point->name; ?></span>
                                        <span class="point_detail">
                                            <?php if ($point->objectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                                <span class="type"><?php echo translate('object_identifiers', strtolower($point->objectType)); ?></span>
                                            <?php } ?>
                                            <span class="point_admin">
                                                <?php if ($point->objectType == Place::OBJECT_TYPE_ADMIN) { ?>
                                                    <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                                <?php } else { ?>
                                                    <?php echo $point->adminName; ?>
                                                    <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="clear"></div>
                                </li>
                            <?php } ?>
                        </ul>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Aucune proposition ne correspond à ma recherche</a></p>
                    <?php } else if ($from_point_response['type'] == JourneyPrecisionController::RESPONSE_ONE_SOLUTION) { ?>
                        <p class="response_type response_selected">Point sélectionné :</p>
                        <div class="selected_point">
                            <?php $point = $from_point_response['places'][0]; ?>
                            <p class="<?php echo strtolower($point->objectType); ?>">
                                <span>
                                    <span class="point_name"><?php echo $point->name; ?></span>
                                    <span class="point_detail">
                                        <?php if ($point->objectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                            <span class="type"><?php echo translate('object_identifiers', strtolower($point->objectType)); ?></span>
                                        <?php } ?>
                                        <span class="point_admin">
                                            <?php echo $point->adminName; ?>
                                            <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="clear"></span>
                            </p>
                            <input type="hidden" name="from[data]" value="<?php echo $point->name . ';' . $point->id; ?>" />
                            <div class="clear"></div>
                        </div>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Modifier</a></p>
                    <?php } else if ($from_point_response['type'] == JourneyPrecisionController::RESPONSE_UNKNOWN_POINT) { ?>
                        <p class="response_type response_error">Vous avez saisi "<?php echo $from_point_response['entry']; ?>". Entrée inconnue, veuillez réitérer votre demande.</p>
                        <div>
                            <input type="text" name="from[name]" />
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Point d'arrivée</legend>
                <div class="point_container">
                    <?php if ($to_point_response['type'] == JourneyPrecisionController::RESPONSE_MULTIPLE_SOLUTION) { ?>
                        <p class="response_type">Vous avez saisi "<?php echo $to_point_response['entry']; ?>". Plusieurs points possibles :</p>
                        <ul class="point_list">
                            <?php foreach ($to_point_response['places'] as $pointIndex => $point) { ?>
                                <li class="<?php echo strtolower($point->objectType); ?>">
                                    <input id="to_<?php echo $pointIndex; ?>" type="radio" name="to[data]" value="<?php echo $point->name; ?>;<?php echo $point->id; ?>" />
                                    <label for="to_<?php echo $pointIndex; ?>">
                                        <span class="point_name"><?php echo $point->name; ?></span>
                                        <span class="point_detail">
                                            <?php if ($point->objectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                                <span class="type"><?php echo translate('object_identifiers', strtolower($point->objectType)); ?></span>
                                            <?php } ?>
                                            <span class="point_admin">
                                                <?php if ($point->objectType == Place::OBJECT_TYPE_ADMIN) { ?>
                                                    <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                                <?php } else { ?>
                                                    <?php echo $point->adminName; ?>
                                                    <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="clear"></div>
                                </li>
                            <?php } ?>
                        </ul>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Aucune proposition ne correspond à ma recherche</a></p>
                    <?php } else if ($to_point_response['type'] == JourneyPrecisionController::RESPONSE_ONE_SOLUTION) { ?>
                        <p class="response_type response_selected">Point sélectionné :</p>
                        <div class="selected_point">
                            <?php $point = $to_point_response['places'][0]; ?>
                            <p>
                                <span class="point_name"><?php echo $point->name; ?></span>
                                <span class="point_detail">
                                    <?php if ($point->objectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                        <span class="type"><?php echo translate('object_identifiers', strtolower($point->objectType)); ?></span>
                                    <?php } ?>
                                    <span class="point_admin">
                                        <?php echo $point->name; ?>
                                        <?php if ($point->adminZipCode) { ?>(<?php echo $point->adminZipCode; ?>)<?php } ?>
                                    </span>
                                </span>
                            </p>
                            <input type="hidden" name="to[data]" value="<?php echo $point->name . ';' . $point->id; ?>" />
                            <div class="clear"></div>
                        </div>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Modifier</a></p>
                    <?php } else if ($to_point_response['type'] == JourneyPrecisionController::RESPONSE_UNKNOWN_POINT) { ?>
                        <p class="response_type response_error">Vous avez saisi "<?php echo $to_point_response['entry']; ?>". Entrée inconnue, veuillez réitérer votre demande.</p>
                        <div>
                            <input type="text" name="to[name]" />
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
            <div class="clear"></div>
            <div class="button_line">
                <input type="hidden" name="option[datetime_represents]" value="<?php echo $datetime_represents; ?>" />
                <input type="hidden" name="datetime" value="<?php echo $datetime; ?>" />
                <input type="submit" value="Valider" />
            </div>
        </form>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/journey/precision.js.php'); ?>