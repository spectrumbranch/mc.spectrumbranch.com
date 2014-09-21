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

		<div class="wrapper">
			<div class="container">

				<h1>Apocalypse Server</h1>

				<?php
					$current_players = array();

					include_once 'serverstatus.php';
					$status = new MinecraftServerStatus();
					$response = $status->getStatus('mc.spectrumbranch.com');

					if(!$response) {
						echo "<span style=\"color: red; font-weight: bold;\">The Server is offline!</span>";
					} else {
						//echo 'The Server ' . $response['hostname'] . ' is running on ' . $response['version'] . ' and is online.<br/>';
						//echo 'There are currently ' . $response['players'] . '/' . $response['maxplayers'] . ' players online.<br/>';

						foreach ($response['playerlist'] as $player) {
							array_push($current_players, str_replace('-','',$player->id));
						}
					}

					// ** TODO: REMOVE THIS TEST DATA LATER **
					// $result = call_url('1','');

					$result = call_url(
						'mc.spectrumbranch.com:8123/apoc_minecraft/whitelist.json',
						array("apikey" => $config['apikey'])
					);

					$online_players = array();
					$offline_players = array();
					if (isset($result) && isset($result->whitelist)) {
						foreach ($result->whitelist as $player) {
							$player->uuid = str_replace('-','',$player->uuid); //Get rid of hyphens
							if (in_array($player->uuid, $current_players))
								array_push($online_players, $player);
							else
								array_push($offline_players, $player);
						}
					}
				?>

				<div class="players online-players">
					<h3>Online Players (<?=$response['players']?>/<?=$response['maxplayers']?>)</h3>

					<?php if (count($online_players) > 0) : ?>
						<?php foreach ($online_players as $player) : ?>
							<div class="player" data-uuid="<?=$player->uuid?>" data-size="100">
								<div class="player-head-container">
									<img class="loader" src="img/loader.gif" alt=""/>
								</div>
								<div class="player-name">
									<?=$player->name?><br/>
								</div>
							</div>
						<?php endforeach ?>
					<?php else : ?>
						<p>
							There are no players currently online.
						</p>
					<?php endif ?>
				
				</div><!-- players -->

				<div class="players offline-players">
					<h4>Offline Players</h4>

					<?php foreach ($offline_players as $player) : ?>
						<div class="player" data-uuid="<?=$player->uuid?>" data-size="50">
							<div class="player-head-container">
								<img class="loader" src="img/loader.gif" alt=""/>
							</div>
							<div class="player-name">
								<?=$player->name?><br/>
							</div>
						</div>
					<?php endforeach ?>

				</div><!-- players -->

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime($fn)?>"></script>

		<script type="text/javascript">

		function lazyLoadImage(src, imgclass, successCallback) {
			var img = $("<img />").attr('src', src).addClass(imgclass)
				.load(function() {
					if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
						console.log('Broken image');
					} else {
						successCallback(img);
					}
				});
		}

		$(document).ready(function() {

			$('.player').each(function() {
				var player = $(this);

				$.ajax({
					url: "get-texture.php",
					type: 'GET',
					data: {uuid: player.data("uuid")},
					dataType: 'json',
					success: function(data) {
						var player_head_container = player.find('.player-head-container');
						var encoded_texture = encodeURIComponent(data.texture);
						lazyLoadImage(
							'playerskin.php?texture=' + encoded_texture + '&size=' + player.data("size"),
							'player-head',
							function(img){
								player_head_container.find('.loader').remove();
								player_head_container.prepend(img);
							}
						);

						// No hats for old skins
						if (!data.oldskin) {
							lazyLoadImage(
								'playerskin.php?texture=' + encoded_texture + '&size=' + player.data("size"),
								'player-hat',
								function(img){
									player_head_container.find('.loader').remove();
									player_head_container.append(img);
								}
							);
						}

					}
				});

			});
		});
		</script>

	</body>
</html>