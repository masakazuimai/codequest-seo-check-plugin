=== CQ SEO CHECK ===
Contributors: masakazuimai
Tags: seo, seo check, seo score, structured data, site audit
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One-click SEO diagnosis from WordPress admin. Scores your site out of 100 across 4 categories: Structured Data, Basic SEO, Content, and Technical SEO.

== Description ==

CQ SEO CHECK lets you diagnose your site's SEO with one click, right from the WordPress admin dashboard.

= Features =

* **Overall SEO Score** - Visualize your site's SEO health on a 100-point scale
* **4 Category Evaluation** - Structured Data, Basic SEO, Content, and Technical SEO scores
* **Detailed Check Items** - Individual diagnosis of title tags, meta descriptions, heading structure, OGP tags, and more
* **Improvement Suggestions** - Actionable advice for each check item
* **Bilingual Support** - Japanese/English based on your WordPress language setting

= External Service Connection =

This plugin sends data to the CodeQuest API service to perform SEO diagnosis.

* **Endpoint**: https://codequest-seo-api.misty-night-a30e.workers.dev
* **Data sent**: Only the URL being diagnosed
* **When**: Only when the user clicks the "Run Check" button or the "Verify" button on the Settings page
* **Additional endpoint**: When verifying an API key, the key is sent to the same service (/user/profile) to validate the key and retrieve plan information
* **Privacy Policy**: https://seo.codequest.work/privacy
* **Terms of Service**: https://seo.codequest.work/terms

Without an API key, you can use the free tier (up to 10 checks). Register for a free account to get 10 checks per month.

= 日本語 / Japanese =

WordPress管理画面からワンクリックでSEO診断ができるプラグインです。

* **総合スコア表示** - 100点満点でサイトのSEO状態を可視化
* **4カテゴリ評価** - 構造化データ、基本SEO、コンテンツ、技術SEOの各カテゴリ別スコア
* **詳細チェック項目** - タイトルタグ、メタディスクリプション、見出し構造、OGPタグなどの個別診断
* **改善提案** - 各項目に対する具体的な改善アドバイス
* **日英対応** - WordPress言語設定に連動して診断結果を日本語/英語で表示

APIキーなしでも10回まで無料で診断できます。アカウント登録（無料）すると毎月10回リセットされます。

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/cq-seo-check/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to "CQ SEO CHECK" in the left menu to run a diagnosis

= API Key Setup (Optional) =

1. Go to "CQ SEO CHECK" → "Settings"
2. Enter your API key and save
3. Get your API key at https://seo.codequest.work

= インストール手順（日本語） =

1. プラグインファイルを `/wp-content/plugins/cq-seo-check/` にアップロード
2. WordPress管理画面の「プラグイン」からCQ SEO CHECKを有効化
3. 左メニューの「CQ SEO CHECK」からSEO診断を実行

= APIキーの設定（任意） =

1. 「CQ SEO CHECK」→「設定」を開く
2. APIキー欄にキーを入力して保存
3. APIキーは https://seo.codequest.work で取得できます

== Frequently Asked Questions ==

= Do I need an API key? =

No, you can run up to 10 free checks without an API key. Register for a free account to get 10 checks per month. For more checks, set up an API key from a paid plan.

= What data is sent externally? =

Only the URL being diagnosed is sent to the CodeQuest API. No WordPress login credentials or site content is transmitted.

= How long does a diagnosis take? =

Typically 10-30 seconds. Depending on the target site's response time, it may take up to 60 seconds.

= APIキーは必要ですか？ =

いいえ、APIキーがなくても10回まで無料で診断できます。アカウント登録（無料）すると毎月10回リセットされ、有料プランのAPIキーを設定するとプラン枠で診断できます。

= どのようなデータが外部に送信されますか？ =

診断対象のURLのみがCodeQuest APIに送信されます。WordPressのログイン情報やサイトのコンテンツが送信されることはありません。

= 診断にどのくらい時間がかかりますか？ =

通常10〜30秒程度です。サイトの応答速度により最大60秒かかる場合があります。

== Screenshots ==

1. SEO diagnosis main page - Enter a URL and run the check
2. Overall score and 4 category scores
3. Detailed check items list

== Changelog ==

= 1.0.1 =
* Plugin renamed from "CodeQuest SEO Check" to "CQ SEO CHECK"
* Plugin slug changed from "codequest-seo-check" to "cq-seo-check"

= 1.0.0 =
* Initial release
* SEO diagnosis (overall score, 4 category evaluation, individual check items)
* Settings page (API key management)
* Japanese/English bilingual support
