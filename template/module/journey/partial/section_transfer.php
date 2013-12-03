<?php

if (isset($journey->SectionList[$sectionIndex + 1])) { 
    $next_section = $journey->SectionList[$sectionIndex + 1];
}

?>
<p>
    <span>Rejoindre à pied l'autre arrêt <strong><?php echo $section->Destination->Name; ?></strong></span>
    <span>(durée : <?php echo gmdate('i', $section->Duration); ?> <abbr title="minutes">min</abbr>)</span>
    <?php if (isset($next_section->Duration) && $next_section->Duration > 0) { ?>
        <br />
        <span>Puis attente de <?php echo gmdate('i', $next_section->Duration); ?> <abbr title="minutes">min</abbr></span>
    <?php } ?>
</p>