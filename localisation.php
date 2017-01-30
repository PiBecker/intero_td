<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && !empty($_POST['adresse_ip']) ){
  // valider l'adresse_ip ici
  $url = "http://ip-api.com/xml/" . $_POST['adresse_ip'];
  $data = file_get_contents($url);
  $data_xml = simplexml_load_string($data);
  if(empty($data_xml)){
    echo('aucun résultat pour votre demande');
  }
  else{
    $coord = [];

    foreach ($data_xml as $key => $value) {
      if($key == "country"){
        $coord["country"] =  (string) $value;
      }
      if($key == "countryCode"){
        $coord["countryCode"] =  (string) $value;
      }
      if($key == "region"){
        $coord["region"] =  (string) $value;
      }
      if($key == "regionName"){
        $coord["regionName"] =  (string) $value;
      }
      if($key == "city"){
        $coord["city"] =  (string) $value;
      }
      if($key == "zip"){
        $coord["zip"] =  (string) $value;
      }
      if($key == "timezone"){
        $coord["timezone"] =  (string) $value;
      }
      if($key == "lat"){
        $coord["latitude"] =  (string) $value;
      }
      if($key == "lon"){
        $coord["longitude"] =  (string) $value;
      }
    }

    echo('<h1>Résultats pour votre recherche</h1>');
    echo("Pays : " . json_encode($coord['country']) . "<br>");
    echo("Code Pays : " . json_encode($coord['countryCode']) . "<br>");
    echo("Région : " . json_encode($coord['region']) . "<br>");
    echo("Région : " . json_encode($coord['regionName']) . "<br>");
    echo("Ville : " . json_encode($coord['city']) . "<br>");
    echo("Code postal : " . json_encode($coord['zip']) . "<br>");
    echo("Fuseau : " . json_encode($coord['timezone']) . "<br>");
    echo("Latitude : " . json_encode($coord['latitude']) . "<br>");
    echo("Longitude : " . json_encode($coord['longitude']));

    echo('<h1>Votre météo</h1>');
    echo('<p>fournie par <a href="www.infoclimat.fr">infoclimat.fr</a></p>');
    // include 'meteo.php';
    $meteo_raw = @file_get_contents('http://www.infoclimat.fr/public-api/gfs/xml?_ll='.$coord["latitude"].','.$coord["longitude"].'&_auth=AxkAFwd5BiRWe1RjAXdVfABoAjdbLVB3US0AYw9qVyoIY1AxAGBTNQVrA34HKAUzUn9XNF5lBDQGbQtzWihSMwNpAGwHbAZhVjlUMQEuVX4ALgJjW3tQd1EzAGcPa1cqCGlQNQBkUy8FawNhBzIFL1JiVyhefgQ9BmELZFoyUjYDaQBhB2QGY1YxVCkBLlVkADsCalsyUG5RZABnD2RXZwhsUGcAYVMyBWMDfwc%2BBTlSaFc%2BXmYENAZiC29aKFIuAxkAFwd5BiRWe1RjAXdVfABmAjxbMA%3D%3D&_c=1c8b9c606cfee533f2eaa85bfc4856e9');
    if(empty($meteo_raw)){
      echo('Votre position est en dehors de la zone France. Aucune donnée disponible.');
    }
    else{
      $meteo_xml = simplexml_load_string($meteo_raw);
      // var_dump($meteo_xml);
      $url_meteo = 'http://www.infoclimat.fr/public-api/mixed/iframeSLIDE?_ll='.$coord["latitude"].','.$coord["longitude"].'&_inc=WyJQYXJpcyIsIjQyIiwiMjk4ODUwNyIsIkZSIl0=&_auth=AxkAFwd5BiRWe1RjAXdVfABoAjdbLVB3US0AYw9qVyoIY1AxAGBTNQVrA34HKAUzUn9XNF5lBDQGbQtzWihSMwNpAGwHbAZhVjlUMQEuVX4ALgJjW3tQd1EzAGcPa1cqCGlQNQBkUy8FawNhBzIFL1JiVyhefgQ9BmELZFoyUjYDaQBhB2QGY1YxVCkBLlVkADsCalsyUG5RZABnD2RXZwhsUGcAYVMyBWMDfwc%2BBTlSaFc%2BXmYENAZiC29aKFIuAxkAFwd5BiRWe1RjAXdVfABmAjxbMA%3D%3D&_c=1c8b9c606cfee533f2eaa85bfc4856e9';
      echo('<iframe seamless width="888" height="400" frameborder="0" src="'.$url_meteo.'"></iframe>');
    }
  }
}
 ?>
