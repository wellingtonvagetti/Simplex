<?php

require 'template.php';
$content = '
   <script language="javascript" type="text/javascript">

function checkData () {
    if (document.form0.numVariables.value == "" ||
        document.form0.numConstraints.value == "") {
			alert("Nenhum valor inserido!")
			return false
	}
	if (! isFinite(document.form0.numVariables.value) ||
		document.form0.numVariables.value < 0 ||
		document.form0.numVariables.value > 10) {
			alert("Entre como o número de valores de decisão de variáveis compreendida entre 0 e 10.")
			return false
	}
	if (! isFinite(document.form0.numVariables.value) ||
		document.form0.numConstraints.value < 0 ||
		document.form0.numConstraints.value > 10) {
			alert("Entre como o número de valores restrições entre 0 e 10.")
			return false
	}
	document.form0.submit()
}
		
	</script>

      <form name="form0" method="post" action="entrada_de_dados.php">
        <p>Qual é o objetivo da função: <input type="radio" name="minmax"
 value="min" checked><strong>Minimizar</strong> <input type="radio" name="minmax"
 value="max"><strong>Maximizar</strong></p>
        <p>Quantas variáveis de decisão é o problema: <input type="text"
 name="numVariables" size="3" maxlength="3"></p>
        <p>Quantas restrições: <input type="text" name="numConstraints"
 size="3" maxlength="3"></p>
        <table border="0" summary="invio form">
          <thead>
            <tr>
              <th><input type="button" value=" OK " 
onClick="checkData()"></th>
              <th><input type="reset" value=" Cancelar "></th>
            </tr>
          </thead>
        </table>
      </form>

	';

$title = 'Simplex PHP';
$pagina = new template;
$pagina->setta_titulo($title);
$pagina->setta_filename(basename($_SERVER["SCRIPT_NAME"]));
$pagina->setta_contenuto($content);
print ($pagina->mostra_pagina());
?>
