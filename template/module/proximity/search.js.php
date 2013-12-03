<script type="text/javascript">
<!--

//----------------------------------------------------------------------------------------------------------------------
// FirstLetter

// Libellé des types de points (pour traduction)
var itemTypeLabels = {
    'STOP_AREA': '<?php echo escape(translate('object_identifiers', 'stop_area')); ?>',
    'STOP_POINT': '<?php echo escape(translate('object_identifiers', 'stop_point')); ?>',
    'ADMIN': '<?php echo escape(translate('object_identifiers', 'admin')); ?>',
    'ADDRESS': '<?php echo escape(translate('object_identifiers', 'address')); ?>',
    'POI': '<?php echo escape(translate('object_identifiers', 'poi')); ?>'
};

// FirstLetter
var fletter_point = new AutocompleteEngine(
    '<?php echo config_get('webservice', 'Url', 'CrossDomainNavitia');
           echo request_get('RegionName'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/container'); ?>',
    '<?php url_link('autocomplete/get_ajax_html/item'); ?>'
);
fletter_point.setPlaceTypeLabels(itemTypeLabels);
fletter_point.setCallbackFunctions({
    onResult: locateAutocompleteItemOnMap,
    onClick: locateAutocompleteItemOnMap,
    onErase: null
});
fletter_point.setCallbackAttributes({
    markerType: 'origin'
});
fletter_point.bind('proximity_search_point', 'FLPointDivId');

//----------------------------------------------------------------------------------------------------------------------
//

//! Permet de placer un marqueur sur la carte quand l'autocomplétion est utilisée
function locateAutocompleteItemOnMap(place, attributes)
{
    
}

//-->
</script>