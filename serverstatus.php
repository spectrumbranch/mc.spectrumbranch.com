<?php

	/**
	 * Minecraft Server Status Query
	 * @author Julian Spravil <julian.spr@t-online.de> https://github.com/FunnyItsElmo
	 * @license Free to use but dont remove the author, license and copyright
	 * @copyright © 2013 Julian Spravil
	 */
	class MinecraftServerStatus {

		private $timeout;

		public function __construct($timeout = 2) {
			$this->timeout = $timeout;
		}

		public function getStatus($host = '127.0.0.1', $port = 25565) {

			if (substr_count($host , '.') != 4) $host = gethostbyname($host);

			$serverdata = array();
			$serverdata['hostname'] = $host;
			$serverdata['version'] = false;
			$serverdata['protocol'] = false;
			$serverdata['players'] = false;
			$serverdata['maxplayers'] = false;
			$serverdata['motd'] = false;
			$serverdata['motd_raw'] = false;
			$serverdata['favicon'] = false;
			$serverdata['ping'] = false;
			$serverdata['playerlist'] = false;

			$socket = $this->connect($host, $port);

			if(!$socket) {
				return false;
			}

			$start = microtime(true);

			$handshake = pack('cccca*', hexdec(strlen($host)), 0, 0x04, strlen($host), $host).pack('nc', $port, 0x01);

			socket_send($socket, $handshake, strlen($handshake), 0); //give the server a high five
			socket_send($socket, "\x01\x00", 2, 0);
			socket_read( $socket, 1 );

			$ping = round((microtime(true)-$start)*1000); //calculate the high five duration

			$packetlength = $this->read_packet_length($socket);

			// if($packetlength < 10) {
			// 	return false;
			// }

			socket_read($socket, 1);

			$packetlength = $this->read_packet_length($socket);

			$data = socket_read($socket, $packetlength, PHP_NORMAL_READ);

			if(!$data) {
				return false;
			}

			// ** TODO: REMOVE THIS TEST DATA LATER **
			// $data = '{"description":"No rules apocalypse mode, eat thai food","players":{"max":24,"online":1,"sample":[{"id":"f32d1dad-c9d4-455d-8e8e-0439f03c2ebd","name":"MGZero"}]},"version":{"name":"1.8","protocol":47}}';

			$data = json_decode($data);

			$serverdata['version'] = $data->version->name;
			$serverdata['protocol'] = $data->version->protocol;
			$serverdata['players'] = $data->players->online;
			$serverdata['maxplayers'] = $data->players->max;

			$motd = $data->description;
			$motd = preg_replace("/(§.)/", "",$motd);
			$motd = preg_replace("/[^[:alnum:][:punct:] ]/", "", $motd);

			$serverdata['motd'] = $motd;
			$serverdata['motd_raw'] = $data->description;
			$serverdata['favicon'] = isset($data->favicon) ? $data->favicon : false;
			$serverdata['ping'] = '$ping';
			$serverdata['playerlist'] = isset($data->players->sample) ? $data->players->sample : array();

			$this->disconnect($socket);

			return $serverdata;

		}

		private function connect($host, $port) {
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($socket, $host, $port); 
			return $socket;
		}

		private function disconnect($socket) {
			if($socket != null) {
				socket_close($socket);
			}
		}

		private function read_packet_length($socket) {
			$a = 0;
			$b = 0;
			while(true) {
				$c = socket_read($socket, 1);
				if(!$c) {
					return 0;
				}
				$c = Ord($c);
				$a |= ($c & 0x7F) << $b++ * 7;
				if( $b > 5 ) {
					return false;
				}
				if(($c & 0x80) != 128) {
					break;
				}
			}
			return $a;
		}

	}