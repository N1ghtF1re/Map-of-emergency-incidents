<?php

include "db.php";

$age = $_GET["age"];



function readExelFile($age, $link){
	$mem = 'data/bigkek';
	$filepath = $mem.''.$age.'.xlsx';
	if ($age == 2005) {
		$ColRegion = 0;
		$ColCity = 2;
		$ColSituation = 57+25;
		$street = 15;
		$ColDate = 5;
	} else {
		$ColRegion = 1;
		$ColCity = 3;
		$ColSituation = 64+36+23;
		$street = -1;
		$ColDate = 12;
	}

	require_once 'Classes/PHPExcel.php';
	$ar=array(); // инициализируем массив

	$inputFileType = PHPExcel_IOFactory::identify($filepath);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
	$objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
	$objPHPExcel = $objReader->load($filepath, ReadDataOnly); // загружаем данные файла в объект
	$ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
	unset($ar[0]); // 0 строка - заголовки таблицы
	sort($ar); 

	for ($i = 0; $i < count($ar); $i++) {
		$region = $ar[$i][$ColRegion];
		$predfixes = array('г ', 'д ', 'гп ', 'снп ', 'п ', 'х ');
		$city = str_replace($predfixes, '', $ar[$i][$ColCity]);

		$date = $ar[$i][$ColDate];
		$date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
		$strt = '';
		if ($street != -1) {
			$strt = $ar[$i][$street];
		}
		$sit = $ar[$i][$ColSituation];
		if (($sit[1] >= '0') && ($sit[1] <= '9')) {
			$sit = $sit[0].$sit[1];
		} else {
			$sit = $sit[0];
		}

		$result = mysqli_query($link, "INSERT INTO Respublic (Region, City, Street, Date, Situation, age) VALUES ('$region', '$city', '$strt','$date', '$sit', '$age')");
		if (!$result) {
			echo 'mda';
		}

		if ($result = mysqli_query($link, "SELECT * FROM RegionList WHERE Name = '$region'")) {
		    //printf("Select вернул %d строк.\n", mysqli_num_rows($result));
			if (mysqli_num_rows($result) == 0) {
			    $osme = APIgetOsmeID($region);
			    $name = $region;
				mysqli_query($link, "INSERT INTO RegionList (OSMeID, Name) VALUES ('$osme', '$name')");
			}

		}

		/*if ($result = mysqli_query($link, "SELECT * FROM CityList WHERE Name = '$city'")) {
		    //printf("Select вернул %d строк.\n", mysqli_num_rows($result));
			if (mysqli_num_rows($result) == 0) {
			    $osme = APIgetOsmeID($city);
			    $name = $city;
				mysqli_query($link, "INSERT INTO CityList (OSMeID, Name) VALUES ('$osme', '$name')");
			}

		}*/


	}

	return $ar; //возвращаем массив
}

function getCityList($n, $link) {
	/*echo MixColors(array('#ff0000', '#00ff00')).'<br>';
	echo '<p style="color: #ff0000">Text</p>';
	echo '<p style="color:'.LighterColor("#ff0000", 50).'">Text</p>';*/

	$CityList = array();
	$MinskOblArr = array();
	if (isChangedFile($link,'kek.xlsx')) {

		echo 'kek';
	};
	$ExcArr=readExelFile($mem, $link); // Читаем эксель

	$currname = $ExcArr[0][0];
	for ($i = 0; $i < count($ExcArr); $i++) {
		if($ExcArr[$i][0] != 'МИНСКАЯ ОБЛАСТЬ') {
			$CityList[] = new City($n, $currname);
		}

		while ($currname == $ExcArr[$i][0]) {
			if($ExcArr[$i][0] != 'МИНСКАЯ ОБЛАСТЬ') {
				$Situation = $ExcArr[$i][57+25];

				// Получаем номер ситуации
				if (($Situation[1] >= '0') && ($Situation[1] <= '9')) {
					$Situation = $Situation[0].$Situation[1];
				} else {
					$Situation = $Situation[0];
				}

				// Инкрементим в массиве ситуаций нужную
				$CityList[count($CityList)-1]->arr[$Situation-1]++;
				//echo $currname;
			} else {
				$predfixes = array('г ', 'д ', 'гп ', 'снп ', 'п ', 'х ');
				$ExcArr[$i][2] = str_replace($predfixes, '', $ExcArr[$i][2]);
				$MinskOblArr[] = $ExcArr[$i];
			}
			$i++;
		}
		$currname = $ExcArr[$i][0];
		



		//$city = $ExcArr[$i][0];
	}


	sort($MinskOblArr);
	$currname = $MinskOblArr[0][2];
	for ($i = 0; $i < count($MinskOblArr); $i++) {
		$CityList[] = new City($n, $currname);

		while ($currname == $MinskOblArr[$i][2]) {
			//echo str_replace('г ', '', $MinskOblArr[$i][2]);

			$Situation = $MinskOblArr[$i][57+25];

		// Получаем номер ситуации
			if (($Situation[1] >= '0') && ($Situation[1] <= '9')) {
				$Situation = $Situation[0].$Situation[1];
			} else {
				$Situation = $Situation[0];
			}

		// Инкрементим в массиве ситуаций нужную
			$CityList[count($CityList)-1]->arr[$Situation-1]++;
			$i++;
		}
		$currname = $MinskOblArr[$i][2];
		//echo $currname;
	} 
	return $CityList; // Возврашаем список регионов 
}

readExelFile($age, $link);
