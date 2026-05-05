=== LearnDash Pay for Lesson ===
Contributors: svenl77, themekraft, buddyforms
Tags: learndash, woocommerce, lessons, pay for lesson, student
Requires at least: 3.9
Tested up to: 6.9
Stable tag: 1.0.4-beta.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

User a Custom Login and define the Login Redirect and Registration Link

== Description ==

This plugin enables you to sell LearnDash Lessons using the Woocommerce platform as payment intermediary.

---

> #### Follow Us
> [Blog](https://themekraft.com/blog/)

---

> **Powered with ❤ by [ThemeKraft](https://themekraft.com)**

---

== Installation ==

Upload the entire plugin folder to the /wp-content/plugins/ directory or install the plugin through the WordPress plugins screen directly.
Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==
= 1.0.4 - 04 May 2026 =
* Plugin Check: added the License plugin-header field and stripped hidden macOS metadata from the release.
* Plugin Check: renamed the text domain from `learndash_pfl` to `learndash-pfl` (WP requires lowercase + hyphens) and propagated the new domain across every translation call.
* Plugin Check: removed the non-existent Domain Path header.
* Plugin Check: normalised line endings to LF (was mixed CRLF/LF).
* Plugin Check: added direct file access protection (`if ( ! defined( 'ABSPATH' ) ) exit;`) to every PHP file in the plugin.
* Plugin Check: hardened the `get_course_lessons` AJAX handler — added a capability check, nonce verification, sanitised the `courses` and `productID` request parameters with `absint`/`wp_unslash`, escaped the rendered `<option>` markup with `wp_kses`, replaced the variable-text `__()` call on lesson titles with `esc_html( get_the_title() )`, and removed the public (`nopriv`) AJAX hook so anonymous visitors can't hit the endpoint.
* Plugin Check: escaped the WooCommerce-not-active admin notice with `esc_html__`.
* Plugin Check: moved the select2 inline initialiser into `admin_print_footer_scripts` and marked the select2 enqueue as `in_footer`, plus passed a localized nonce + ajaxUrl object for the lesson-fetch flow.
* Cleaned up the `lesson-type.php` template: dropped the redundant `_e( esc_html( ... ) )` and emit the cart-text via `esc_html()`.
* Updated Freemius SDK to 2.13.1.
* Tested up to WordPress 6.9.

= 1.0.3 - 07 Feb 2023 =
* Fixed issue with ajax call on lesson product page.
* Fixed jQuery error related with select2 library.
* Enabled bundle license auto activation.
* Updated trial version.

= 1.0.2 - 26 Jan 2023 =
* Fixed issue with course access after having made the purchase.
* Fixed issue in lessons prior to plugin activation.
* Fixed issue with Add to cart text on archive pages.
* Tested up to WordPress 6.1.1 

= 1.0.1 - 01 Aug 2022 =
* Fixed issue with add to cart button.
* Removed the use of PHP session variables.
* Improved AJAX call for lesson lists.
* Added default css to buy button to adapt it to Woocommerce styles.
* Tested up to WordPress 6.0.1

= 1.0.0 - 06 Jul 2022 =
* First version release.