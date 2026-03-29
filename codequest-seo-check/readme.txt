=== CodeQuest SEO Check ===
Contributors: orectic
Tags: seo, seo check, seo score, structured data, site audit
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

ワンクリックでサイトのSEOスコアを診断。構造化データ・基本SEO・コンテンツ・技術SEOの4カテゴリで評価します。

== Description ==

CodeQuest SEO Checkは、WordPress管理画面からワンクリックでサイトのSEO診断ができるプラグインです。

= 主な機能 =

* **総合スコア表示** - 100点満点でサイトのSEO状態を可視化
* **4カテゴリ評価** - 構造化データ、基本SEO、コンテンツ、技術SEOの各カテゴリ別スコア
* **詳細チェック項目** - タイトルタグ、メタディスクリプション、見出し構造、OGPタグなどの個別診断
* **改善提案** - 各項目に対する具体的な改善アドバイス
* **日英対応** - WordPress言語設定に連動して診断結果を日本語/英語で表示

= 外部サービスへの接続 =

このプラグインは、SEO診断を実行するために外部のCodeQuest APIサービスにデータを送信します。

* **接続先**: https://codequest-seo-api.misty-night-a30e.workers.dev
* **送信データ**: 診断対象のURL
* **タイミング**: ユーザーが「診断する」ボタンをクリックした時のみ
* **プライバシーポリシー**: https://seo.codequest.work/privacy
* **利用規約**: https://seo.codequest.work/terms

APIキーを設定していない場合、無料枠（10回まで）でご利用いただけます。アカウント登録すると毎月10回リセットされます。

== Installation ==

1. プラグインファイルを `/wp-content/plugins/codequest-seo-check/` ディレクトリにアップロード
2. WordPress管理画面の「プラグイン」からCodeQuest SEO Checkを有効化
3. 左メニューの「SEO Check」からSEO診断を実行

= APIキーの設定（任意） =

1. 「SEO Check」→「設定」を開く
2. APIキー欄にキーを入力して保存
3. APIキーはhttps://seo.codequest.workで取得できます

== Frequently Asked Questions ==

= APIキーは必要ですか？ =

いいえ、APIキーがなくても10回まで無料で診断できます。アカウント登録（無料）すると毎月10回リセットされ、有料プランのAPIキーを設定するとプラン枠で診断できます。より多くの診断やPro機能を利用するにはAPIキーが必要です。

= どのようなデータが外部に送信されますか？ =

診断対象のURLのみがCodeQuest APIに送信されます。WordPressのログイン情報やサイトのコンテンツが送信されることはありません。

= 診断にどのくらい時間がかかりますか？ =

通常10〜30秒程度です。サイトの応答速度により最大60秒かかる場合があります。

== Screenshots ==

1. SEO診断メインページ - URLを入力して診断を実行
2. 総合スコアと4カテゴリ別スコア
3. 詳細チェック項目一覧

== Changelog ==

= 1.0.0 =
* 初回リリース
* SEO診断機能（総合スコア、4カテゴリ評価、個別チェック項目）
* 設定ページ（APIキー管理）
* 日英多言語対応
