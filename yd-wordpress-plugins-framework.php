<?php
/**
 * @package YD_Wordpress-plugins-framework
 * @author Yann Dubois
 * @version 0.1.0
 */

/*
 Plugin Name: YD Wordpress Plugins Framework
 Plugin URI: http://www.yann.com/en/wp-plugins/yd-wordpress-plugins-framework
 Description: An object oriented PHP framework for building Wordpress plugins and widgets. | Funded by <a href="http://www.abc.fr">ABC.FR</a>
 Version: 0.1.0
 Author: Yann Dubois
 Author URI: http://www.yann.com/
 License: GPL2
 */

/**
 * @copyright 2010  Yann Dubois  ( email : yann _at_ abc.fr )
 *
 *  Original development of this plugin was kindly funded by http://www.abc.fr
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 Revision 0.1.0:
 - Original beta release
 */

include_once( 'inc/yd-widget-framework.inc.php' );

/**
 * 
 * Just fill up necessary settings in the configuration array
 * to create a new custom plugin instance...
 * 
 */
$junk = new YD_Plugin( 
	array(
		'name' 				=> 'YD Plugin',
		'version'			=> '0.1.0',
		'has_option_page'	=> false,
		'has_shortcode'		=> false,
		'has_widget'		=> false,
		'widget_class'		=> '',
		'has_cron'			=> false,
		'crontab'			=> array(
			'daily'			=> array( 'YD_MiscWidget', 'daily_update' ),
			'hourly'		=> array( 'YD_MiscWidget', 'hourly_update' )
		),
		'has_stylesheet'	=> false,
		'stylesheet_file'	=> 'css/yd.css',
		'has_translation'	=> false,
		'translation_domain'=> '', // must be copied in the widget class!!!
		'translations'		=> array(
			array( 'English', 'Yann Dubois', 'http://www.yann.com/' ),
			array( 'French', 'Yann Dubois', 'http://www.yann.com/' )
		),		
		'initial_funding'	=> array( 'Yann.com', 'http://www.yann.com' ),
		'additional_funding'=> array(),
		'form_blocks'		=> array(
			'block1' => array( 
				'test'	=> 'text' 
			)
		),
		'option_field_labels'=>array(
				'test'	=> 'Test label'
		),
		'option_defaults'	=> array(
				'test'		=> 'whatever'
		),
		'form_add_actions'	=> array(
				'Manually run hourly process'	=> array( 'YD_MiscWidget', 'hourly_update' ),
				'Check latest'					=> array( 'YD_MiscWidget', 'check_update' )
		),
		'has_cache'			=> false,
		'option_page_text'	=> 'This plugin has no option... yet!',
		'backlinkware_text' => 'Features Plugin developed using YD Plugin Framework',
		'plugin_file'		=> __FILE__		
 	)
);

/**
 * 
 * You must specify a unique class name
 * to avoid collision with other plugins...
 * 
 */
class YD_MiscWidget extends YD_Widget {
    
	function do_things( $op ) {
		// do things
		$option_key = 'yd-plugin';
		$options = get_option( $option_key );
		
		$op->error_msg .= 'Great.';
		$op->update_msg .= 'Cool.';
		
		update_option( 'YD_P_last_action', time() );
	}
	
	function hourly_update( $op ) {
		if( !$op || !is_object( $op ) ) {
			$op = new YD_OptionPage(); //dummy object
		}
		self::do_things( &$op );
		update_option( 'YD_P_hourly', time() );
	}
	
	function daily_update( $op ) {
		if( !$op || !is_object( $op ) ) {
			$op = new YD_OptionPage(); //dummy object
		}
		self::do_things( &$op );
		update_option( 'YD_P_daily', time() );
	}
	
	function check_update( $op ) {
		$op->update_msg .= '<p>';
		if( $last = get_option( 'YD_P_daily' ) ) {
			$op->update_msg .= 'Last daily action was on: ' 
				. date( DATE_RSS, $last ) . '<br/>';
		} else { 
			$op->update_msg .= 'No daily action yet.<br/>';
		}
		if( $last = get_option( 'YD_P_hourly' ) ) {
			$op->update_msg .= 'Last hourly action was on: ' 
				. date( DATE_RSS, $last ) . '<br/>';
		} else { 
			$op->update_msg .= 'No hourly action yet.<br/>';
		}
		if( $last = get_option( 'YD_P_last_action' ) ) {
			$op->update_msg .= 'Last completed action was on: ' 
				. date( DATE_RSS, $last ) . '<br/>';
		} else { 
			$op->update_msg .= 'No recorded action yet.<br/>';
		}
		$op->update_msg .= '</p>';
	}
}
?>