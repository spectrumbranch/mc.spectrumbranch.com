<?php
	include('config.php');
	include('utils.php');

	// Get params
	$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : $_GET['uuid'] ;

	$result = call_url(
		'mc.spectrumbranch.com:8123/apoc_minecraft/skin.json',
		array(
			"apikey" => $config['apikey'],
			"uuid" => $uuid
		)
	);

	$texture = isset($result->skin) ? $result->skin : 'img/char.png';

	// Grab original image
	$image = imagecreatefrompng($texture);

	// Detect if the skin does not have any transparency (old style)
	$oldskin = !check_transparent($image);

	echo json_encode(array(
		'texture' => $texture,
		'oldskin' => $oldskin
	));
?>