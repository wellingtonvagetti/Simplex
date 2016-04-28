<?php

class template {
	var $titulo;
	var $filename;
	var $alta;
	var $bassa;
	var $pagina;
	var $contenuto;
	var $setta_alta;
	var $setta_bassa;
	Function setta_titulo($title) {
		$this->titulo = $title;
	}
	Function setta_filename($filename) {
		$this->filename = $filename;
	}
	Function setta_alta() {
		if (!isset($this->setta_alta)) {
			$this->setta_alta = 1;
		}
		
		
		$keywords = '  <meta name="keywords" content="Tiago e Wellington">
';
		$style = '  <link rel="StyleSheet" href="stile.css" type="text/css" media="screen">';
		$this->alta = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>Tiago e Wellington - ' . $this->titulo . ' </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name=author content="Tiago e Wellington">
' . $keywords . $style . '
</head>

<body>';
	}
	Function setta_bassa() {
		if (!isset($this->setta_bassa)) {
			$this->setta_bassa = 1;
		}
		$this->bassa = '
';
	}
	Function setta_contenuto($content) {
		$this->contenuto = $content;
	}
	Function mostra_pagina() {
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		if (!isset($this->setta_alta)) {
			$this->setta_alta();
		}
		if (!isset($this->setta_bassa)) {
			$this->setta_bassa();
		}
		$this->pagina = $this->alta . '<h2>' . "$this->titulo" . '</h2>' . $this->contenuto . $this->bassa;
		return ($this->pagina);
	}
}
?>
