<?php
/**
 * 設定ページクラス
 *
 * @package CodeQuest_SEO_Check
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
            __( 'API設定', 'codequest-seo-check' ),
            array( $this, 'render_section_description' ),
            'cqseo-settings'
        );

        add_settings_field(
            'cqseo_api_key',
            __( 'APIキー', 'codequest-seo-check' ),
            array( $this, 'render_api_key_field' ),
            'cqseo-settings',
            'cqseo_api_section'
        );
    }

    /**
     * セクション説明を表示
     */
    public function render_section_description() {
        echo '<p>' . esc_html__( 'APIキーを設定すると、プランの回数枠で診断が可能になります。未入力の場合は無料枠（10回まで）で動作します。', 'codequest-seo-check' ) . '</p>';
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
            placeholder="<?php echo esc_attr__( 'APIキーを入力（任意）', 'codequest-seo-check' ); ?>"
        />
        <p class="description">
            <?php
            printf(
                /* translators: %s: CodeQuest URL */
                esc_html__( 'APIキーは %s で取得できます。', 'codequest-seo-check' ),
                '<a href="https://seo.codequest.work" target="_blank" rel="noopener noreferrer">seo.codequest.work</a>'
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
            <h1><?php echo esc_html__( 'CodeQuest SEO Check 設定', 'codequest-seo-check' ); ?></h1>
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
