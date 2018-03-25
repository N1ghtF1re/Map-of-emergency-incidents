<?php
function APIgetOsmeID($city) {

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

function getOsmeID($City) {
	switch ($City) {
		case 'Березинский район': 	
		return 70575;

		case 'Борисовский район': 	
		return 70569;

		case 'Вилейский район': 	
		return 70568;

		case 'Воложинский район': 	
		return 70565;

		case 'Дзержинский район': 	
		return 70563;

		case 'Жодино': 
		return 79911;

		case 'Клецкий район': 	
		return 71130;

		case 'Копыльский район': 	
		return 71132;

		case 'Крупский район': 	
		return 70639;

		case 'Логойский район': 	
		return 70542;

		case 'Любанский район': 	
		return 71140;

		case 'Минский район': 	
		return 59190;

		case 'Молодечненский район': 	
		return 70566;

		case 'Мядельский район': 	
		return 70719;

		case 'Несвижский район': 	
		return 71128;

		case 'Пуховичский район': 	
		return 70549;

		case 'Слуцкий район': 	
		return 71134;

		case 'Смолевичский район': 	
		return 69554;

		case 'Солигорский район': 	
		return 71133;
		
		case 'Стародорожский район': 	
		return 71135;

		case 'Столбцовский район': 	
		return 70752;

		case 'Узденский район': 	
		return 70561;
		
		case 'Червенский район': 	
		return 59751;
## Города
		case 'Борисов': 
		return 1749244;

		case 'Жодино':
		return 79911;

		case 'Заславль': 
		return 6722551;

		case 'Молодечно':
		return 6722552;

		case 'Слуцк':
		return 6722597;

		case 'Солигорск':
		return 6722606;



		return 0;

	}
}