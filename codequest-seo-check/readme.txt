=== CodeQuest SEO Check ===
Contributors: masakazuimai
Tags: seo, seo check, seo score, structured data, site audit
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One-click SEO diagnosis from WordPress admin. Scores your site out of 100 across 4 categories: Structured Data, Basic SEO, Content, and Technical SEO.

== Description ==

CodeQuest SEO Check lets you diagnose your site's SEO with one click, right from the WordPress admin dashboard.

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
* **When**: Only when the user clicks the "Run Check" button
* **Privacy Policy**: https://seo.codequest.work/privacy
* **Terms of Service**: https://seo.codequest.work/terms

Without an API key, you can use the free tier (up to 10 checks). Register for a free account to get 10 checks per month.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/codequest-seo-check/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to "SEO Check" in the left menu to run a diagnosis

= API Key Setup (Optional) =

1. Go to "SEO Check" → "Settings"
2. Enter your API key and save
3. Get your API key at https://seo.codequest.work

== Frequently Asked Questions ==

= Do I need an API key? =

No, you can run up to 10 free checks without an API key. Register for a free account to get 10 checks per month. For more checks, set up an API key from a paid plan.

= What data is sent externally? =

Only the URL being diagnosed is sent to the CodeQuest API. No WordPress login credentials or site content is transmitted.

= How long does a diagnosis take? =

Typically 10-30 seconds. Depending on the target site's response time, it may take up to 60 seconds.

== Screenshots ==

1. SEO diagnosis main page - Enter a URL and run the check
2. Overall score and 4 category scores
3. Detailed check items list

== Changelog ==

= 1.0.0 =
* Initial release
* SEO diagnosis (overall score, 4 category evaluation, individual check items)
* Settings page (API key management)
* Japanese/English bilingual support
