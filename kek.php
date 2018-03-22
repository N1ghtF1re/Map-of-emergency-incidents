<?php


### INCLUDES ###

include "colors.php";
include "osmID.php";


###PROCEDURES###


// Класс региона
class City { 
	var $name;
	var $color;
	var $SitArr = array(); // Массив ситуаций. В каждом элементе N хранится, сколько ситуация N+1 повторялась раз 
	function  __construct($n, $name){
		$this->name = $name;
		for ($i = 0; $i < $n; $i++) {
			$arr[] = 0;
		}
		$this->color = 5;
	}
}

function readExelFile($filepath){
	require_once 'Classes/PHPExcel.php';
	$ar=array(); // инициализируем массив

	$inputFileType = PHPExcel_IOFactory::identify($filepath);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
	$objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
	$objPHPExcel = $objReader->load($filepath); // загружаем данные файла в объект
	$ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
	unset($ar[0]); // 0 строка - заголовки таблицы
	sort($ar); 
	return $ar; //возвращаем массив
}

/*$colorHex = '#FFAA00';
$result = hexToRgb($colorHex);
var_dump($result);
 
$colorRgb = array(255, 0, 0);
$result = rgbToHex($colorRgb);
var_dump($result);*/

function getCityList($n) {
	/*echo MixColors(array('#ff0000', '#00ff00')).'<br>';
	echo '<p style="color: #ff0000">Text</p>';
	echo '<p style="color:'.LighterColor("#ff0000", 50).'">Text</p>';*/

	$CityList = array();
	$MinskOblArr = array();
	$ExcArr=readExelFile('kek.xlsx'); // Читаем эксель

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
				$ExcArr[$i][2] = str_replace('г ', '', $ExcArr[$i][2]);
				$ExcArr[$i][2] = str_replace(' ', '', $ExcArr[$i][2]);
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

function getMaxArr($List, $n) {
	$MaxArr = array();
	for ($i = 0; $i < $n; $i++) {
		$MaxArr[$i] = $List[0]->arr[$i];
	}

	for ($i = 1; $i < count($List); $i++) {
		for ($j = 0; $j < $n; $j++) {
			if ($MaxArr[$j] < $List[$i]->arr[$j]) {
				$MaxArr[$j] = $List[$i]->arr[$j];
			}
		}
	}

	return $MaxArr;
}

function getColors($arr, $BasicColors,$MaxArr, $n) {
	$colorMap = array();
	for ($j = 0; $j < $n; $j++) {
		if ($arr[$j] != 0) {
			$coef = Round( 100 - ( $arr[$j]  / $MaxArr[$j] ) * 100 );
			$colorMap[] = LighterColor($BasicColors[$j], $coef);
			//echo '<div style="display:inline-block; height: 10px; width: 10px; background: '.$BasicColors[$j].'"></div>';
			//echo '<div style="display:inline-block; height: 10px; width: 10px; background: '.$colorMap[count($colorMap)-1].'"></div>';
		}
	}	
	return MixColors($colorMap);
}
### BEGIN ###



$BasicColors = array(
	'#ff0000',
	'#ff4d00',
	'#ff9a00',
	'#ffe700',
	'#caff00',
	'#7dff00',
	'#30ff00',
	'#00ff1d',
	'#00ff6a',
	'#00ffb7',
	'#00faff',
	'#00adff',
	'#0060ff',
	'#0013ff',
	'#3a00ff',
	'#8700ff',
	'#d400ff',
	'#ff00dd',
	'#ff0090');
$n = 19;


for ($i = 0; $i < $n; $i++) {
	echo '<div style="height: 40px; width: 40px; display:inline-block; background:'.$BasicColors[$i].'"></div>'; 
}
echo '<div></div>';
$CityList = getCityList($n);

$MaxArr = getMaxArr($CityList, $n);
for ($i = 0; $i < count($MaxArr); $i++) {
	echo ''.($i+1).') '.$MaxArr[$i].'<br>';
}
echo '<br>';

for ($i = 0; $i < count($CityList); $i++) {
	echo '<p style = "background: '.getColors($CityList[$i]->arr, $BasicColors,$MaxArr, $n).'">'.$CityList[$i]->name.' -  '.getOsmeID($CityList[$i]->name).' (';



	for ($j = 0; $j < $n; $j++) {
		echo $CityList[$i]->arr[$j] == 0 ? '0' : $CityList[$i]->arr[$j];

		echo ', ';
	}

	echo ")</p>";
}



