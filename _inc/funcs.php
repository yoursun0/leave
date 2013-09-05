<?php
function mkArray($s){
	$a = array();
	$AAs = preg_split("/[,]/", $s);
	foreach($AAs as $AA){
		$AA = trim($AA);
		if(strpos($AA, "-") === false){
			$a[] = $AA;
		} else {
			$BBs = preg_split("/[-]/", $AA);
			if(count($BBs) != 2){
				echo "mkArray error: Invalid range found in string (".$AA.")" ;
				return array();
			} else {
				$BBs[0] = trim($BBs[0]);
				$BBs[1] = trim($BBs[1]);
				if(ereg('^[0-9]*$', $BBs[0]) && ereg('^[0-9]*$', $BBs[1])){
					if($BBs[0] < $BBs[1]){
						for($i = $BBs[0]; $i <= $BBs[1]; $i++){
							$a[] = $i;
						}
					} else {
						echo "mkArray error: Invalid range found in string (".$AA.")" ;
						return array();
					}
				} else if(ereg('^[A-Za-z]$', $BBs[0]) && ereg('^[A-Za-z]$', $BBs[1])){
					if(ereg('^[A-Z]$', $BBs[0])){
						$BBs[0] = strtoupper($BBs[0]);
						$BBs[1] = strtoupper($BBs[1]);
					} else {
						$BBs[0] = strtolower($BBs[0]);
						$BBs[1] = strtolower($BBs[1]);
					}

					if($BBs[0] < $BBs[1]){
						for($i = $BBs[0]; $i <= $BBs[1]; $i++){
							$a[] = $i;
						}
					} else {
						echo "mkArray error: Invalid range found in string (".$AA.")" ;
						return array();
					}
				} else {
					echo "mkArray error: Invalid range found in string (".$AA.")" ;
					return array();
				}
			}
		}
	}
	return $a;
}

?>