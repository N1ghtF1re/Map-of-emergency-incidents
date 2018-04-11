<?php 
function getBasicColors ($n) {

    $colors = array(
            'red' => 255, 
            'green' => 0, 
            'blue' => 0
        );
    $basiccolor = array(); // ВОТ ЭТОТ;
    
    $basiccolor[] = $colors;
   
    $kek = 255*5.22;
    $shift = round($kek / $n);
    
    $trg_plus = True;
    $status = 'green';
    
    for ($i = 1; $i < $n; $i++){
        $exit_bool= False;
        $delta_shift = $shift;    
        while(!$exit_bool) {
                        
            if ($trg_plus) {
                //echo 'kek';
                while(($delta_shift != 0) && ( $colors[$status] < 255)) {
                    $colors[$status]++;  
                    $delta_shift--;
                }
            } 
            else {
                while(($delta_shift != 0) && ( $colors[$status] > 0 )) {
                    $colors[$status]--;                  
                    $delta_shift--;
                }        
            }
            if ($delta_shift == 0) {
                $basiccolor[] = $colors;
                $exit_bool = true;
            } 
            else {
                switch($status) {
                case 'green':
                    $status = 'red';
                break;
                case 'red':
                    $status = 'blue';
                break;
                case 'blue':
                    $status = 'green';
                break;

                }
                if ($trg_plus) {
                    $trg_plus = false; 
                }
                else {
                    $trg_plus = true;
                }
            }
        }
    }
    $newbasiccolor = array();
    for ($i = 0; $i < count($basiccolor); $i++) {
        $m = array(
        'r' => $basiccolor[$i]['red'],
        'g' => $basiccolor[$i]['green'],
        'b' => $basiccolor[$i]['blue']
        );
        $newbasiccolor[] = rgbToHex($m);
        //echo $newbasiccolor[$i];
    }
    return $newbasiccolor;

}

/*$mem = getBasicColors(19);

for ($i = 0; $i < count($mem); $i++) {
	//echo $mem['red'];
	$m = array(
		'r' => $mem[$i]['red'],
		'g' => $mem[$i]['green'],
		'b' => $mem[$i]['blue']
	);
	echo '<div style="width:30px; height: 30px; background:'.rgbToHex($m).'"></div>';
}*/