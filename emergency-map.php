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
  <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker.css" />
  <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/moment.js"></script>
  <script type="text/javascript" src="js/daterangepicker.js"></script>

  <script src="js/jquery-confirm.js"></script>
  <link rel="stylesheet" href="css/check.css">
  <link rel="stylesheet" href="css/main.css">

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
### INCLUDES ###

include "config.php"; // CONFIG FILE

// Unit for connect with DB
include "includes/db.php";
$maxn = getMaxN($link, $tablename); // Number of Situations
$maxYear = getMaxYear($link, $tablename);
$minYear = getMinYear($link, $tablename);

// Unit with classes and functions for work with GET-parrametrs
// Classes: CGetParam; Fields: n, age, ror, sort, resp; Methods: getURL()
// Functions: getN(), getAge(), getRorPar(), getSort(), getResp(), getAllParams()
include "includes/getParams.php";

$ParamObj = getAllParams($maxn, $minYear, $maxYear);
// Unit with functions for the interaction of colors
// Functions: hexToRgb($color), rgbToHex($color), MixColors($Colors), LighterColor($Color, $Percent)
include "includes/colors.php";

// Unit with functions for getting basic colors (Get N colors for each situation)
// Functions: getBasicColors($n)
include "includes/basiccolors.php";

// BAD UNIT. NADO ISPRAVIT'
include "includes/getIndex.php";

// Units with functions for Sorting Situations
// Functions: formNsortarray ($link,  $ParamObj)
include "includes/SitSort.php";

// Unit with other necessary functions and classes
// Classes: City; Field: name, color, SitArr
// functions:
include "includes/EmergencyMapAPI.php";


### BEGIN ###

$BasicColors = getBasicColors($ParamObj->n);

// Получаем массив, в каком порядке нужно сортировать ситуации
$SitSortArr = formNsortarray($link,  $ParamObj, $tablename);

// Получаем массив регионов из БД
$CityList = getCityListNEW($tablename, $link, $SitSortArr, $ParamObj, $maxn);

// Получаем массив маскимальных повторений ситуаций
$MaxArr = getMaxArr($CityList, $ParamObj->n);

// JS Код, который закрашивает регионы
writeJS($SituationNameArr, $CityList, $BasicColors, $SitSortArr, $MaxArr, $ParamObj, $maxn);
?>
<style media="screen">
  .daterangepicker {
    z-index: 99999999 !important;
    color: #000 !important;
  }
  #datepick {
    color: #5659b6 !important;
    font-size: 14px;
    height: 30px;
    text-align: center;
    border: 1px solid #5659b6;
}
  #update {
    background: #575ab6;
    border-color: #572da2;
    margin-top: 4px;
  }

  @media (min-width: 992px) {
  .jconfirm .col-md-6 {
    width: 60%;
    margin-left: 20% !important;
}
}
</style>
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
        <h2> Выберите диапазон дат</h2>
        <div class=""><input type="text" name="daterange" id="datepick" value="01/01/2015 - 01/31/2015" /> <button id="update" class="applyBtn btn btn-sm btn-success" type="button">Обновить</button></div>
      	<h2> или Выберите год </h2>
      	<ul>
      		<?php writeAgeList($ParamObj, $minYear, $maxYear); ?>
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
  	<p>Интервал<br> <?= mysqlDateToPhpDate($ParamObj->minDate)?> - <?=mysqlDateToPhpDate($ParamObj->maxDate)?></p>
  	<p>Группы: <?=$ParamObj->n?></p>
  	<?php writeColorsList($ParamObj, $BasicColors); ?>
  </div>

  <ul class="mn">
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

<?php
$newdate = strtotime($ParamObj->minDate);
$mindate = date( 'd/m/Y', $newdate );
$newdate = strtotime($ParamObj->maxDate);
$maxdate = date( 'd/m/Y', $newdate );
?>
<script type="text/javascript">
var dstart = '';
var dend = '';
$(function() {
    $('#datepick').daterangepicker({
    "locale": {
        "format": "DD.MM.YYYY",
        "separator": " - ",
        "applyLabel": "Принять",
        "cancelLabel": "Отмена",
        "fromLabel": "От",
        "toLabel": "До",
        "customRangeLabel": "Кастом",
        "weekLabel": "Н",
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Noябрь",
            "Декабрь"
        ],
        "firstDay": 1
    },
    "startDate": "<?=$mindate?>",
    "endDate": "<?=$maxdate?>"
}, function(start, end, label) {
  dstart = start.format('YYYY-MM-DD');
  dend = end.format('YYYY-MM-DD');
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});
});

$('#update').click(function(){
  let dates = ($('#datepick').val()).split(' - ', 2);
  datestart = dates[0];
  datestart = datestart.substr(6,4) + '-' + datestart.substr(3,2) + '-' + datestart.substr(0,2);
  dateend = dates[1];
  dateend = dateend.substr(6,4) + '-' + dateend.substr(3,2) + '-' + dateend.substr(0,2);
  location.href = '<?=$ParamObj->getURLwithoutDate()?>' + '&minDate=' + datestart + '&maxDate=' + dateend;
});
</script>
</body>

</html>

<?php
mysqli_close($link);
?>
