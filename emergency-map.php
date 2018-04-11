<!DOCTYPE html>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Emergency map - лучшая карта чрезвычайных ситуаций Беларуси.</title>
  <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1">
  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
	<link href="css/jquery-confirm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/menu.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="crossorigin="anonymous"></script>
  <script src="js/jquery-confirm.js"></script>
  <link rel="stylesheet" href="css/check.css">
  <link rel="stylesheet" href="css/main.css">

<?php
### GET PARAMETRES ###

include "includes/getParams.php";

$ParamObj = getAllParams();

### INCLUDES ###

include "config.php";
include "includes/colors.php";
// include "osmID.php";
include "includes/db.php";
include "includes/basiccolors.php";
include "includes/getIndex.php";
include "includes/SitSort.php";
include "includes/EmergencyMapAPI.php";

### BEGIN ###

$BasicColors = getBasicColors($ParamObj->n);

// Получаем массив, в каком порядке нужно сортировать ситуации
$SitSortArr = formNsortarray($link,  $ParamObj);

// Получаем массив регионов из БД
$CityList = getCityListNEW($tablename, $link, $SitSortArr, $ParamObj);

// Получаем массив маскимальных повторений ситуаций
$MaxArr = getMaxArr($CityList, $ParamObj->n);

// JS Код, который закрашивает регионы
writeJS($SituationNameArr, $CityList, $BasicColors, $SitSortArr, $MaxArr, $ParamObj);
?>
</head>

<body>
  <div id="nav" class="navigation">
    <div class="navigation__inner">
      <div class="nopad">
        <h1 class="logo smooth-scroll" href="#top">B<span>rakh</span>M<span>en</span></h1>
        <i class="fas fa-times close-menu" onclick="$('#show').click();"></i>
        <h2> Выберите количество групп ситуаций </h2>
      	<ul>
          <?php writeGroupList($ParamObj); ?>
      	</ul>
      	<h2> Выберите год </h2>
      	<ul>
      		<?php writeAgeList($ParamObj); ?>
      	</ul>
        <h2> Режим "Вся республика" <input type="checkbox" id="switch" <?php  if($ParamObj->resp == 'on'){echo 'checked';} ?> />
          <?php  writeRespSwitch($ParamObj); ?>
        </h2>
      </div>
    </div>
  </div>

<div class="menu">
	<div>BrakhMen Emergency Map</div>
  <div class="status">
  	<p>Год: <?=$ParamObj->age?></p>
  	<p>Группы: <?=$ParamObj->n?></p>
  	<?php writeColorsList($ParamObj, $BasicColors); ?>
  </div>

  <ul class="mn">
  	<!--<li><a href="#" onclick='$( "#options" ).toggle();'><i class="fas fa-sort-numeric-up"></i></a></li>-->
    <li id="show"><i class="fas fa-bars"></i></li>
  	<li><a href="https://brakhmen.info/" target="_blank"><i class="fas fa-home"></i></a></li>
  	<li><a href="https://vk.com/brakhmen" target="_blank"><i class="fab fa-vk"></i></a></li>
  	<li><a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents" target="_blank"><i class="fab fa-github"></i></a></li>
  </ul>
</div>

<div id="map"></div>

<footer><a href="https://brakhmen.info/" target="_blank">BrakhMen Corp. © 2018</a></footer>
<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter47417146 = new Ya.Metrika2({ id:47417146, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/47417146" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

<script type="text/javascript">
var btn = document.getElementById('show');
var nav = document.getElementById('nav');

btn.addEventListener('click', function() {
    nav.classList.toggle('active');
});

</script>
</body>

</html>

<?php
mysqli_close($link);
?>
