<?php
/**
 * Plugin Name: Tutor ChatGPT
 * Description: Generate content using ChatGPT AI.
 * Author: Themeum
 * Version: 1.0.0
 * Author URI: http://themeum.com
 * Requires at least: 5.3
 * Tested up to: 6.1
 *
 * @package TutorPro\ChatGPT
 */

 require_once tutor_pro()->path . '/vendor/autoload.php';

define( 'TUTOR_CHATGPT_FILE', __FILE__ );
define( 'TUTOR_CHATGPT_DIR', plugin_dir_path( __FILE__ ) );

new TutorPro\ChatGPT\Init();
