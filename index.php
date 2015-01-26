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
		<title>FTB Server</title>
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<meta property="og:title" content="FTB Server" />
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
				<br/>
				Come join us in Feed the Beast! <a href="http://harryscheiner.com/mc/ftb/" target="_blank" style="color:#337abd;">Click here for info</a>!
				<?php
					include_once 'serverstatus.php';
					$status = new MinecraftServerStatus();
					$response = $status->getStatus('mc.spectrumbranch.com');

					// ** TODO: REMOVE THIS TEST DATA LATER **
					// $result = call_url('1','');

					$result = call_url(
						'mc.spectrumbranch.com:8123/' . $config['world'] . '/whitelist.json',
						array("apikey" => $config['apikey'])
					);
					$whitelist = isset($result) && isset($result->whitelist) ? $result->whitelist : (object) array();

					$showkdr = false;
				?>

				<?php include('inc/player-list.php') ?>

				<div class="whitelist-note" style="display: none;">
					NOTE: Kill count does not include kills on suiciding players.<br/>
					NOTE 2: Hover over a players head to see when they were last online!
				</div>

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime($fn)?>"></script>

		<script type="text/javascript">
			$('title').html($('title').html()+" (<?=$num_current_players.'/'.$num_max_players?>)");

			$.ajax({
				url : "get-player-info.php",
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
							if (data[name].last_seen !== undefined)
								$(this).attr('data-lastseen',data[name].last_seen).find('.player-head-container').attr('title', 'Last seen: ' + data[name].last_seen);
							$(this).find('.player-kdr').addClass('visible');
						} else {
							$(this).attr('data-lastseen','1970-01-01 00:00:00');
							$(this).find('.player-head-container').attr('title', 'Last seen: Never');
						}
					});

					$(".offline-players .player").sort(sort_players).appendTo('.offline-players');
					$(".online-players .player-head-container").attr('title', 'Last seen: Online now!');
					setTimeout(function() {
						$('.player-info').addClass('visible');
					}, 100);
				}
			});

			function sort_players(a, b){
				return ($(b).data('lastseen')) > ($(a).data('lastseen')) ? 1 : -1;    
			}
		</script>

	</body>
</html>