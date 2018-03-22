<?php 

function hexToRgb($color) {
    // проверяем наличие # в начале, если есть, то отрезаем ее
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }
   
    // разбираем строку на массив
    if (strlen($color) == 6) { // если hex цвет в полной форме - 6 символов
        list($red, $green, $blue) = array(
            $color[0] . $color[1],
            $color[2] . $color[3],
            $color[4] . $color[5]
        );
    } elseif (strlen($cvet) == 3) { // если hex цвет в сокращенной форме - 3 символа
        list($red, $green, $blue) = array(
            $color[0]. $color[0],
            $color[1]. $color[1],
            $color[2]. $color[2]
        );
    } else {
        return false; 
    }
 
    // переводим шестнадцатиричные числа в десятичные
    $red = hexdec($red); 
    $green = hexdec($green);
    $blue = hexdec($blue);
     
    // вернем результат
    return array(
        'r' => $red, 
        'g' => $green, 
        'b' => $blue
    );
}

// перевод цвета из RGB в HEX
function rgbToHex($color) {
    $red = dechex($color['r']); 
    if (strlen($red) == 1) {
        $red = '0'.$red;
    }
    $green = dechex($color['g']);
    if (strlen($green) == 1) {
        $green = '0'.$green;
    }
    $blue = dechex($color['b']);
    if (strlen($blue) == 1){
        $blue = '0'.$blue;
    }
    return "#" . $red . $green . $blue;
}

function MixColors($Colors)
{
   $rgbsum = array (
        'r' => 0, 
        'g' => 0, 
        'b' => 0
    );

    for ($i = 0; $i < count($Colors); $i++) {
        //echo $Colors[$i];
        $Result = hexToRgb($Colors[$i]);
        //var_dump($Result);
        $rgbsum['r'] += $Result['r'];
        $rgbsum['g'] += $Result['g'];
        $rgbsum['b'] += $Result['b'];
    }
    $rgbsum['r'] = round($rgbsum['r'] / count($Colors));
    $rgbsum['g'] = round($rgbsum['g'] / count($Colors));
    $rgbsum['b'] = round($rgbsum['b'] / count($Colors));


    return rgbToHex($rgbsum);
}


function LighterColor($Color, $Percent) {

    $rgb = array (
        'r' => 0, 
        'g' => 0, 
        'b' => 0
    );

    if ($Percent <= 0) {
        return $Color;
    }
    if ($Percent > 100) {
        $Percent = 100;
    }

    $Result = hexToRgb($Color);
    $rgb['r'] = $Result['r'] + round((255 - $Result['r']) * $Percent / 100);
    $rgb['g'] = $Result['g'] + round((255 - $Result['g']) * $Percent / 100);
    $rgb['b'] = $Result['b'] + round((255 - $Result['b']) * $Percent / 100);

    return rgbToHex($rgb);
}
