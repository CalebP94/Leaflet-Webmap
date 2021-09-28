<?php
$dns='pgsql:host=localhost;port=5432;dbname=CitySewer;';
$username='postgres';
$password='ThisIsARedGISNetwork_BeRED_LikeME100!';
$db = new PDO($dns, $username, $password);
//$queryII= "SELECT subbasin from ssgravitymain";
if(isset($_POST['subbasinid'])){
    $queryII = "SELECT subbasin, public.ST_AsGeoJSON(ST_Transform(geom,4326),5) AS geojson FROM ssgravitymain
    WHERE subbasin='{$_POST['subbasinid']}'";

$gm = $db->query($queryII);
$geojsonII = array(
    'type'  =>  'FeatureCollection',
    'features' => array()
);


while($row = $gm->fetch(PDO::FETCH_ASSOC)){
    $properties = $row;
    unset($properties['geojsonII']);
    
    
    unset($properties['geom']);
    $featureGM = array(
        'type' => 'Feature',
        'geometry' => json_decode($row['geojson']),
        'properties' => $properties
    );
    array_push($geojsonII['features'], $featureGM);
}
echo json_encode($geojsonII, JSON_NUMERIC_CHECK);
};
?>