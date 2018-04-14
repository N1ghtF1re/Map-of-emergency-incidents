<?php

// Class of get pararms
class CGetParam {
	var $n;
  var $age;
  var $ror;
  var $sort;
  var $resp;
	var $minDate;
	var $maxDate;

	function  __construct($n, $age, $ror, $sort, $resp, $minDate, $maxDate){
		$this->n = $n;
    $this->age = $age;
    $this->ror = $ror;
    $this->sort = $sort;
    $this->resp = $resp;
		$this->minDate = $minDate;
		$this->maxDate = $maxDate;

	}
  function getURL() {
    $n = $this->n;
    $age = $this->age;
    $ror = $this->ror;
    $sort = $this->sort;
    $resp = $this->resp;
		$maxDate = $this->maxDate;
		$minDate = $this->minDate;

		if ($age != -1) {
    	return $_SERVER['PHP_SELF']."?n=".$n."&age=".$age."&ror=".$ror."&resp=".$resp;
		} else {
			return $_SERVER['PHP_SELF']."?n=".$n."&ror=".$ror."&resp=".$resp."&minDate=".$minDate."&maxDate=".$maxDate;
		}
  }
	function getURLwithoutDate() {
    $n = $this->n;
    $age = $this->age;
    $ror = $this->ror;
    $sort = $this->sort;
    $resp = $this->resp;
		$maxDate = $this->maxDate;
		$minDate = $this->minDate;

		return $_SERVER['PHP_SELF']."?n=".$n."&ror=".$ror."&resp=".$resp;

  }

}

// Return N,  (N c (3,6,9,19))
function getN($maxn) {
  $n = $_GET["n"];
  if ($n == '') {
  	$n = $maxn;
  }

  switch ($n) {
  	case 3:
  	case 6:
  	case 9:
  	case 19:
    return $n;
  	break;

  	default:
  	header("Location: ".$_SERVER['PHP_SELF'] );
  }

}


// Return age
function getDates($minYear, $maxYear){
  $age = $_GET["age"];
	$minDate = $_GET["minDate"];
	$maxDate = $_GET["maxDate"];
  if (($age != '') || (($minDate == '') || ($maxDate == '')) ) {
		if($age == '') {
			$age = $maxYear;
		}
		if (($age > $maxYear)  || ($age < $minYear)) {
			header("Location: ".$_SERVER['PHP_SELF'] );
		}
		$date = array(
			'min' => $age.'-01-01',
			'max' => ($age*1 + 1).'-01-01',
			'year' => $age
		);
		// return $age;
  } else {
		$date = array(
			'min' => $minDate,
			'max' => $maxDate,
			'year' => -1
		);
	}

	return $date;
}


// Return ror (color's shift)
function getRorPar() {
  $ror = $_GET["ror"];
  if ($ror == '')  {
  	$ror = 0;
  }
  $ror = $ror*1;
  if (is_string($ror)) {
  	header("Location: ".$_SERVER['PHP_SELF'] );
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
function getAllParams($maxn, $minYear, $maxYear) {
  $n = getN($maxn);
  $date = getDates($minYear, $maxYear);
  $ror = getRorPar();
  $sort = getSort();
  $resp = getResp();
  $result = new CGetParam($n, $date['year'], $ror, $sort, $resp, $date['min'], $date['max']);
  return $result;
}

 ?>
