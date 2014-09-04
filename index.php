<?php include('config.php') ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10 lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Apocalypse Server Whitelist</title>
		<meta name="description" content="">
		<meta name="keywords" content="">
		
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="<?=($fn='css/main.css').'?v='.filemtime($fn)?>">
		
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>

	</head>
	<body>

		<div class="wrapper">
			<div class="container">

				<h1>Apocalypse Server Whitelist</h1>

				<div class="players">
					<?php

					$data = array("apikey" => $config['apikey']);
					$data_string = json_encode($data);

					$ch = curl_init('mc.spectrumbranch.com:8123/apoc_minecraft/whitelist.json');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($data_string))
					);
					
					$curl_result = curl_exec($ch);

					$result = json_decode($curl_result);

					foreach ($result->whitelist as $player) {
					?>

					<div class="player">
						<div class="player-head">
							<img src="https://minotar.net/helm/<?=$player->name?>/100.png" alt=""/>
						</div>
						<div class="player-name">
							<?=$player->name?>
						</div>
					</div>

					<?php } ?>
				</div>

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime($fn)?>"></script>

		<script type="text/javascript">

<?php /*
			$.ajax({
				url : "http://mc.spectrumbranch.com:8123/apoc_minecraft/whitelist.json",
				type: "POST",
				data : {apikey: "<?=$config['apikey']?>"},
				success: function(data)
				{
					var content = '';
					for (var i=0;i<data.whitelist.length;i++) {
						content += '<div class="player">'
								+ '	<div class="player-head">'
								+ '		<img src="https://minotar.net/helm/' + data.whitelist[i].name + '/100.png" alt=""/>'
								+ '	</div>'
								+ '	<div class="player-name">'
								+ data.whitelist[i].name
								+ '	</div>'
								+ '</div>';
					}
					$('.players').html(content);
				}
			});
*/?>

		</script>

	</body>
</html>