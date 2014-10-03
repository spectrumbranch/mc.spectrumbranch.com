<?php
	include('config.php');
	include('utils.php');

	$result = call_url(
		'mc.spectrumbranch.com:8123/apoc_minecraft/parsedLogs.json',
		array(
			"apikey" => $config['apikey']
		)
	);

	$display = array();
	foreach ($result as $logfile) {
		foreach ($logfile as $key => $type) {
			if (is_array($type)) {
				foreach ($type as $entry) {

					switch ($key) {
						case 'kill':
							if (!isset($display[$entry->target]))
								$display[$entry->target] = array();

							$entry->actor = preg_replace('/ using \[.+\]/', '', $entry->actor);

							if (!isset($display[$entry->actor]['kill']))
								$display[$entry->actor]['kill'] = 0;
							if (!isset($display[$entry->target]['death']))
								$display[$entry->target]['death'] = 0;

							$display[$entry->actor]['kill'] += 1;
							$display[$entry->target]['death'] += 1;

							break;
						case 'death':
							if (!isset($display[$entry->target]))
								$display[$entry->target] = array();
							if (!isset($display[$entry->target][$key]))
								$display[$entry->target][$key] = 1;
							else
								$display[$entry->target][$key] += 1;
							break;
						default:
							break;
					} // switch key
					
				} // foreach entry
			} // if type is array
		} // foreach key=>type
	} // foreach logfile

	ksort($display);
	echo json_encode($display);
?>