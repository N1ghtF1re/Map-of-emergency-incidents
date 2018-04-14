<?php

function getSitIndexArr($n,$sit,$maxn){ // Example: {n = 6; sit = 0..5}; n - на сколько массивов делить; sit - какой массив запрашивают
	$kek = intdiv($maxn,$n);	//Целочисленное деления (20/6) = 3 сколько элементов в норм масиве
	$lol = $maxn % $n; //	Деление по модулю,	Целочисленный остаток от деления (20%6) = 2 - сколько элементов надо распихать
	$keklol = $kek*$sit;
	$arrayRet = array();
	if (($sit+1)<=$lol){
		for ($i = $keklol+1+$sit; $i<=$keklol+$kek+1+$sit ;$i++){
		$arrayRet[] = $i;
		}
		return $arrayRet;
	}
	else {
	for($i = $keklol+1+$lol; $i<=$keklol+$kek+$lol ;$i++){
		$arrayRet[] = $i;
	}
	return 	$arrayRet;
	}
}

//
//kek = 3
//  1 2 3 4 5 6 .. 19
//19 / 6 = 3
// $sit=0(1,2,3,4),$sit=1(5,6,7), $sit=2(8,9,10) $sit=3(11,12,13) $sit=4(14,15,16) $sit=5(17,18,19)
// n = 6; sit = 0..$maxn; n - на сколько массивов поделено; sit - ситуация, нужно вернуть в каком по счёту массиве
function getIndex($n,$pos,$maxn){
	$kek = intdiv($maxn,$n);	//Целочисленное деления (19/6) = 3 сколько элементов в норм масиве
	$lol = $maxn % $n; //	Деление по модулю,	Целочисленный остаток от деления (19%6) = 1 - сколько элементов надо распихать
	$sit = intdiv($pos,$kek); // 6/4 = 1;
	if (($sit+1)<=$lol){		 //2<=1
		if ((($pos%$kek)==1) && ($sit>=1)) {  //6%3 == 1 && sit >=1
		return ($sit-1);
	}
		else
		{
			return $sit;
		}
	}
	else {
			if (($pos%$kek)==($lol+1)) {return $sit;}
		return $sit-1;
	}
}
function getIndexkek($n, $sit) {
				switch ($n) {

					case 6:
					switch ($sit) {
						case 2:
						case 4:
						case 11:
						$index = 0;
						break;

						case 7:
						case 8:
						case 9:
						$index = 1;
						break;

						case 17:
						case 18:
						case 19:
						$index = 2;
						break;

						case 1:
						case 5:
						case 12:
						$index = 3;
						break;

						case 10:
						case 13:
						case 14:
						$index = 4;
						break;

						case 15:
						case 16:
						$index = 5;
						break;

					}

					break;

					case 9:
					switch($sit){
						case 2:
						case 4:
						$index = 0;
						break;

						case 11:
						case 8:
						$index = 1;
						break;

						case 7:
						case 9:
						$index = 2;
						break;

						case 17:
						$index = 3;
						break;

						case 18:
						case 19:
						$index = 4;
						break;

						case 1:
						case 5:
						$index = 5;
						break;

						case 12:
						case 14:
						$index = 6;
						break;

						case 10:
						case 13:
						$index = 7;
						break;

						case 15:
						case 16:
						$index = 8;
						break;
					}
					break;

					case 3:
					//echo 'kek';
					switch ($sit) {
					case 1:
					case 5:
					case 10:
					case 13:
					case 14:
					$index = 0;
					break;

					case 15:
					case 16:
					case 17:
					case 18:
					case 19:
					case 2:
					$index = 1;
					break;

					case 4:
					case 7:
					case 8:
					case 9:
					case 11:
					case 12:
					$index = 2;
					break;
					}
					break;

					default:
					$index = -1;
				}

	return $index;
}
