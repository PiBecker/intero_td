<?php

$data = file_get_contents("http://ip-api.com/xml");
$data_xml = simplexml_load_string($data);
$coord = [];

foreach ($data_xml as $key => $value) {
  if($key == "lat"){
    $coord["lat"] =  (string) $value;
  }
  if($key == "lon"){
      $coord["lon"] =  (string) $value;
  }
}
echo(json_encode($coord));

 ?>
