<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

namespace StudioPress\Genesis\Upgrade;

/**
 * Upgrade class. Called when `db_version` Genesis setting is below 112.
 *
 * @since 3.1.0
 */
class Upgrade_DB_112 implements Upgrade_DB_Interface {
	/**
	 * Upgrade method.
	 *
	 * @since 1.1.2
	 * @since 3.1.0 Moved to class method.
	 */
	public function upgrade() {
		genesis_update_settings(
			[
				'header_right'            => genesis_get_option( 'header_full' ) ? 0 : 1,
				'nav_superfish'           => 1,
				'subnav_superfish'        => 1,
				'nav_extras_enable'       => genesis_get_option( 'nav_right' ) ? 1 : 0,
				'nav_extras'              => genesis_get_option( 'nav_right' ),
				'nav_extras_twitter_id'   => genesis_get_option( 'twitter_id' ),
				'nav_extras_twitter_text' => genesis_get_option( 'nav_twitter_text' ),
			]
		);
	}
}