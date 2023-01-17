=== Stripe for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com/add-on/stripe/
LD Requires at least: 3.0
Slug: learndash-stripe
Tags: integration, payment gateway, stripe
Requires at least: 5.0
Tested up to: 5.9.3
Requires PHP: 7.0
Stable tag: 1.9.3

Integrate LearnDash LMS with Stripe.

== Description ==

Integrate LearnDash LMS with Stripe.

LearnDash comes with the ability to accept payment for courses by leveraging PayPal. Using this add-on, you can quickly and easily accept payments using the Stripe payment gateway. Use it with PayPal, or just use Stripe - the choice is yours!

= Integration Features = 

* Accept payments using Stripe
* Automatic user creation and enrollment
* Compatible with built-in PayPal option
* Lightbox overlay

See the [Add-on](https://learndash.com/add-on/stripe/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog ==

= 1.9.3 =

* Added coupon support
* Added support to the new LearnDash global default currency configuration

= 1.9.2 =

* Fixed PHP notice in legacy checkout
* Fixed empty amount in transaction record for recurring payments
* Fixed incorrect label on translations page
* Fixed Stripe payment button not honoring the "active" status for the gateway to be active or inactive
* Fixed LearnDash purchase emails not sending 
* Fixed checkout not working for amounts under 10 in any currency

= 1.9.1 =

* Fixed error when the Stripe API key was not filled in

= 1.9.0 =

* Added LD 3.6 subscription trial and recurring payment limit support
* Updated Stripe PHP SDK package
* Updated don't remove course access on subscription with recurring limit and add filter hook to change its behavior
* Fixed PHP warning error when saving settings
* Fixed double account creation and timeout error

= 1.8.2 =

* Fixed typo in stripe customer id meta key name for live mode
* Fixed uncaught exception and undefined methods

= 1.8.1 =

* Added different stripe customer user meta key for test and live mode, this enables users to buy courses in both test and live mode
* Updated logged in users will always be enrolled to bought course regardless email used in Stripe checkout
* Fixed course purchase button can't be re-clicked more than once if there's error in the first try

= 1.8.0.2 = 

* Fixed load specific js-cookie version for Stripe SCA compatibility

* Load specific js-cookie version

= 1.8.0.1 = 

* Updated security review changes

= 1.8.0 =

* Updated the Stripe PHP SDK                                                                 
* Fixed syntax error                                                                                   
* Fixed 404 /customers/ Stripe error in legacy checkout                                          
* Fixed undefined property error                                                                 
* Fixed 404 error response when retrieving Stipe customer object

= 1.7.0 =

* Added dependencies check
* Added allow_promotion_codes arg in Stripe session creation API
* Updated add 'customer' arg in Stripe session API so that Stripe only create 1 customer object for each WP user
* Fixed token used more than once error on legacy checkout
* Fixed fatal error due to uncaught Stripe API Exception
* Fixed Stripe JS is loaded multiple times if there's multiple payment button on a page

View the full changelog [here](https://www.learndash.com/add-on/stripe/).