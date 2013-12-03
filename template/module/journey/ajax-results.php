<?php use Nv2\Model\Entity\Journey\Journey; ?>

<div>
    <!-- Colonne de résultats (onglets) -->
    <div class="journey_result_list hidden-phone">
        <?php include(TEMPLATE_DIR . '/module/journey/partial/result_list.php'); ?>
    </div>
    
    <!-- Feuille de route détaillée -->
    <div class="journey_detail">
        <?php include(TEMPLATE_DIR . '/module/journey/partial/detail_list.php'); ?>
    </div>
</div>   