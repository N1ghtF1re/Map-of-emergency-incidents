<?php
$link = mysqli_connect($db_host, $db_user, $db_password, $db_database);

/* проверяем соединение */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/*
$mysqli = new mysqli("mysql.hostinger.ru", "u903425936_emerg", "brakhkek", "u903425936_emerg");


if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}*/

/*if ($result = $mysqli->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    //printf("Default database is %s.\n", $row[0]);
    $result->close();
}*/
