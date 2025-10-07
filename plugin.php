<?php

/**
 * Plugin Name: oEmbed Widget for Telegram Posts
 * Description: Automatically embeds Telegram links (channels, groups, or posts) as responsive iframes using WordPress oEmbed.
 * Plugin URI: https://github.com/aiiddqd/oembed-telegram-post-widget
 * Author: aiiddqd
 * Author URI: https://github.com/aiiddqd/
 * Tested up to: 6.8.2
 * License: GPL-2.0+
 * Version: 1.0
 */

namespace aiiddqd;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

oEmbedWidgetForTelegramPosts::init();

class oEmbedWidgetForTelegramPosts
{
    public static function init()
    {
        add_action('init', [self::class, 'register_oembed']);
    }

    /**
     * Register Telegram oEmbed provider and handlers
     */
    public static function register_oembed()
    {
        // Register Telegram URLs for oEmbed processing
        $pattern = '#https?://t\.me/.*#i';
        wp_embed_register_handler('post-widget-telegram', $pattern, function ($matches, $attr, $url) {

            $urlParts = explode('t.me/', $url);
            $postValue = $urlParts[1] ?? '';
            if (empty($postValue)) {
                return false;
            }

            
            /* 
             * Avoid phpcs warning about directly embedding scripts because this is how Telegram oEmbed works
             *
             * phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript 
             */
            return sprintf(
                '<script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-post="%s" data-width="100%%"></script>',
                esc_attr($postValue)
            );
            /* phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript */

        });
    }
}
