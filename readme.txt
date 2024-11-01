=== Integrate Yabi for WooCommerce ===
Contributors: kakaroto84
Tags: woocommerce, electronic invoice
Donate link: https://www.paypal.me/datakun
Requires at least: 6.4
Tested up to: 6.5.2
Requires PHP: 8.0
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create your electronic invoices of purchases made in woocommerce with Yabi

== Description ==

This plugin helps you integrate WooCommerce with the Yabi platform with which you can generate your electronic invoices from completed orders.

Instructions for invoice
The fields of the invoice are the name and the consecutive number of the invoice, before the DIAN the requirement for the numbering had to be passed. Please fill in the corresponding data. Example: SETT-26.

1. Go to settings page.
2. Fill the text field "Invoice Name" with the invoice name. Example: "SETT-", "SE"
3. Fill the text field with the consecutive invoice. Example: if the consecutive number is 25, please fill the text field with 26.
4. Click in button "Save".


Instructions for client
1. To get started, contact Yabi to send you the personalized Token of your account by email.
2. Enter the Yabi system https://einvoices.yabi.co/public/login with your username and password.
3. Click on "Business Units".
4. Click on a business unit, this displays the information of the business unit.
5. Copy the code of the business unit.
6. Go to settings page.
7. Paste the previously copied code in the text field "Business Unit Uuid".
8. Copy and paste the personalized Token in the text field "Token".
9. Copy the example URL client in the text field "URL client".
10. Click in button "Save".

== Installation ==

Install Integrate Yabi for WooCommerce like you would install any other WordPress plugin.

Dashboard Method:

1. Login to your WordPress admin and go to Plugins -> Add New
2. Type \"Integrate Yabi for WooCommerce\" in the search bar and select this plugin
3. Click \"Install\", and then \"Activate Plugin\"


Upload Method:

1. Unzip the plugin and upload the \"wc-yabi\" folder to your \'wp-content/plugins\' directory
2. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==

= What does this plugin do? =

This plugin creates the invoices depending on the Woocommerce information and the information provided by the administrator

= Can invoices be canceled? =

No, You have to enter the yabi system to cancel it



== Screenshots ==
1. Where is Yabi deployed? 
2. Yabi information
3. Yabi settings
4. Order Details (Status: Completed)

== Changelog ==
= 1.0.0 =
* Initial release
= 1.1.0 =
* Fixed problem with decimal numbers in purchases of more than one item
= 1.1.1 =
* Timeout was increased as Yabi servers started experiencing service delays
= 1.2.0 =
* Update to 1.8 Version of Yabi
* Change numeric variables to strings
= 1.3.0 =
* A selection box was created to be able to select the city code
* Change default tax level code for natural person
= 1.3.1 =
* The taxscheme field was deleted, the field is no longer required
= 1.4.0 =
* Discount coupon validation
= 1.5.0 =
* Include observations to the invoice
= 1.5.1 =
* Fixed decimal problem on discount coupon
= 1.6.0 =
* In properties you can configure the account as a natural person or as a company, with the respective change the invoices will be generated depending on the type chosen
= 1.6.1 =
* Tested in last WP Version
= 1.7.0 =
* The shipping price is now taken into account in the invoice
= 1.8.0 =
* Better error report, database save mutation for debug
* Tested in last WP Version
= 2.0.0 =
* Update to 2.0 Version of Yabi
* Tested in last WP Version
= 2.0.1 =
* The field to write the URL of the connection to Yabi was enabled
= 3.0.0 =
* New implementation of plugin, now you have access to credit
= 3.0.1 =
* Fixed display position of fields and names
= 3.0.2 =
* Fixed php warning of undefined array key
= 3.0.3 =
* Fixed php warning of undefined array key when first installed
= 3.0.3.1 =
* Change required version of PHP
= 3.0.4 =
* Fixed old version display
= 3.0.5 =
* Add validation to identifier, the field must contain a positive number of at least 5 digits.