Prototype IHM Navitia 2
=======================

Configuration requise
---------------------

- PHP >= 5.3
- Extension PHP CURL

Installation
------------

Copier les sources sur un serveur Web, aucun virtual host ou configuration sp�cifique requise.


Lier l'IHM � Navitia
--------------------

Dans le fichier "config/webservice.php", changer les valeurs de "Navitia" et "CrossDomainNavitia" pour qu'elles correspondent � celles des moteurs. Exemple : "http://api.navitia.io/v0/"


Configurer le listing des r�gions de l'accueil
----------------------------------------------

Dans le fichier "data/region_coords", cr�er autant de sections dont la cl� correspond au "region_id" que source de donn�es.
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

Dans l'exemple, "transilien" correspond au region_id de la source de donn�es Navitia.

A l'heure actuelle (version "j�suite"), les region_id doivent �tre ramen�s en minuscule (comme dans l'exemple) pour que la traduction soit effectu�e correctement.


Configurer les traductions des r�gions de l'accueil
---------------------------------------------------

Dans le fichier "data/translations/fre-FR/region.name.php", cr�er des correspondances entre les "region_id" de chaque source de donn�es et un libell� � afficher dans la langue souhait�e :
Exemple :

$translations = array(
    'paris' => 'Paris',
    'nantes' => 'Nantes M�tropole (Tan)',
    'blois' => 'Communaut� d�Agglom�ration de Blois (TUB)',
    'lille' => 'Lille M�tropole (Transpole)',
    'rennes' => 'Rennes M�tropole (STAR)',
    'toulouse' => 'Toulouse M�tropole (Tiss�o)',
    'transilien' => 'Transilien',
    'paca' => 'TER PACA',
    'ny' => 'New-York City (MTA, NJTA et PATH)',
    'sf' => 'San Francisco (MUNI et BART)',
);

A l'heure actuelle (version "j�suite"), les region_id doivent �tre ramen�s en minuscule (comme dans l'exemple) pour que la traduction soit effectu�e correctement.