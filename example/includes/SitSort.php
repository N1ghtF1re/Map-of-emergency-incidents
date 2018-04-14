  <?php

  function qsort(&$array,&$kokos) {

  $left = 0;
  $right = count($array) - 1;

  my_sort($array, $left, $right,$kokos);

  }
  // Nikitos
  function my_sort(&$array, $left, $right, &$kokos) {
  $l = $left;
  $r = $right;
  $center = $array[(int)($left + $right) / 2];
  do {
  while ($array[$r] > $center) {
  $r--;
  }
  while ($array[$l] < $center) {
  $l++;
  }
  if ($l <= $r)
    {
    list($array[$r], $array[$l]) = array($array[$l], $array[$r]);
    list($kokos[$r], $kokos[$l]) = array($kokos[$l], $kokos[$r]);
    $l++;
    $r--;
    }

  } while ($l <= $r);
  if ($r > $left) {
  my_sort($array, $left, $r,$kokos);
  }
  if ($l < $right) {
  my_sort($array, $l, $right,$kokos);
  }
  }

  function formNsortarray ($link,  $ParamObj, $tablename){
     $year = $ParamObj->age;
     $n = $ParamObj->n;
     $minDate = $ParamObj->minDate;
     $maxDate = $ParamObj->maxDate;
     $CauseArr = array();
     $lol = array();

    for  ($i = 0; $i < $n; $i++) {
    // MYSQLI QUERY
      $m = $i + 1;
      //echo "SELECT COUNT(1) FROM SitList WHERE year = '$year' AND situation = '$m'";
      $result = mysqli_query($link, "SELECT COUNT(1) FROM ".$tablename." WHERE DATE(Date) >= '$minDate' AND DATE(Date) < '$maxDate' AND situation = '$m'");
      $kek =  mysqli_fetch_assoc($result);
      $CauseArr[$i] = $kek['COUNT(1)'];
      $lol[$i] = $i;

    }  //HELLO WORLD

    qsort($CauseArr,$lol);


    return  array_reverse($lol);

  }
  //WEBOS
