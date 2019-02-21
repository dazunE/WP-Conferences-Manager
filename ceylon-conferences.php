<?php

/**
 * Plugin Name: WP Conferences Manager
 * Version: 1.0
 * Plugin URI: https://www.ceylon.solutions/conferences-manager
 * Description: Conferences Managements Module
 * Author: ceylondevs
 * Text Domain:
 * License: GPL v3
 */

define( 'CEYLON_CONF_TEXT_DOMAIN', 'ceylon-conf' );
define( 'CEYLON_CONF_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'CEYLON_CONF_PLUGIN_URL' , plugin_dir_url( __FILE__ ) );
define( 'CEYLON_CONF_VERSION' , '1.0' );

require_once CEYLON_CONF_PLUGIN_PATH . '/vendor/autoload.php';

require_once CEYLON_CONF_PLUGIN_PATH . '/includes/bootstrap.php';

require_once CEYLON_CONF_PLUGIN_PATH . '/includes/speakers/speakers.php';

require_once CEYLON_CONF_PLUGIN_PATH . '/includes/sessions/sessions.php';