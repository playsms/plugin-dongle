<?php

/**
 * This file is part of playSMS.
 *
 * playSMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * playSMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with playSMS. If not, see <http://www.gnu.org/licenses/>.
 */
error_reporting(0);

if (!(php_sapi_name() === 'cli' || defined('STDIN'))) {
	echo 'Run only from CLI' . PHP_EOL;

	exit();
}

$playsms_path = isset($argv[1]) && $argv[1] && is_dir($argv[1]) ? $argv[1] : '';

if (!$called_from_hook_call && $playsms_path) {
	if (chdir($playsms_path)) {
		// ignore CSRF
		$core_config['init']['ignore_csrf'] = true;

		if (is_file('init.php')) {
			include "init.php";

			$fn = isset($core_config['apps_path']['libs']) && $core_config['apps_path']['libs'] ? $core_config['apps_path']['libs'] . '/function.php' : '';
			if ($fn && is_file($fn)) {
				include $fn;
			} else {
				echo 'playSMS libs not found' . PHP_EOL;

				exit();
			}
		} else {
			echo 'playSMS init not found' . PHP_EOL;

			exit();
		}

		if (!chdir("plugin/gateway/dongle/")) {
			echo 'Plugin dongle not found' . PHP_EOL;

			exit();
		}
	} else {
		echo 'playSMS installation not found' . PHP_EOL;

		exit();
	}
}

$smsc = $argv[2] ?? '';
$sms_datetime = $argv[3] ?? core_display_datetime(core_get_datetime());
$sms_sender = $argv[4] ?? '';
$sms_msg = isset($argv[5]) && $argv[5] ? base64_decode($argv[5]) : '';

if ($smsc && $sms_sender && $sms_msg) {
	_log("incoming from:" . $sms_sender . " m:[" . $sms_msg . "] smsc:" . $smsc, 2, "dongle callback");

	$sms_sender = addslashes($sms_sender);
	$sms_receiver = addslashes($sms_receiver);
	$sms_msg = addslashes($sms_msg);
	$smsc = addslashes($smsc);

	recvsms($sms_datetime, $sms_sender, $sms_msg, $sms_receiver, $smsc);
}
