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
/*
// create GD image resource from source image file
$src = imagecreatefrompng('http://textures.minecraft.net/texture/13e81b9e19ab1ef17a90c0aa4e1085fc13cd47ced5a7a1a492803b3561e4a15b');


$thumb = new Imagick('http://textures.minecraft.net/texture/13e81b9e19ab1ef17a90c0aa4e1085fc13cd47ced5a7a1a492803b3561e4a15b');
$thumb->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 0);
$thumb->writeImage('img/mythumb.png');
$thumb->destroy(); 
*/
?>


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
						$uuid = str_replace('-','',$player->uuid);

						// Curl stuff
						$data = array(
							"apikey" => $config['apikey'],
							"uuid" => $uuid
						);
						$data_string = json_encode($data);
						$ch = curl_init('mc.spectrumbranch.com:8123/apoc_minecraft/skin.json');
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen($data_string))
						);
						$curl_result2 = curl_exec($ch);
						$result2 = json_decode($curl_result2);

						$texture = isset($result2->skin) ? urlencode($result2->skin) : 'none';
					?>

					<div class="player">
						<div class="player-head">
							<!-- <img src="https://minotar.net/helm/<?=$player->name?>/100.png" alt=""/> -->
							<img src="playerhead.php?texture=<?=$texture?>&size=100" alt=""/>
						</div>
						<div class="player-name">
							<?=$player->name?><br/>
							<!-- <span style="font-size:0.7em"><?=$uuid?></span> -->
						</div>
					</div>

					<?php } ?>

					<?php /* foreach ($result->whitelist as $player) {
					?>

					<div class="player">
						<div class="player-head">
							<img src="https://minotar.net/helm/<?=$player->name?>/100.png" alt=""/>
						</div>
						<div class="player-name">
							<?=$player->uuid?>
						</div>
					</div>

					<?php } */ ?>


	<?php /*
	
foreach ($uuids as $uuid) {
	$imgdata = file_get_contents('https://sessionserver.mojang.com/session/minecraft/profile/' . $uuid);
	$img = json_decode(base64_decode(json_decode($imgdata,true)['properties'][0]['value']),true)['textures']['SKIN']['url'];

	// Add $img to some kind of cache-y list thing that I can call (if possible just add it to the whitelist API results :) )
}


			$imgdata = file_get_contents('https://sessionserver.mojang.com/session/minecraft/profile/9f87456a6e6c4eecadb77b7cc5e44065');
			$img = json_decode(base64_decode(json_decode($imgdata,true)['properties'][0]['value']),true)['textures']['SKIN']['url'];


			?>
			<div class="row">
				<div class="col-md-12">
					<div class="well">
						<img style="image-rendering: -webkit-optimize-contrast;" src="<?php echo $img; ?>">
						<h1>Harrydg</h1><br>
						<h5>9f87456a6e6c4eecadb77b7cc5e44065</h5>
						<pre><?php
							print_r(json_decode($imgdata,true));
						?></pre>
						<pre><?php
							print_r(json_decode(base64_decode(json_decode($imgdata,true)['properties'][0]['value']),true));
						?></pre>
						<pre><?php
							print_r($value);
						?></pre>
					</div>
				</div>
			</div>
			<?php */
	?>








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