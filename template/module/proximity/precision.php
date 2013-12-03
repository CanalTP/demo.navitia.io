<div class="journey_container"> 
    <div class="window_title">Itinéraires</div>
    
    <h1 class="journey_title">Demande de précision</h1>
    
    <div class="precision_container proximity_precision">
        <form method="post" action="<?php url_link('proximity/execute'); ?>" class="clearfix">
            <fieldset>
                <legend>Lieux publics</legend>
                <div class="point_container">
                    <ul>
                        <?php foreach ($point_response['pointList']['site'] as $pointIndex => $point) { ?>
                            <li>
                                <input id="site_<?php echo $pointIndex; ?>" type="radio" name="point[coord]" value="<?php echo $point->Name . ';' . $point->Object->Coords->Lon . ';' . $point->Object->Coords->Lat; ?>" />
                                <label for="site_<?php echo $pointIndex; ?>">
                                    <span class="point_name"><?php echo $point->Name; ?></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </fieldset>
            <fieldset>
                <legend>Adresses</legend>
                <div class="point_container">
                    <ul>
                        <?php foreach ($point_response['pointList']['address'] as $pointIndex => $point) { ?>
                            <li>
                                <input id="address_<?php echo $pointIndex; ?>" type="radio" name="point[coord]" value="<?php echo $point->Name . ';' . $point->Object->Coords->Lon . ';' . $point->Object->Coords->Lat; ?>" />
                                <label for="address_<?php echo $pointIndex; ?>">
                                    <span class="point_name"><?php echo $point->Name; ?></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </fieldset>
            <fieldset>
                <legend>Arrêts</legend>
                <div class="point_container">
                    <ul>
                        <?php foreach ($point_response['pointList']['stopArea'] as $pointIndex => $point) { ?>
                            <li>
                                <input id="stoparea_<?php echo $pointIndex; ?>" type="radio" name="point[coord]" value="<?php echo $point->Name . ';' . $point->Object->Coords->Lon . ';' . $point->Object->Coords->Lat; ?>" />
                                <label for="stoparea_<?php echo $pointIndex; ?>">
                                    <span class="point_name"><?php echo $point->Name; ?></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </fieldset>
            <div class="button_line">
                <input type="submit" value="Valider" />
            </div>
        </form>
    </div>
</div>