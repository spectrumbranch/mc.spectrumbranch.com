<?php
	include('config.php');
	$texture = isset($_GET['texture']) ? $_GET['texture'] : $_GET['texture'] ;
	if ($texture == 'none')
		$texture = 'img/char.png';
	$size = isset($_GET['size']) ? $_GET['size'] : 64;

	// Get params
	header('Content-type: image/png');

	// Grab original image
	$image = imagecreatefrompng($texture);

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
	// imagecolortransparent($resized, imagecolorallocate($resized, 0, 0, 0));

	// Resize original image and combine into resized canvas. Head first, then mask.
	imagecopyresized($resized, $image, 0, 0, $part_x, $part_y, $old_width * $ratio, $old_height * $ratio, $old_width, $old_height);
	imagecopyresized($resized, $image, 0, 0, $part_x2, $part_y, $old_width * $ratio, $old_height * $ratio, $old_width, $old_height);

	// Output resized image to page
	imagepng($resized);

	// Clean up
	ImageDestroy($image);
	ImageDestroy($resized);
?>