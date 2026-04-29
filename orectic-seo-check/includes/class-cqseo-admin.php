<?php
/**
 * 管理画面クラス
 *
 * @package Orectic_SEO_Check
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CQSEO_Admin {

    /**
     * 管理画面フックを登録
     */
    public function register() {
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * 管理メニューを追加
     */
    public function add_menu() {
        add_menu_page(
            __( 'ORECTIC SEO CHECK', 'orectic-seo-check' ),
            __( 'ORECTIC SEO CHECK', 'orectic-seo-check' ),
            'manage_options',
            'cqseo-check',
            array( $this, 'render_main_page' ),
            'dashicons-chart-bar',
            80
        );

        add_submenu_page(
            'cqseo-check',
            __( '設定', 'orectic-seo-check' ),
            __( '設定', 'orectic-seo-check' ),
            'manage_options',
            'cqseo-settings',
            array( new CQSEO_Settings(), 'render_page' )
        );
    }

    /**
     * CSS/JSを読み込み
     *
     * @param string $hook 現在の管理画面フック
     */
    public function enqueue_assets( $hook ) {
        if ( 'toplevel_page_cqseo-check' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'cqseo-admin',
            CQSEO_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CQSEO_VERSION
        );

        wp_enqueue_script(
            'cqseo-admin',
            CQSEO_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            CQSEO_VERSION,
            true
        );

        wp_localize_script( 'cqseo-admin', 'cqseoData', array(
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'cqseo_check_nonce' ),
            'siteUrl'   => home_url( '/' ),
            'freeLimit'  => CQSEO_FREE_LIMIT,
            'signupUrl'  => 'https://seo.codequest.work/ja/signup',
            'pricingUrl' => 'https://seo.codequest.work/ja/pricing',
            'seoCheckUrl' => 'https://seo.codequest.work/ja/seo-check',
            'i18n'      => array(
                'checking'       => __( '診断中...', 'orectic-seo-check' ),
                'error'          => __( 'エラー', 'orectic-seo-check' ),
                'runCheck'       => __( '診断する', 'orectic-seo-check' ),
                'score'          => __( 'スコア', 'orectic-seo-check' ),
                'structuredData' => __( '構造化データ', 'orectic-seo-check' ),
                'basicSeo'       => __( '基本SEO', 'orectic-seo-check' ),
                'content'        => __( 'コンテンツ', 'orectic-seo-check' ),
                'technical'      => __( '技術SEO', 'orectic-seo-check' ),
                'good'           => __( '合格', 'orectic-seo-check' ),
                'warning'        => __( '警告', 'orectic-seo-check' ),
                'errorStatus'    => __( 'エラー', 'orectic-seo-check' ),
                /* translators: 1: 残り回数 2: 無料枠上限 */
                'freeRemaining'  => __( '無料枠残り: %1$d/%2$d回', 'orectic-seo-check' ),
                'freeUpgrade'    => __( '登録すると月次リセット + 履歴保存 →', 'orectic-seo-check' ),
                'timeout'        => __( 'タイムアウト: サーバーからの応答がありませんでした。', 'orectic-seo-check' ),
                'techLocked'     => __( '技術SEO（基本3項目のみ）', 'orectic-seo-check' ),
                'techLockedDesc' => __( 'セキュリティ・リダイレクト・サイトマップ等の詳細診断は有料プランで利用可能', 'orectic-seo-check' ),
                'techLockedCta'  => __( '料金プランを見る →', 'orectic-seo-check' ),
                'ctaTitle'       => __( 'さらに詳しい診断と改善コード生成が利用できます', 'orectic-seo-check' ),
                'ctaButton'      => __( 'Web版で詳しく診断する', 'orectic-seo-check' ),
                'ctaSub'         => __( 'Web版で診断すると改善コードも生成できます', 'orectic-seo-check' ),
                'fixCode'        => __( 'Web版で改善する', 'orectic-seo-check' ),
            ),
        ) );
    }

    /**
     * メインページをレンダリング
     */
    public function render_main_page() {
        $plan_label = $this->get_plan_label();
        ?>
        <div class="wrap cqseo-wrap">
            <h1>
                <?php echo esc_html__( 'ORECTIC SEO CHECK', 'orectic-seo-check' ); ?>
                <?php if ( $plan_label ) : ?>
                    <span class="cqseo-plan-badge"><?php echo esc_html( $plan_label ); ?></span>
                <?php endif; ?>
            </h1>

            <div class="cqseo-check-form">
                <div class="cqseo-input-group">
                    <label for="cqseo-url"><?php echo esc_html__( '診断するURL', 'orectic-seo-check' ); ?></label>
                    <div class="cqseo-input-row">
                        <input
                            type="url"
                            id="cqseo-url"
                            class="cqseo-url-input"
                            value="<?php echo esc_attr( home_url( '/' ) ); ?>"
                            placeholder="https://example.com"
                        />
                        <button type="button" id="cqseo-run-check" class="button button-primary cqseo-check-btn">
                            <?php echo esc_html__( '診断する', 'orectic-seo-check' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div id="cqseo-loading" class="cqseo-loading" style="display:none;">
                <span class="spinner is-active"></span>
                <span><?php echo esc_html__( '診断中...サイトの分析には最大60秒かかる場合があります。', 'orectic-seo-check' ); ?></span>
            </div>

            <div id="cqseo-error" class="cqseo-error notice notice-error" style="display:none;">
                <p></p>
            </div>

            <div id="cqseo-results" class="cqseo-results" style="display:none;">
                <!-- 総合スコア -->
                <div class="cqseo-score-section">
                    <div class="cqseo-score-circle">
                        <svg viewBox="0 0 120 120" class="cqseo-score-svg">
                            <circle cx="60" cy="60" r="54" class="cqseo-score-bg" />
                            <circle cx="60" cy="60" r="54" class="cqseo-score-bar" id="cqseo-score-bar" />
                        </svg>
                        <div class="cqseo-score-text">
                            <span id="cqseo-score-value" class="cqseo-score-number">0</span>
                            <span class="cqseo-score-label"><?php echo esc_html__( '/ 100', 'orectic-seo-check' ); ?></span>
                        </div>
                    </div>
                    <div id="cqseo-free-remaining" class="cqseo-free-remaining" style="display:none;"></div>
                </div>

                <!-- カテゴリ別スコア -->
                <div class="cqseo-categories" id="cqseo-categories"></div>

                <!-- プライマリCTA（JS側で動的に表示制御） -->
                <div id="cqseo-primary-cta" class="cqseo-primary-cta" style="display:none;"></div>

                <!-- チェック項目一覧 -->
                <div class="cqseo-checks" id="cqseo-checks"></div>

                <!-- seo.codequest.work への導線 -->
                <div class="cqseo-upsell-links">
                    <details class="cqseo-upsell-details">
                    <summary class="cqseo-upsell-summary"><?php echo esc_html__( 'さらに詳しく分析する', 'orectic-seo-check' ); ?></summary>
                    <div class="cqseo-upsell-grid">
                        <a href="https://seo.codequest.work/ja/seo-check?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-editor-code"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '改善コード自動生成', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '全プランで利用可能（件数制限あり）', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/keyword?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-search"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'キーワード調査', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'サジェスト・検索ボリューム・見出し分析', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/competitor-analysis?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-chart-line"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '競合SEO比較', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '最大6サイトのSEOスコアを並べて比較', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/structured-data-check?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-database"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '構造化データ診断', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'JSON-LD・リッチリザルト対応状況を詳細チェック', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/core-web-vitals?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-performance"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'Core Web Vitals測定', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'LCP・CLS・INPをモバイル/デスクトップで計測', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/site-diagnosis?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-admin-site-alt3"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'サイト全体診断', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '全ページのSEOスコアを一括チェック', 'orectic-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/competitor-keywords?utm_source=wp-plugin&utm_medium=upsell-card&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-visibility"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '競合キーワード調査', 'orectic-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '競合サイトのランクインキーワードを分析', 'orectic-seo-check' ); ?></span>
                        </a>
                    </div>
                    <p class="cqseo-upsell-footer">
                        <a href="https://seo.codequest.work/ja/pricing?utm_source=wp-plugin&utm_medium=upsell-footer&utm_campaign=wp-plugin-v1" target="_blank" rel="noopener">
                            <?php echo esc_html__( '料金プランを見る →', 'orectic-seo-check' ); ?>
                        </a>
                    </p>
                    </details>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * 現在のプラン名を取得（キャッシュ付き）
     *
     * @return string プラン名（未設定の場合は空文字）
     */
    private function get_plan_label() {
        if ( empty( CQSEO_API::get_api_key() ) ) {
            return __( 'Free', 'orectic-seo-check' );
        }

        $cached = get_transient( 'cqseo_plan_name' );
        if ( false !== $cached ) {
            return $cached;
        }

        $api     = new CQSEO_API();
        $profile = $api->get_user_profile();

        if ( is_wp_error( $profile ) ) {
            return '';
        }

        $plan = isset( $profile['plan'] ) ? sanitize_text_field( $profile['plan'] ) : '';
        if ( empty( $plan ) ) {
            return '';
        }

        $label = ucfirst( $plan ) . __( 'プラン', 'orectic-seo-check' );
        set_transient( 'cqseo_plan_name', $label, HOUR_IN_SECONDS );
        return $label;
    }
}
