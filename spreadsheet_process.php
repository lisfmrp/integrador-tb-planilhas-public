<?php
require_once 'PHPExcel/IOFactory.php';
include("bd_connection.php");

function vars($k1,$k2,$file) {
	$m = json_decode(file_get_contents($file),true);
	if(isset($m[$k1][$k2]))
		return $m[$k1][$k2];
	else
		return "???";
}

if (isset($_POST['upload_excel'])) {
    //recebe arquivo
    $file_directory = str_replace('\\', '/', getcwd()) . "/uploads/";
	$vars_file = str_replace('\\', '/', getcwd()) . "/vars.json";
    $new_file_name = date("dmY") . time() . $_FILES["result_file"]["name"];

    //recebe nome do arquivo
    $file_name_csv = explode('.', $_FILES["result_file"]["name"]);
    unset($file_name_csv[sizeof($file_name_csv) - 1]);
    $file_name_csv = implode('', $file_name_csv);

	//echo json_encode(var_dump($_FILES));
    if (move_uploaded_file($_FILES["result_file"]["tmp_name"], $file_directory . $new_file_name)) {
        //leitura do arquivo e armazenamento em array
        $file_type = PHPExcel_IOFactory::identify($file_directory . $new_file_name);
        $objReader = PHPExcel_IOFactory::createReader($file_type);
        $objPHPExcel = $objReader->load($file_directory . $new_file_name);
        $sheet_count = $objPHPExcel->getSheetCount();
        for ($i = 0; $i < $sheet_count; $i++) {
            $xls_data[] = $objPHPExcel->getSheet($i)->toArray(null, True, True, True);
        }
			
		//echo json_encode($xls_data[0]); exit();
		//var_dump($xls_data[0]); exit();
		//echo sizeof($xls_data[0]); exit();

		$db = "dados_tb";
		$system = $_POST['sistema'];
		if($system == "iltb") {
			$db = "expandtpt";
		}
		
		if($db == "integrador" || $db == "expandtpt") {
			$table = $db.".".$system;
		} else if($db == "dados_tb") {
			if($system == "sinan")
				$table = $db.".sinan_rio_2017_2022";
			else if($system == "gal")
				$table = $db.".gal_2019_2023";
		}
		
		$columns = $xls_data[0][1];
		$ignoredColumns = array();
		$sqlBase = "INSERT IGNORE INTO $table (";
		foreach($columns as $c_key => $c) {
			$columnTranslated = utf8_decode(vars($system,$c,$vars_file));
			if($columnTranslated != "???")
				$sqlBase .= $columnTranslated.",";
			else
				$ignoredColumns[] = $c_key;
		}
		$sqlBase = substr($sqlBase,0,strlen($sqlBase)-1).") VALUES ";
		$sqlInserts = array();
		
		for($i=2;$i<sizeof($xls_data[0]);$i++) {
			$sqlInsertValues = "";
			foreach($xls_data[0][$i] as $v_key => $v) {
				if(!in_array($v_key,$ignoredColumns)) {
					$tempDate = false;
					if(!is_int($v) && (strpos($v,"/") !== false || strpos($v,"-") !== false)) $tempDate = strtotime($v);
					if ($tempDate !== false) {
						$v = date("Y-m-d",$tempDate);
					}
					$sqlInsertValues .= "'".utf8_decode(str_replace("'","",$v))."',";
				}
			}
			$sqlInserts[] = "(".substr($sqlInsertValues,0,strlen($sqlInsertValues)-1).")";			
		}		        		
		$sqlFinal = $sqlBase . implode(",",$sqlInserts).";";
		$conn->query($sqlFinal);
		echo $sqlFinal; exit();
		header("Location: spreadsheet.php?t=success&msg=Dados carregados com sucesso!");
    } else {
		header("Location: spreadsheet.php?t=error&msg=Erro ao carregar arquivo. Por favor, tente novamente.");
	}
} else {
	header("Location: spreadsheet.php?t=error&msg=Arquivo não encontrado.");
}