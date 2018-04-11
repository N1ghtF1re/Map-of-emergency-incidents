
$BasicColors = getBasicColors($n);
/*
for ($i = 0; $i < $n; $i++) {
	echo '<div style="height: 40px; width: 40px; display:inline-block; background:'.$BasicColors[$i].'"></div>';
}
echo '<div></div>';
*/

$SitSortArr = formNsortarray($link, $age, $n);

$CityList = getCityListNEW($n, $link, $age, $SitSortArr, $sort);

$MaxArr = getMaxArr($CityList, $n);



// JS Код, который закрашивает регионы
echo '<script>
	ymaps.ready(function() {

   function ShowCity(Keks, color, mem) {
   	Keks += ", Беларусь";
   	// Получаем координаты полигона
     $.getJSON("https://nominatim.openstreetmap.org/search.php?q=" + Keks + "&format=json&polygon_geojson=1&limit=1")
         .then(function (data) {

             $.each(data, function(ix, place) {
                 if ("relation" == place.osm_type) {

  // Создаем полигон с нужными координатами
                 	 if ((place.geojson.coordinates[1] == undefined) || ( place.geojson.coordinates[1][1] !=  undefined)) {
	                     var p = new ymaps.Polygon(place.geojson.coordinates, {
	                          hintContent: Keks
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
	                          hintContent: Keks
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
						// alert(Keks + mem);
						$.alert({
						title: Keks,
						animation:\'scale\',
						content: mem
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
     });';



       $kek = '';
for ($i = 0; $i < count($CityList); $i++) {
					$mem = '\n';
					// Получаем, сколько раз каждая ситуация повторяется
                    for ($j = 0; $j < $n; $j++) {
                    	$count = $CityList[$i]->arr[$j] == 0 ? 0 : $CityList[$i]->arr[$j];
                    	$mem = $mem.'<div> <div style="height: 10px; width: 10px; margin-right: 5px; display: inline-block; background: '.$BasicColors[getRor($j, $ror, $n)].'"></div>';
                      	$mem = $mem.getSitName($j, $n, $SitSortArr,$sort).' - <b>'.$count.'</b> раз</div>';
                    }

	  $kek = $kek."ShowCity('".$CityList[$i]->name."','".getColors($CityList[$i]->arr, $BasicColors,$MaxArr, $n, $ror)."', '".$mem."');"; // Формируем JS-код, вызывающий функцию, которая будет отрисовывать города/районы на карте

}
echo $kek;


echo '
 });
</script>';


?>

	<style>
		.jconfirm.white .jconfirm-bg{background: none !important;}
		.jconfirm  {
			    z-index: 9999999999;
			    width: 60%;
			    margin: 0 auto;
			    font-size: 13px;
		}
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
		#options {
		z-index: 999;
		background: #fff;
        width: 300px;
        height: 360px;
        text-align: left;
		font-size: 18px;
        padding: 15px;
        border: 1px solid #3f51b5;
        border-radius: 2px;
        color: #3f51b5;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
      }
      #options ul {
      	list-style: none;
      	padding: 0;
      	margin: 0;
      }
      #options ul li{
      	display: inline-block;
      	padding: 3px 6px;
      }
      #options a {
      	text-decoration: none;
      	color:  #3f51b5;
      }
      .status {
      	position: absolute;
      	width: 180px;
      	height: 100px;
      	top : 10%;
      	left: 0;
      	background: #fff;
      	z-index: 9999;
      	margin: 0;
      	padding: 0 !important;

      }
      .status p {
      	font-size: 14px;
      	padding: 0;
      	color: #000;
      	padding-left: 5px !important;
      }
    </style>
</head>

<body>
<duv class="menu">
	<div>BrakhMen Emergency Map</div>
<div class="status">
	<p>Год: <?=$age?></p>
	<p>Группы: <?=$n?></p>
	<?php
		for ($i = 0; $i < $n; $i++) {
		?>
			<a href="https://brakhmen.info/map/emergency-map.php?n=<?=$n?>&age=<?=$age?>&ror=<?=getRor($i, $ror, $n)?>" style="display: inline-block; float: left; padding: 0 !important; width: <?=round(150/$n)?>px; height: 20px; background: <?=$BasicColors[getRor($i, $ror, $n)]?>"></a>
		<?php
		}
		?>
</div>
<div id="options" style="display: none">
	<h2> Выберите количество групп ситуаций </h2>
	<ul>
		<li><a href="https://brakhmen.info/map/emergency-map.php?n=3&age=<?=$age?>&ror=<?=$ror?>">3</a></li>
		<li><a href="https://brakhmen.info/map/emergency-map.php?n=6&age=<?=$age?>&ror=<?=$ror?>">6</a></li>
		<li><a href="https://brakhmen.info/map/emergency-map.php?n=9&age=<?=$age?>&ror=<?=$ror?>">9</a></li>
		<li><a href="https://brakhmen.info/map/emergency-map.php?n=19&age=<?=$age?>&ror=<?=$ror?>">19</a></li>
	</ul>
	<h2> Выберите год </h2>
	<ul>
		<?php
			for ($i = 2005; $i <= 2016; $i++) {
				echo '<li><a href="https://brakhmen.info/map/emergency-map.php?n='.$n.'&ror='.$ror.'&age='.$i.'">';
				echo $i == 2007 ? 'Вернуть 2007' : $i;
				echo '</a></li>';

			}
		?>
	</ul>
	<br><br>
	<a onclick='$( "#options" ).hide();'>Закрыть</a>
</div>
<ul class="mn">
	<li><a href="#" onclick='$( "#options" ).toggle();'><i class="fas fa-sort-numeric-up"></i></a></li>
	<li><a href="https://brakhmen.info/" target="_blank"><i class="fas fa-home"></i></a></li>
	<li><a href="https://vk.com/brakhmen" target="_blank"><i class="fab fa-vk"></i></a></li>
	<li><a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents" target="_blank"><i class="fab fa-github"></i></a></li>
</ul>
</duv>
<div id="map"></div>


<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter47417146 = new Ya.Metrika2({ id:47417146, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/47417146" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

</body>

</html>
