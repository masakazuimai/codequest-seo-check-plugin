=== ORECTIC SEO CHECK ===
Contributors: masakazuimai
Tags: seo, seo check, seo score, structured data, site audit
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One-click SEO diagnosis from WordPress admin. Scores your site out of 100 across 4 categories: Structured Data, Basic SEO, Content, and Technical SEO.

== Description ==

ORECTIC SEO CHECK lets you diagnose your site's SEO with one click, right from the WordPress admin dashboard.

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

Without an API key, you can use the free tier (up to 3 checks). Register for a free account to get monthly resets and diagnosis history.

= 日本語 / Japanese =

WordPress管理画面からワンクリックでSEO診断ができるプラグインです。

* **総合スコア表示** - 100点満点でサイトのSEO状態を可視化
* **4カテゴリ評価** - 構造化データ、基本SEO、コンテンツ、技術SEOの各カテゴリ別スコア
* **詳細チェック項目** - タイトルタグ、メタディスクリプション、見出し構造、OGPタグなどの個別診断
* **改善提案** - 各項目に対する具体的な改善アドバイス
* **日英対応** - WordPress言語設定に連動して診断結果を日本語/英語で表示

APIキーなしでも3回まで無料で診断できます。アカウント登録（無料）すると毎月リセット＋診断履歴が利用可能になります。

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/orectic-seo-check/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to "ORECTIC SEO CHECK" in the left menu to run a diagnosis

= API Key Setup (Optional) =

1. Go to "ORECTIC SEO CHECK" → "Settings"
2. Enter your API key and save
3. Get your API key at https://seo.codequest.work

= インストール手順（日本語） =

1. プラグインファイルを `/wp-content/plugins/orectic-seo-check/` にアップロード
2. WordPress管理画面の「プラグイン」からORECTIC SEO CHECKを有効化
3. 左メニューの「ORECTIC SEO CHECK」からSEO診断を実行

= APIキーの設定（任意） =

1. 「ORECTIC SEO CHECK」→「設定」を開く
2. APIキー欄にキーを入力して保存
3. APIキーは https://seo.codequest.work で取得できます

== Frequently Asked Questions ==

= Do I need an API key? =

No, you can run up to 3 free checks without an API key. Register for a free account to get monthly resets and diagnosis history. For more checks, set up an API key from a paid plan.

= What data is sent externally? =

Only the URL being diagnosed is sent to the CodeQuest API. No WordPress login credentials or site content is transmitted.

= How long does a diagnosis take? =

Typically 10-30 seconds. Depending on the target site's response time, it may take up to 60 seconds.

= APIキーは必要ですか？ =

いいえ、APIキーがなくても3回まで無料で診断できます。アカウント登録（無料）すると毎月リセット＋診断履歴が利用可能になり、有料プランのAPIキーを設定するとプラン枠で診断できます。

= どのようなデータが外部に送信されますか？ =

診断対象のURLのみがCodeQuest APIに送信されます。WordPressのログイン情報やサイトのコンテンツが送信されることはありません。

= 診断にどのくらい時間がかかりますか？ =

通常10〜30秒程度です。サイトの応答速度により最大60秒かかる場合があります。

== Screenshots ==

1. SEO diagnosis main page - Enter a URL and run the check
2. Overall score and 4 category scores
3. Detailed check items list

== Changelog ==

= 1.2.2 =
* Fixed: API error messages (e.g. quota exceeded) now display correctly instead of "Unknown error"
* Changed: Primary CTA text updated to "Web版で詳しく診断する" for clarity
* Changed: Inline fix button text updated to "Web版で改善する"
* Changed: CTA title and subtitle reworded to accurately describe the web version flow

= 1.2.1 =
* Fixed: Primary CTA "View improvement code" button now links to SEO check page instead of signup page

= 1.2.0 =
* New: Added Competitor Keyword Research card to feature discovery section
* Changed: Code generation description updated — now available on all plans (with limits per plan)
* Changed: Free account checks updated from 10 to 3 per month (aligned with API)
* Changed: Upgrade CTA text updated to accurately reflect free plan benefits (monthly reset + history)
* Updated bilingual translations for all new/changed UI strings

= 1.0.6 =
* Fixed CTA text to accurately reflect free plan features (no misleading claims)
* Technical SEO lock display now correctly shows "basic 3 items only" with link to pricing
* Inline CTA links and upgrade links changed from text to small button style for better visibility
* Updated bilingual translations for corrected UI strings

= 1.0.5 =
* Added primary CTA block after score display to improve conversion flow
* Free quota display now includes signup link ("Sign up free for 10 checks/month")
* Technical SEO category shows lock UI when max score is 0 (instead of confusing "0/0")
* Failed check items now show inline link to improvement code generation
* Feature cards section collapsed by default (expandable via toggle)
* All external links now include UTM tracking parameters
* API response now preserves `layers` (ranking/serp/technical) and `spamWarnings` fields
* Added bilingual translations for all new UI strings

= 1.0.4 =
* Security: API keys are now encrypted at rest using AES-256-CBC (legacy plain-text values are auto-migrated on next save)
* Security: Added per-user rate limiting (10 requests/minute) on the diagnosis AJAX endpoint
* Security: Generic API error messages to prevent information leakage
* No user-facing functional changes

= 1.0.3 =
* Internal refactoring: unified free tier limit via CQSEO_FREE_LIMIT constant
* Internal refactoring: centralized API user profile fetching in CQSEO_API class
* Internal refactoring: centralized API key retrieval via CQSEO_API::get_api_key()
* No user-facing functional changes

= 1.0.2 =
* Free tier without API key changed from 10 to 3 checks
* Free account registration provides 10 checks per month

= 1.0.1 =
* Plugin renamed to "ORECTIC SEO CHECK"
* Plugin slug changed to "orectic-seo-check"

= 1.0.0 =
* Initial release
* SEO diagnosis (overall score, 4 category evaluation, individual check items)
* Settings page (API key management)
* Japanese/English bilingual support
