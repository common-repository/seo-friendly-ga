<?php 
/*
	Plugin Name: SEO Friendly GA
	Plugin URI: https://mudimedia.com/wordpress-plugins/seo-friendly-google-analytics
	Description: This plugin inserts your Google Analytics Tracking Code to your WordPress site in SEO friendly way. This plugin increases your Google PagaSpeed Insights score by serving Analytics code correctly. Your optimization effects "Minimize third-party usage" and "Serve static assets with an efficient cache policy" points on PageSpeed Insights.
	Tags: seo friendly google analytics, google analytics, ga, google, analytics, tracking, statistics, stats, google pagespeed insight
	Author: Mudimedia Software
	Author URI: https://mudimedia.com/
	Donate link: https://mudimedia.com/wordpress-plugins/seo-friendly-google-analytics
	Contributors: subet
	Requires at least: 4.0
	Tested up to: 5.3
	Version: 1.0.0
	Requires PHP: 5.6
	Text Domain: seo-friendly-ga
	Domain Path: /languages
	License: GPL v2 or later
	License URI:  http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) die();

if (!class_exists('SEO_Friendly_GA')) {
	
	class SEO_Friendly_GA {
		
		function __construct() {
			
			$this->constants();
			$this->includes();
			
			add_action('admin_menu',            array($this, 'add_menu'));
			add_filter('admin_init',            array($this, 'add_settings'));
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
			add_filter('plugin_action_links',   array($this, 'action_links'), 10, 2);
			add_action('plugins_loaded',        array($this, 'load_i18n'));
			add_action('admin_init',            array($this, 'check_version'));
			add_action('admin_init',            array($this, 'reset_options'));
			add_action('admin_notices',         array($this, 'admin_notices'));
			
		} 
		
		function constants() {
			
			if (!defined('SFGA_VERSION')) define('SFGA_VERSION', '1.0.0');
			if (!defined('SFGA_REQUIRE')) define('SFGA_REQUIRE', '4.0');
			if (!defined('SFGA_AUTHOR'))  define('SFGA_AUTHOR',  'Mudimedia Software');
			if (!defined('SFGA_NAME'))    define('SFGA_NAME',    __('SEO Friendly GA', 'seo-friendly-ga'));
			if (!defined('SFGA_HOME'))    define('SFGA_HOME',    'https://mudimedia.com/seo-friendly-google-analytics');
			if (!defined('SFGA_PATH'))    define('SFGA_PATH',    'options-general.php?page=seo-friendly-ga');
			if (!defined('SFGA_URL'))     define('SFGA_URL',     plugin_dir_url(__FILE__));
			if (!defined('SFGA_DIR'))     define('SFGA_DIR',     plugin_dir_path(__FILE__));
			if (!defined('SFGA_FILE'))    define('SFGA_FILE',    plugin_basename(__FILE__));
			if (!defined('SFGA_SLUG'))    define('SFGA_SLUG',    basename(dirname(__FILE__)));
			
		}
		
		function includes() {
			
			require_once SFGA_DIR .'inc/plugin-core.php';
			
		}
		
		function add_menu() {
			
			$title_page = esc_html__('SEO Friendly GA', 'seo-friendly-ga');
			$title_menu = esc_html__('SEO Friendly GA',    'seo-friendly-ga');
			
			add_options_page($title_page, $title_menu, 'manage_options', 'seo-friendly-ga', array($this, 'display_settings'));
			
		}
		
		function add_settings() {
			
			register_setting('sfga_plugin_options', 'sfga_options', array($this, 'validate_settings'));
			
		}
		
		function admin_scripts($hook) {
			
			if ($hook === 'settings_page_seo-friendly-ga') {
				
				wp_enqueue_style('seo-friendly-ga', SFGA_URL .'css/settings.css', array(), SFGA_VERSION);
				
				wp_enqueue_script('seo-friendly-ga', SFGA_URL .'js/settings.js', array('jquery'), SFGA_VERSION);
				
				$this->localize_scripts();
				
			}
			
		}
		
		function localize_scripts() {
			
			$script = array(
				'confirm_message' => esc_html__('Do you want to restore all default options?', 'seo-friendly-ga')
			);
			
			wp_localize_script('seo-friendly-ga', 'sfga_google_analytics', $script);
			
		}
		
		function action_links($links, $file) {
			
			if ($file === SFGA_FILE && current_user_can('manage_options')) {
				
				$settings = '<a href="'. admin_url(SFGA_PATH) .'">'. esc_html__('Settings', 'seo-friendly-ga') .'</a>';
				
				array_unshift($links, $settings);
				
			}
			
			return $links;
			
		}
				
		function check_version() {
			
			$wp_version = get_bloginfo('version');
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				
				if (version_compare($wp_version, SFGA_REQUIRE, '<')) {
					
					if (is_plugin_active(SFGA_FILE)) {
						
						deactivate_plugins(SFGA_FILE);
						
						$msg  = '<strong>'. SFGA_NAME .'</strong> '. esc_html__('requires WordPress ', 'seo-friendly-ga') . SFGA_REQUIRE;
						$msg .= esc_html__(' or higher, and has been deactivated! ', 'seo-friendly-ga');
						$msg .= esc_html__('Please return to the', 'seo-friendly-ga') .' <a href="'. admin_url() .'">';
						$msg .= esc_html__('WP Admin Area', 'seo-friendly-ga') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'seo-friendly-ga');
						
						wp_die($msg);
						
					}
					
				}
				
			}
			
		}
		
		function load_i18n() {
			
			$domain = 'seo-friendly-ga';
			
			$locale = apply_filters('sfga_locale', get_locale(), $domain);
			
			$dir    = trailingslashit(WP_LANG_DIR);
			
			$file   = $domain .'-'. $locale .'.mo';
			
			$path_1 = $dir . $file;
			
			$path_2 = $dir . $domain .'/'. $file;
			
			$path_3 = $dir .'plugins/'. $file;
			
			$path_4 = $dir .'plugins/'. $domain .'/'. $file;
			
			$paths = array($path_1, $path_2, $path_3, $path_4);
			
			foreach ($paths as $path) {
				
				if ($loaded = load_textdomain($domain, $path)) {
					
					return $loaded;
					
				} else {
					
					return load_plugin_textdomain($domain, false, SFGA_DIR .'languages/');
					
				}
				
			}
			
		}
		
		function admin_notices() {
			
			$screen = get_current_screen();
			
			if (!property_exists($screen, 'id')) return;
			
			if ($screen->id === 'settings_page_seo-friendly-ga') {
				
				if (isset($_GET['sfga-reset-options'])) {
					
					if ($_GET['sfga-reset-options'] === 'true') : ?>
						
						<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Default options restored.', 'seo-friendly-ga'); ?></strong></p></div>
						
					<?php else : ?>
						
						<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made to options.', 'seo-friendly-ga'); ?></strong></p></div>
						
					<?php endif;
					
				}
				
			}
			
		}
		
		function reset_options() {
			
			if (isset($_GET['sfga-reset-options']) && wp_verify_nonce($_GET['sfga-reset-options'], 'sfga_reset_options')) {
				
				if (!current_user_can('manage_options')) exit;
				
				$update = update_option('sfga_options', $this->default_options());
				
				$result = $update ? 'true' : 'false';
				
				$location = add_query_arg(array('sfga-reset-options' => $result), admin_url(SFGA_PATH));
				
				wp_redirect(esc_url_raw($location));
				
				exit;
				
			}
			
		}

		function default_options() {
			
			$options = array(
				
				'sfga_id'          => '',
				'sfga_location'    => 'header',
				'sfga_enable'      => 1,
				'sfga_display_ads' => 0,
				'link_attr'       => 0,
				'sfga_anonymize'   => 0,
				'sfga_force_ssl'   => 0,
				'admin_area'      => 0,
				'disable_admin'   => 0,
				'sfga_custom_loc'  => 0,
				'tracker_object'  => '',
				'sfga_custom_code' => '',
				'sfga_custom'      => '',
				//
				'sfga_universal'   => 1,
				'version_alert'   => 0,
				'default_options' => 0
				
			);
			
			return apply_filters('sfga_default_options', $options);
			
		}
		
		function validate_settings($input) {
			
			$input['sfga_id'] = wp_filter_nohtml_kses($input['sfga_id']);
			
			if (!isset($input['sfga_location'])) $input['sfga_location'] = null;
			if (!array_key_exists($input['sfga_location'], $this->options_locations())) $input['sfga_location'] = null;
			
			if (!isset($input['sfga_enable'])) $input['sfga_enable'] = null;
			if (!array_key_exists($input['sfga_enable'], $this->options_libraries())) $input['sfga_enable'] = null;
			
			if (!isset($input['sfga_display_ads'])) $input['sfga_display_ads'] = null;
			$input['sfga_display_ads'] = ($input['sfga_display_ads'] == 1 ? 1 : 0);
			
			if (!isset($input['link_attr'])) $input['link_attr'] = null;
			$input['link_attr'] = ($input['link_attr'] == 1 ? 1 : 0);
			
			if (!isset($input['sfga_anonymize'])) $input['sfga_anonymize'] = null;
			$input['sfga_anonymize'] = ($input['sfga_anonymize'] == 1 ? 1 : 0);
			
			if (!isset($input['sfga_force_ssl'])) $input['sfga_force_ssl'] = null;
			$input['sfga_force_ssl'] = ($input['sfga_force_ssl'] == 1 ? 1 : 0);
			
			if (!isset($input['admin_area'])) $input['admin_area'] = null;
			$input['admin_area'] = ($input['admin_area'] == 1 ? 1 : 0);
			
			if (!isset($input['disable_admin'])) $input['disable_admin'] = null;
			$input['disable_admin'] = ($input['disable_admin'] == 1 ? 1 : 0);
			
			if (!isset($input['sfga_custom_loc'])) $input['sfga_custom_loc'] = null;
			$input['sfga_custom_loc'] = ($input['sfga_custom_loc'] == 1 ? 1 : 0);
			
			if (isset($input['tracker_object'])) $input['tracker_object'] = wp_strip_all_tags(trim($input['tracker_object']));
			
			if (isset($input['sfga_custom_code'])) $input['sfga_custom_code'] = wp_strip_all_tags(trim($input['sfga_custom_code']));
			
			if (isset($input['sfga_custom'])) $input['sfga_custom'] = stripslashes($input['sfga_custom']);
			
			return $input;
			
		}
		
		function options_locations() {
			
			return array(
				
				'header' => array(
					'value' => 'header',
					'label' => esc_html__('Include tracking code in page head (via', 'seo-friendly-ga') .' <code>wp_head</code>'. esc_html__(')', 'seo-friendly-ga')
				),
				'footer' => array(
					'value' => 'footer',
					'label' => esc_html__('Include tracking code in page footer (via', 'seo-friendly-ga') .' <code>wp_footer</code>'. esc_html__(')', 'seo-friendly-ga')
				)
			);
			
		}
		
		function options_libraries() {
			
			$url1 = 'https://developers.google.com/analytics/devguides/collection/analyticsjs/';
			$url2 = 'https://developers.google.com/analytics/devguides/collection/gtagjs/';
			$url3 = 'https://developers.google.com/analytics/devguides/collection/gajs/';
			
			$link1 = '<a target="_blank" rel="noopener noreferrer" href="'. $url1 .'">'. esc_html__('Universal Analytics', 'seo-friendly-ga') .'</a> ';
			$link2 = '<a target="_blank" rel="noopener noreferrer" href="'. $url2 .'">'. esc_html__('Global Site Tag', 'seo-friendly-ga') .'</a> ';
			$link3 = '<a target="_blank" rel="noopener noreferrer" href="'. $url3 .'">'. esc_html__('Legacy', 'seo-friendly-ga') .'</a> ';
			
			return array(
				
				1 => array(
					'value' => 1,
					'label' => $link1 .' <span class="sfga-note">/</span> <code>analytics.js</code> <span class="sfga-note">'. esc_html__('(default and optimized)', 'seo-friendly-ga') .'</span>'
				),
				2 => array(
					'value' => 2,
					'label' => $link2 .' <span class="sfga-note">/</span> <code>gtag.js</code> <span class="sfga-note">'. esc_html__('(new method)', 'seo-friendly-ga') .'</span>'
				), 
				3 => array(
					'value' => 3,
					'label' => $link3 .' <span class="sfga-note">/</span> <code>ga.js</code> <span class="sfga-note">'. esc_html__('(deprecated)', 'seo-friendly-ga') .'</span>'
				)
			);
			
		}
		
		function display_settings() {
			
			$sfga_options = get_option('sfga_options', $this->default_options());
			
			require_once SFGA_DIR .'inc/settings-display.php';
			
		}
		
		function select_menu($items, $menu) {
			
			$options = get_option('sfga_options', $this->default_options());
			
			$universal = isset($options['sfga_universal']) ? $options['sfga_universal'] : 1;
			
			$tracking = isset($options['sfga_enable']) ? $options['sfga_enable'] : 1;
			
			$checked = '';
			
			$output = '';
			
			$class = '';
			
			foreach ($items as $item) {
				
				$key = isset($options[$menu]) ? $options[$menu] : '';
				
				$value = isset($item['value']) ? $item['value'] : '';
				
				if ($menu === 'sfga_enable') {
					
					if ($tracking == 0) $key = 1;
					
					if (!$universal && $tracking == 1) $key = 3;
					
					$class = ' sfga-select-method';
					
				}
				
				$checked = ($value == $key) ? ' checked="checked"' : '';
				
				$output .= '<div class="sfga-radio-inputs'. esc_attr($class) .'">';
				$output .= '<input type="radio" name="sfga_options['. esc_attr($menu) .']" value="'. esc_attr($item['value']) .'"'. $checked .'> ';
				$output .= '<span>'. $item['label'] .'</span>'; //
				$output .= '</div>';
				
			}
			
			return $output;
			
		}
		
		function callback_reset() {
			
			$nonce = wp_create_nonce('sfga_reset_options');
			
			$href  = add_query_arg(array('sfga-reset-options' => $nonce), admin_url(SFGA_PATH));
			
			$label = esc_html__('Restore default plugin options', 'seo-friendly-ga');
			
			return '<a class="sfga-reset-options" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
			
		}
		
	}
	
	$GLOBALS['SEO_Friendly_GA'] = $SEO_Friendly_GA = new SEO_Friendly_GA(); 
	
	sfga_google_analytics_init($SEO_Friendly_GA);
	
}
