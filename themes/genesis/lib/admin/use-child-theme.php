<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Admin
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

add_action( 'admin_notices', 'genesis_use_child_theme_notice' );

/**
 * Display Warning that Genesis should always be used with a child theme.
 *
 * @since 2.3.0
 */
function genesis_use_child_theme_notice() {

	if ( is_child_theme() ) {
		return;
	}

	$allowed_html = [
		'a' => [
			'href' => [],
		],
	];

	include GENESIS_VIEWS_DIR . '/misc/use-child-theme-notice.php';

}
