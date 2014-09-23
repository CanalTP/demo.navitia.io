<p class="section_mode">
    <?php if (isset($section->displayInformations['physical_mode'])) { ?>
        <img src="<?php img_src('/img/mode/' . strtolower($section->displayInformations['physical_mode']) .'.png'); ?>" alt="" />
    <?php } ?>
</p>

<div class="section_content">
    <span class="section_title">
        <span class="headsign">
            <?php if (isset($section->displayInformations['physical_mode'])) { ?>
                <?php echo $section->displayInformations['physical_mode']; ?>
            <?php } ?>
            (Ligne <?php echo $section->displayInformations['code']; ?>)
            <?php if (isset($section->displayInformations['headsign'])) { ?>
                <?php echo $section->displayInformations['headsign']; ?>
            <?php } ?>
        </span>
        <span class="direction">en direction de <strong><?php echo $section->displayInformations['direction']; ?></strong></span>
    </span>
    <ol class="journey_stop_list">
        <li class="journey_section_step journey_section_departure">
            <span class="time"><?php echo $section->departureDateTime->format('H:i'); ?></span>
            <span class="description">Monter à <strong><?php echo $section->from->name; ?></strong>
                (<a href="<?php url_link('schedule/departure_board/' .
                    $section->links['line']->id . '/' .
                    $section->links['route']->id . '/' .
                    $section->from->id . '/' .
                    $section->departureDateTime->format('Ymd\T040000')
                ); ?>">Horaires</a>)
            </span>
        </li>
    
        <?php if (is_array($section->stopDateTimes)) { ?>
            <?php foreach ($section->stopDateTimes as $stopDateTime) { ?>
                <li class="journey_section_step journey_section_stop">
                    <span class="time"><?php echo $stopDateTime->departureDateTime->format('H:i'); ?></span>
                    <span class="description">Arrêt à <strong><?php echo $stopDateTime->stopPoint->name; ?></strong>
                        (<a href="<?php url_link('schedule/departure_board/' .
                            $section->links['line']->id . '/' .
                            $section->links['route']->id . '/' .
                            $stopDateTime->stopPoint->id . '/' .
                            $section->departureDateTime->format('Ymd\T040000')
                        ); ?>">Horaires</a>)
                    </span>
                </li>
            <?php } ?>
        <?php } ?>
    
        <li class="journey_section_step journey_section_destination">
            <span class="time"><?php echo $section->arrivalDateTime->format('H:i'); ?></span>
            <span class="description">Descendre à <strong><?php echo $section->to->name; ?></strong>
                (<a href="<?php url_link('schedule/departure_board/' .
                    $section->links['line']->id . '/' .
                    $section->links['route']->id . '/' .
                    $section->to->id . '/' .
                    $section->departureDateTime->format('Ymd\T040000')
                ); ?>">Horaires</a>)
            </span>
        </li>
    </ol>
</div>

<div class="section_options">
    <a href="javascript:void(0);" class="option_link">
        <img src="<?php img_src('/img/journey_plus_button.png'); ?>" alt="+" width="16" height="16" />
    </a>
    <ul class="avoid hide">
        <li><a href="javascript:void(0);" class="mode;<?php echo $section->links['physical_mode']->id; ?>">Eviter le <strong><?php echo $section->displayInformations['physical_mode']; ?></strong></a></li>
        <li><a href="javascript:void(0);" class="line;<?php echo $section->links['line']->id; ?>">Eviter la ligne <strong><?php echo $section->displayInformations['code']; ?></strong></a></li>
        <li><a href="javascript:void(0);" class="stop;<?php echo $section->from->id; ?>">Eviter l'arrêt <strong><?php echo $section->from->name; ?></strong></a></li>
        <li><a href="javascript:void(0);" class="stop;<?php echo $section->to->id; ?>">Eviter l'arrêt <strong><?php echo $section->to->name; ?></strong></a></li>
    </ul>
</div>

<div class="clear"></div>