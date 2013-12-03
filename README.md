Prototype IHM Navitia 2
=======================

Configuration requise
---------------------

- PHP >= 5.3
- Extension PHP CURL

Installation
------------

Copier les sources sur un serveur Web, aucun virtual host ou configuration spécifique requise.


Lier l'IHM à Navitia
--------------------

Dans le fichier "config/webservice.php", changer les valeurs de "Navitia" et "CrossDomainNavitia" pour qu'elles correspondent à celles des moteurs. Exemple : "http://api.navitia.io/v0/"


Configurer le listing des régions de l'accueil
----------------------------------------------

Dans le fichier "data/region_coords", créer autant de sections dont la clé correspond au "region_id" que source de données.
Exemple :

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

Dans l'exemple, "transilien" correspond au region_id de la source de données Navitia.

A l'heure actuelle (version "jésuite"), les region_id doivent être ramenés en minuscule (comme dans l'exemple) pour que la traduction soit effectuée correctement.


Configurer les traductions des régions de l'accueil
---------------------------------------------------

Dans le fichier "data/translations/fre-FR/region.name.php", créer des correspondances entre les "region_id" de chaque source de données et un libellé à afficher dans la langue souhaitée :
Exemple :

$translations = array(
    'paris' => 'Paris',
    'nantes' => 'Nantes Métropole (Tan)',
    'blois' => 'Communauté d’Agglomération de Blois (TUB)',
    'lille' => 'Lille Métropole (Transpole)',
    'rennes' => 'Rennes Métropole (STAR)',
    'toulouse' => 'Toulouse Métropole (Tisséo)',
    'transilien' => 'Transilien',
    'paca' => 'TER PACA',
    'ny' => 'New-York City (MTA, NJTA et PATH)',
    'sf' => 'San Francisco (MUNI et BART)',
);

A l'heure actuelle (version "jésuite"), les region_id doivent être ramenés en minuscule (comme dans l'exemple) pour que la traduction soit effectuée correctement.