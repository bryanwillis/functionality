<?php


<?php
/*
Plugin Name:  Styles: TwentyFourteen
Plugin URI:   http://wpdefault.com/styles-twentyfourteen-plugin/
Description:  Adds fonts and colors Customize options to the <a href="http://wordpress.org/themes/twentyfourteen" target="_blank">Twenty Fourteen default theme</a> using the <a href="http://wordpress.org/plugins/styles/" target="_blank">Styles plugin</a>.
Version:      1.0.2
Author:       Zulfikar Nore
Author URI:   http://www.wpstrapcode.com

Require:      Styles 1.1.7
Styles Class: Styles_Child_Theme
*/

/**
 * Original plugin is Styles: TwentyThirteen
 * Copyright (c) 2013 Brainstorm Media. All rights reserved.
 *
 * Styles: TwentyFourteen in the derivative format Copyright (c) 2013 WP Strap Code/ZGani AKA Zulfikar Nore
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

if ( !class_exists( 'Styles_Child_Notices' ) ) {
    include dirname( __FILE__ ) . '/classes/styles-child-notices/styles-child-notices.php';
}



/*
Plugin Name:  Styles: TwentyFourteen
Plugin URI:   http://wpdefault.com/styles-twentyfourteen-plugin/
Description:  Adds fonts and colors Customize options to the <a href="http://wordpress.org/themes/twentyfourteen" target="_blank">Twenty Fourteen default theme</a> using the <a href="http://wordpress.org/plugins/styles/" target="_blank">Styles plugin</a>.
Version:      1.0.0
Author:       Zulfikar Nore
Author URI:   http://www.wpstrapcode.com

Require:      Styles 1.1.7
Styles Class: Styles_Child_Theme
*/








function admin_notice_theme( $output ) {
	global $user_identity, $user_url;
	get_currentuserinfo();
		
	$my_theme  = wp_get_theme();
	$themename = $my_theme->get( 'Name' );
	$sitename  = get_bloginfo( 'name' );
	$pluginuri = home_url();
	$version   = date( 'Y.m.d' );
	
	$output = '<textarea>' . "\r\n";
	$output .= '/*' . "\r\n";
	$output .= 'Plugin Name:  Styles: ' . $themename . "\r\n";
	$output .= 'Plugin URI:   ' . $pluginuri . "\r\n";
	$output .= 'Description:  A theme specific plugin for ' . $sitename . ', that adds theme customizer controls using the <a href="http://wordpress.org/plugins/styles/" target="_blank">Styles plugin</a>.' . "\r\n";
        $output .= 'Version:      ' . $version . "\r\n";
        $output .= 'Author:       ' . $user_identity . "\r\n";
        $output .= 'Author URI:   ' . $user_url . "\r\n";
        $output .= 'License:      GPL' . "\r\n";
        $output .= "\r\n";
        $output .= 'Require:      Styles 1.1.7' . "\r\n";
        $output .= 'Styles Class: Styles_Child_Theme' . "\r\n";
	$output .= '*/' . "\r\n";
	$output .= '</textarea>' . "\r\n";

	return $output;
	
}

function admin_notice_theme_echo() {
	echo admin_notice_theme( $output );
}



class Functionality_Plugin {
	protected $plugin_filename = '';
	protected $plugin_location = '';
	public function __construct( $plugin_filename, $plugin_location = WP_PLUGIN_DIR ) {
		$this->set_plugin_filename( $plugin_filename );
		$this->set_plugin_location( $plugin_location );
	}
	public function get_plugin_filename() {
		return $this->plugin_filename;
	}
	public function set_plugin_filename( $filename ) {
		$this->plugin_filename = sanitize_file_name( $filename );
	}
	public function get_plugin_location() {
		return $this->plugin_location;
	}
	public function set_plugin_location( $dir ) {
		$this->plugin_location = trailingslashit( $dir );
	}
	public function get_plugin_header( $plugin_header = array() ) {
		global $user_identity, $user_url;
		get_currentuserinfo();
		$plugin = $this->plugin_location . $this->plugin_filename;
		$default_plugin_header = array(
			'Plugin Name' => get_bloginfo( 'name' ),
			'Plugin URI'  => home_url(),
			'Description' => sprintf ( __( "A site-specific functionality plugin for %s where you can paste your code snippets instead of using the theme's functions.php file", 'functionality' ), get_bloginfo( 'name' ) ),
			'Author'      => $user_identity,
			'Author URI'  => $user_url,
			'Version'     => date( 'Y.m.d' ),
			'License'     => 'GPL',
		);
		$plugin_header = wp_parse_args( $plugin_header, $default_plugin_header );
		$plugin_header = apply_filters( 'functionality_plugin_header', $plugin_header, $plugin );
		$plugin_header_comment = "/*\n";
		foreach ( $plugin_header as $i => $v ) {
			$plugin_header_comment .= "{$i}: {$v}\n";
		}
		$plugin_header_comment .= "*/\n";
		return apply_filters( 'functionality_plugin_header_comment', $plugin_header_comment, $plugin );
	}
	public function create_plugin() {
		$file = $this->plugin_location . $this->plugin_filename;
		if ( file_exists( $file ) )
			return;
		$file_contents = "<?php\n\n" . $this->get_plugin_header() . "\n";
		if ( null != ( $handle = @fopen( $file, 'w' ) ) ) {
			if ( null != fwrite( $handle, $file_contents, strlen( $file_contents ) ) ) {
				fclose( $handle );
			}
		}
		do_action( 'functionality_plugin_created', $file );
	}
}
