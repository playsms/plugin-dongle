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
defined('_SECURE_') or die('Forbidden');

// hook_sendsms
// called by main sms sender
// return true for success delivery
// $smsc : smsc
// $sms_sender : sender mobile number
// $sms_footer : sender sms footer or sms sender ID
// $sms_to : destination sms number
// $sms_msg : sms message tobe delivered
// $gpid : group phonebook id (optional)
// $uid : sender User ID
// $smslog_id : sms ID
function dongle_hook_sendsms($smsc, $sms_sender, $sms_footer, $sms_to, $sms_msg, $uid = '', $gpid = 0, $smslog_id = 0, $sms_type = 'text', $unicode = 0)
{
	global $plugin_config;

	_log("enter smsc:" . $smsc . " smslog_id:" . $smslog_id . " uid:" . $uid . " to:" . $sms_to, 3, "dongle_hook_sendsms");

	// override plugin gateway configuration by smsc configuration
	$plugin_config = gateway_apply_smsc_config($smsc, $plugin_config);

	$sms_footer = stripslashes($sms_footer);
	$sms_msg = stripslashes($sms_msg);

	if ($sms_footer) {
		$sms_msg = $sms_msg . $sms_footer;
	}

	if (is_executable(_ASTERISK_) && $smsc && $sms_to && $sms_msg) {
		$command = _ASTERISK_ . sprintf(' -rx "dongle sms %s %s %s"', $smsc, $sms_to, $sms_msg);
		@shell_exec($command);

		$p_status = 1;
	} else {
		$p_status = 2;
	}

	dlr($smslog_id, $uid, $p_status);

	_log("exit smsc:" . $smsc . " smslog_id:" . $smslog_id . " p_status:" . $p_status, 3, "dongle_hook_sendsms");

	return true;
}
