<?php
	include('../config.php');
	include('../utils.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie10 lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Mahgeetah Server</title>
		<meta name="description" content="">
		<meta name="keywords" content="">

		<base href="../">

		<meta property="og:title" content="Mahgeetah Server" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://mc.spectrumbranch.com/mahgeetah/" />
		<meta property="og:site_name" content="catnips" />
		<meta property="og:image" content="http://mc.spectrumbranch.com/img/chicken.png" />
		<meta property="og:description" content="See who's whitelisted, who's online, and more!" />

		<link rel="shortcut icon" href="mahgeetah/favicon.ico" type="image/x-icon">
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="<?=($fn='css/main.css').'?v='.filemtime('../'.$fn)?>">
		
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>

	</head>
	<body>

		<?php $cur_page = 'mahgeetah'; include('../inc/header.php') ?>

		<div class="wrapper">
			<div class="container">

				<?php
					include_once '../serverstatus.php';
					$status = new MinecraftServerStatus();
					$response = $status->getStatus('mc.mahgeetah.com','25941');

					$handle = fopen('http://harryscheiner.com/mc/mahgeetah/whitelist.json', 'r');
					$contents = stream_get_contents($handle);
					fclose($handle);
					$whitelist = isset($contents) && !is_null($contents) ? json_decode($contents) : (object) array();

					$showkdr = false;
				?>

				<?php include('../inc/player-list.php') ?>

				<div class="whitelist-note">
					NOTE: May not show all players currently whitelisted.<br/>
					Last update from live whitelist was
					<?= filemtime_remote('http://harryscheiner.com/mc/mahgeetah/whitelist.json') ?>
				</div>

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime('../'.$fn)?>"></script>

		<script type="text/javascript">
			$('title').html($('title').html()+" (<?=$num_current_players.'/'.$num_max_players?>)");
			$('.player-info').addClass('visible');
		</script>

	</body>
</html>