=== Open Menu - The official plugin for Open Menu ===
Contributors: openmenu
Donate link: http://openmenu.com
Tags: openmenu, restaurant, menu, restaurants, menus, open menu, dining, food
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 1.1.1

Easily create posts that are based on your Open Menu Format restaurant menu.  Fully integrates an Open Menu Format menu or menus into an existing theme.

== Description ==
This plugin allows you to easily create posts that are based on your Open Menu Format menu and thus embedding restaurant menus in any Wordpress website.  This plugin fully integrates an Open Menu Format menu or menus into an existing theme.  Widget / Menu ready themes work best.

The Open Menu Plugin is the official plugin for Open Menu and adding restaurant menus to any Wordpress website.

Features:

* Open Menu Custom Post Type
* Widgets: Restaurant Location / Specials / Cuisine Tag Cloud
* [openmenu] Shortcode
* Custom Functions
* Site wide setiings


== Detailed Features ==
Open Menu Custom Post Type: 
	Create custom posts which are menus based off of your Open Menu Format menu.  Choose what to display, how to display it and the plugin does the rest.
	
	Settings:
		Open Menu Location (URL) - This is a required field that points to your Open Menu Format menu
		
		Filter - Menu Name to display: If your Open Menu Format menu contains multiple menus (ex. Lunch / Dinner) you can choose which menu to display in your post by entering the menu name here.

		Restaurant Information: Stores basic information about the restaurant that is referenced by the menu. This is primarly used in scenarios where many restaurant menu's will be displayed.  Information, along with the excerpt, will be used to generate a single page of all menus.

		Cuisine Types: Define which cuisine type describes this restaurant.

Widgets:
	Open Menu: Location  - Displays the restaurants location and hours
	Open Menu: Specials  - Displays the menu items marked as special
	Open Menu: Tag Cloud - A tag cloud for the cuisine types

Short code:
	[openmenu]
	
	Parameters:
		omf_url         = URL pointing to the Open Menu Format menu
		display_type    = menu (only option currently available)
		menu_filter     = Will display only the menu name matching this filter
		display_columns = 1 | 2 - How many columns to display a menu in
	
		[defaults to Open Menu Option setting]

	Samples: 
		[openmenu omf_url="http://openmenu.com/menus/sample.xml"]
		[openmenu omf_url="http://openmenu.com/menus/sample.xml" display_type="menu" display_columns="1"]

Custom Functions: 
	Display a location block: openmenu_location( post_id, title );
	Display a specials block: openmenu_specials( post_id, title );

Site Wide Open Menu Settings:
	
	Look & Feel: 
		Display Type: What information will be displayed: Menu, Restaurant Information or Both
		How many columns: How many columns will be used to display a menu (1 or 2)
		Theme: only default is currently supported
	
	Wordpress Theme: 
		Show posts on homepage: Determines whether Open Menu post types are displayed on the homepage blog post listing and in the RSS feed for the website.
		Hidesidebar: Forces the sidebar of a post to be hidden.  Gives the impression of a full-width page and may be more desirable when displaying menus.
		Width override: Attempts to force the width of the post to this amount.  Can be helpful for adjusting the display on troublesome themes.
		Menu background color: Set the background color the menu will display on (defaults to white - #fff)

Icon designed by Ben Dunkle, core designer for Wordpress.org. Website: http://field2.com - Contact ben@field2.com

== Installation ==

1. Unzip the openmenu.zip file
2. Upload the entire 'openmenu' folder to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Update Site Wide options through the Settings -> Open Menu Options


== Frequently Asked Questions ==

= How do I get a menu in the Open Menu Format so I can use this awesome plugin? =

Goto: http://OpenMenu.com/about.php and read about Open Menu
Online Menu Creator: http://OpenMenu.com/creator

= How do I find out about updates to this plugin? =

Any updates will be posted on the Open Menu - http://OpenMenu.com/blog

= Can I display menus for multiple restaurants? =

Yes.  This is the main reason for using custom post types.  This allows you to create an entire Wordpress website of restaurants and menus

= Can I add a menu to a page? =

Yes.  All you need to do is use the shortcode described above.  Very simple and can be added anywhere in a page in minutes.


== Screenshots ==
1. Open Menu Overview
2. Adding/Editing a Menu
3. Open Menu Options
4. Sample Rendered Menu


== Changelog ==

= 1.1.1 =
* Fixed issue with Dashboard display

= 1.1 =
* Added Menu / Menu Group Widget
* Added auto-detection of installed plugin folder (no longer assume /openmenu folder)
* Updated Restaurant Location widget to add the Include Hours setting
* Updated Specials Widget to include Menu Name filter
* Fixed issue where empty menu group (no menu items) caused crash

= 1.0.1 =
* Initial public release


== Upgrade Notice ==

= 1.0.1 =
* Initial public release