<!doctype html>
<html lang="pt-br" class="h-100">
  <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Enviar dados em planilha (XLS)</title>
	<link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">

	<!-- Bootstrap core CSS -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

	<style>
		.container {
			width: auto;
			max-width: 680px;
			padding: 0 15px;
		}

		.footer {
			background-color: #f5f5f5;
		}
		
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}	

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
			  font-size: 3.5rem;
			}
		}
	</style>
  </head>
  <body class="d-flex flex-column h-100">
	<main role="main" class="flex-shrink-0">
	  <div class="container">
		<h1 class="mt-5">Enviar dados em planilha (XLS)</h1>
		
		<form action="spreadsheet_process.php" method="POST" enctype="multipart/form-data">
			<div class="form-group mt-4">
				<label for="sistema" style="font-weight:bold;">Sistema:</label>
				<select id="sistema" name="sistema" required>
					<option value="">Selecione...</option>
					<option value="gal">GAL</option>
					<option value="sinan">SINAN</option>
					<option value="sitetb">SITE-TB</option>					
					<option value="tbweb">TB Web</option>
					<option value="iltb">IL-TB (Projeto ExpandTPT)</option>
				</select>
			</div>
			<div class="form-group mt-4">
				<label for="result_file" class="sr-only">Escolher o arquivo XLS:</label>
				<input type="file" name="result_file" class="form-control-file" id="result_file" required>
			</div>			
			<button type="submit" name="upload_excel" class="btn btn-block btn-sm btn-secondary mb-2 mt-5" onclick="clearAlerts();">Enviar</button>
		</form>
		
		<?php 
			if(isset($_GET["t"])) {
				$t = $_GET["t"];
				if($t == "error") {
					$json = json_encode(unserialize($_GET["msg"]),JSON_PRETTY_PRINT);
					if(!$json || $json == "false") $json = $_GET["msg"];
		?>
					<div class="alert alert-danger mt-4" role="alert" id="div-danger">							
						<?="<pre style='white-space: break-spaces;'>$json</pre>"?>
					</div>	
		<?php 	} else if($t == "success") { ?>
					<div class="alert alert-success mt-4" role="alert" id="div-success">
						<?=$_GET["msg"]?>
					</div>	
		<?php	}
			}
		?>
	  </div>
	</main>

	<footer class="footer mt-auto py-3">
	  <div class="container text-center">
		<span class="text-muted"><?=utf8_decode("Laboratório de Informática em Saúde - FMRP - USP")?></span>
	  </div>
	</footer>
	
	<script type="text/javascript">
		function clearAlerts() {
			//document.getElementById("div-danger").innerHTML = "";
			//document.getElementById("div-success").innerHTML = "";
			document.getElementById("div-danger").style.display = "none";
			document.getElementById("div-success").style.display = "none";
		}
	</script>
  </body>
</html>	