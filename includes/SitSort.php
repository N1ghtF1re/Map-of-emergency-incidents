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

  function formNsortarray ($link,  $ParamObj){
     $year = $ParamObj->year;
     $n = $ParamObj->n;
     $CauseArr = array();
     $lol = array();

    for  ($i = 0; $i < $n; $i++) {
    // MYSQLI QUERY
      $m = $i + 1;
      $result = mysqli_query($link, "SELECT COUNT(*) FROM SitList WHERE year = '$year' AND situation = '$m'");
      $kek =  mysqli_fetch_assoc($result);
      $CauseArr[$i] = $kek['COUNT(*)'];
      $lol[$i] = $i;

    }  //HELLO WORLD

    qsort($CauseArr,$lol);


    return  array_reverse($lol);

  }
  //WEBOS
