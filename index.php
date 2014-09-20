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

				<?php
					$current_players = [];

					include_once 'serverstatus.php';
					$status = new MinecraftServerStatus();
					$response = $status->getStatus('mc.spectrumbranch.com');
					if(!$response) {
						echo"The Server is offline!";
					} else {
						echo 'The Server ' . $response['hostname'] . ' is running on ' . $response['version'] . ' and is online.<br/>';
						echo 'There are currently ' . $response['players'] . '/' . $response['maxplayers'] . ' players online.<br/>';

						foreach ($response['playerlist'] as $player) {
							$current_players[] = $player->id;
						}
					}

				?>


				<div class="players">

					<?php 
					$result = call_url(
						'mc.spectrumbranch.com:8123/apoc_minecraft/whitelist.json',
						["apikey" => $config['apikey']]
					);

					$online_players = [];
					$offline_players = [];
					foreach ($result->whitelist as $player) {
						if (in_array($player->uuid, $current_players))
							$online_players[] = $player;
						else
							$offline_players[] = $player;
					}

					function displayPlayer($player, $apikey) {
						$uuid = str_replace('-','',$player->uuid);

						$result2 = call_url(
							'mc.spectrumbranch.com:8123/apoc_minecraft/skin.json',
							[
								"apikey" => $apikey,
								"uuid" => $uuid
							]
						);

						$texture = isset($result2->skin) ? urlencode($result2->skin) : 'none';
					?>

					<div class="player">
						<div class="player-head-container">
							<img class="player-head" src="playerskin.php?texture=<?=$texture?>&size=100&hat=0" alt=""/>
							<img class="player-hat" src="playerskin.php?texture=<?=$texture?>&size=100&hat=1" alt=""/>
						</div>
						<div class="player-name">
							<?=$player->name?><br/>
						</div>
					</div>

					<?php } 

					// Loop through each ONLINE player
					foreach ($online_players as $player) {
						displayPlayer($player, $config['apikey']);
					} ?>

					<hr/>

					<?php 
					// Loop through each OFFLINE player
					foreach ($offline_players as $player) {
						displayPlayer($player, $config['apikey']);
					} ?>

				</div>

			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="<?=($fn='js/main.js').'?v='.filemtime($fn)?>"></script>

		<script type="text/javascript">

		// $('.player-head-container').click(function() {
		// 	$(this).find('.player-hat').fadeToggle(200);
		// });

		</script>

	</body>
</html>