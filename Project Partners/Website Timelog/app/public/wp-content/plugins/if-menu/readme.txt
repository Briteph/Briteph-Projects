=== If Menu - Visibility control for Menus ===
Contributors: andreiigna, elenalyrd
Tags: menu, visibility, rules, roles, hide, if, nav menu, show, display
Requires at least: 5
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 0.16.3
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display tailored menu items to each visitor with visibility rules

== Description ==

Control what menu items your site's visitors see, with visibility rules. Here are a few examples:

* Display a menu item only if `User is logged in`
* Hide menus if `Device is mobile`
* Display menus for `Admins and Editors`
* Hide Login or Register links for `Logged in Users`
* Display menus for `Users from US or UK`
* Display menus only for `Customers with active membership`
* Display menus for visitors browsing with `Language English or Spanish`

After the plugin is enabled, each menu item will have a new option “Change menu item visibility” which will enable the selection of visibility rules.

Check the examples in screenshots or try it here → [demos.layered.store](https://demos.layered.store)

## Features

* Basic set of visibility rules
  * User state `User is logged in`
  * User roles `Admin` `Editor` `Author` etc
  * Page type `Front page` `Single page` `Single post`
  * Is Archive page (year, category, search results, etc)
  * Visitor device `Is Mobile`
* Advanced visibility rules - requires [More Visibility Rules Add-on](https://layered.store/plugins/more-visibility-rules)
  * Visitor location - detect visitor's Country
  * Visitor language - detect visitor's selected Language
  * WooCommerce Subscriptions - Display menus for users with active subscription
  * WooCommerce Memberships - Display menus for customers with active membership plans
  * Groups - Detect if users are in specific groups
  * WishList Member - Detect the users' membership level
  * Restrict Content Pro - Detect the users' subscription level
* Multiple rules - mix multiple rules for a menu item visibility
  * show if `User is logged in` AND `Device is mobile`
  * show if `User is Admin` AND `Is front page`
* Support for [adding your custom rules](https://wordpress.org/plugins/if-menu/#how%20can%20i%20add%20a%20custom%20visibility%20rule%20for%20menu%20items%3F)

== Frequently Asked Questions ==

= Show or hide menus if user is logged in =

One of the most popular uses of the plugin is to show the "Register/Login" menu for non-logged-in users, and "Your account" for logged-in users.

To enable this for "Register/Login" menu, follow these steps:
1. Go to WordPress Admin on your website -> Appearance -> Menus
2. Expand the menu item for "Register" or "Login" page
3. Enable the option "Enable visibility rules"
4. Choose the rule "Hide if user logged in"

For showing the "Your account page", follow these steps:
1. Go to WordPress Admin on your website -> Appearance -> Menus
2. Expand the menu item for "Your account page" page
3. Enable the option "Enable visibility rules"
4. Choose the rule "Show if user logged in"

![image info](https://ps.w.org/if-menu/assets/screenshot-2.png)

= Mix multiple visibility rules =

Multiple visibility rules can be used at once, like so:

For showing a menu item only for admins on desktop:
1. Go to WordPress Admin on your website -> Appearance -> Menus
2. Expand the menu item you want
3. Enable the option "Enable visibility rules"
4. Choose the rule "Show if user is Administrator"
5. Click the "+" button at the end of the visibility rule, and change to "AND"
6. On the newly added row, choose "Hide if device is mobile"

For showing a menu item for Admins or users with an active subscription:
1. Go to WordPress Admin on your website -> Appearance -> Menus
2. Expand the menu item you want
3. Enable the option "Enable visibility rules"
4. Choose the rule "Show if user is Administrator"
5. Click the "+" button at the end of the visibility rule, and change to "OR"
6. On the newly added row, choose "Show if Has active subscription __"

To remove an extra visibility rule:
1. Go to WordPress Admin on your website -> Appearance -> Menus
2. Expand the menu item with multiple visibility rules
3. Click on the "AND" / "OR" buttons at end of visibility option
4. Change to "+"

= If Menu is broken, no visibility rules are available =

There’s a known limitation with adding functionality for menu items in WordPress, and conflicts may happen between some plugins and themes.

If there are multiple plugins that extend Menu Items, for example If Menu and a plugin for Menu Icons, only one of them can add the needed functionality and the other one won't work as expected.

This is an ongoing [issue with WordPress](http://core.trac.wordpress.org/ticket/18584) which hopefully will be fixed in a future release.

If the "Menus" page is blank or options for visibility rules are not displaying, there is a way to test which plugin/theme causes this conflict.
Please disable other plugins or themes until you find the one that causes the problem, and contact the respective developers.
In the message include the link to WordPress ticket about menu items http://core.trac.wordpress.org/ticket/18584 where they can see detailed info on how to fix the problem.

= Changes to menus are not saved =

This problem may happen on sites with a large number of menu items.
In most cases, this is not a limitation or problem caused by plugins or WordPress, but by the hosting server.

Your hosting provider or server limits the amount of data that can be sent to WordPress for saving in database.
The setting is named "PHP max_input_vars" and it's value should be increased, ex: `max_input_vars = 200` to `max_input_vars = 500`.
Contact your hosting provider or make the change yourself if you have access. More details can be found here https://core.trac.wordpress.org/ticket/14134

= How can I add a custom visibility rule for menu items? =

New rules can be added by any other plugin or theme.

Example of adding a new custom rule for displaying/hiding a menu item when current page is a custom-post-type.

`
// theme's functions.php or plugin file
add_filter('if_menu_conditions', 'my_new_menu_conditions');

function my_new_menu_conditions($conditions) {
  $conditions[] = array(
    'id'        =>  'single-my-custom-post-type',                       // unique ID for the rule
    'name'      =>  __('Single my-custom-post-type', 'i18n-domain'),    // name of the rule
    'condition' =>  function($item) {                                   // callback - must return Boolean
      return is_singular('my-custom-post-type');
    }
  );

  return $conditions;
}
`

= Where can I find conditional functions? =

WordPress provides [a lot of functions](https://developer.wordpress.org/themes/references/list-of-conditional-tags/) which can be used to create custom rules for almost any combination that a theme/plugin developer can think of.

== Screenshots ==

1. If Menu website demo
2. Enable visibility rules for Menu Items
3. Example of visibility rules

== Changelog ==

= 0.16.3 - 26 June 2022 =
* Added - More usage examples in plugin FAQs section
* Updated - WordPress v6 compatibility
* Updated - Integration with Restrict Content Pro plugin is improved

= 0.16.2 - 17 January 2020 =
* Fixed - Error shown about the registered REST Api endpoint
* Updated - Ensure compatibility with WordPress 5.6

= 0.16.1 - 11 April 2020 =
* Fixed - Improved compatibility with other plugins that extend menu items

= 0.16 - 1 April 2020 =
* Added - Visibility rule - Is Archive page
* Updated - Ensure compatibility with WordPress 5.4
* Updated - Improved compatibility with WooCommerce Membership/Subscription plugins

= 0.15 - 2 July 2019 =
* Updated - Texts & styles for If Menu settings page
* Fixed - PHP error that may appear for Visibility Rules saved before If Menu v0.9
