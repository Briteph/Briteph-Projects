=== PaidMembershipsPro for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com/add-on/paidmembershipspro/
LD Requires at least: 3.0
Slug: learndash-paidmemberships
Tags: integration, membership, paid memberships pro,
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 1.3.5

Integrate LearnDash LMS with Paid Memberships Pro.

== Description ==

Integrate LearnDash LMS with Paid Memberships Pro.

PaidMemberships Pro is one of the most popular free membership plugins available for WordPress with robust user statistics and reporting of membership levels. 

With this integration, you can create membership level access and associate the access levels to LearnDash courses. Customers are auto-enrolled into courses after signing-up for membership.

= Integration Features = 

* Associate membership levels to one or more courses
* Auto-expire membership levels after X amount of time
* Create trial membership levels with various payment gateways

See the [Add-on](https://learndash.com/add-on/paidmembershipspro/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog == 

= 1.3.5 =

* Added additional non validated email user check to sync with LD course access
* Updated display email confirmation message to groups associated courses
* Fixed non validated emails getting course access
* Fixed PHPDOC block value

= 1.3.4 =

* Added cron function to update active members course access in the background preventing timeout error
* Added silent course enrollment and improve cron object access update
* Updated change option and method name
* Updated membership change doesn't affect user manual course/group enrollment
* Updated change retroactive tool basis to membership rather than order
* Fixed undefined index error 
* Fixed fatal error due to undefined method call
* Fixed remove old levels course/group associations only if Multiple Memberships addon is not active
* Fixed warnings and errors

= 1.3.1 =

* Fixed compatibility issue with PHP versions less than 7.3

= 1.3.0 = 

* Added integration with LearnDash groups membership functionality
* Updated code refactor for improved documentation blocks, spacing, and function names
* Fixed untranslatable strings

= 1.2.0 =
* Added LD integration PMP submenu page and add retroactive tool button
* Added retroactive tool
* Added disable PMP protection for courses page
* Updated improve metabox saving function to require membership option
* Fixed undefined index error notice

View the full changelog [here](https://www.learndash.com/add-on/paidmembershipspro/).