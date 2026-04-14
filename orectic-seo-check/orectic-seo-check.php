<?php
/**
 * Plugin Name: ORECTIC SEO CHECK
 * Plugin URI: https://seo.codequest.work
 * Description: ワンクリックでサイトのSEOスコアを診断。構造化データ・基本SEO・コンテンツ・技術SEOの4カテゴリで100点満点のスコアを表示します。
 * Version: 1.0.3
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: ORECTIC
 * Author URI: https://orecticdesign.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: orectic-seo-check
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CQSEO_VERSION', '1.0.3' );
define( 'CQSEO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CQSEO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CQSEO_API_BASE', 'https://codequest-seo-api.misty-night-a30e.workers.dev' );
define( 'CQSEO_FREE_LIMIT', 3 );

require_once CQSEO_PLUGIN_DIR . 'includes/class-cqseo-admin.php';
require_once CQSEO_PLUGIN_DIR . 'includes/class-cqseo-api.php';
require_once CQSEO_PLUGIN_DIR . 'includes/class-cqseo-settings.php';

/**
 * 管理画面の初期化
 */
function cqseo_admin_init() {
    $admin    = new CQSEO_Admin();
    $settings = new CQSEO_Settings();

    $admin->register();
    $settings->register();
}
add_action( 'plugins_loaded', 'cqseo_admin_init' );

/**
 * AJAX: SEO診断実行
 */
function cqseo_ajax_run_check() {
    check_ajax_referer( 'cqseo_check_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( '権限がありません。', 'orectic-seo-check' ) ) );
    }

    $url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';

    if ( empty( $url ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
        wp_send_json_error( array( 'message' => __( '有効なURLを入力してください。', 'orectic-seo-check' ) ) );
    }

    $api    = new CQSEO_API();
    $result = $api->check( $url );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array( 'message' => $result->get_error_message() ) );
    }

    wp_send_json_success( $result );
}
add_action( 'wp_ajax_cqseo_run_check', 'cqseo_ajax_run_check' );

/**
 * AJAX: APIキー検証
 */
function cqseo_ajax_verify_key() {
    check_ajax_referer( 'cqseo_verify_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( '権限がありません。', 'orectic-seo-check' ) ) );
    }

    $api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

    if ( empty( $api_key ) ) {
        wp_send_json_error( array( 'message' => __( 'APIキーを入力してください。', 'orectic-seo-check' ) ) );
    }

    $api     = new CQSEO_API();
    $profile = $api->get_user_profile( $api_key, 15 );

    if ( is_wp_error( $profile ) ) {
        wp_send_json_error( array( 'message' => $profile->get_error_message() ) );
    }

    $plan = isset( $profile['plan'] ) ? sanitize_text_field( $profile['plan'] ) : 'unknown';
    /* translators: %s: プラン名 */
    $message = sprintf( __( '認証成功（%sプラン）', 'orectic-seo-check' ), ucfirst( $plan ) );
    wp_send_json_success( array( 'message' => $message ) );
}
add_action( 'wp_ajax_cqseo_verify_key', 'cqseo_ajax_verify_key' );

/**
 * プラグイン有効化時の処理
 */
function cqseo_activate() {
    add_option( 'cqseo_api_key', '' );
}
register_activation_hook( __FILE__, 'cqseo_activate' );

/**
 * プラグイン無効化時のクリーンアップ
 */
function cqseo_deactivate() {
    // オプションは削除しない（再有効化時に設定を保持）
}
register_deactivation_hook( __FILE__, 'cqseo_deactivate' );
