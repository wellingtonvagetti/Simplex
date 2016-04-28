<?php



	Function mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera)
	
	{
		$content = '
		<strong>' . $minmax . ' z = ';

		
		if (! isset($c[1]))
			echo "Errore: não existe c[1]\n";
		elseif ($c[1]->value() == 1) {
			$content .= sprintf(" x<sub>1</sub>");
		} elseif ($c[1]->num() == 0) {
			 
		} elseif ($c[1]->value() == -1) {
			$content .= sprintf(" - x<sub>1</sub>");
		} else {
			$content .= sprintf(" %s x<sub>1</sub>", $c[1]->fractoa());
		}			
		for ($j=2; $j<$numVariables+1; $j++) {
			if (! isset($c[$j])) { // == 0
					
			} elseif ($c[$j]->num() >= 0) {
				if ($c[$j]->value() == 1) { 
					$content .= sprintf(" + x<sub>%d</sub>", $j);
				} elseif ($c[$j]->num() == 0) { 
					
				} else { 
					$content .= sprintf(" + %s x<sub>%d</sub>", $c[$j]->fractoa(), $j);
				}
			} elseif ($c[$j]->value() == -1) { 
					$content .= sprintf(" - x<sub>%d</sub>", $j);
			} else {  // < 0 != -1
				$c1 = new racional (-$c[$j]->num(), $c[$j]->den());
				if ($c[$j]->value() == -1) { 
					$content .= sprintf(" - x<sub>%d</sub>", $j);
				} else {
					$content .= sprintf(" - %s x<sub>%d</sub>", $c1->fractoa(), $j);
				}
				unset ($c1);
			}
		}
		
		if (! isset($d))
			; 
		elseif ($d->num() > 0) { 
			$content .= sprintf(" + %s ", $d->fractoa());
		} elseif ($d->num() < 0) {
			$d1 = new racional (-$d->num(), $d->den());
			$content .= sprintf(" - %s ", $d1->fractoa());
			unset ($d1);
		} else
			; 

		$content .= '<br><br> Sujeito a<br><br>
		';
		
		for ($i=1; $i<$numConstraints+1; $i++) {
			
			$content .= $i . ') ';
			
			if (! isset($a[$i][1]))
				;
			elseif ($a[$i][1]->value() == 1) {
				$content .= sprintf(" x<sub>1</sub>");
			} elseif ($a[$i][1]->num() == 0) {
				
			} elseif ($a[$i][1]->value() == -1) {
				$content .= sprintf(" - x<sub>1</sub>");
			} else {
				$content .= sprintf(" %s x<sub>1</sub>", $a[$i][1]->fractoa());
			}
			
			for ($j=2; $j<$numVariables+1; $j++) {
				if (! isset($a[$i][$j]))
					;
				elseif ($a[$i][$j]->num() >= 0) { 
					if ($a[$i][$j]->value() == 1) {
						$content .= sprintf(" + x<sub>%d</sub>", $j);
					} elseif ($a[$i][$j]->num() == 0 ) {
						
					} else {
						$content .= sprintf(" + %s x<sub>%d</sub>", $a[$i][$j]->fractoa(), $j);
					}
				} elseif ($a[$i][$j]->value() == -1) { 
						$content .= sprintf(" - x<sub>%d</sub>", $j);
					} else {
					$a1 = new racional (-$a[$i][$j]->num(), $a[$i][$j]->den());
					$content .= sprintf(" - %s x<sub>%d</sub>", $a1->fractoa(), $j);
					unset ($a1);
				}
			}
			
			$content .= sprintf(" %s %s <br>", htmlentities($lge[$i]), $b[$i]->fractoa());
		}
					$content .= '
		&nbsp; &nbsp; &nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
		
		if (! strcmp ($intera,"true"))
			$content .= ' e INTERI';
		$content .= " &nbsp; &nbsp; per i = 1,...,$numVariables</strong><br><br>\n\n";

		return $content;
	}

?>
