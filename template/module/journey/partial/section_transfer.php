<?php

if (isset($journey->sections[$sectionIndex + 1])) { 
    $next_section = $journey->sections[$sectionIndex + 1];
}

?>
<p>
    <span>Rejoindre à pied l'autre arrêt <strong><?php echo $section->to->name; ?></strong></span>
    <span>(durée : <?php echo gmdate('i', $section->duration); ?> <abbr title="minutes">min</abbr>)</span>
    <?php if (isset($next_section->duration) && $next_section->duration > 0) { ?>
        <br />
        <span>Puis attente de <?php echo gmdate('i', $next_section->duration); ?> <abbr title="minutes">min</abbr></span>
    <?php } ?>
</p>