<?php
	include('config.php');
	include('utils.php');
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
		<title>Apocalypse Server</title>
		<meta name="description" content="">
		<meta name="keywords" content="">

		<meta property="og:title" content="Apocalypse Server" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://mc.spectrumbranch.com/" />
		<meta property="og:site_name" content="catnips" />
		<meta property="og:image" content="http://mc.spectrumbranch.com/img/egg.png" />
		<meta property="og:description" content="See who's whitelisted, who's online, and more!" />

		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="<?=($fn='css/main.css').'?v='.filemtime($fn)?>">
		
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>

	</head>
	<body>

		<?php $cur_page = 'apocalypse'; include('inc/header.php') ?>

		<div class="wrapper">
			<div class="container">

				<?php
					include_once 'serverstatus.php';
					$status = new MinecraftServerStatus();
					$response = $status->getStatus('mc.spectrumbranch.com');

					// ** TODO: REMOVE THIS TEST DATA LATER **
					// $result = call_url('1','');

					$result = call_url(
						'mc.spectrumbranch.com:8123/apoc_minecraft/whitelist.json',
						array("apikey" => $config['apikey'])
					);
					$whitelist = isset($result) && isset($result->whitelist) ? $result->whitelist : (object) array();

					$showkdr = true;
				?>

				<?php include('inc/player-list.php') ?>

				<div class="whitelist-note">
					NOTE: Kill/death count feature is still in development and may not be 100% accurate.
				</div>

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime($fn)?>"></script>

		<script type="text/javascript">
			$('title').html($('title').html()+" (<?=$num_current_players.'/'.$num_max_players?>)");

			$.ajax({
				url : "get-kdr.php",
				type: "POST",
				success: function(data) {
					data = JSON.parse(data);
					$('.player').each(function() {
						var name = $(this).find('.player-name').html().trim();
						if (data[name] !== undefined) {
							if (data[name].kill !== undefined)
								$(this).find('.player-kdr .kills').html(data[name].kill);
							if (data[name].death !== undefined)
								$(this).find('.player-kdr .deaths').html(data[name].death);
							$(this).find('.player-kdr').addClass('visible');
						}
					});
				}
			});

		</script>

	</body>
</html>