=== bbPress for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com/add-on/bbpress/
LD Requires at least: 3.0.0
Slug: learndash-bbpress
Tags: bbpress, forums, integrations
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 7.0
Stable tag: 2.2.2

Integrate LearnDash LMS with bbPress.

== Description ==

Integrate LearnDash LMS with bbPress.

bbPress is a forum plugin for WordPress with over 1.5 million downloads. This integration makes it easy for you to add forums to your courses. You can protect the forums so that only users who are enrolled into your courses are able to view content and to post new threads & topics.

= Integration Features = 

* Automatic forum access
* Public or private forums
* Multi-forum support
* Custom Access Denied messages
* Dynamic associated forum widget

See the [Add-on](https://learndash.com/add-on/bbpress/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog ==

= 2.2.2 =

* Fixed setting field labels don't point to their fields
* Fixed some formatting and improved variable names
* Fixed incorrect and refactor forum access check logic
* Fixed custom message on topic page doesn't follow forum setting

= 2.2.1 =

* Added group support ld bbpress shortcodes and widgets
* Added ld_bbpress_forum_courses shortcode tag
* Added add get forum courses function and change some styles
* Added class shortcode file and register ld_bbpress_course_forums shortcode tag
* Added get course forums html function and refactor course forums widget
* Updated refactor get forum courses widget
* Fixed undefined index error in saving post meta function
* Fixed bug that allow user access forums if forum view setting is set to any and one of course / group selectors is empty

= 2.2.0 =

* Added dependency check
* Added LearnDash group support
* Updated post limit message for group
* Fixed undefined error notices

= 2.1.1 =

* Added course forum widget
* Updated name and moved `functions.php` and `forum-widget.php`
* Updated load translation function
* Updated addon name to match the other addons
* Fixed issue with forum link not working for non logged in users
* Fixed course forums not displaying on bbp page other than forums page

View the full changelog [here](https://www.learndash.com/add-on/bbpress/).