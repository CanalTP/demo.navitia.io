<?php

$classList = array(
    // Classes du framework
    'Nv2\\Lib\\Nv2\\Config\\Config'                                  => CLASS_DIR . '/Lib/Nv2/Config/Config.php',
    'Nv2\\Lib\\Nv2\\Config\\ConfigFile'                              => CLASS_DIR . '/Lib/Nv2/Config/ConfigFile.php',
    'Nv2\\Lib\\Nv2\\Controller\\Controller'                          => CLASS_DIR . '/Lib/Nv2/Controller/Controller.php',
    'Nv2\\Lib\\Nv2\\Core\\Module'                                    => CLASS_DIR . '/Lib/Nv2/Core/Module.php',
    'Nv2\\Lib\\Nv2\\Data\\FileData'                                  => CLASS_DIR . '/Lib/Nv2/Data/FileData.php',
    'Nv2\\Lib\\Nv2\\Debug\\Debug'                                    => CLASS_DIR . '/Lib/Nv2/Debug/Debug.php',
    'Nv2\\Lib\\Nv2\\Http\\Request'                                   => CLASS_DIR . '/Lib/Nv2/Http/Request.php',
    'Nv2\\Lib\\Nv2\\Http\\Url'                                       => CLASS_DIR . '/Lib/Nv2/Http/Url.php',
    'Nv2\\Lib\\Nv2\\Service\\IdConverter'                            => CLASS_DIR . '/Lib/Nv2/Service/IdConverter.php',
    'Nv2\\Lib\\Nv2\\Service\\ServiceRequest'                         => CLASS_DIR . '/Lib/Nv2/Service/ServiceRequest.php',
    'Nv2\\Lib\\Nv2\\Service\\ParallelServiceRequest'                 => CLASS_DIR . '/Lib/Nv2/Service/ParallelServiceRequest.php',
    'Nv2\\Lib\\Nv2\\Service\\NavitiaRequest'                         => CLASS_DIR . '/Lib/Nv2/Service/NavitiaRequest.php',
    'Nv2\\Lib\\Nv2\\Template\\Template'                              => CLASS_DIR . '/Lib/Nv2/Template/Template.php',
    'Nv2\\Lib\\Nv2\\Text\\Translator'                                => CLASS_DIR . '/Lib/Nv2/Text/Translator.php',
    'Nv2\\Lib\\Nv2\\Text\\TranslationFile'                           => CLASS_DIR . '/Lib/Nv2/Text/TranslationFile.php',
    
    // Controleurs transverses
    'Nv2\\Controller\\Autocomplete\\AutocompleteAjaxHtmlController'  => CLASS_DIR . '/Controller/Autocomplete/AutocompleteAjaxHtmlController.php',
    'Nv2\\Controller\\Home\\HomeController'                          => CLASS_DIR . '/Controller/Home/HomeController.php',
    'Nv2\\Controller\\Home\\SelectRegionController'                  => CLASS_DIR . '/Controller/Home/SelectRegionController.php',
    'Nv2\\Controller\\ParentSite\\ParentSiteController'              => CLASS_DIR . '/Controller/ParentSite/ParentSiteController.php',
    
    // Controleurs itinéraires
    'Nv2\\Controller\\Journey\\JourneyExecuteController'             => CLASS_DIR . '/Controller/Journey/JourneyExecuteController.php',
    'Nv2\\Controller\\Journey\\JourneyPrecisionController'           => CLASS_DIR . '/Controller/Journey/JourneyPrecisionController.php',
    'Nv2\\Controller\\Journey\\JourneyResultsController'             => CLASS_DIR . '/Controller/Journey/JourneyResultsController.php',
    'Nv2\\Controller\\Journey\\JourneySearchController'              => CLASS_DIR . '/Controller/Journey/JourneySearchController.php',
    
    // Controleurs proximité
    'Nv2\\Controller\\Proximity\\ProximitySearchController'          => CLASS_DIR . '/Controller/Proximity/ProximitySearchController.php',
    'Nv2\\Controller\\Proximity\\ProximityExecuteController'         => CLASS_DIR . '/Controller/Proximity/ProximityExecuteController.php',
    'Nv2\\Controller\\Proximity\\ProximityPrecisionController'       => CLASS_DIR . '/Controller/Proximity/ProximityPrecisionController.php',
    'Nv2\\Controller\\Proximity\\ProximityResultsController'         => CLASS_DIR . '/Controller/Proximity/ProximityResultsController.php',
    
    // Controleurs horaires
    'Nv2\\Controller\\Schedule\\ScheduleStopExecuteController'       => CLASS_DIR . '/Controller/Schedule/ScheduleStopExecuteController.php',
    'Nv2\\Controller\\Schedule\\ScheduleCoordExecuteController'      => CLASS_DIR . '/Controller/Schedule/ScheduleCoordExecuteController.php',
    'Nv2\\Controller\\Schedule\\ScheduleLineExecuteController'       => CLASS_DIR . '/Controller/Schedule/ScheduleLineExecuteController.php',
    'Nv2\\Controller\\Schedule\\ScheduleTrainExecuteController'      => CLASS_DIR . '/Controller/Schedule/ScheduleTrainExecuteController.php',
    'Nv2\\Controller\\Schedule\\ScheduleSearchController'            => CLASS_DIR . '/Controller/Schedule/ScheduleSearchController.php',
    'Nv2\\Controller\\Schedule\\ScheduleSelectLineController'        => CLASS_DIR . '/Controller/Schedule/ScheduleSelectLineController.php',
    'Nv2\\Controller\\Schedule\\ScheduleSelectDirectionController'   => CLASS_DIR . '/Controller/Schedule/ScheduleSelectDirectionController.php',
    'Nv2\\Controller\\Schedule\\ScheduleSelectStopController'        => CLASS_DIR . '/Controller/Schedule/ScheduleSelectStopController.php',
    'Nv2\\Controller\\Schedule\\ScheduleStopScheduleController'      => CLASS_DIR . '/Controller/Schedule/ScheduleStopScheduleController.php',
    'Nv2\\Controller\\Schedule\\ScheduleProximityBoardController'    => CLASS_DIR . '/Controller/Schedule/ScheduleProximityBoardController.php',
    'Nv2\\Controller\\Schedule\\ScheduleRouteScheduleController'     => CLASS_DIR . '/Controller/Schedule/ScheduleRouteScheduleController.php',
    
    // Controleurs meeting
    'Nv2\\Controller\\Meeting\\MeetingExecuteController'             => CLASS_DIR . '/Controller/Meeting/MeetingExecuteController.php',
    'Nv2\\Controller\\Meeting\\MeetingPrecisionController'           => CLASS_DIR . '/Controller/Meeting/MeetingPrecisionController.php',
    'Nv2\\Controller\\Meeting\\MeetingResultsController'             => CLASS_DIR . '/Controller/Meeting/MeetingResultsController.php',
    'Nv2\\Controller\\Meeting\\MeetingSearchController'              => CLASS_DIR . '/Controller/Meeting/MeetingSearchController.php',
    
    // API Navitia (instances nécessaires pour utiliser les fonctionnalités)
    'Nv2\\Model\\NavitiaApi\\Base\\NavitiaApi'                       => CLASS_DIR . '/Model/NavitiaApi/Base/NavitiaApi.php',
    'Nv2\\Model\\NavitiaApi\\Journey\\Journeys'                      => CLASS_DIR . '/Model/NavitiaApi/Journey/Journeys.php',
    'Nv2\\Model\\NavitiaApi\\Journey\\StreetNetwork'                 => CLASS_DIR . '/Model/NavitiaApi/Journey/StreetNetwork.php',
    'Nv2\\Model\\NavitiaApi\\Proximity\\ProximityList'               => CLASS_DIR . '/Model/NavitiaApi/Proximity/ProximityList.php',
    'Nv2\\Model\\NavitiaApi\\Schedule\\StopSchedules'                => CLASS_DIR . '/Model/NavitiaApi/Schedule/StopSchedules.php',
    'Nv2\\Model\\NavitiaApi\\Schedule\\RouteSchedules'               => CLASS_DIR . '/Model/NavitiaApi/Schedule/RouteSchedules.php',
    
    // Entités de base
    'Nv2\\Model\\Entity\\Base\\Entity'                               => CLASS_DIR . '/Model/Entity/Base/Entity.php',
    
    // Entités liées aux API Navitia
    'Nv2\\Model\\Entity\\Places\\Place'                              => CLASS_DIR . '/Model/Entity/Places/Place.php',
    'Nv2\\Model\\Entity\\Journey\\Journey'                           => CLASS_DIR . '/Model/Entity/Journey/Journey.php',
    'Nv2\\Model\\Entity\\Journey\\Section'                           => CLASS_DIR . '/Model/Entity/Journey/Section.php',
    'Nv2\\Model\\Entity\\Journey\\SectionStreetNetwork'              => CLASS_DIR . '/Model/Entity/Journey/SectionStreetNetwork.php',
    'Nv2\\Model\\Entity\\Journey\\SectionTransfer'                   => CLASS_DIR . '/Model/Entity/Journey/SectionTransfer.php',
    'Nv2\\Model\\Entity\\Journey\\SectionWaiting'                    => CLASS_DIR . '/Model/Entity/Journey/SectionWaiting.php',
    'Nv2\\Model\\Entity\\Journey\\SectionPublicTransport'            => CLASS_DIR . '/Model/Entity/Journey/SectionPublicTransport.php',
    'Nv2\\Model\\Entity\\Journey\\StreetNetworkPathItem'             => CLASS_DIR . '/Model/Entity/Journey/StreetNetworkPathItem.php',
    'Nv2\\Model\\Entity\\Journey\\GeoJson'                           => CLASS_DIR . '/Model/Entity/Journey/GeoJson.php',
    'Nv2\\Model\\Entity\\Proximity\\ProximityListItem'               => CLASS_DIR . '/Model/Entity/Proximity/ProximityListItem.php',
    'Nv2\\Model\\Entity\\Schedule\\DepartureBoards\\Board'           => CLASS_DIR . '/Model/Entity/Schedule/DepartureBoards/Board.php',
    'Nv2\\Model\\Entity\\Schedule\\DepartureBoards\\BoardItem'       => CLASS_DIR . '/Model/Entity/Schedule/DepartureBoards/BoardItem.php',
    'Nv2\\Model\\Entity\\Schedule\\RouteSchedules\\Result'           => CLASS_DIR . '/Model/Entity/Schedule/RouteSchedules/Result.php',
    'Nv2\\Model\\Entity\\Schedule\\RouteSchedules\\TableRow'         => CLASS_DIR . '/Model/Entity/Schedule/RouteSchedules/TableRow.php',
    
    'Nv2\\Model\\Entity\\Data\\Uri'                                  => CLASS_DIR . '/Model/Entity/Data/Uri.php',
    'Nv2\\Model\\Entity\\Data\\Link'                                 => CLASS_DIR . '/Model/Entity/Data/Link.php',
    
    // Entités TC (possibilité d'utiliser getList() {static})
    'Nv2\\Model\\Entity\\Transport\\EntryPoint'                      => CLASS_DIR . '/Model/Entity/Transport/EntryPoint.php',
    'Nv2\\Model\\Entity\\Transport\\Company'                         => CLASS_DIR . '/Model/Entity/Transport/Company.php',
    'Nv2\\Model\\Entity\\Transport\\Network'                         => CLASS_DIR . '/Model/Entity/Transport/Network.php',
    'Nv2\\Model\\Entity\\Transport\\CommercialMode'                  => CLASS_DIR . '/Model/Entity/Transport/CommercialMode.php',
    'Nv2\\Model\\Entity\\Transport\\PhysicalMode'                    => CLASS_DIR . '/Model/Entity/Transport/PhysicalMode.php',
    'Nv2\\Model\\Entity\\Transport\\StopArea'                        => CLASS_DIR . '/Model/Entity/Transport/StopArea.php',
    'Nv2\\Model\\Entity\\Transport\\StopPoint'                       => CLASS_DIR . '/Model/Entity/Transport/StopPoint.php',
    'Nv2\\Model\\Entity\\Transport\\StopDateTime'                    => CLASS_DIR . '/Model/Entity/Transport/StopDateTime.php',
    'Nv2\\Model\\Entity\\Transport\\JourneyPattern'                  => CLASS_DIR . '/Model/Entity/Transport/JourneyPattern.php',
    'Nv2\\Model\\Entity\\Transport\\JourneyPatternPoint'             => CLASS_DIR . '/Model/Entity/Transport/JourneyPatternPoint.php',
    'Nv2\\Model\\Entity\\Transport\\Line'                            => CLASS_DIR . '/Model/Entity/Transport/Line.php',
    'Nv2\\Model\\Entity\\Transport\\Connection'                      => CLASS_DIR . '/Model/Entity/Transport/Connection.php',
    'Nv2\\Model\\Entity\\Transport\\Route'                           => CLASS_DIR . '/Model/Entity/Transport/Route.php',
    'Nv2\\Model\\Entity\\Transport\\VehicleJourney'                  => CLASS_DIR . '/Model/Entity/Transport/VehicleJourney.php',
    'Nv2\\Model\\Entity\\Transport\\Admin'                           => CLASS_DIR . '/Model/Entity/Transport/Admin.php',
    'Nv2\\Model\\Entity\\Transport\\Fare'                            => CLASS_DIR . '/Model/Entity/Transport/Fare.php',
    'Nv2\\Model\\Entity\\Transport\\FareTotal'                       => CLASS_DIR . '/Model/Entity/Transport/FareTotal.php',
    
    // Entités géographiques (possibilité d'utiliser getList() {static})
    'Nv2\\Model\\Entity\\Geo\\Coord'                                 => CLASS_DIR . '/Model/Entity/Geo/Coord.php',
    'Nv2\\Model\\Entity\\Geo\\Region'                                => CLASS_DIR . '/Model/Entity/Geo/Region.php',
    'Nv2\\Model\\Entity\\Geo\\Address'                               => CLASS_DIR . '/Model/Entity/Geo/Address.php',
    'Nv2\\Model\\Entity\\Geo\\Poi'                                   => CLASS_DIR . '/Model/Entity/Geo/Poi.php',
    'Nv2\\Model\\Entity\\Geo\\City'                                  => CLASS_DIR . '/Model/Entity/Geo/City.php',
    'Nv2\\Model\\Entity\\Geo\\Country'                               => CLASS_DIR . '/Model/Entity/Geo/Country.php',
);