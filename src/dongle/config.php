<?php
defined('_SECURE_') or die('Forbidden');

// get dongle config from registry
$data = registry_search(1, 'gateway', 'dongle');

$plugin_config['dongle'] = $data['gateway']['dongle'];
$plugin_config['dongle']['name'] = 'dongle';

// smsc configuration
$plugin_config['dongle']['_smsc_config_'] = [];

// location of Asterisk binary
define('_ASTERISK_', '/usr/sbin/asterisk');
