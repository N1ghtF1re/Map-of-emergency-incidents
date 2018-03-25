<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Emergency map - лучшая карта чрезвычайных ситуаций Беларуси.</title>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
     <script type="text/javascript" src="umd/index.js"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>


<?php


### INCLUDES ###

include "colors.php";
include "osmID.php";
include "db.php";


### CLASSES ###


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
		$this->color = 0;
	}
}


### FUNCTIONS ###


function isChangedFile($link, $file) {
	$query = "SELECT DateValue FROM Settings WHERE Name = 'ExcelUpd'";

	if ($result = mysqli_query($link, $query)) {

	    /* выборка данных и помещение их в массив */
	    $row = mysqli_fetch_assoc($result);
	    $changedate = date ("Y-m-d H:i:s", filemtime($file));
	    if ($row['DateValue'] !=  $changedate) {
	    	mysqli_query($link, "UPDATE Settings SET DateValue = '$changedate' WHERE Name = 'ExcelUpd'");
	    	return true;
	    } else {
	    	return false;
	    }
	    

	    /* очищаем результирующий набор */
	    mysqli_free_result($result);
	}
}

function readExelFile($filepath, $link){

	mysqli_query($link, "DELETE FROM situations");

	//if ($mysqli->query("SELECT * FROM situations") === TRUE) echo 'kek';
	require_once 'Classes/PHPExcel.php';
	$ar=array(); // инициализируем массив

	$inputFileType = PHPExcel_IOFactory::identify($filepath);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
	$objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
	$objPHPExcel = $objReader->load($filepath); // загружаем данные файла в объект
	$ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
	unset($ar[0]); // 0 строка - заголовки таблицы
	sort($ar); 

	for ($i = 0; $i < count($ar); $i++) {
		$region = $ar[$i][0];
		$predfixes = array('г ', 'д ', 'гп ', 'снп ', 'п ', 'х ');
		$city = str_replace($predfixes, '', $ar[$i][2]);

		$date = $ar[$i][5];
		$date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));

		$sit = $ar[$i][57+25];
		if (($sit[1] >= '0') && ($sit[1] <= '9')) {
			$sit = $sit[0].$sit[1];
		} else {
			$sit = $sit[0];
		}

		$result = mysqli_query($link, "INSERT INTO situations (Region, City, Date, Situation) VALUES ('$region', '$city', '$date', '$sit')");
		if (!$result) {
			echo 'Query error'."INSERT INTO situations (Region, City, Date, Situation) VALUES ('$region', '$city', '$date', '$sit')".'<br>';
		}

		if ($result = mysqli_query($link, "SELECT * FROM RegionList WHERE Name = '$region'")) {
		    //printf("Select вернул %d строк.\n", mysqli_num_rows($result));
			if (mysqli_num_rows($result) == 0) {
			    $osme = APIgetOsmeID($region);
			    $name = $region;
				mysqli_query($link, "INSERT INTO RegionList (OSMeID, Name) VALUES ('$osme', '$name')");
			}

		}

		if ($result = mysqli_query($link, "SELECT * FROM CityList WHERE Name = '$city'")) {
		    //printf("Select вернул %d строк.\n", mysqli_num_rows($result));
			if (mysqli_num_rows($result) == 0) {
			    $osme = APIgetOsmeID($city);
			    $name = $city;
				mysqli_query($link, "INSERT INTO CityList (OSMeID, Name) VALUES ('$osme', '$name')");
			}

		}


	}

	return $ar; //возвращаем массив
}

/*$colorHex = '#FFAA00';
$result = hexToRgb($colorHex);
var_dump($result);
 
$colorRgb = array(255, 0, 0);
$result = rgbToHex($colorRgb);
var_dump($result);*/

function getCityListNEW($n, $link) {
	/*echo MixColors(array('#ff0000', '#00ff00')).'<br>';
	echo '<p style="color: #ff0000">Text</p>';
	echo '<p style="color:'.LighterColor("#ff0000", 50).'">Text</p>';*/

	$CityList = array();
	$MinskOblArr = array();
	if (isChangedFile($link,'kek.xlsx')) {
		$ExcArr=readExelFile('kek.xlsx', $link);
		return getCityList($n, $link);
	};
	 // Читаем эксель

	$result = mysqli_query($link, "SELECT * FROM situations LIMIT 1");
	$cn =  mysqli_fetch_assoc($result);
	$currname = $cn['Region'];
	$CityList[] = new City($n, $currname);

	$result = mysqli_query($link, "SELECT * FROM situations");
	while($SitRow = mysqli_fetch_assoc($result) ){ 
		
		

		if ($currname == $SitRow['Region']) {
				$CityList[count($CityList)-1]->arr[$SitRow['Situation']]++;
		} else {
			//echo $currname;
			$currname = $SitRow['Region'];
			$CityList[] = new City($n, $currname);
		}
		
		



		//$city = $ExcArr[$i][0];
	}

	return $CityList; // Возврашаем список регионов 
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
	$ExcArr=readExelFile('kek.xlsx', $link); // Читаем эксель

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
			//echo '<div style="display:inline-block; height: 30px; width: 30px; background: '.$colorMap[count($colorMap)-1].'">'.$arr[$j].'</div>';
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

/*
for ($i = 0; $i < $n; $i++) {
	echo '<div style="height: 40px; width: 40px; display:inline-block; background:'.$BasicColors[$i].'"></div>'; 
}
echo '<div></div>';
*/
$CityList = getCityListNEW($n, $link);

$MaxArr = getMaxArr($CityList, $n);


/*
for ($i = 0; $i < count($MaxArr); $i++) {
	echo ''.($i+1).') '.$MaxArr[$i].'<br>';
}
echo '<br>';
*/
	/*
for ($i = 0; $i < count($CityList); $i++) {
	echo '<p style = "background: '.getColors($CityList[$i]->arr, $BasicColors,$MaxArr, $n).'">'.$CityList[$i]->name.' -  '.getOsmeID($CityList[$i]->name).' (';



for ($j = 0; $j < $n; $j++) {
		echo $CityList[$i]->arr[$j] == 0 ? '0' : $CityList[$i]->arr[$j];

		echo ', ';
	}

	echo ")</p>";
}*/


echo " <script>
   	var myMap;

// Дождёмся загрузки API и готовности DOM.
ymaps.ready(init);

function init () {
    geoMap1 = new ymaps.Map('map', {
      center: [53.5, 27],
      type: \"yandex#map\",
      zoom: 7,
      controls: ['zoomControl', 'typeSelector',  'fullscreenControl']
    }, {
        searchControlProvider: 'yandex#search'
    });";

   $kek = '';
for ($i = 0; $i < count($CityList); $i++) {
	  $kek = $kek."osme.geoJSON('".getOsmeID($CityList[$i]->name)."', {lang: 'ru', quality:2}, function (data) {
      var collection".$i." = osme.toYandex(data, ymaps);
      collection".$i.".setStyles(() => ({opacity:0.5, fillColor:'".getColors($CityList[$i]->arr, $BasicColors,$MaxArr, $n)."'}));
      collection".$i.".add(geoMap1);
    });";

}


echo $kek;

/*echo "osme.geoJSON('BY-HM', {lang: 'ru', quality:2}, function (data) {
      var collection1 = osme.toYandex(data, ymaps);
      collection1.setStyles(() => ({opacity:0.8, fillColor:'#fff'}));
      collection1.add(geoMap1);
    });";*/
    
 

echo "}
   </script>";

?>

	<style>
        body, html {
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
        }
        #map {
            width: 100%;
            height: 90%;
        }
        .menu {
        	margin: 0;	
        	padding: 0;	
        	list-style: 0;
        	height: 10%;
        	background: #3f51b5;
        	display: table;
    		width: 100%;
        }
        .menu div {
		    font-family: sans-serif;
		    font-size: 24px;
		    color: #fff;
		    vertical-align: middle;
		    padding-left: 20px;
		    display: table-cell;
		}
		.mn {
			padding-right: 40px;
			padding: 0;	
			margin: 0;	
			height: 10%;	
			list-style: none;
			display: table-cell;
			width: 200px;
			vertical-align: middle;
		}
		.mn li {
			display: inline-block;
			font-size: 32px;
		}
		.mn li a{
			color: #fff !important;
		}
    </style>
</head>

<body>
<duv class="menu">
	<div>BrakhMen Emergency Map</div>
<ul class="mn">
	<li><a href="https://brakhmen.info/" target="_blank"><i class="fas fa-home"></i></a></li>
	<li><a href="https://vk.com/brakhmen" target="_blank"><i class="fab fa-vk"></i></a></li>
	<li><a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents" target="_blank"><i class="fab fa-github"></i></a></li>
</ul>
</duv>
<div id="map"></div>
</body>

</html>
<?php 

?>
