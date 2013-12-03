<p class="section_mode">
    <?php if (isset($section->TransportDisplayInfo['physical_mode'])) { ?>
        <img src="<?php img_src('/img/mode/' . strtolower($section->TransportDisplayInfo['physical_mode']) .'.png'); ?>" alt="" />
    <?php } ?>
</p>

<div class="section_content">
    <span class="section_title">
        <span class="headsign">
            <?php if (isset($section->TransportDisplayInfo['physical_mode'])) { ?>
                <?php echo $section->TransportDisplayInfo['physical_mode']; ?>
            <?php } ?>
            (Ligne <?php echo $section->TransportDisplayInfo['line_code']; ?>)
            <?php if (isset($section->TransportDisplayInfo['headsign'])) { ?>
                <?php echo $section->TransportDisplayInfo['headsign']; ?>
            <?php } ?>
        </span>
        <span class="direction">en direction de <strong><?php echo $section->Destination->Name; ?></strong></span>
    </span>
    <ol class="journey_stop_list">
        <li class="journey_section_step journey_section_departure">
            <span class="time"><?php echo $section->DepartureTime->format('H:i'); ?></span>
            <span class="description">Monter à <strong><?php echo $section->Origin->Name; ?></strong>
                (<a href="<?php url_link('schedule/departure_board/' . $section->TransportUriInfo['line'] . '/' . $section->TransportUriInfo['route'] . '/' . $section->Origin->Uri . '/' . $section->DepartureTime->format('Ymd\T040000')) ; ?>">Horaires</a>)
            </span>
        </li>
    
        <?php if (is_array($section->IntermediateStopList)) { ?>
            <?php foreach ($section->IntermediateStopList as $stopTime) { ?>
                <li class="journey_section_step journey_section_stop">
                    <span class="time"><?php echo $stopTime->DepartureTime->format('H:i'); ?></span>
                    <span class="description">Arrêt à <strong><?php echo $stopTime->StopPoint->Name; ?></strong>
                        (<a href="<?php url_link('schedule/departure_board/' . $section->TransportUriInfo['line'] . '/' . $section->TransportUriInfo['route'] . '/' . $stopTime->StopPoint->Uri . '/' . $section->DepartureTime->format('Ymd\T040000')) ; ?>">Horaires</a>)
                    </span>
                </li>
            <?php } ?>
        <?php } ?>
    
        <li class="journey_section_step journey_section_destination">
            <span class="time"><?php echo $section->ArrivalTime->format('H:i'); ?></span>
            <span class="description">Descendre à <strong><?php echo $section->Destination->Name; ?></strong>
                (<a href="<?php url_link('schedule/departure_board/' . $section->TransportUriInfo['line'] . '/' . $section->TransportUriInfo['route'] . '/' . $section->Destination->Uri . '/' . $section->DepartureTime->format('Ymd\T040000')) ; ?>">Horaires</a>)
            </span>
        </li>
    </ol>
</div>

<div class="section_options">
    <a href="javascript:void(0);" class="option_link">
        <img src="<?php img_src('/img/journey_plus_button.png'); ?>" alt="+" width="16" height="16" />
    </a>
    <ul class="avoid hide">
        <?php /*
        <li><a href="javascript:void(0);" class="mode;<?php echo $section->TransportUriInfo['physical_mode']; ?>">Eviter le <strong><?php echo $section->TransportDisplayInfo['physical_mode']; ?></strong></a></li>
        */ ?>
        <li><a href="javascript:void(0);" class="line;<?php echo $section->TransportUriInfo['line']; ?>">Eviter la ligne <strong><?php echo $section->TransportDisplayInfo['line_code']; ?></strong></a></li>
        <?php /*
        <li><a href="javascript:void(0);" class="stop;<?php echo $section->Origin->Uri; ?>">Eviter l'arrêt <strong><?php echo $section->Origin->Name; ?></strong></a></li>
        <li><a href="javascript:void(0);" class="stop;<?php echo $section->Destination->Uri; ?>">Eviter l'arrêt <strong><?php echo $section->Destination->Name; ?></strong></a></li>
        */ ?>
    </ul>
</div>

<div class="clear"></div>