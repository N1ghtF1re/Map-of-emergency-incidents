<?php 
function getIndex($n, $sit) {
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