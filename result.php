<?php

/***************** METEO *****************/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && !empty($_POST['adresse_ip']) ){
  // valider l'adresse_ip ici
  $ip = $_POST['adresse_ip'];
  $url_meteo = "http://ip-api.com/xml/" . $_POST['adresse_ip'];
  $data_meteo = @file_get_contents($url_meteo);
  $data_meteo_xml = simplexml_load_string($data_meteo);
  if(empty($data_meteo_xml)){
    echo('aucun résultat pour votre demande');
  }
  else{
    $coord = [];

    foreach ($data_meteo_xml as $key => $value) {
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

/***************** VELIB STAN *****************/
  $data_velib = @@file_get_contents('http://www.velostanlib.fr/service/carto');
  $data_velib_xml = simplexml_load_string($data_velib);
  if(!empty($data_velib_xml)){
    if(isset($data_velib_xml) && isset($data_velib_xml->markers)){
      $stations=[];
      for($i=1 ; $i < sizeof($data_velib_xml->markers->marker) ; $i++) {
        $place_velib = file_get_contents('http://www.velostanlib.fr/service/stationdetails/nancy/' . $data_velib_xml->markers->marker[$i]['number']);
        $place_velib_xml = simplexml_load_string($place_velib);
        $data_velib_xml->markers->marker[$i]['places_availables'] = $place_velib_xml->free;
        $stations[] = $data_velib_xml->markers->marker[$i];

        /* facultatif, si jamais le flux 'plante' */
        unset($place_velib);
        unset($place_velib_xml);
      }
    }
  }

    echo('
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/style.css" />

        <title>Météo</title>
      </head>
      <body>
        <div class="row">
          <div class="col-md-6 col-md-offset-5">
            <img alt="météo" src="assets/img/logo.png">
          </div>
        </div>
        <hr>
        <div class="container">');

    echo("<h3 class='text-center'>Informations pour l'adresse ip : " . $ip . "</h3>");
    echo("<b>Pays :</b> " . $coord['country'] . "<br>");
    echo("<b>Code Pays :</b> " . $coord['countryCode'] . "<br>");
    echo("<b>Région :</b> " . $coord['region'] . "<br>");
    echo("<b>Région :</b> " . $coord['regionName'] . "<br>");
    echo("<b>Ville :</b> " . $coord['city'] . "<br>");
    echo("<b>Code postal :</b> " . $coord['zip'] . "<br>");
    echo("<b>Fuseau :</b> " . $coord['timezone'] . "<br>");
    echo("<b>Latitude :</b> " . $coord['latitude'] . "<br>");
    echo("<b>Longitude :</b> " . $coord['longitude']);
    echo("<br><br>");

    $meteo_raw = @file_get_contents('http://www.infoclimat.fr/public-api/gfs/xml?_ll='.$coord["latitude"].','.$coord["longitude"].'&_auth=AxkAFwd5BiRWe1RjAXdVfABoAjdbLVB3US0AYw9qVyoIY1AxAGBTNQVrA34HKAUzUn9XNF5lBDQGbQtzWihSMwNpAGwHbAZhVjlUMQEuVX4ALgJjW3tQd1EzAGcPa1cqCGlQNQBkUy8FawNhBzIFL1JiVyhefgQ9BmELZFoyUjYDaQBhB2QGY1YxVCkBLlVkADsCalsyUG5RZABnD2RXZwhsUGcAYVMyBWMDfwc%2BBTlSaFc%2BXmYENAZiC29aKFIuAxkAFwd5BiRWe1RjAXdVfABmAjxbMA%3D%3D&_c=1c8b9c606cfee533f2eaa85bfc4856e9');
    if(empty($meteo_raw)){
      echo('Votre position est en dehors de la zone France. Aucune donnée disponible.');
    }
    else{
      $meteo_xml = simplexml_load_string($meteo_raw);
      $url_meteo_meteo = 'http://www.infoclimat.fr/public-api/mixed/iframeSLIDE?_ll='.$coord["latitude"].','.$coord["longitude"].'&_inc=WyJQYXJpcyIsIjQyIiwiMjk4ODUwNyIsIkZSIl0=&_auth=AxkAFwd5BiRWe1RjAXdVfABoAjdbLVB3US0AYw9qVyoIY1AxAGBTNQVrA34HKAUzUn9XNF5lBDQGbQtzWihSMwNpAGwHbAZhVjlUMQEuVX4ALgJjW3tQd1EzAGcPa1cqCGlQNQBkUy8FawNhBzIFL1JiVyhefgQ9BmELZFoyUjYDaQBhB2QGY1YxVCkBLlVkADsCalsyUG5RZABnD2RXZwhsUGcAYVMyBWMDfwc%2BBTlSaFc%2BXmYENAZiC29aKFIuAxkAFwd5BiRWe1RjAXdVfABmAjxbMA%3D%3D&_c=1c8b9c606cfee533f2eaa85bfc4856e9';
      echo('<div class="text-center">');
      echo('<iframe seamless width="888" height="400" frameborder="0" src="'.$url_meteo_meteo.'"></iframe>');
      echo('<p>fournie par <a href="http://www.infoclimat.fr" target="new_blank">infoclimat.fr</a></p>');
      echo('</div>');
    }

    echo('<hr>');
    echo("<h3 class='text-center'>Disponibilités VélibStan à Nancy</h3><br>");
    if(!empty($stations)){
      echo('<table class="table-striped" style="width:100%">
      <tr>
      <th><h3>Lieu</h3></th>
      <th><h3>Numéro</h3></th>
      <th><h3>Places disponibles</h3></th>
      </tr>');
      for($i = 0;  $i < sizeof($stations); $i++) {
        echo('<tr>');
        echo('<td>' . $stations[$i]['name'] . '</td>');
        echo('<td>' . $stations[$i]['number'] . '</td>');
        if($stations[$i]['places_availables'] == 0){
          echo('<td style="color:red;"><b>' . $stations[$i]['places_availables'] . '</b></td>');
        }
        else{
          echo('<td>' . $stations[$i]['places_availables'] . '</td>');
        }

        echo('</tr>');
      }
      echo('</table');
    }
    else{
      echo('<h4 class="text-center"> Service actuellement indisponible... </h4>');
    }






    echo('</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      </body>
    </html>');

  }
}
 ?>
