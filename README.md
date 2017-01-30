adresse pour proxy pour webetu:
    www-cache.iutnc......... :3128

Préalable
Construire une feuille de style XSL permettant, depuis la dtd de météo (faite au cours du TD Un type de données structurées : XML), de construire un fragment html informant sur la météo au cours de la journée.
Identifier les éléments de coordonnées gps dans l'url de la météo.
tester les apis http://ip-api.com/ et https://freegeoip.net/.
Des outils php
Avec file_get_contents, php vous permet de charger une page distante et de récupérer les entêtes html des requêtes par $http_response_header.

Attention, vous êtes derrière un proxy, il vous faut le configurer par :


$opts = array('http' => array('proxy'=> 'tcp://127.0.0.1:8080', 'request_fulluri'=> true));

$context = stream_context_create($opts);
simplexml_load_string  permettra de transformer la chaîne de caractère récupérée en objet xml aisément utilisable.

XSLTProcessor, avec importStylesheet et transformToXML permet, toujours avec php, d'effectuer les transformations xsl à partir d'un document xml.

Construire une page html à partir de votre géolocalisation ip.
Générer, à partir d'une page php, une page html qui, à partir de la géolocalisation, en xml, IP du client de votre page php :

dans une première partie de la page, récupère les données météo, génére le fragment html à l'aide de la feuille XSL et inclut ce fragment dans la page générée.
Dans une seconde partie de la page, affiche une carte Leaflet plaçant la position du client et centrée sur cette position. Cette carte indiquera les lieux des différents parkings velolib de Nancy. Le popup indiquant les parking à vélos indiqueront le nombre de places libres et le nombre de vélos disponibles.
Penser à vérifier le bon fonctionnement de vos requêtes avant de traiter les données...

... et à rendre votre travail.
