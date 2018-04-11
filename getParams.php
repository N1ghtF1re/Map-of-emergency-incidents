<?php

// Class of get pararms
class CGetParam {
	var $n;
  var $age;
  var $ror;
  var $sort;
  var $resp;


	function  __construct($n, $age, $ror, $sort, $resp){
		$this->n = $n;
    $this->age = $age;
    $this->ror = $ror;
    $this->sort = $sort;
    $this->resp = $resp;
	}
  function getURL() {
    $n = $this->n;
    $age = $this->age;
    $ror = $this->ror;
    $sort = $this->sort;
    $resp = $this->resp;

    return "https://brakhmen.info/map/emergency-map.php?n=".$n."&age=".$age."&ror=".$ror."&resp=".$resp;
  }

}

// Return N,  (N c (3,6,9,19))
function getN() {
  $n = $_GET["n"];
  if ($n == '') {
  	$n = 19;
  }

  switch ($n) {
  	case 3:
  	case 6:
  	case 9:
  	case 19:
    return $n;
  	break;

  	default:
  	header("Location: https://brakhmen.info/map/emergency-map.php");
  }

}


// Return age ( age = [2005..2017] )
function getAge() {
  $age = $_GET["age"];
  if ($age == '') {
    $age = 2005;
  }
  if (($age > 2017)  || ($age < 2005)) {
  	header("Location: https://brakhmen.info/map/emergency-map.php");
  }
  return $age;
}


// Return ror (color's shift)
function getRorPar() {
  $ror = $_GET["ror"];
  if ($ror == '')  {
  	$ror = 0;
  }
  $ror = $ror*1;
  if (is_string($ror)) {
  	header("Location: https://brakhmen.info/map/emergency-map.php");
  }
  return $ror;
}


// Return sort mode (on/off)
function getSort(){
  $sort = $_GET["sort"];
  if ($sort != 'on') {
    $sort = 'off';
  }
  return $sort;
}


// Return respublic mode (on/off)
function getResp() {
  $resp = $_GET["resp"];

  if ($resp != 'on') {
    $resp = 'off';
  }
  return $resp;
}

// Return obj with all params
function getAllParams() {
  $n = getN();
  $age = getAge();
  $ror = getRorPar();
  $sort = getSort();
  $resp = getResp();
  $result = new CGetParam($n, $age, $ror, $sort, $resp);
  return $result;
}

 ?>
