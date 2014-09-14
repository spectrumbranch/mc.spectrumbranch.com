<?php
	include('utils.php');

	// Get params
	$texture = isset($_GET['texture']) ? $_GET['texture'] : $_GET['texture'] ;
	if ($texture == 'none')
		$texture = 'img/char.png';
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

	$new_width = $old_width * $ratio;
	$new_height = $old_height * $ratio;

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

	//imagecopyresized ( resource $dst_image , resource $src_image ,
	// int $dst_x , int $dst_y , int $src_x , int $src_y ,
	// int $dst_w , int $dst_h , int $src_w , int $src_h )

	if (!$hat || !$oldskin)
		imagecopyresized($resized, $image, 0, 0, 0, 0, $old_width * $ratio, $old_height * $ratio, $old_width, $old_height);

	// Output resized image to page
	header('Content-type: image/png');
	imagepng($resized);

	// Clean up
	ImageDestroy($image);
	ImageDestroy($resized);
?>