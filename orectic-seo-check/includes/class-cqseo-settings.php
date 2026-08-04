<?php
/**
 * 設定ページクラス
 *
 * @package Orectic_SEO_Check
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CQSEO_Settings {

    /**
     * 設定を登録
     */
    public function register() {
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_assets' ) );
    }

    /**
     * 設定ページ用アセットを読み込み
     *
     * @param string $hook 現在の管理画面フック
     */
    public function enqueue_settings_assets( $hook ) {
        if ( false === strpos( $hook, 'cqseo-settings' ) ) {
            return;
        }

        wp_enqueue_script(
            'cqseo-settings',
            CQSEO_PLUGIN_URL . 'assets/js/settings.js',
            array(),
            CQSEO_VERSION,
            true
        );

        wp_localize_script( 'cqseo-settings', 'cqseoSettingsData', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'cqseo_verify_nonce' ),
            'i18n'    => array(
                'enterKey'     => __( 'Please enter an API key', 'orectic-seo-check' ),
                'verifying'    => __( 'Verifying...', 'orectic-seo-check' ),
                'verifyFailed' => __( 'Verification failed', 'orectic-seo-check' ),
                'networkError' => __( 'Connection error', 'orectic-seo-check' ),
            ),
        ) );
    }

    /**
     * Settings APIで設定フィールドを登録
     */
    public function register_settings() {
        register_setting(
            'cqseo_settings_group',
            'cqseo_api_key',
            array(
                'type'              => 'string',
                'sanitize_callback' => array( 'CQSEO_API', 'sanitize_and_encrypt_api_key' ),
                'default'           => '',
                'show_in_rest'      => false,
            )
        );

        add_settings_section(
            'cqseo_api_section',
            __( 'API Settings', 'orectic-seo-check' ),
            array( $this, 'render_section_description' ),
            'cqseo-settings'
        );

        add_settings_field(
            'cqseo_api_key',
            __( 'API Key', 'orectic-seo-check' ),
            array( $this, 'render_api_key_field' ),
            'cqseo-settings',
            'cqseo_api_section'
        );
    }

    /**
     * セクション説明を表示
     */
    public function render_section_description() {
        echo '<p>' . esc_html__( 'Set an API key to use your plan\'s check quota. Without a key, you can use the free tier (up to 3 checks).', 'orectic-seo-check' ) . '</p>';
    }

    /**
     * APIキー入力フィールドを表示
     */
    public function render_api_key_field() {
        $api_key = CQSEO_API::get_api_key();
        ?>
        <input
            type="password"
            name="cqseo_api_key"
            id="cqseo_api_key"
            value="<?php echo esc_attr( $api_key ); ?>"
            class="regular-text"
            autocomplete="off"
            placeholder="<?php echo esc_attr__( 'Enter API key (optional)', 'orectic-seo-check' ); ?>"
        />
        <button type="button" id="cqseo-verify-key" class="button" style="margin-left: 8px;">
            <?php echo esc_html__( 'Verify', 'orectic-seo-check' ); ?>
        </button>
        <span id="cqseo-verify-result" style="margin-left: 8px;"></span>
        <p class="description">
            <?php
            $allowed_html = array(
                'a' => array(
                    'href'   => array(),
                    'target' => array(),
                    'rel'    => array(),
                ),
            );
            echo wp_kses(
                sprintf(
                    /* translators: %s: CodeQuest URL */
                    __( 'You can get an API key at %s.', 'orectic-seo-check' ),
                    '<a href="https://seo.codequest.work" target="_blank" rel="noopener noreferrer">seo.codequest.work</a>'
                ),
                $allowed_html
            );
            ?>
        </p>
        <?php
    }

    /**
     * 設定ページをレンダリング
     */
    public function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'ORECTIC SEO CHECK Settings', 'orectic-seo-check' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'cqseo_settings_group' );
                do_settings_sections( 'cqseo-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
