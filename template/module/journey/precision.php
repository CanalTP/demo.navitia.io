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
                    <?php if ($origin_point_response['type'] == JourneyPrecisionController::RESPONSE_MULTIPLE_SOLUTION) { ?>
                        <p class="response_type">Vous avez saisi "<?php echo $origin_point_response['entry']; ?>". Plusieurs points possibles :</p>
                        <ul class="point_list">
                            <?php foreach ($origin_point_response['pointList'] as $pointIndex => $point) { ?>
                                <li class="<?php echo strtolower($point->ObjectType); ?>">
                                    <input id="origin_<?php echo $pointIndex; ?>" type="radio" name="origin[data]" value="<?php echo $point->name . ';' . $point->id; ?>" />
                                    <label for="origin_<?php echo $pointIndex; ?>">
                                        <span class="point_name"><?php echo $point->Name; ?></span>
                                        <span class="point_detail">
                                            <?php if ($point->ObjectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                                <span class="type"><?php echo translate('object_identifiers', strtolower($point->ObjectType)); ?></span>
                                            <?php } ?>
                                            <span class="point_admin">
                                                <?php if ($point->ObjectType == Place::OBJECT_TYPE_ADMIN) { ?>
                                                    <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                                <?php } else { ?>
                                                    <?php echo $point->AdminName; ?>
                                                    <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="clear"></div>
                                </li>
                            <?php } ?>
                        </ul>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Aucune proposition ne correspond à ma recherche</a></p>
                    <?php } else if ($origin_point_response['type'] == JourneyPrecisionController::RESPONSE_ONE_SOLUTION) { ?>
                        <p class="response_type response_selected">Point sélectionné :</p>
                        <div class="selected_point">
                            <?php $point = $origin_point_response['pointList'][0]; ?>
                            <p class="<?php echo strtolower($point->ObjectType); ?>">
                                <span>
                                    <span class="point_name"><?php echo $point->Name; ?></span>
                                    <span class="point_detail">
                                        <?php if ($point->ObjectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                            <span class="type"><?php echo translate('object_identifiers', strtolower($point->ObjectType)); ?></span>
                                        <?php } ?>
                                        <span class="point_admin">
                                            <?php echo $point->AdminName; ?>
                                            <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="clear"></span>
                            </p>
                            <input type="hidden" name="origin[data]" value="<?php echo $point->name . ';' . $point->id; ?>" />
                            <div class="clear"></div>
                        </div>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Modifier</a></p>
                    <?php } else if ($origin_point_response['type'] == JourneyPrecisionController::RESPONSE_UNKNOWN_POINT) { ?>
                        <p class="response_type response_error">Vous avez saisi "<?php echo $origin_point_response['entry']; ?>". Entrée inconnue, veuillez réitérer votre demande.</p>
                        <div>
                            <input type="text" name="origin[name]" />
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Point d'arrivée</legend>
                <div class="point_container">
                    <?php if ($destination_point_response['type'] == JourneyPrecisionController::RESPONSE_MULTIPLE_SOLUTION) { ?>
                        <p class="response_type">Vous avez saisi "<?php echo $destination_point_response['entry']; ?>". Plusieurs points possibles :</p>
                        <ul class="point_list">
                            <?php foreach ($destination_point_response['pointList'] as $pointIndex => $point) { ?>
                                <li class="<?php echo strtolower($point->ObjectType); ?>">
                                    <input id="destination_<?php echo $pointIndex; ?>" type="radio" name="destination[data]" value="<?php echo $point->Name; ?>;<?php echo $point->id; ?>" />
                                    <label for="destination_<?php echo $pointIndex; ?>">
                                        <span class="point_name"><?php echo $point->Name; ?></span>
                                        <span class="point_detail">
                                            <?php if ($point->ObjectType != Place::OBJECT_TYPE_ADMIN) { ?>
                                                <span class="type"><?php echo translate('object_identifiers', strtolower($point->ObjectType)); ?></span>
                                            <?php } ?>
                                            <span class="point_admin">
                                                <?php if ($point->ObjectType == Place::OBJECT_TYPE_ADMIN) { ?>
                                                    <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                                <?php } else { ?>
                                                    <?php echo $point->AdminName; ?>
                                                    <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="clear"></div>
                                </li>
                            <?php } ?>
                        </ul>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Aucune proposition ne correspond à ma recherche</a></p>
                    <?php } else if ($destination_point_response['type'] == JourneyPrecisionController::RESPONSE_ONE_SOLUTION) { ?>
                        <p class="response_type response_selected">Point sélectionné :</p>
                        <div class="selected_point">
                            <?php $point = $destination_point_response['pointList'][0]; ?>
                            <p>
                                <span class="point_name"><?php echo $point->Name; ?></span>
                                <span class="point_detail">
                                    <?php if ($point->ObjectType != AutocompleteItem::OBJECT_TYPE_ADMIN) { ?>
                                        <span class="type"><?php echo translate('object_identifiers', strtolower($point->ObjectType)); ?></span>
                                    <?php } ?>
                                    <span class="point_admin">
                                        <?php echo $point->AdminName; ?>
                                        <?php if ($point->AdminZipCode) { ?>(<?php echo $point->AdminZipCode; ?>)<?php } ?>
                                    </span>
                                </span>
                            </p>
                            <input type="hidden" name="destination[data]" value="<?php echo $point->Name . ';' . $point->id; ?>" />
                            <div class="clear"></div>
                        </div>
                        <p class="point_modify"><a href="<?php url_link('journey/search'); ?>">Modifier</a></p>
                    <?php } else if ($destination_point_response['type'] == JourneyPrecisionController::RESPONSE_UNKNOWN_POINT) { ?>
                        <p class="response_type response_error">Vous avez saisi "<?php echo $destination_point_response['entry']; ?>". Entrée inconnue, veuillez réitérer votre demande.</p>
                        <div>
                            <input type="text" name="destination[name]" />
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
            <div class="clear"></div>
            <div class="button_line">
                <input type="hidden" name="option[clockwise]" value="<?php echo $clockwise; ?>" />
                <input type="hidden" name="datetime" value="<?php echo $datetime; ?>" />
                <input type="submit" value="Valider" />
            </div>
        </form>
    </div>
</div>

<?php include(TEMPLATE_DIR . '/module/journey/precision.js.php'); ?>