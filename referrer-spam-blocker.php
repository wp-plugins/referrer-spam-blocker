<?php
/*
 * Plugin Name: Referrer Spam Blocker
 * Version: 1.0
 * Description: Block access to the site for well known referrer spam sites
 * Author: Tomasz Tybulewicz
 * Author URI: http://tybulewicz.com/
 * Requires at least: 3.8
 * Tested up to: 4.2.2
 *
 * @package WordPress
 * @author Tomasz Tybulewicz <tomasz@tybulewicz.com>
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

/**
 * Returns the main instance of Single_Page_Per_Category to prevent the need to use globals.
 *
 * @since  1.0.0
 */
function Referrer_Spam_Blocker_Setup () {
	$blocker = function () {
		$instance = new \Tybulewicz\ReferrerSpamDetector\Detector();

		if (!isset($_SERVER['HTTP_REFERER'])) {
			return;
		}

		if ($instance->isSpam($_SERVER['HTTP_REFERER'])) {
			wp_die("Spam Referrer detected!");
		}
	};

	add_action( 'init', $blocker, 0 );
}

Referrer_Spam_Blocker_Setup();