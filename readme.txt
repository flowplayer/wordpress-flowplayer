=== Flowplayer 5 for WordPress ===
Contributors: flowplayerorg, grapplerulrich, anssi
Donate link: http://flowplayer.org/download
Tags: video, html5 video, flowplayer, responsive, flowplayer5, player
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The video player for the web. HTML5 responsive video player. From the makers of Flowplayer.

== Description ==

Flowplayer 5 for WordPress lets you manage your self hosted videos easily and dispaly them with a simply and minimalistic player. This is the official Flowplayer.org WordPress Plugin.

= Includes Flowplayer Designer =

[Video transcoding and hosting solution](http://flowplayer.org/designer/) integrated in the Flowplayer 5 for WordPress.

* Maximum browser coverage with Flowplayer 5 using MP4 and WEBM encodings
* Optimal device compatibility with 640px video width
* Rigorously optimized encoder for best results
* Maximum streaming throughout on a global video network
* Completely free with Flowplayer watermark

__The video upload in Flowplayer Designer is just the beginning…__

__Stay tuned for a BIG announcement at the end of this year!__

= Main features =

* One central place to manage all of your videos
* Video can be added using shortcodes e.g. [flowplayer5 id="123"]
* Skin selection with three default Flowplayer skins: Minimalist, Functional and Playful
* Show your video in any desired player size
* [Subtitles support](http://flowplayer.org/docs/subtitles.html)
* [Google Analytics](http://flowplayer.org/docs/analytics.html) support for tracking video audience and traffic
* Playback options: autoplay, loop
* Include a [splash image](http://flowplayer.org/docs/setup.html#splash) for your video
* Video selection using the WordPress 3.5 Media Library
* Detects the video dimensions for configuring the correct player size

= Commercial Flowplayer =

The Commercial version is free of Flowplayer branding and you can use your logo. Commercial Flowplayer version can be enabled by supplying a [license key](http://flowplayer.org/download).

== Credits ==

The plugin can also be found on [GitHub](https://github.com/flowplayer/wordpress-flowplayer).

* Thank you [Tom McFarlin](http://tommcfarlin.com/) for the [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
* The settings code was adapted from [Easy Digital Downloads](https://github.com/easydigitaldownloads/Easy-Digital-Downloads) by [Pippin Williamson](http://pippinsplugins.com/)

* The meta box settings was adapted from [Theme Foundation](http://themefoundation.com/wordpress-meta-boxes-guide/) by [Alex Mansfield](http://sackclothstudios.com/)

== Installation ==

= Installing from the WordPress dashboard =

1. Navigate to the 'Add New' plugins dashboard
2. Search for 'Flowplayer5 for WordPress'
3. Click 'Install Now'
4. Activate the plugin on the WordPress Plugin dashboard

= Uploading in the WordPress dashboard =

1. Navigate to the 'Add New' plugins dashboard
2. Navigate to the 'Upload' area
3. Select `flowplayer5.zip` from your computer
4. Upload
5. Activate the plugin on the WordPress Plugin dashboard

= Using FTP =

1. Download `flowplayer5.zip`
2. Extract the `flowplayer5` directory to your computer
3. Upload the `flowplayer5` directory to your `wp-content/plugins` directory
4. Activate the plugin on the WordPress Plugins dashboard

= Configuration =

You can configure Google Analytics, a Commercial Flowplayer license key and a custom watermark logo in the plugin's global options. You can purchase a commercial license at [flowplayer.org](http://flowplayer.org/download).

== Frequently Asked Questions ==

= Why use Flowplayer when there is video support since WordPress 3.6? =

Flowplayer 5 for WordPress provides a video management system where you can manage all of your video froma centeral place. This plugin also allows for simple customisation of the videos. We are continuasly adding further features to the plugin.

= How to load Flowplayer assets locally when using Flowplayer Commercial =

If you want to load the Flowplayer assets (JS, CSS and SWF) for your site then you can download the files from [your account](http://flowplayer.org/account/). Create a new folder `flowplayer-commercial` in `wp-content`. Place the files in this new folder. The option in the settings to use Floplayer CDN should be disabled.

= Flowplayer Designer API Issues? =

If you are unable to connect to the Flowplayer Designer API, make sure you are conected to the internet and that you are logged in. You can login in the Settings.

= Known Flowplayer Issues? =

If you are having a issue please check the [Flowplayer Known Issues page](http://flowplayer.org/docs/known-issues.html).

= What happens when I disable the plugin? =

Nothing, other then it being disabled.

= What happens when I uninstall the plugin? =

Why would you want to do that? :-) If you do need to uninstall the plugin all of the data (Flowplayer videos and settings) will be deleted so that you do not have unnecessary data left on your database. Your media files will not be deleted. If you want to backup the Flowplayer videos that you have created you can easily export them under Tools -> Export -> Videos.

= Developer Docs =

= Filters =

* [fp5_filter_set_messages()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/admin/class-flowplayer5-admin.php#L246)
* [fp5_post_type_labels()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/includes/class-flowplayer5.php#L177)
* [fp5_post_type_supports()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/includes/class-flowplayer5.php#L193)
* [fp5_post_type_rewrite()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/includes/class-flowplayer5.php#L197)
* [fp5_post_type_args()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/includes/class-flowplayer5.php#L204)
* [fp5_filter_flowplayer_data()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/frontend/class-flowplayer5-shortcode.php#L168)
* [fp5_filter_has_shortcode()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/frontend/class-flowplayer5-frontend.php#L98)

= Actions =

* [fp5_video_top()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/frontend/class-flowplayer5-shortcode.php#L170)
* [fp5_video_bottom()](https://github.com/flowplayer/wordpress-flowplayer/blob/master/frontend/class-flowplayer5-shortcode.php#L180)

= Examples =

Here are a few code examples of things that have been asked.
`/**
 * Allow flowplayer files should be loaded on the home page.
 */
function fp5_has_shortcode( $has_shortcode ) {
	$has_shortcode = is_front_page();
	return $has_shortcode;
}
add_filter( 'fp5_filter_has_shortcode', 'fp5_has_shortcode' );`

`/**
 * Change post type arg to support hierarchical format.
 */
function fp5_post_type_arg_hierarchical( $args ) {
	$args['supports']     = array( 'title', 'page-attributes' );
	$args['hierarchical'] = true;
	return $args;
}
add_filter( 'fp5_post_type_args', 'fp5_post_type_arg_hierarchical' );`

`/**
 * Display links for single video posts and activate archive page.
 */
function fp5_post_type_arg_video_post( $args ) {
	$args['public']            = true;
	$args['show_in_nav_menus'] = true;
	$args['has_archive']       = true;
	return $args;
}
add_filter( 'fp5_post_type_args', 'fp5_post_type_arg_video_post' );`

== Screenshots ==

1. Posting a video
2. Plugin Settings

== Changelog ==

We have alot of plans for this plugin. You can see some of the up and comming features in the [roadmap](https://github.com/flowplayer/wordpress-flowplayer/issues?labels=enhancement&page=1&state=open)

= 1.3.0 =
* code improvements
* added a few extra filters
* updated [FAQ](http://wordpress.org/plugins/flowplayer5/faq/) with more code documention

= 1.2.0 =
* added functionality to fetch videos from Flowplayer Designer directly in the admin area
* added a few filters and actions
* reorganisations of files and folders
* update to Flowplayer 5.4.4
* enable subtitle after being disabled in version 1.0.0

= 1.1.0 =
* added an extra column to show the shortcode in the overview
* added a button in the posts pages so add shortcodes easily
* fixed typos and updated pot file

= 1.0.0 =
* complete rewrite of plugin - now you can manage all of your videos in one place
* updated the Flowplayer code to version 5.4.3
* added preload option
* added CDN option
* added a few more flowplayer options
* added embed options
* disable subtitles temporarily till Flowplayer version 5.5 is released

= 0.5.0 =
* updated the Flowplayer code to version 5.3.2
* fixed splash image sizing

= 0.4.0 =
* fixed the new "show logo on origin site" checkbox that was introduced in version 0.3
* now possible to add several players with different skins in one post/page
* fixed: the "Send to Editor" button became non-functional if the media library window was closed without choosing media

= 0.3.0 =
* now in the posting UI the height of the player is calculated based on video's aspect ratio
* added option to show the logo also in the origin site, and not just only in virally embedded players

= 0.2.0 =
* fixed to work when this plugin is symlinked in the wp-content/plugins directory
* fixed link to plugins configuration page
* fixed player scaling, does not use a fixed player size any more
* added an option to make the player size fixed

= 0.1.0 =
* Initial release

== Upgrade Notice ==

= 1.3.0 =
* code improvemnts

= 1.2.0 =
* add videos from Flowplayer Designer directly in the admin area

= 1.1.0 =
* add small new features

= 1.0.0 =
* big plugin rewrite

= 0.5.0 =
* bugs fixed

= 0.4.0 =
* fixes a critical issue with the media library

= 0.2.0 =
* Bugs fixed. Player size is no longer fixed: Works better on different screen sizes.

= 0.1.0 =
* This is the first stable release.