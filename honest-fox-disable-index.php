<?php

namespace HonestFox;

/*
Plugin Name: Disable Indexing
Description: A Honest Fox plugin to help noindex specific pages based on ID.
Version: 1.0
Author: Honest Fox
Author URI: https://www.honestfox.com.au
*/

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('HonestFoxDisableIndexing')) {
    class HonestFoxDisableIndexing
    {
        /**
        * Set your post IDs here for no-indexing - example
        *
        * private static $noIndexIdArray = [23424, 25252] would no index post IDs 23424 and 25252
        *
        */
        private static $noIndexIdArray = [];

        public function __construct()
        {
            // Disable robots on specific posts
            add_action('wp_head', [$this, 'noIndex'], 0);
        }

        /**
        * Filter matching post or page ID to add noindex tags
        *
        */
        public static function noIndex()
        {
            if (!is_admin() && is_page() || is_single()) {
                $postId = get_queried_object_id();

                if (in_array($postId, self::$noIndexIdArray, false)) {
                    if (is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')) {
                        add_filter('wpseo_robots', '__return_false');
                    } else {
                        echo '<meta name="robots" content="noindex, nofollow" />';
                    }
                }
            }
        }
    }

    new HonestFoxDisableIndexing();
}
