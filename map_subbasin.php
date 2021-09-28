<?php
$dns='pgsql:host=localhost;port=5432;dbname=CitySewer;';
$username='postgres';
$password='ThisIsARedGISNetwork_BeRED_LikeME100!';
$db = new PDO($dns, $username, $password);
$query = "SELECT subbasinid from ssbasinboundary";

if(isset($_POST['subbasinid'])){
    $query = "SELECT subbasinid, public.ST_AsGeoJSON(ST_Transform(geom, 4326),5) AS geojson FROM ssbasinboundary
    WHERE subbasinid ='{$_POST['subbasinid']}' ";
}
$rs = $db->query($query); 

$geojson = array(
    'type'  =>  'FeatureCollection',
    'features' => array()
);

while($row = $rs->fetch(PDO::FETCH_ASSOC)){
    $properties = $row;
    unset($properties['geojson']);
    unset($properties['geom']);
    $feature = array(
        'type' => 'Feature',
        'geometry' => json_decode($row['geojson']),
        'properties' => $properties
    );
    array_push($geojson['features'], $feature);
}
echo json_encode($geojson, JSON_NUMERIC_CHECK);


?>

