<?php
class matrix {
	public $row; 
	public $col; 
	public $Tableau; 
	public $S; 
	public $cambia_segno; 
	public $sol; 
	public function matrix($row, $col, $matriz, $S, $cambia_segno)  {
		$this->row = $row;
		$this->col = $col;
		$this->Tableau = $matriz;
		$this->S = $S;
		$this->cambia_segno = $cambia_segno;
	}
	
	public function in_S($j) {
		$m = count($this->S);
		for ($i = 0; $i < $m; $i++) if ($j == $this->S[$i]) return $i;
		return -1;
	}
	
	public function display_tableau()  {
		$content = '
        <table frame="box" summary="visualizzazione tableau" cellspacing="5" cellpadding="3">
         <thead>
        ';
		
		$content.= '<tr  align="center" valign="middle">
            <th></th><th></th>';
		for ($j = 1; $j < $this->col; $j++)
		
		$content.= sprintf("<th>x%d</th>\n", $j);
		$content.= '</tr>
         </thead>
         <tbody>
         ';
		for ($i = 0; $i < $this->row; $i++) {
			
			$content.= '<tr  align="center"  valign="middle"><td><strong>r' . $i . '</strong></td>';
			for ($j = 0; $j < $this->col; $j++) {
				
				if ($this->in_S($j) >= 0) $bgcolor = "#E8E8E8";
				else $bgcolor = "#FFFFFF";
				if (!isset($this->Tableau[$i][$j])) $content.= sprintf("<td bgcolor=\"$bgcolor\">0</td>\n");
				else $content.= sprintf("<td bgcolor=\"$bgcolor\">%s</td>\n", $this->Tableau[$i][$j]->fractoa());
			} 
			$content.= '</tr>';
		} 
		$content.= '
         </tbody>
        </table>
        ';
		return $content;
	}
	public function display_equations($fase)  {
		if ($fase == 1) $content = '
        <strong> min z = ';
		else $content = '
        <strong> min &rho; = &Sigma; &alpha;<sub>i</sub> = ';
		
		if (!isset($this->Tableau[0][1])) $content.= sprintf("&nbsp; &nbsp;");
		else if ($this->Tableau[0][1]->value() == 1) {
			$content.= sprintf(" x<sub>1</sub>");
		} 
		else if ($this->Tableau[0][1]->num() == 0) {
			
			
		} 
		else if ($this->Tableau[0][1]->value() == - 1) {
			$content.= sprintf(" - x<sub>1</sub>");
		} 
		else {
			$content.= sprintf(" %s x<sub>1</sub>", $this->Tableau[0][1]->fractoa());
		}
		for ($j = 2; $j < $this->col; $j++) {
			if (!isset($this->Tableau[0][$j])) {
				
				
			} 
			else if ($this->Tableau[0][$j]->num() >= 0) {
				if ($this->Tableau[0][$j]->value() == 1) { 
					$content.= sprintf(" + x<sub>%d</sub>", $j);
				} 
				else if ($this->Tableau[0][$j]->num() == 0) { 
					
					
				} 
				else { 
					$content.= sprintf(" + %s x<sub>%d</sub>", $this->Tableau[0][$j]->fractoa(), $j);
				}
			} 
			else if ($this->Tableau[0][$j]->value() == - 1) { 
				$content.= sprintf(" - x<sub>%d</sub>", $j);
			} 
			else { 
				$tmp = new racional;
				$tmp = clone $this->Tableau[0][$j];
				$tmp->negatefrac();
				$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
			}
		} 
		
		if (!isset($this->Tableau[0][0])) {
			
			
		} 
		else if ($this->Tableau[0][0]->num() > 0) {
			$content.= sprintf(" - %s ", $this->Tableau[0][0]->fractoa());
		} 
		else if ($this->Tableau[0][0]->num() < 0) {
			$tmp = new racional;
			$tmp = clone $this->Tableau[0][0];
			$tmp->negatefrac();
			$content.= sprintf(" + %s ", $tmp->fractoa());
		} 
		else;
		$content.= '<br><br>
        Soggetto a<br><br>
        ';
		
		for ($i = $fase; $i < $this->row; $i++) {
			
			if ($fase == 1) $content.= $i . ') ';
			else $content.= $i - 1 . ') ';
			
			if (!isset($this->Tableau[$i][1])) {
				
				
			} 
			else if ($this->Tableau[$i][1]->value() == 1) {
				$content.= sprintf(" x<sub>1</sub>");
			} 
			else if ($this->Tableau[$i][1]->value() == - 1) {
				$content.= sprintf(" - x<sub>1</sub>");
			} 
			else if ($this->Tableau[$i][1]->num() == 0) {
				
				
			} 
			else {
				$content.= sprintf(" %.s x<sub>1</sub>", $this->Tableau[$i][1]->fractoa());
			}
			
			for ($j = 2; $j < $this->col; $j++) {
				if (!isset($this->Tableau[$i][$j])) {
					
					
				} 
				else if ($this->Tableau[$i][$j]->num() >= 0) { 
					if ($this->Tableau[$i][$j]->value() == 1) { 
						$content.= sprintf(" + x<sub>%d</sub>", $j);
					} 
					else if ($this->Tableau[$i][$j]->num() == 0) { 
						
						
					} 
					else { 
						$content.= sprintf(" + %s x<sub>%d</sub>", $this->Tableau[$i][$j]->fractoa(), $j);
					}
				} 
				else if ($this->Tableau[$i][$j]->value() == - 1) { 
					$content.= sprintf(" - x<sub>%d</sub>", $j);
				} 
				else { 
					$tmp = new racional;
					$tmp = clone $this->Tableau[$i][$j];
					$tmp->negatefrac();
					$content.= sprintf(" - %s x<sub>%d</sub>", $tmp->fractoa(), $j);
				}
			} 
			
			if (!isset($this->Tableau[$i][0])) $content.= sprintf(" = 0 <br>");
			else $content.= sprintf(" = %s <br>", $this->Tableau[$i][0]->fractoa());
		} 
		
		$content.= '
        &nbsp; &nbsp; x<sub>i</sub> &gt;= 0';
		
		if (isset($intera) && !strcmp($intera, "true")) $content.= ' e INTERI';
		$var = $this->col - 1;
		$content.= ' &nbsp; per i =1,...,' . $var . '</strong>
        ';
		return $content;
	}
	public function display_status($fase)  {
		
		$content = 'Indice da base: S = { ';
		for ($i = 0; $i < count($this->S); $i++) $content.= $this->S[$i] . ', ';
		
		$content = substr_replace($content, ' }', strlen($content) - 2);
		$content.= ' <br>
        Solucao da base: ';
		if ($fase == 1) {
			$p = new racional(-$this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			$content.= sprintf("&rho; = %s <br>", $p->fractoa());
			
			for ($j = 1; $j < $this->col; $j++) {
				$i = $this->in_S($j);
				if ($i >= 0) { 
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->Tableau[$i + 2][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->Tableau[$i + 2][0];
				} 
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new racional(0, 1);
				}
			}
		} 
		else { 
			if ($this->cambia_segno == false) 
			$this->sol[0] = new racional(-$this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			else { 
				$this->sol[0] = new racional($this->Tableau[0][0]->num(), $this->Tableau[0][0]->den());
			}
			$content.= sprintf("z = %s <br>", $this->sol[0]->fractoa());
			
			for ($j = 1; $j < $this->col; $j++) {
				$i = $this->in_S($j);
				if ($i >= 0) { 
					if (isset($i)) $content.= sprintf("x<sub>%d</sub> = %s <br>", $j, $this->Tableau[$i + 1][0]->fractoa());
					else echo "in_base NON FUNZIONA<br />\n";
					$this->sol[$j] = clone $this->Tableau[$i + 1][0];
				} 
				else {
					$content.= sprintf("x<sub>%d</sub> = 0 <br>", $j);
					$this->sol[$j] = new racional(0, 1);
				}
			}
		}
		return $content;
	}
	public function solucao_otima() {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = [ ", $this->sol[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $this->sol[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup>", $this->sol[$j]->fractoa());
		$content.= "</font><br>\n";
		return $content;
	}
	public function soluzioni_ottime($verticeA, $verticeB) {
		$content = '<font color="#0000FF" size="+2">';
		$content.= sprintf("z<sup>*</sup> = %s, &nbsp;&nbsp;&nbsp; x<sup>*</sup> = &lambda; [ ", $verticeA[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $verticeA[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup> + (1-&lambda;) [ ", $verticeB[0]->fractoa());
		for ($j = 1; $j < $this->col - 1; $j++) $content.= sprintf("%s, ", $verticeB[$j]->fractoa());
		$content.= sprintf("%s ]<sup>T</sup>", $verticeB[$j]->fractoa());
		$content.= "</font><br>\n";
		return $content;
	}
	
	public function in_base($var)  {
		for ($i = 0; $i < count($this->S); $i++)
		
		if ($this->S[$i] == $var) return $i;
		
		return -1;
	}
	public function riduci_tableau($varArtificials)  {
		
		for ($i = 0; $i < $this->row; $i++) for ($j = $this->col - $varArtificials; $j < $this->col; $j++) unset($this->Tableau[$i][$j]);
		$this->col-= $varArtificials;
		
		for ($i = 0; $i < $this->row; $i++) for ($j = 0; $j < $this->col; $j++) $this->Tableau[$i - 1][$j] = $this->Tableau[$i][$j];
		
		$this->row--;
	}
	public function correggi_base()  {
		
		
	}
	public function prima_fase($numArtificials)  {
		$this->riduci_tableau($numArtificials);
		$this->correggi_base();
	}
	public function fuori_base_artificiali($numArtificials)  {
		for ($j = $this->col - $numArtificials; $j < $this->col; $j++) if ($this->in_base($j) >= 0) return false;
		return true;
	}
	public function estrai_base_artificiale($j, $h, &$k, $numArtificials)  {
		
		for ($n = 1; $n < $this->col; $n++) $arry[$n] = $this->in_base($n);
		
		$k = 0;
		while ($k < $this->col - 1) {
			$k++;
			if ($arry[$k] < 0 && isset($this->Tableau[$h][$k]) && $this->Tableau[$h][$k]->num() > 0) break;
		} 
		if ($k >= $this->col - $numArtificials)
			return false;
		
		$this->pivot($h, $k, 1);

		return true;
	}
	public function unica()  {
		
		for ($j = 1; $j < $this->col; $j++) if ($this->in_S($j) < 0) 
		if (!isset($this->Tableau[0][$j]) || $this->Tableau[0][$j]->num() == 0) return false;
		return true;
	}
	public function altra_solucao(&$i, &$j, $fase) {
		
		for ($j = 1; $j < $this->col; $j++) {
			if ($this->in_S($j) < 0)
			 if (!isset($this->Tableau[0][$j]) || $this->Tableau[0][$j]->num() == 0) {
				
				$h = - 1;
				$min = 10e9; 
				for ($i = 1; $i < $this->row; $i++) {
					if (isset($this->Tableau[$i][$j])) if ($this->Tableau[$i][$j]->num() > 0) {
						$quo = $this->Tableau[$i][0]->value() / $this->Tableau[$i][$j]->value();
						if ($quo < $min) {
							$h = $i;
							$min = $quo;
						} 
						
					}
					
				} 
				if ($h == - 1) 
				continue; 
				
				$this->pivot($h, $j, $fase);
				
				$i = $h;
				break;
			} 
			
		}
	}
	
	public function pivot($h, $k, $fase)  {
		$pivot = new racional($this->Tableau[$h][$k]->num(), $this->Tableau[$h][$k]->den());
		
		for ($j = 0; $j < $this->col; $j++) if (isset($this->Tableau[$h][$j]) && $this->Tableau[$h][$j]->num != 0) $this->Tableau[$h][$j]->divfrac($this->Tableau[$h][$j], $pivot);
		
		for ($i = 0; $i < $this->row; $i++)
		
		if ($i != $h) {
			if (isset($this->Tableau[$i][$k])) $m = new racional($this->Tableau[$i][$k]->num(), $this->Tableau[$i][$k]->den());
			for ($j = 0; $j < $this->col; $j++)
			
			if ($j == $k) if (isset($this->Tableau[$i][$j])) $this->Tableau[$i][$j]->set(0, 1);
			else $this->Tableau[$i][$j] = new racional(0, 1);
			
			else {
				$tmp = new racional;
				if (isset($m)) {
					if (!isset($this->Tableau[$h][$j])) $this->Tableau[$h][$j] = new racional();
					$tmp->mulfrac($m, $this->Tableau[$h][$j]);
					if (isset($this->Tableau[$i][$j])) $this->Tableau[$i][$j]->subfrac($this->Tableau[$i][$j], $tmp);
					else $this->Tableau[$i][$j] = new racional(-$tmp->num(), $tmp->den());
				} 
				
			} 
			
		} 
		if ($fase == 1) $this->S[$h - 2] = $k;
		else $this->S[$h - 1] = $k;
	}
	public function simplex(&$row, &$col, $fase)  {
		
		$otima = 0;
		$illimitata = - 1;
		$migliorabile = 1;
		$k = - 1;
		$min = 1;
		
		for ($j = 1; $j < $this->col; $j++) {
			if (($this->in_S($j) < 0)) {
				if ( ! isset($this->Tableau[0][$j])) {
					$this->Tableau[0][$j] = new racional;
				}
				if ($this->Tableau[0][$j]->num() < 0 && $this->Tableau[0][$j]->value() < $min) {
				$k = $j;
				$min = $this->Tableau[0][$j]->value();
				} 
			}
			
		} 
		if ($k == - 1) 
		return $otima; 
		
		$h = - 1;
		$min = 10e9; 
		
		for ($fase == 1 ? $i = 2 : $i = 1; $i < $this->row; $i++) {
			if (isset($this->Tableau[$i][$k])) if ($this->Tableau[$i][$k]->num() > 0) {
				$quo = $this->Tableau[$i][0]->value() / $this->Tableau[$i][$k]->value();
				if ($quo < $min) {
					$h = $i;
					$min = $quo;
				} 
				
			} 
			
		} 
		if ($h == - 1) 
		return $illimitata; 
		
		$this->pivot($h, $k, $fase);
		$row = $h;
		$col = $k;
		return $migliorabile;
	}
	public function simplex_duale(&$row, &$col)  {
		
		$otima = 0;
		$inammissibile = - 1;
		$nonammissibile = 1;
		$h = - 1;
		$min = 1;
		
		for ($i = 1; $i < $this->row; $i++) {
			if ($this->Tableau[$i][0]->num() < 0 && $this->Tableau[$i][0]->value() < $min) {
				$h = $i;
				$min = $this->Tableau[$i][0];
			} 
			
		} 
		if ($h == - 1) 
		return $otima; 
		
		$k = - 1;
		$min = 10e9; 
		for ($j = 1; $j < $this->col; $j++) {
			if ($this->Tableau[$h][$j]->num() < 0) {
				$quo = $this->Tableau[0][$j]->value() / (-$this->Tableau[$h][$j]->value());
				if ($quo < $min) {
					$k = $j;
					$min = $quo;
				} 
				
			} 
			
		} 
		if ($k == - 1) 
		return $inammissibile; 
		
		
		$this->pivot($h, $k, 2);

		$row = $h;
		$col = $k;
		return $nonammissibile;
	}
	
	public function elemento($i, $j)  {
		return $this->Tableau[$i][$j];
	}
	public function check_intera()  {
		
		if ($this->sol[0]->den() != 1) return false;
		
		for ($j = 0; $j < $this->col; $j++) {
			$i = $this->in_base($j);
			if ($i != 0) {
				if (isset($this->sol[$i]->den)) if ($this->sol[$i]->den() != 1) {
					return false;
				} 
				
			}
			
		} 
		return true;
	}
	public function frac($x)  {
		$int = floor($x->value());
		$f = new racional($int, 1);
		$f->subfrac($x, $f);
		return $f;
	}
	public function aggiungi_vincolo()  {
		
		$f = new racional;
		$frac = new racional;
		$h = - 1;
		$max = 0;
		for ($i = 0; $i < $this->row; $i++) {
			$frac = $this->frac($this->Tableau[$i][0]);
			if ($frac->value() > $max) {
				$max = $frac->value();
				$h = $i;
			} 
			
		} 
		if ($h == - 1) return "o programa &egrave; mal aplicado: não encontra restrições não inteiros";
		else $content = '<p>Riga con f<sub>i</sub> massima: <strong>' . $h . '</strong></p>';
		
		$f = clone $this->frac($this->Tableau[$h][0]);
		$this->Tableau[$this->row][0] = new racional(-$f->num(), $f->den());
		$f = $this->frac($this->Tableau[$h][1]);
		$this->Tableau[$this->row][1] = new racional(-$f->num(), $f->den());
		if ($this->Tableau[$this->row][1]->num() != 0) {
			$f->set(-$this->Tableau[$this->row][1]->num(), $this->Tableau[$this->row][1]->den());
			$content.= sprintf(" %s x<sub>1</sub>", $f->fractoa());
		} 
		for ($j = 2; $j < $this->col; $j++) {
			$f = clone $this->frac($this->Tableau[$h][$j]);
			$this->Tableau[$this->row][$j] = new racional(-$f->num(), $f->den());
			if ($this->Tableau[$this->row][$j]->num() != 0) {
				$f->set(-$this->Tableau[$this->row][$j]->num(), $this->Tableau[$this->row][$j]->den());
				$content.= sprintf(" + %s x<sub>%d</sub>", $f->fractoa(), $j);
			} 
			
		} 
		$f->set(-$this->Tableau[$this->row][0]->num, $this->Tableau[$this->row][0]->den());
		$content.= sprintf(" &gt;= %s", $f->fractoa());
		$this->Tableau[$this->row][$this->col] = new racional(1, 1);
		
		$this->S[] = $this->col;
		$this->row++;
		$this->col++;
		return $content;
	}
}
?>

