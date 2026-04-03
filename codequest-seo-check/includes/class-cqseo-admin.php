<?php
/**
 * 管理画面クラス
 *
 * @package CodeQuest_SEO_Check
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
            __( 'CQ SEO CHECK', 'codequest-seo-check' ),
            __( 'CQ SEO CHECK', 'codequest-seo-check' ),
            'manage_options',
            'cqseo-check',
            array( $this, 'render_main_page' ),
            'dashicons-chart-bar',
            80
        );

        add_submenu_page(
            'cqseo-check',
            __( '設定', 'codequest-seo-check' ),
            __( '設定', 'codequest-seo-check' ),
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
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'cqseo_check_nonce' ),
            'siteUrl' => home_url( '/' ),
            'i18n'    => array(
                'checking'       => __( '診断中...', 'codequest-seo-check' ),
                'error'          => __( 'エラー', 'codequest-seo-check' ),
                'runCheck'       => __( '診断する', 'codequest-seo-check' ),
                'score'          => __( 'スコア', 'codequest-seo-check' ),
                'structuredData' => __( '構造化データ', 'codequest-seo-check' ),
                'basicSeo'       => __( '基本SEO', 'codequest-seo-check' ),
                'content'        => __( 'コンテンツ', 'codequest-seo-check' ),
                'technical'      => __( '技術SEO', 'codequest-seo-check' ),
                'good'           => __( '合格', 'codequest-seo-check' ),
                'warning'        => __( '警告', 'codequest-seo-check' ),
                'errorStatus'    => __( 'エラー', 'codequest-seo-check' ),
                /* translators: %d: 残り回数 */
                'freeRemaining'  => __( '無料枠残り: %d/10回', 'codequest-seo-check' ),
                'timeout'        => __( 'タイムアウト: サーバーからの応答がありませんでした。', 'codequest-seo-check' ),
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
                <?php echo esc_html__( 'CodeQuest SEO CHECK', 'codequest-seo-check' ); ?>
                <?php if ( $plan_label ) : ?>
                    <span class="cqseo-plan-badge"><?php echo esc_html( $plan_label ); ?></span>
                <?php endif; ?>
            </h1>

            <div class="cqseo-check-form">
                <div class="cqseo-input-group">
                    <label for="cqseo-url"><?php echo esc_html__( '診断するURL', 'codequest-seo-check' ); ?></label>
                    <div class="cqseo-input-row">
                        <input
                            type="url"
                            id="cqseo-url"
                            class="cqseo-url-input"
                            value="<?php echo esc_attr( home_url( '/' ) ); ?>"
                            placeholder="https://example.com"
                        />
                        <button type="button" id="cqseo-run-check" class="button button-primary cqseo-check-btn">
                            <?php echo esc_html__( '診断する', 'codequest-seo-check' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div id="cqseo-loading" class="cqseo-loading" style="display:none;">
                <span class="spinner is-active"></span>
                <span><?php echo esc_html__( '診断中...サイトの分析には最大60秒かかる場合があります。', 'codequest-seo-check' ); ?></span>
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
                            <span class="cqseo-score-label"><?php echo esc_html__( '/ 100', 'codequest-seo-check' ); ?></span>
                        </div>
                    </div>
                    <div id="cqseo-free-remaining" class="cqseo-free-remaining" style="display:none;"></div>
                </div>

                <!-- カテゴリ別スコア -->
                <div class="cqseo-categories" id="cqseo-categories"></div>

                <!-- チェック項目一覧 -->
                <div class="cqseo-checks" id="cqseo-checks"></div>

                <!-- seo.codequest.work への導線 -->
                <div class="cqseo-upsell-links">
                    <h3><?php echo esc_html__( 'さらに詳しく分析する', 'codequest-seo-check' ); ?></h3>
                    <div class="cqseo-upsell-grid">
                        <a href="https://seo.codequest.work/ja/seo-check" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-editor-code"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '改善コード自動生成', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'コピペで実装できる改善コードを生成', 'codequest-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/keyword" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-search"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'キーワード調査', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'サジェスト・検索ボリューム・見出し分析', 'codequest-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/competitor-analysis" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-chart-line"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '競合SEO比較', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '最大6サイトのSEOスコアを並べて比較', 'codequest-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/structured-data-check" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-database"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( '構造化データ診断', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'JSON-LD・リッチリザルト対応状況を詳細チェック', 'codequest-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/core-web-vitals" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-performance"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'Core Web Vitals測定', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( 'LCP・CLS・INPをモバイル/デスクトップで計測', 'codequest-seo-check' ); ?></span>
                        </a>
                        <a href="https://seo.codequest.work/ja/site-diagnosis" target="_blank" rel="noopener" class="cqseo-upsell-card">
                            <span class="cqseo-upsell-icon dashicons dashicons-admin-site-alt3"></span>
                            <span class="cqseo-upsell-label"><?php echo esc_html__( 'サイト全体診断', 'codequest-seo-check' ); ?></span>
                            <span class="cqseo-upsell-desc"><?php echo esc_html__( '全ページのSEOスコアを一括チェック', 'codequest-seo-check' ); ?></span>
                        </a>
                    </div>
                    <p class="cqseo-upsell-footer">
                        <a href="https://seo.codequest.work/ja/pricing" target="_blank" rel="noopener">
                            <?php echo esc_html__( '料金プランを見る →', 'codequest-seo-check' ); ?>
                        </a>
                    </p>
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
        $api_key = get_option( 'cqseo_api_key', '' );
        if ( empty( $api_key ) ) {
            return __( 'Free', 'codequest-seo-check' );
        }

        $cached = get_transient( 'cqseo_plan_name' );
        if ( false !== $cached ) {
            return $cached;
        }

        $response = wp_remote_get(
            CQSEO_API_BASE . '/user/profile',
            array(
                'headers' => array( 'X-API-Key' => $api_key ),
                'timeout' => 5,
            )
        );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return '';
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        $plan = isset( $body['plan'] ) ? sanitize_text_field( $body['plan'] ) : '';

        if ( ! empty( $plan ) ) {
            $label = ucfirst( $plan ) . __( 'プラン', 'codequest-seo-check' );
            set_transient( 'cqseo_plan_name', $label, HOUR_IN_SECONDS );
            return $label;
        }

        return '';
    }
}
