<?php
	include('config.php');
	include('utils.php');
	date_default_timezone_set('America/New_York');

	// Get params
	$debug = isset($_GET['d']) ? true : false;

	$result = call_url(
		'mc.spectrumbranch.com:8123/' . $config['world'] . '/parsedLogs.json',
		array(
			"apikey" => $config['apikey']
		)
	);

	if ($debug) {
		echo "<pre>";
		echo json_encode($result);
		die();
	}

	$display = array();
	foreach ($result as $logfile) {
		foreach ($logfile as $key => $type) {
			if (is_array($type)) {
				foreach ($type as $entry) {

					switch ($key) {
						// case 'login':
						// 	if (!isset($display[$entry->user]))
						// 		$display[$entry->user] = array();
						// 	if (!isset($display[$entry->user][$key]))
						// 		$display[$entry->user][$key] = array(floor($entry->x).', '.floor($entry->z));
						// 	else
						// 		$display[$entry->user][$key][] = floor($entry->x).', '.floor($entry->z);
						// 	break;
						case 'logout':

							$string_timestamp = $logfile->date.' '.$entry->time;

							// $timestamp = date_create_from_format('Y-m-d H:i:s', $string_timestamp));

							if (!isset($display[$entry->user]))
								$display[$entry->user] = array();
							// if (!isset($display[$entry->user][$key]))
							// 	$display[$entry->user][$key] = array($string_timestamp);
							// else
							// 	$display[$entry->user][$key][] = $string_timestamp;
							$display[$entry->user]['last_seen'] = $string_timestamp;
							break;
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
					}
				}
			}
		}
	}
	ksort($display);

	echo "<pre>";
	echo json_encode($display);

?>