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
                'enterKey'     => __( 'APIキーを入力してください', 'orectic-seo-check' ),
                'verifying'    => __( '検証中...', 'orectic-seo-check' ),
                'verifyFailed' => __( '検証に失敗しました', 'orectic-seo-check' ),
                'networkError' => __( '通信エラー', 'orectic-seo-check' ),
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
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            )
        );

        add_settings_section(
            'cqseo_api_section',
            __( 'API設定', 'orectic-seo-check' ),
            array( $this, 'render_section_description' ),
            'cqseo-settings'
        );

        add_settings_field(
            'cqseo_api_key',
            __( 'APIキー', 'orectic-seo-check' ),
            array( $this, 'render_api_key_field' ),
            'cqseo-settings',
            'cqseo_api_section'
        );
    }

    /**
     * セクション説明を表示
     */
    public function render_section_description() {
        echo '<p>' . esc_html__( 'APIキーを設定すると、プランの回数枠で診断が可能になります。未入力の場合は無料枠（10回まで）で動作します。', 'orectic-seo-check' ) . '</p>';
    }

    /**
     * APIキー入力フィールドを表示
     */
    public function render_api_key_field() {
        $api_key = get_option( 'cqseo_api_key', '' );
        ?>
        <input
            type="password"
            name="cqseo_api_key"
            id="cqseo_api_key"
            value="<?php echo esc_attr( $api_key ); ?>"
            class="regular-text"
            autocomplete="off"
            placeholder="<?php echo esc_attr__( 'APIキーを入力（任意）', 'orectic-seo-check' ); ?>"
        />
        <button type="button" id="cqseo-verify-key" class="button" style="margin-left: 8px;">
            <?php echo esc_html__( '検証', 'orectic-seo-check' ); ?>
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
                    __( 'APIキーは %s で取得できます。', 'orectic-seo-check' ),
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
            <h1><?php echo esc_html__( 'ORECTIC SEO CHECK 設定', 'orectic-seo-check' ); ?></h1>
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
