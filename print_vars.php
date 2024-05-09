<?php
	$vars = json_decode(file_get_contents("vars.json"),true);
	//var_dump($vars);
	foreach($vars as $systemName => $system) {
		echo strtoupper($systemName).";Nome do campo/variável no Integrador da TB (nome amigável)<br/>";
		foreach($system as $originalField => $mappedField) {
			echo $originalField.";".$mappedField."<br/>";
		}	
	}
	
	/*
	"Sexo": "sexo",
		"Contato de TB": "contato_tb",
		"Data de início do tratamento atual": "data_inicio_tto_atual",
		"Medicamentos": "medicamentos",
		"Situação de Encerramento": "situacao_encerramento",
		"Residência - Município": "municipio_residencia"
	*/
?>