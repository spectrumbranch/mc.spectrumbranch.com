<?php
	function check_transparent($im) {
		$width = imagesx($im); // Get the width of the image
		$height = imagesy($im); // Get the height of the image
		// We run the image pixel by pixel and as soon as we find a transparent pixel we stop and return true.
		for($i = 0; $i < $width; $i++) {
			for($j = 0; $j < $height; $j++) {
				$rgba = imagecolorat($im, $i, $j);
				if(($rgba & 0x7F000000) >> 24) {
					return true;
				}
			}
		}
		// If we dont find any pixel the function will return false.
		return false;
	}

	function call_url($url, $data) {

		// ** TODO: REMOVE THIS TEST DATA LATER **
		if ($url == '1')
			return json_decode('{"success":true,"whitelist":[{"uuid":"a9e9aef7-e601-4403-9fe3-0fbc0726696c","name":"Cerena_Leigh"},{"uuid":"ce8b5d7a-45df-4359-ade5-70a81056c01c","name":"MR_GRAY_333"},{"uuid":"cc4802fa-7f55-407f-9842-5fdf4c34167e","name":"xxphoebusxx"},{"uuid":"d2ba804c-f0b4-49fe-8169-18d5e0255463","name":"Derpington_III"},{"uuid":"245ea895-28e5-41b0-a57b-c094a9a80630","name":"black_cat86"},{"uuid":"bb6160f2-967a-4830-9f41-c933370f6292","name":"fiddlecastro"},{"uuid":"e429bf1d-5311-4437-9cfa-0aa1ee16c172","name":"tonsoffun1234"},{"uuid":"8d56cd8e-9158-40fc-963e-c8a1e64a5761","name":"Ad0lf_Hipster"},{"uuid":"5bbc3d4e-b2a0-4a23-b5cd-681f9d4fcf2c","name":"KingSigma"},{"uuid":"8db513d1-903d-4420-a238-984476284de1","name":"Kotroskin"},{"uuid":"f32d1dad-c9d4-455d-8e8e-0439f03c2ebd","name":"MGZero"},{"uuid":"1db085a2-f373-4ecf-a85a-d0b9326a2b30","name":"Jesseleventhal"},{"uuid":"9f87456a-6e6c-4eec-adb7-7b7cc5e44065","name":"Harrydg"},{"uuid":"6b55d632-98bc-40c9-9b82-2e80e67d725f","name":"wirefist"},{"uuid":"ccf2e7bd-2828-4af3-9e31-f364e3b4eaa1","name":"catnips"},{"uuid":"4b44d680-aba8-42cc-a50c-47e012d21199","name":"TenchinMogutso"},{"uuid":"33d4281b-ed02-420d-b4ef-c8e44338b2ee","name":"Justihnhd"},{"uuid":"308db751-7e64-4c3f-8d0a-9834b060ac74","name":"tatltael47"},{"uuid":"594cfdbd-755e-4bc9-a5b3-e11d37c1c321","name":"acergriseum919"},{"uuid":"0a1a8576-f12b-46d3-bf69-36e559ddcb51","name":"Starforsaken101"},{"uuid":"b86eba46-37d8-4cff-9cc8-9c94f0a7f5b9","name":"Qozle"},{"uuid":"b32e4c2b-6c55-4a14-b7bf-986e54665f26","name":"kylexstonexfist"},{"uuid":"13c503cb-7a2b-461b-aa05-a28eeb28c8cf","name":"Dreihund"},{"uuid":"5240e968-2d29-4172-95bf-1c3283b0d2cd","name":"Biocyberunit"},{"uuid":"2f7be629-f3e9-4f25-b8be-964e29a5f347","name":"spectrumbranch"},{"uuid":"bab8b97a-a155-4573-9a79-4f1fda1e0181","name":"gm112"},{"uuid":"53eb33e0-a772-4898-9c47-d5782b7e81cf","name":"CarlShayman"},{"uuid":"dc0049fd-8621-4a14-b9ea-39a3ba1f8d83","name":"Dr_Curry"},{"uuid":"84276239-8493-43f5-ac14-dd524ef0c70c","name":"Just_Kino"}]}');

		$data_string = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$curl_result = curl_exec($ch);
		return json_decode($curl_result);
	}

	function filemtime_remote($url, $format = null) {
		date_default_timezone_set('America/New_York');

		$result = false;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FILETIME, true);

		$result = curl_exec($curl);
		if ($result === false) {
			die (curl_error($curl));
		}
		$timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
		if ($timestamp != -1) { //otherwise unknown
			if (is_null($format))
				$result = date("Y-m-d H:i", $timestamp) . ' ET'; //etc
			else
				$result = date($format, $timestamp);
		}

		return $result;
	}
?>