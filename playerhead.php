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
	$size = isset($_GET['size']) ? $_GET['size'] : 64;
	$hat = isset($_GET['hat']) ? $_GET['hat'] != false : true; // default to true

	// Grab original image
	$image = imagecreatefrompng($texture);

	// Detect if the skin does not have any transparency (old style)
	$oldskin = !check_transparent($image);

	//get old width and height
	$old_width = 64;
	$old_height = 64;

	// Head location
	$part_x = 8; // head
	$part_x2 = 40; // mask
	$part_y = 8;
	$part_width = 8;
	$part_height = 8;

	$ratio = $size / $part_width;

	$new_width = $part_width * $ratio;
	$new_height = $part_height * $ratio;

	// Create a "canvas" for the resized image to fit into
	$resized = imagecreatetruecolor($new_width, $new_height);
	imagealphablending($resized, false);
	//Create alpha channel for transparent layer
	$col=imagecolorallocatealpha($resized, 255, 255, 255, 127);
	//Create overlapping 100x50 transparent layer
	imagefilledrectangle($resized, 0, 0, $new_width, $new_height, $col);
	//Continue to keep layers transparent
	imagealphablending($resized, true);
	//Keep trnsparent when saving
	imagesavealpha($resized, true);

	// Resize original image and combine into resized canvas. Head first, then mask.
	imagecopyresized($resized, $image, 0, 0, $part_x, $part_y, $old_width * $ratio, $old_height * $ratio, $old_width, $old_height);
	if ($hat && !$oldskin)
		imagecopyresized($resized, $image, 0, 0, $part_x2, $part_y, $old_width * $ratio, $old_height * $ratio, $old_width, $old_height);

	// Output resized image to page
	header('Content-type: image/png');
	imagepng($resized);

	// Clean up
	ImageDestroy($image);
	ImageDestroy($resized);
?>