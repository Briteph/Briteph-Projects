=== MemberPress for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com/add-on/memberpress/
LD Requires at least: 3.0
Slug: learndash-memberpress
Tags: integration, membership, memberpress,
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 2.2.2

Integrate LearnDash LMS with MemberPress.

== Description ==

Integrate LearnDash LMS with MemberPress.

MemberPress is a premium WordPress membership plugin that excels in memberships, grouping, coupons, reminders, reports, and more.

With this integration you can create membership levels in MemberPress and associate the access levels to LearnDash courses. Customers are then auto-enrolled into courses after signing-up for membership.

= Integration Features = 

* Associate membership levels to one or more courses
* Automatic removal upon membership cancellation
* Create trial membership levels with various payment gateways

See the [Add-on](https://learndash.com/add-on/memberpress/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog ==

= 2.2.2 =

* Updated get live subscription of user rather than cached version
* Updated run expire subscription access in silent queue and update access
* Fixed retroactive tool on multisite installs
* Fixed retroactive tool resetting user access counter on each run
* Fixed error when deactivating plugin
* Fixed unregistered cron events on plugin deactivation

= 2.2.1.2 =

* Fixed free trial subscriptions not enrolling users

= 2.2.1.1 =

* Updated set default retroactive process to 5 per batch
* Fixed trial subscriptions not being enrolled in courses/groups

= 2.2.1 =

* Updated make retroactive notice dismissible
* Fixed PHP 8 compatibility
* Fixed undefined index error

= 2.2.0 =

* Added dependencies check
* Added filter hook for retroactive tool per batch value
* Added LD group support in retroactive tool
* Added LearnDash group support
* Added Groups selector in membership edit page and its saving function
* Added warning notice when adding courses to a membership
* Updated use only transaction data to decide user access in retroactive tool
* Updated notice to set up server cron job if there are more than 5 courses/groups added in a membership
* Updated select2 element focus styles
* Updated change dropdown style
* Updated change courses selector to select2 select field
* Fixed incorrect string text domain resulting in untranslatable words
* Fixed cron update course access reset course enrollment date to update time instead of transaction/subscription time
* Fixed PHP warning
* Fixed retroactive tool for old subscription integration

= 2.1.1 =
* Updated process course queue update 1 at a time
* Updated make sure the returned membership associated courses value is unique
* Fixed cron update course access run in batch for transactions and subscriptions to prevent timeout error
* Fixed missing cron schedules filter parameter

View the full changelog [here](https://www.learndash.com/add-on/memberpress/).