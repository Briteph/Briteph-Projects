=== Gravity Forms for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com/add-on/gravity-forms/
LD Requires at least: 3.0
Slug: learndash-gravity-forms
Tags: integration, gravity forms
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 2.1.3

Integrates LearnDash LMS with Gravity Forms.

== Description ==

Integrate LearnDash LMS with Gravity Forms.

Gravity Forms is hands-down the industry's best custom form development plugin.

With this integration, you can create a completely custom course registration form and associate your online courses to it so that when users register, they are auto-enrolled.

= Integration Features = 

* Create a unique registration form
* Automatically enrolls user
* Accept payments using Gravity Forms add-ons

Note: this integration requires a Developer License from Gravity Forms.

See the [Add-on](https://learndash.com/add-on/gravity-forms/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog ==

= 2.1.3 =

* Updated improve LD GF select2 box styling
* Updated add horizontal padding to LD option input field
* Updated improve style of LD options select box in GF field choices
* Updated use entry form feed setting to make its filter hook works instead of pulling feed settings from DB
* Fixed editor CSS styles are broken because of jQuery UI base theme CSS
* Fixed undefined array index error
* Fixed incorrect property name is JS script
* Fixed JS conflict error on form edit screen
* Fixed selecting options in LD options select box for GF field choices close GF choices UI flyout

= 2.1.2 =

* Added ability to assign a LearnDash group
* Added allow conditional group enrollment based upon a form input
* Added ability to support Gravity Forms conditonal logic 
* Fixed PHP notice
* Fixed users not being enrolled when a Stripe payment field is part of the form

= 2.1.1 =

* Added payment refunded hooked function for paid form
* Added payment completed hooked function for paid form
* Added logic to bail course enrollment in user registration hook if the submitted form is a paid form
* Added paid form setting field to mark a paid form on user registration feed setting

View the full changelog [here](https://www.learndash.com/add-on/gravity-forms/).