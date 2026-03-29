<?php
/**
 * プラグイン削除時のクリーンアップ
 *
 * @package CodeQuest_SEO_Check
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'cqseo_api_key' );
