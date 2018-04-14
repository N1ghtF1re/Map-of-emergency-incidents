<?php

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

// Полачем название ситуации
function getSitName ($SituationNameArr, $id, $n, $SitSortArr, $sort,$maxn) {
    $arr = getSitIndexArr($n, $id, $maxn);
		//echo $n.",".$id.",".$maxn;
		//print_r($arr);

    if ($n == $maxn) {
      if ($sort == 'off') {
        return $SituationNameArr[$id];
      } else {
        return $SituationNameArr[$SitSortArr[$id]];
      }
    } else { // Если $n != $maxn => ситуации группируются и нужно вывести все ситуации в группе
      $mems = $SituationNameArr[$arr[0]-1];
      for ($i = 1; $i < count($arr); $i++) {
          $mems = $mems.', '.$SituationNameArr[$arr[$i]-1];
      }
      return $mems;
    }




}

// Циклически сдвигаем цвет на нужное значение
function getRor($num, $shift, $n) {
	$res = $num + $shift;
	if ($res >= $n) {
		$res = $res % $n;
	}
	return $res;

}

// Получаем массив максимальных значений
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


// Получаем цвет определенного региона
function getColors($arr, $BasicColors,$MaxArr, $n, $ror) {
	$colorMap = array();
	for ($j = 0; $j < $n; $j++) {
		if ($arr[$j] != 0) {
			$coef = Round( 100 - ( $arr[$j]  / $MaxArr[$j] ) * 100 ); // Получаем коэффициент как процент от максимального значенмй
			$colorMap[] = LighterColor($BasicColors[getRor($j, $ror, $n)], $coef); // Осветляем коэффициент на coef %
		}
	}
	return MixColors($colorMap); // Смешиваем цвета и возвращаем их
}


// Получаем список регионов
function getCityListNEW($tablename, $link, $SitSortArr, $ParamObj, $maxn) {
	$n = $ParamObj->n;
	$age = $ParamObj->age;
	$resp = $ParamObj->resp;
	$minDate = $ParamObj->minDate;
	$maxDate = $ParamObj->maxDate;

	//echo $minDate;
	//echo $maxDate;
	$CityList = array();
//echo "SELECT * FROM ".$tablename." WHERE DATE(Date) > '$minDate' AND DATE(Date) < '$maxDate'  ORDER BY Region";
  if ($resp == 'off') {
	   $result = mysqli_query($link, "SELECT * FROM ".$tablename." WHERE DATE(Date) >= '$minDate' AND DATE(Date) < '$maxDate'  ORDER BY Region LIMIT 1");
  } else {
     $result = mysqli_query($link, "SELECT * FROM Respublic  WHERE DATE(Date) >= '$minDate' AND DATE(Date) < '$maxDate' ORDER BY Region LIMIT 1");
  }
	$cn =  mysqli_fetch_assoc($result);
	$currname = $cn['Region'];
	$CityList[] = new City($n, $currname);
  if ($resp == 'off') {
	   $result = mysqli_query($link, "SELECT * FROM ".$tablename." WHERE DATE(Date) >= '$minDate' AND DATE(Date) < '$maxDate'  ORDER BY Region");
  } else {
    $result = mysqli_query($link, "SELECT * FROM Respublic  WHERE DATE(Date) >= '$minDate' AND DATE(Date) < '$maxDate' ORDER BY Region");
  }
	while($SitRow = mysqli_fetch_assoc($result) ){
    if(stristr($SitRow['Region'], 'ОБЛАСТЬ') !== FALSE) {
      continue;
    }

		if ($currname == $SitRow['Region']) {
				$index = getIndex($n, $SitRow['Situation'], $maxn);
				//echo $index;
				if ($index == -1) {
					$index = $SitRow['Situation']-1;
          if (($sort == 'on') && ($n == 19)) {
            $index = array_search($index, $SitSortArr);
          }
				}
				$CityList[count($CityList)-1]->arr[$index]++;
		} else {
			//echo $currname;
			$currname = $SitRow['Region'];
			$CityList[] = new City($n, $currname);
			$index = getIndex($n, $SitRow['Situation'], $maxn);
				//echo $index;
				if ($index == -1) {
					$index = $SitRow['Situation']-1;
          if ($sort == 'off') {
            $index = array_search($index, $SitSortArr);
          }
				}
			$CityList[count($CityList)-1]->arr[$index]++;
		}





		//$city = $ExcArr[$i][0];
	}

	return $CityList; // Возврашаем список регионов
}
function writeJS($SituationNameArr, $CityList, $BasicColors, $SitSortArr, $MaxArr, $ParamObj,$maxn) {
	$n = $ParamObj->n;
	$ror = $ParamObj->ror;
	$sort = $ParamObj->sort;
	echo '<script>
		ymaps.ready(function() {

	   function ShowCity(Region, color, desc) {
	   	Region += ", Беларусь";
	   	// Получаем координаты полигона
	     $.getJSON("https://nominatim.openstreetmap.org/search.php?q=" + Region + "&format=json&polygon_geojson=1&limit=1")
	         .then(function (data) {

	             $.each(data, function(ix, place) {
	                 if ("relation" == place.osm_type) {

	                   // Создаем полигон с нужными координатами
	                 	 if ((place.geojson.coordinates[1] == undefined) || ( place.geojson.coordinates[1][1] !=  undefined)) {
		                     var p = new ymaps.Polygon(place.geojson.coordinates, {
		                          hintContent: Region
		                      }, {
		                          fillColor: color,
		                          strokeColor: color,
		                          // Делаем полигон прозрачным для событий карты.
		                          interactivityModel: \'default#transparent\',
		                          strokeWidth: 2,
		                          opacity: 0.7
		                      });
	                      } else {
							var p = new ymaps.Polygon(place.geojson.coordinates[0], {
		                          hintContent: Region
		                      }, {
		                          fillColor: color,
		                          strokeColor: color,
		                          // Делаем полигон прозрачным для событий карты.
		                          interactivityModel: \'default#transparent\',
		                          strokeWidth: 2,
		                          opacity: 0.7
		                      });
	                      }

	  // Добавляем полигон на карту';


	                      echo '
	                     p.events.add(\'click\', function () {
							$.alert({
							title: Region,
							animation:\'scale\',
							content: desc
							});
						 });
	                     map.geoObjects.add(p);';

	                echo'
	                 }
	             });
	         }, function (err) {
	             console.log(err);
	         });
	   }
	     var map,
	         center = [27, 53.5],
	         zoom = 7;

	     map = new ymaps.Map(\'map\', {
	         center: center,
	         zoom: zoom,
	         controls: []
	     }, {
				 	 minZoom: 6,
					 suppressMapOpenBlock: true
					 //restrictMapArea: true
			 });';



	$JScode = '';
	for ($i = 0; $i < count($CityList); $i++) {
						$desc = '\n';
						// Получаем, сколько раз каждая ситуация повторяется
	                    for ($j = 0; $j < $n; $j++) {
	                    	$count = $CityList[$i]->arr[$j] == 0 ? 0 : $CityList[$i]->arr[$j];
	                    	$desc = $desc.'<div> <div style="height: 10px; width: 10px; margin-right: 5px; display: inline-block; background: '.$BasicColors[getRor($j, $ror, $n)].'"></div>';
	                      	$desc = $desc.getSitName($SituationNameArr, $j, $n, $SitSortArr,$sort, $maxn).' - <b>'.$count.'</b> раз</div>';
	                    }
		  if ($CityList[$i]->name != '') {
		  $JScode = $JScode."ShowCity('".$CityList[$i]->name."','".getColors($CityList[$i]->arr, $BasicColors,$MaxArr, $n, $ror)."', '".$desc."');"; // Формируем JS-код, вызывающий функцию, которая будет отрисовывать города/районы на карте
// ($CityList, $BasicColors, $SitSortArr, $sort, $MaxArr, $n, $ror)

		}
	}
	echo $JScode;


	echo '

	 });
	</script>';
}


function writeGroupList($ParamObj){
	$narr = array(3,6,9,19);
	$currobj = clone $ParamObj;
	for($i = 0; $i < count($narr); $i++) {
		$currn = $narr[$i];
		$currobj->n = $narr[$i];
		echo "<li><a ";
		if ($ParamObj->n == $currn) {echo " class='selected'";}
		echo "href=".$currobj->getURL().">".$currn."</a></li>";

	}
	unset($currobj);
}



function writeAgeList($ParamObj, $minYear, $maxYear) {
	$currobj = clone $ParamObj;
	for ($i = $minYear; $i <= $maxYear; $i++) {
		echo '<li><a';
		if ($i == $ParamObj->age) {
			echo " class='selected'";
		}
		$currobj->age = $i;
		echo ' href="'.$currobj->getURL().'">';
		echo $i == 2007 ? 'Вернуть 2007' : $i;
		echo '</a></li>';

	}
	unset($currobj);
}

function writeRespSwitch($ParamObj) {
	$currobj = clone $ParamObj;
	if($ParamObj->resp == 'on') {
		$currobj->resp = 'off';
	} else  {
		$currobj->resp = 'on';
	}
	echo '<label for="switch" onclick="location.href = \''.$currobj->getURL().'\'"></label>';
	unset($currobj);
}

function writeColorsList($ParamObj, $BasicColors) {
	$currobj = clone $ParamObj;
	for ($i = 0; $i < $ParamObj->n; $i++) {
		$currobj->ror = getRor($i, $ParamObj->ror, $ParamObj->n);
		echo '<a href="'.$currobj->getURL().'" style="display: inline-block; float: left; padding: 0 !important; width: '.round(150/$ParamObj->n).'px; height: 20px; background: '.$BasicColors[getRor($i, $ParamObj->ror, $ParamObj->n)].'"></a>';
	}
	unset($currobj);
}

function mysqlDateToPhpDate($date) {
	$newdate = strtotime($date);
	$newdate = date( 'd.m.Y', $newdate );
	return $newdate;
}


 ?>
