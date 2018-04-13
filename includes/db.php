<?php
$link = mysqli_connect($db_host, $db_user, $db_password, $db_database);

/* проверяем соединение */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

function getMaxN($link, $tablename) {
  $result = mysqli_query($link, "SELECT Max(Situation) FROM ".$tablename."");
  $res_assoc=  mysqli_fetch_assoc($result);
  $maxn = $res_assoc["Max(Situation)"];
  return $maxn;
}

function getMaxYear($link, $tablename) {
    $result = mysqli_query($link, "SELECT Max(age) FROM ".$tablename."");
    $res_assoc=  mysqli_fetch_assoc($result);
    $maxyear = $res_assoc["Max(age)"];
    return $maxyear;
}

function getMinYear($link, $tablename) {
    $result = mysqli_query($link, "SELECT Min(age) FROM ".$tablename."");
    $res_assoc=  mysqli_fetch_assoc($result);
    $minyear = $res_assoc["Min(age)"];
    return $minyear;
}
