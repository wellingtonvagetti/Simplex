<?php

require 'template.php';
require 'matriz.php';
require 'util.php';
require 'racional.php';
require 'imagens.php';

function scrivi_pagina($content)  {
	$title = 'Simplex PHP';
	$pagina = new template;
	$pagina->setta_titulo($title);
	$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
	$pagina->setta_contenuto($content);
	print ($pagina->mostra_pagina());
}
function init_variables(&$minmax, &$numVariables, &$numConstraints, &$_a, &$_b, &$_c, &$_d, &$lge, &$intera, &$cambia_segno, &$grafico, &$image, &$name) /* Legge i dati che gli sono stati inviati e crea le variabili corrispondenti */ {
	
	foreach ($_POST as $var => $value) {
		$$var = $value;
		
	} 
	
	if (!strcmp($minmax, "max")) $cambia_segno = true;
	else $cambia_segno = false;
	
	for ($j = 1; $j < $numVariables + 1; $j++) {
		for ($i = 1; $i < $numConstraints + 1; $i++) {
			$_a[$i][$j] = new racional;
			if (isset($a[$i][$j]))
				$_a[$i][$j]->scanfrac($a[$i][$j]);
			
		} 
		
	} 
	
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		$_b[$i] = new racional;
		if (isset($b[$i]))
			$_b[$i]->scanfrac($b[$i]);
		
	} 
	
	for ($j = 1; $j < $numVariables + 1; $j++) {
		$_c[$j] = new racional;
		if (isset($c[$j]))
			$_c[$j]->scanfrac($c[$j]);
		
	} 
	
	$_d = new racional;
	if (isset($d))
		$_d->scanfrac($d);
	
} 
function riduci_standard(&$minmax, &$numVariables, $numConstraints, &$a, &$b, &$c, &$d, &$lge, &$intera, &$cambia_segno, &$base) {
	$tmp = "<p>";
	
	if (strcmp($minmax, "min")) {
		
		for ($j = 1; $j < $numVariables + 1; $j++) {
			$c[$j]->negatefrac();
		} 
		$d->negatefrac();
		$minmax = "min";
		$tmp = "&Egrave; Mudou o problema do máximo para o mínimo, alterando o sinal de <b>z</b>.<br>\n";
	} 
	$numAux = 0;
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		if (!strcmp($lge[$i], "=<")) {
			
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new racional(1, 1);
			$lge[$i] = "=";
			$tmp.= "Introduzida a variável <em>slack</em> x<sub>$j</sub> na linha $i.<br>\n";
			
			if ($b[$i]->value() >= 0) $base[] = array($i => $j);
			
		} 
		else if (!strcmp($lge[$i], ">=")) {
			
			$numAux++;
			$j = $numVariables + $numAux;
			$a[$i][$j] = new racional(-1, 1);
			$lge[$i] = "=";
			$tmp.= "Introduzida a variável <em>surplus</em> x<sub>$j</sub> na linha $i.<br>\n";
			if ($b[$i]->value() < 0) $base[] = array($i => $j);
		} 
		
	} 
	$numVariables+= $numAux;
	$tmp.= "</p>\n\n";

	return $tmp;
} 
function risorse_non_negative(&$a, &$b, &$lge, $numVariables, $numConstraints) {
	
	for ($i = 1; $i < $numConstraints + 1; $i++) {
		
		if (isset($b[$i]) and $b[$i]->num() < 0) { 
			$cambia_segno_vincolo[] = $i;
			
			$b[$i]->negatefrac();
			for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $a[$i][$j]->negatefrac();
			
			if (!strcmp($lge[$i], "=<")) $lge[$i] = ">=";
			else if (!strcmp($lge[$i], ">=")) $lge[$i] = "=<";
		} 
		
	} 
	if (isset($cambia_segno_vincolo) && count($cambia_segno_vincolo)) {
		
		for ($i = 0; $i < count($cambia_segno_vincolo); $i++) $tmp = "A restrição de número " . $cambia_segno_vincolo[$i] . " &egrave; multiplicado por -1, a fim de obter o recurso positivo.<br>\n";
	} 
	else $tmp = "";
	return $tmp;
} 
function di_base($a, $c, $numConstraints, $numVariables, $i, $j)  {
	if (isset($c[$j]) and $c[$j]->num() != 0) return false;
	
	for ($k = 1; $k < $numConstraints + 1; $k++)
	
	if ($k != $i)
	
	if (isset($a[$k][$j]) and $a[$k][$j]->num() != 0)
	
	return false;
	
	return true;
}

function cerca_base($a, $c, $numConstraints, $numVariables, $i, &$div)  {
	$div = new racional;
	
	for ($j = 1; $j < $numVariables + 1; $j++) {
		
		if (!isset($a[$i][$j])) continue;
		$div = $a[$i][$j];
		if ($div->num() > 0) {
			
			if (di_base($a, $c, $numConstraints, $numVariables, $i, $j)) {
				
				return $j;
			} 
			
		} 
		
	} 
	
	unset($div);
	return 0;
} 
function normalizza(&$a, &$b, $i, $j, $numVariables) {
	$div = new racional;
	$div = $a[$i][$j];
	if ($div->value() == 1) return "";
	elseif ($div->num() > 0) {
		
		$b[$i]->divfrac($b[$i], $div);
		for ($k = 0; $k < $numVariables + 1; $k++) {
			if (isset($a[$i][$k])) $a[$i][$k]->divfrac($a[$i][$k], $div);
		} 
		$content.= "A restrição de número $i &egrave; dividido por " . $div->fractoa() . " a fim de não introduzir uma variável artificial não necessário.<br>\n";
	} 
	else $content.= "<font color=\"Red\" size=\"+4\">Errore: solicitar a normalização sem pivô positivo.<br></font>\n";
	return $content;
} 
function aggiungi_artificiali($i, $numArtificials, $numVariables, &$a, $b, &$rho, &$base) {
	$k = $numVariables + $numArtificials;
	$a[$i][$k] = new racional(1, 1);
	for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) {
		$rho[$j]->subfrac($rho[$j], $a[$i][$j]);
	}
	$rho[0]->subfrac($rho[0], $b[$i]);
	$base[$i - 1] = $k;
	return "Introduzido variável artificial x<sub>$k</sub> en riga $i.<br>\n";
} 
function gia_in_base(&$base, $i) {
	
	if (isset($base[$i]) && $base[$i] != 0) return true;
	return false;
} 
function riduci_canonica(&$minmax, &$numVariables, &$numConstraints, &$numArtificials, &$a, &$b, &$c, &$d, &$lge, &$base, &$rho) {
	
	$base = array_fill(0, $numConstraints, 0);
	$tmp = "<p>";
	$numArtificials = 0;
	
	$tmp.= risorse_non_negative($a, $b, $lge, $numVariables, $numConstraints);
	
	for ($i = 1; $i < $numConstraints + 1; $i++) if (!gia_in_base($base, $i - 1)) {
		
		$div = 1;
		if ($j = cerca_base($a, $c, $numConstraints, $numVariables, $i, $div)) {
			
			if ($div->value() != 1) $tmp.= normalizza($a, $b, $i, $j, $numVariables);
			
			$base[$i - 1] = $j;
		} 
		else {
			if (!isset($rho)) for ($k = 0; $k < $numVariables + 1; $k++) $rho[$k] = new racional(0, 1);
			$tmp.= aggiungi_artificiali($i, ++$numArtificials, $numVariables, $a, $b, $rho, $base);
			
			$base[$i - 1] = $numVariables + $numArtificials;
		}
	} 
	$tmp.= "</p>\n\n";
	$numVariables+= $numArtificials;
	return $tmp;
} 
function crea_tableau_simplex($a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, &$Tableau) {
	$matriz[0][0] = new racional(-$d->num(), $d->den());
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matriz[0][$j] = new racional($c[$j]->num(), $c[$j]->den());
	for ($i = 0; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matriz[$i][0] = new racional($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matriz[$i][$j] = new racional($a[$i][$j]->num(), $a[$i][$j]->den());
	$Tableau = new matrix($numConstraints + 1, $numVariables + 1, $matriz, $base, $cambia_segno);
} 
function crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, &$Tableau) {
	
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($rho[$j])) $matriz[0][$j] = new racional($rho[$j]->num(), $rho[$j]->den());
	
	for ($j = 0; $j < $numVariables + 1; $j++) if (isset($c[$j])) $matriz[1][$j] = new racional($c[$j]->num(), $c[$j]->den());
	if ($cambia_segno)
		$matriz[1][0] = new racional(-$d->num(), $d->den());
	else
		$matriz[1][0] = new racional($d->num(), $d->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) if (isset($b[$i])) $matriz[$i + 1][0] = new racional($b[$i]->num(), $b[$i]->den());
	for ($i = 1; $i < $numConstraints + 1; $i++) for ($j = 1; $j < $numVariables + 1; $j++) if (isset($a[$i][$j])) $matriz[$i + 1][$j] = new racional($a[$i][$j]->num(), $a[$i][$j]->den());
	
	for ($i = 0; $i < count($base); $i++) {
		
			$base_df[$i] = $base[$i];
		
		
	} 
	$Tableau = new matrix($numConstraints + 2, $numVariables + 1, $matriz, $base_df, $cambia_segno);
	if (isset($div))
		unset($div);
} 
function fase_1(&$Tableau, &$content, $numArtificials)  {
	$content.= '<h2>Fase I</h2>';
	$uscita = false;
	$passo  = 0;
	do {
		$content.= "<h4>passo $passo :</h4>\n";
		$content.= '<table summary="mostra o quadro em uma mão e em\'então o valor das variáveis" cellpadding="50%" cellspacing="50%">
 <tbody>
  <tr><td>';
		$content.= $Tableau->display_tableau();
		$content.= "</td>\n  <td>";
		$content.= $Tableau->display_status(1);
		$content.= "</td>\n  </tr>\n </tbody>\n</table>\n";
		
		$result = $Tableau->simplex($i, $j, 1);
		if ($result == 0) {
			$uscita = true;
			$content.= "&rho; &egrave; minimizada.<br>\n";
		} 
		else if ($result == - 1) {
			$uscita = true;
			$content.= "Caso impossível: &rho; menos infinito. L\'algoritmo &egrave; implementação aplicada.<br>\n";
			scrivi($content);
			if (isset($grafico)) {
				delete($grafico);
				exit(0);
			} 
			
		} 
		else {
			$content.= "nenhuma solução viável. L'algoritmo continua a iteração.<br>\n";
			$content.= "Linha  <strong>r$i</strong> Coluna <strong>x$j</strong>.<br>\n";
		}
		$passo ++;
	}
	while ($uscita == false && $passo  < 25);
	if ($passo  == 25) $content.= "L'algoritmo termina perch&egrave; alcançado o número máximo de iterações fornecidos.<br>\n";
	
	$p = new racional;
	$p = $Tableau->elemento(0, 0);
	if ($p->value() < 0) {
		$content.= 'A região de qualificação&agrave; &egrave; vazio.<p><font color="red" size="+2"><strong>Não há soluções.<br></strong></font></p>';
		scrivi_pagina($content);
		echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
		if (isset($grafico)) {
			delete($grafico);
			exit(0);
		} 
		exit(0);
			
	} 
	else if ($p->value() > 0) {
		$content.= "Não fornecidos se: &rho;, para a definição de não-negativo, &egrave; menor do que zero. L'algoritmo &egrave; implementação aplicada.<br>\n";
		scrivi_pagina($content);
		echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
		if (isset($grafico)) {
			delete($grafico);
			exit(0);
		} 
		exit(1);		
	} 
	else
	
	if ($Tableau->fuori_base_artificiali($numArtificials)) $content.= "Todas as variáveis artificiais são fora da base<br>\n";
	
	else {
		$content.= "Algumas variáveis artificiais foram baseadas. Precisa de mais operações de articulação.<br>\n";
		
		for ($j = 1; $j < $Tableau->col; $j++) $arry[$j] = $Tableau->in_base($j);
		
		for ($j = $Tableau->col - $numArtificials; $j < $Tableau->col; $j++) {
			
			if ($arry[$j] >= 0) {
				if ($Tableau->estrai_base_artificiale($j, $arry[$j], $k, $numArtificials)) {
					$content.= sprintf("Linha  <strong>r%d</strong> Coluna <strong>x%d</strong>.<br>", $arry[$j], $k);
					$content.= '<h4>passo ' . $passo ++ . ':</h4>';
					$content.= $Tableau->display_tableau();
					$content.= $Tableau->display_status(1);
				} 
				else {
					$content.= '<strong>Una o pi&ugrave; variáveis artificiais foram baseados.</strong><br />';
					if ($p = 0)
						$content.='A solução é exclusivamente determinada.'; 
					else
					 $content.= 'Há equações redundantes (linearmente dependente do outro).<br>Fin\'não implementaram as rotinas adequadas para o efeito e o\'algoritmo termina.';
					
					if (isset($grafico)) {
						delete($grafico);
					} 
					
					exit(0);
				}
			} 
			
		} 
		
	}
} 
function fase_2(&$Tableau, &$content, $grafico, $name, &$image) {
	$content.= '<h2>Método simplex</h2>';
	$uscita = false;
	$passo  = 0;
	do {
		$content.= '<h4>passo ' . $passo  . ':</h4>';
		$content.= '
    <table summary="mostra o quadro em uma mão e em\'então o valor de
    variáveis" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
		$content.= $Tableau->display_tableau();
		$content.= '</td>
      <td>';
		$content.= $Tableau->display_status(2);
		$content.= '</td>
      </tr>
     </tbody>
    </table>
    ';
		if (isset($grafico)) {
			if (!isset($x_1)) {
				$x_1 = $Tableau->sol[1]->value();
				$x_2 = $Tableau->sol[2]->value();
			} 
			$x_1old = $x_1;
			$x_2old = $x_2;
			$x_1 = $Tableau->sol[1]->value();
			$x_2 = $Tableau->sol[2]->value();
			$image->passo ($x_1old, $x_2old, $x_1, $x_2, $passo , $name);
			$content.= "<center><img src=\"$name.png\" alt=\"[IMG]  elegíveis na região'\" align=\"middle\"></center>";
		} 
		$result = $Tableau->simplex($i, $j, 2);
		if ($result == 0) {
			$uscita = true;
			if ($Tableau->unica()) {
				$content.= '<font color="red" size="+2">Solução <strong>ótima</strong>: &nbsp;&nbsp;&nbsp; </font>';
				$content.= $Tableau->solucao_otima();
			} 
			else {
				$content.= '<strong>É &egrave; uma das possíveis soluções ótimas.</strong><br>';
				$verticeA = $Tableau->sol;
				$Tableau->altra_solucao($i, $j, 2);
				$content.= sprintf("Linha  <strong>r%d</strong> Coluna <strong>x%d</strong>.<br>", $i, $j);
				$content.= '<h4>passo ' . ++$passo  . ':</h4>';
				$content.= '
    <table summary="mostra o quadro em uma mão e em\'então o valor de
    variáveis" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
				$content.= $Tableau->display_tableau();
				$content.= '</td>
      <td>';
				$content.= $Tableau->display_status(2);
				$content.= '</td>
      </tr>
     </tbody>
    </table>
    ';
				$verticeB = $Tableau->sol;
				$content.= $Tableau->soluzioni_ottime($verticeA, $verticeB);
			}
		} 
		else if ($result == - 1) {
			$uscita = true;
			$content.= '<font color="red" size="+2">solucao <strong>illimitata</strong>.<br>L\'algoritmo termina.</font><br>';
		} 
		else {
			$content.= 'Solução aprimoravel. algoritmo continua a interação.<br>';
			$content.= sprintf("Linha  <strong>r%d</strong> Coluna <strong>x%d</strong>.<br>", $i, $j);
		}
		$passo ++;
	}
	while ($uscita == false && $passo  < 25);
	if ($passo  == 25) $content.= sprintf("L'algoritmo termina porque&egrave; foi alcançado o número máximo de iterações fornecidas.<br>");
} 
function piani_di_taglio(&$Tableau, &$content) {
	$content.= '<h2>Resolução por meio dos planos de método de corte</h2>';
	$tagli = 0;
	$passo  = 0;
	
	while (!$Tableau->check_intera() && $tagli < 25) {
		
		$content.= '<h4>Adicionando uma restrição:</h4>';
		$content.= $Tableau->aggiungi_vincolo();
		$tagli++;
		
		
		$uscita = false;
		$passo  = 0;
		do {
			$content.= '<h4>passo ' . $passo  . ':</h4>';
			$content.= '
    <table summary="mostra o quadro em uma mão e em\'então o valor de
     variáveis" cellpadding="50%" cellspacing="50%">
     <tbody>
      <tr><td>';
			$content.= $Tableau->display_tableau();
			$content.= '</td>
      <td>';
			$content.= $Tableau->display_status(2);
			$content.= '</td>
      </tr>
     </tbody>
    </table>';
			
			$result = $Tableau->simplex_duale($i, $j);
			if ($result == 0) {
				$uscita = true;
				$content.= '<font color="red" size="+2">solucao <strong>otima</strong>: &nbsp;&nbsp;&nbsp; </font>';
				$content.= $Tableau->solucao_otima();
			} 
			else if ($result == - 1) {
				$uscita = true;
				$content.= '<font color="red" size="+2">solucao <strong>inadmissível</strong>.<br>L\'paradas algoritmo.</font><br>';
				scrivi_pagina($content);
				echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
				exit(0);
			} 
			else {
				$content.= "solucao super-otima. L'algoritmo continua a iteração.<br>\n";
				$content.= sprintf("Linha  <strong>r%d</strong> Coluna <strong>x%d</strong>.<br>\n", $i, $j);
			}
			$passo ++;
		}
		while ($uscita == false && $passo  < 25);
	} 
	if ($passo  == 0) {
		$content.= '<font color="red" size="+2">solucao <strong>otima</strong>: &nbsp;&nbsp;&nbsp; </font>';
		$content.= $Tableau->solucao_otima();
	} //$passo  == 0
	if ($passo  == 25) $content.= sprintf("L'algoritmo termina porque&egrave; alcançado o número máximo de iterações fornecida.<br>");
	if ($tagli == 25) $content.= sprintf("L'algoritmo termina porque&egrave; alcançado o número máximo de iterações fornecida.<br>");
} 

$tmpimages = glob("images/tmp/tmp*");
if (!empty($tmpimages) > 0) foreach ($tmpimages as $filename) {
	if (isset($filename)) unlink($filename);
} 

init_variables($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $lge, $intera, $cambia_segno, $grafico, $image, $name);

$content = '<h4>Problema Inserido &egrave;</h4>';
$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);

$tmp = riduci_standard($minmax, $numVariables, $numConstraints, $a, $b, $c, $d, $lge, $intera, $cambia_segno, $base);

if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>O FORMULÁRIO NORMALIZADO coincide representação do problema de entrada</strong>.</p>";
else {
	$content.= '<h4>Problema expresso na forma padrão</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);
	if (isset($verbose)) $content.= $tmp;
}

$tmp = riduci_canonica($minmax, $numVariables, $numConstraints, $numArtificials, $a, $b, $c, $d, $lge, $base, $rho);

if (!strcmp($tmp, "<p></p>\n\n")) $content.= "<p><strong>A FORMA VERDADEIRA coincide com o Padrão</strong>.</p>";
else {
	$content.= '<h4>O problema expresso em FORMA VERDADEIRA</h4>';
	$content.= mostra_equazioni($minmax, $numVariables, $numConstraints, $c, $d, $a, $lge, $b, $intera);
	if (isset($verbose)) $content.= $tmp;
}
unset($tmp);

if (isset($grafico)) $content.= "<center><img src=\"$name.png\" alt=\"[IMG]  região não legível'\" align=\"middle\"></center>";
if ($numArtificials > 0) {
	
	crea_tableau_fase_1($rho, $a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, $Tableau);
	$content.= '<h4>O problema expresso em forma canônica para a primeira fase</h4>';
	$content.= $Tableau->display_equations(2);
	$content.= fase_1($Tableau, $content,$numArtificials);
	
	$Tableau->prima_fase($numArtificials);
} 
else crea_tableau_simplex($a, $b, $c, $d, $numVariables, $numConstraints, $cambia_segno, $base, $Tableau);

$content.= fase_2($Tableau, $content, $grafico, $name, $image);
if (strcmp($intera, "true")) {
	
	scrivi_pagina($content);
	echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
	exit(0);
} 

$content.= piani_di_taglio($Tableau, $content);
scrivi_pagina($content);
echo "\n<!--" . memory_get_peak_usage(true) . " bytes-->" . "\n";
exit(0);
?>
