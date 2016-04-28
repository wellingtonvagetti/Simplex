<?php

require 'template.php';

foreach ($_POST as $var => $value) {
	$$var = $value;
}
date_default_timezone_set('UTC');

$content = '
	<script language="javascript" type="text/javascript">
		function looksLikeANumber(theString) {
			
			var result = true;
			var length = theString.length;
			if (length == 0) return (true);
			var x = ""
			var y = "1234567890-+*. /"
			var yLength = y.length;
			for (var i = 0; i <= length; i++) { 
				x = theString.charAt(i);
				result = false;
				for (var j = 0; j <= yLength; j++) {
					if (x == y.charAt(j)) {result = true; break}
				} 
				if (result == false) return(false);
			} 
			return(result);
		} 

		function checkData(dataForm) {
			for (var i = 0; i < dataForm.length-2; i++) { 
				name = dataForm.elements[i].name;
				if (name.substr(0,3) == "lge" || name == "minmax" || name == "name" || name == "intera" || name == "XDEBUG_SESSION_START") {}
				else {
					if (! looksLikeANumber (dataForm.elements[i].value)) {
						alert (name + " não e\' um numero!\n")
						return false
					}
				}
			} 
			dataForm.submit()
		}
	</script>
	
	<form name="form1" method="post" action="simplex.php">
	<input type="hidden" name="minmax" value="' . $minmax . '">
	<input type="hidden" name="numVariables" value="' . $numVariables . '">
	<input type="hidden" name="numConstraints" value="' . $numConstraints . '">
 	<input type="hidden" name="name" value="tmp' . date("siH") . '">' .
 	'<input type="hidden" name="XDEBUG_SESSION_START" value="testID">' .
 	'<input type="hidden" name="XDEBUG_PROFILE" value="">' .
 	"\n";

if (isset($intera) && !strcmp($intera, "true")) $content.= '<input type="hidden" name="intera" value="true">';
else $content.= '<input type="hidden" name="intera" value="false">';
$content.= '
	<strong>' . $minmax . ' z = ';

for ($j = 0; $j < $numVariables; $j++) {
	$content.= sprintf("<input type=\"text\" name=\"c[%d]\" size=\"5\" 
maxlength=\"5\"> x<sub>%d</sub> ", $j + 1, $j + 1);
	if($j < ($numVariables-1))
		$content.= sprintf("+\n");
}

$content.= ' <br><br> ';

for ($i = 0; $i < $numConstraints; $i++) {
	
	$content.= $i + 1 . ') ';
	
	$content.= sprintf("<input type=\"text\" name=\"a[%d][1]\" size=\"5\" 
maxlength=\"5\"> x<sub>1</sub>\n", $i + 1); // Le altre $numVariables variabili.
	for ($j = 1; $j < $numVariables; $j++) {
		$content.= sprintf("+ <input type=\"text\" name=\"a[%d][%d]\" size=\"5\" 
maxlength=\"5\"> x<sub>%d</sub>\n", $i + 1, $j + 1, $j + 1);
	}
	$content.= sprintf("<select 
name=\"lge[%d]\"><option>=&lt;</select> <input 
type=\"text\" name=\"b[%d]\" size=\"5\" maxlength=\"5\"><br>\n", $i + 1, $i + 1);
}

$content.= '
	&nbsp; &nbsp; x<sub>i</sub> &gt;= 0';

if (isset($intera) && !strcmp($intera, "true")) $content.= ' e INTERI';
$content.= ' &nbsp; i =1,...,' . $numVariables;

$content.= '</strong><br>
        <table border="0" summary="envio form">
          <thead>
            <tr>
              <th><input type="button" value=" OK " 
onClick="checkData(this.form)"></th>
              <th><input type="reset" 
value=" Cancelar "></th>
            </tr>
          </thead>
        </table>
	
	';

$title = 'Simplex PHP';
$pagina = new template;
$pagina->setta_titulo($title);
$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
$pagina->setta_contenuto($content);
print ($pagina->mostra_pagina());
?>
