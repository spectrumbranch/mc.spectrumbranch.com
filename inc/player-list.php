<?php /*

	Expects the following:

	$response;	// Result from serverstatus.php
	$whitelist	// Result from reading from a whitelist.json
	$showkdr	// boolean variable for showing kills and deaths

*/ 
	
	$online_player_ids = array(); // For removing online players from the offline results

	$online_players = array();
	$offline_players = array();

	$num_current_players = 0;
	$num_max_players = 0;

	if(!$response) {
		echo "<span style=\"color: red; font-weight: bold;\">The Server is offline!</span>";
	} else {
		foreach ($response['playerlist'] as $player) {
			$player->id = str_replace('-','',$player->id); //Get rid of hyphens
			array_push($online_player_ids, $player->id);
			array_push($online_players, (object) array(
					'uuid' => $player->id,
					'name' => $player->name
				)
			);
		}

		sort($online_players);

		$num_current_players = $response['players'];
		$num_max_players = $response['maxplayers'];
	}

	foreach ($whitelist as $player) {
		$player->uuid = str_replace('-','',$player->uuid); //Get rid of hyphens
		if (!in_array($player->uuid, $online_player_ids))
			array_push($offline_players, $player);
	}
?>

<div class="players online-players">
	<h3>Online Players (<?=$num_current_players.'/'.$num_max_players?>)</h3>

	<?php if (count($online_players) > 0) : ?>
		<?php foreach ($online_players as $player) : ?>
			<div class="player" data-uuid="<?=$player->uuid?>" data-size="100">
				<div class="player-head-container">
					<img class="loader" src="img/loader.gif" alt=""/>
				</div>
				<div class="player-name">
					<?=$player->name?>
				</div>
				<?php if ($showkdr) : ?>
				<div class="player-kdr">
					<span class="kills">0</span> kills<br/>
					<span class="deaths">0</span> deaths
				</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	<?php else : ?>
		<p>
			There are no players currently online.
		</p>
	<?php endif ?>

</div><!-- players -->

<div class="players offline-players">
	<h4>Offline Players (<?= count($offline_players).'/'.(count($online_players)+count($offline_players))?>)</h4>

	<?php foreach ($offline_players as $player) : ?><div class="player" data-uuid="<?=$player->uuid?>" data-size="50">
		<div class="player-head-container">
			<img class="loader" src="img/loader.gif" alt=""/>
		</div>
		<div class="player-info">
			<div class="player-name">
				<?=$player->name?>
			</div>
			<?php if ($showkdr) : ?>
			<div class="player-kdr">
				<span class="kills">0</span> kills<br/>
				<span class="deaths">0</span> deaths
			</div>
			<?php endif ?>
		</div>
	</div><?php endforeach ?>

</div><!-- players -->