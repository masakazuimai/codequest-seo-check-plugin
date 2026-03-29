<?php
/**
 * API通信クラス
 *
 * @package CodeQuest_SEO_Check
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CQSEO_API {

    /**
     * SEO診断APIにリクエストを送信
     *
     * @param string $url 診断対象URL
     * @return array|WP_Error 診断結果またはエラー
     */
    public function check( $url ) {
        $api_key = get_option( 'cqseo_api_key', '' );
        $locale  = $this->get_locale();

        $headers = array(
            'Content-Type'    => 'application/json',
            'Accept-Language' => $locale,
        );

        if ( ! empty( $api_key ) ) {
            $headers['X-API-Key'] = $api_key;
        }

        $response = wp_remote_post(
            CQSEO_API_BASE . '/check',
            array(
                'headers' => $headers,
                'body'    => wp_json_encode( array( 'url' => $url ) ),
                'timeout' => 60,
            )
        );

        if ( is_wp_error( $response ) ) {
            return new WP_Error(
                'cqseo_api_error',
                /* translators: %s: エラーメッセージ */
                sprintf( __( 'API接続エラー: %s', 'codequest-seo-check' ), $response->get_error_message() )
            );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( 200 !== $code ) {
            $message = isset( $data['message'] ) ? sanitize_text_field( $data['message'] ) : __( '不明なエラーが発生しました。', 'codequest-seo-check' );
            return new WP_Error( 'cqseo_api_error', $message );
        }

        if ( null === $data ) {
            return new WP_Error( 'cqseo_api_error', __( 'APIレスポンスの解析に失敗しました。', 'codequest-seo-check' ) );
        }

        return $this->sanitize_response( $data );
    }

    /**
     * APIレスポンスをサニタイズ
     *
     * @param array $data APIレスポンスデータ
     * @return array サニタイズ済みデータ
     */
    private function sanitize_response( $data ) {
        $sanitized = array(
            'url'        => isset( $data['url'] ) ? esc_url_raw( $data['url'] ) : '',
            'score'      => isset( $data['score'] ) ? absint( $data['score'] ) : 0,
            'maxScore'   => isset( $data['maxScore'] ) ? absint( $data['maxScore'] ) : 100,
            'categories' => array(),
            'checks'     => array(),
        );

        $valid_categories = array( 'structured_data', 'basic_seo', 'content', 'technical' );

        if ( isset( $data['categories'] ) && is_array( $data['categories'] ) ) {
            foreach ( $valid_categories as $cat_key ) {
                if ( isset( $data['categories'][ $cat_key ] ) ) {
                    $sanitized['categories'][ $cat_key ] = array(
                        'score' => absint( $data['categories'][ $cat_key ]['score'] ),
                        'max'   => absint( $data['categories'][ $cat_key ]['max'] ),
                    );
                }
            }
        }

        if ( isset( $data['checks'] ) && is_array( $data['checks'] ) ) {
            foreach ( $data['checks'] as $check ) {
                $sanitized['checks'][] = array(
                    'id'         => isset( $check['id'] ) ? sanitize_text_field( $check['id'] ) : '',
                    'name'       => isset( $check['name'] ) ? sanitize_text_field( $check['name'] ) : '',
                    'category'   => isset( $check['category'] ) && in_array( $check['category'], $valid_categories, true )
                        ? $check['category']
                        : '',
                    'status'     => isset( $check['status'] ) && in_array( $check['status'], array( 'good', 'warning', 'error' ), true )
                        ? $check['status']
                        : 'error',
                    'score'      => isset( $check['score'] ) ? intval( $check['score'] ) : 0,
                    'max_score'  => isset( $check['max_score'] ) ? absint( $check['max_score'] ) : 0,
                    'value'      => isset( $check['value'] ) ? sanitize_text_field( $check['value'] ) : '',
                    'message'    => isset( $check['message'] ) ? sanitize_text_field( $check['message'] ) : '',
                    'suggestion' => isset( $check['suggestion'] ) ? sanitize_text_field( $check['suggestion'] ) : null,
                );
            }
        }

        if ( isset( $data['anonymousUsed'] ) ) {
            $sanitized['anonymousUsed'] = absint( $data['anonymousUsed'] );
        }

        return $sanitized;
    }

    /**
     * WordPress言語設定からAPIロケールを取得
     *
     * @return string 'ja' or 'en'
     */
    private function get_locale() {
        $wp_locale = get_locale();
        return ( 0 === strpos( $wp_locale, 'ja' ) ) ? 'ja' : 'en';
    }
}
