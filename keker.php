<?php


function getOsmeID($city) {

	$city = str_replace(' ', '%20', $city);
	$json = file_get_contents('http://overpass-api.de/api/interpreter?data=%5Bout:json%5D;relation[%22name%22=%22'.$city.'%22];%20/*added%20by%20auto%20repair*/%20(._;%3E;);%20/*end%20of%20auto%20repair*/%20out%20meta;');

	//var_dump(json_decode($json));
	$jsonkek = json_decode($json, true);

	foreach ($jsonkek as $jsons) {
		
		
		foreach ($jsons as $jsonsk) {
				if ($jsonsk['type'] == 'relation') 
				{
					//echo print_r($jsonsk).'<br><br>';
					return $jsonsk['id'];
				} 
			}

	}
	return $city;	 
}

//echo getOsmeID('Лида')
?>