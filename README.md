navitia.io demo website
=======================

Requirements
------------

- PHP >= 5.3 (5.4 or greater is recommended)
- php_curl extension

Installation
------------

Simply put the source files on a web server, no specific settings required.


Plug in navitia.io webservice
-----------------------------

In the 'config/webservice.php' file, change:

* 'Navitia' setting: use the webservice URL. Example: 'http://api.navitia.io/v1/'.
* 'CrossDomainNavitia' setting: use the webservice URL with the proxy script '/crossdomain_service.php?url=' don't forget the beginning slash to avoid unexpected behaviors...
* 'Token' setting: use a token provided by CanalTP (contact us!).


Set up home region list
-----------------------

In the 'data/region_coords/coords.php' file, create as much link as needed to handle region data sources by using the region_id.
Example:

    $data = array(
        'transilien' => array(
            'order' => 8,
            'init_coords' => array(
                'lon' => 2.35239,
                'lat' => 48.857035,
                'zoom' => 11,
            ),
        ),
    );

In the example above, 'transilien' is the region_id.

Notice: in version 0.13, region_id detection is case sensitive.


Set up home region name labels
------------------------------

In the 'data/translations/{locale}/region.name.php' file, make links between region_id and labels.
Example:

    $translations = array(
        'paris' => 'Paris',
        'nantes' => 'Nantes Métropole (Tan)',
        'blois' => 'Communauté d'Agglomération de Blois (TUB)',
        'lille' => 'Lille Métropole (Transpole)',
        'rennes' => 'Rennes Métropole (STAR)',
        'toulouse' => 'Toulouse Métropole (Tisséo)',
        'transilien' => 'Transilien',
        'paca' => 'TER PACA',
        'ny' => 'New-York City (MTA, NJTA et PATH)',
        'sf' => 'San Francisco (MUNI et BART)',
    );

Notice: in version 0.13, region_id detection is case sensitive.