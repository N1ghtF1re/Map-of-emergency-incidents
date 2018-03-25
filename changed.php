<?php
include "db.php";

function isChangedFile($link, $file) {
	$query = "SELECT DateValue FROM Settings WHERE Name = 'ExcelUpd'";

	if ($result = mysqli_query($link, $query)) {

	    /* выборка данных и помещение их в массив */
	    $row = mysqli_fetch_assoc($result);
	    $changedate = date ("Y-m-d H:i:s", filemtime($filename));
	    if ($row['DateValue'] !=  $changedate) {
	    	mysqli_query($link, "UPDATE Settings SET DateValue = '$changedate' WHERE Name = 'ExcelUpd'");
	    	return true;
	    } else {
	    	return false;
	    }
	    

	    /* очищаем результирующий набор */
	    mysqli_free_result($result);
	}
}

if (isChangedFile($link,'kek.xlsx')) {

echo 'kek';
};