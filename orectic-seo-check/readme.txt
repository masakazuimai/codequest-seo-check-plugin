=== ORECTIC SEO CHECK ===
Contributors: masakazuimai
Tags: seo, seo check, seo score, structured data, site audit
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.3.2
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

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/orectic-seo-check/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to "ORECTIC SEO CHECK" in the left menu to run a diagnosis

= API Key Setup (Optional) =

1. Go to "ORECTIC SEO CHECK" → "Settings"
2. Enter your API key and save
3. Get your API key at https://seo.codequest.work

== Frequently Asked Questions ==

= Do I need an API key? =

No, you can run up to 3 free checks without an API key. Register for a free account to get monthly resets and diagnosis history. For more checks, set up an API key from a paid plan.

= What data is sent externally? =

Only the URL being diagnosed is sent to the CodeQuest API. No WordPress login credentials or site content is transmitted.

= How long does a diagnosis take? =

Typically 10-30 seconds. Depending on the target site's response time, it may take up to 60 seconds.

== Screenshots ==

1. SEO diagnosis main page - Enter a URL and run the check
2. Overall score and 4 category scores
3. Detailed check items list

== Changelog ==

= 1.3.2 =
* Compatibility: Tested with WordPress 7.1
* Fixed: The score circle animation no longer triggers a jQuery deprecation warning for number-typed CSS values
* Changed: Removed the manual load_plugin_textdomain() call. Translations are now loaded automatically, as recommended since WordPress 4.6

= 1.3.1 =
* Changed: The readme is now written entirely in English. The Japanese sections that were embedded directly in readme.txt have been removed so the readme can be translated on translate.wordpress.org like any other locale
* No functional changes

= 1.3.0 =
* Changed: Source strings are now written in English, making the plugin translatable into any language on translate.wordpress.org
* Changed: Japanese translations rewritten to follow the WordPress Japanese Translation Style Guide
* Changed: Plugin description is now in English
* Fixed: Plan label no longer built by string concatenation — now uses a placeholder so translators can control spacing
* Added: API error strings that were missing from the translation template are now translatable

= 1.2.3 =
* Compatibility: Tested with WordPress 7.0
* No functional changes

= 1.2.2 =
* Fixed: API error messages (e.g. quota exceeded) now display correctly instead of "Unknown error"
* Changed: Primary CTA text now states that the detailed diagnosis runs on the web version
* Changed: Inline fix button text now states that improvements are made on the web version
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
