=== Flowplayer 5 for WordPress ===
Contributors: anssi, grapplerulrich
Donate link: http://flowplayer.org/download
Tags: video, html5 video, flowplayer, responsive, flowplayer5
Requires at least: 3.5
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A HTML5 responsive video player plugin. From the makers of Flowplayer.

== Description ==

The main features of this plugin are:

* One central place to mange all of your videos
* Video can be added using shortcodes [flowplayer5 id="1"]
* Skin selection with three default Flowplayer skins: Minimalist, Functional and Playful
* Show your video in any desired player size
* Playback options: autoplay, loop
* Include a [splash image](http://flowplayer.org/docs/index.html#splash) for your video
* [Google Analytics](http://flowplayer.org/docs/analytics.html) support for tracking video audience and traffic
* Video selection using the WordPress 3.5 Media Library
* Detects the video dimensions for configuring the correct player size
* Commercial Flowplayer version can be enabled by supplying a [license key](http://flowplayer.org/download)
* Use your own logo watermark images when using the commercial Flowplayer version

== Credits ==
Thank you [Tom McFarlin](http://tommcfarlin.com/) for the [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
The settings code was adapted from [Easy Digital Downloads](https://github.com/easydigitaldownloads/Easy-Digital-Downloads) by [Pippin Williamson](http://pippinsplugins.com/)
The meta box settings was adapted from [Theme Foundation](http://themefoundation.com/wordpress-meta-boxes-guide/) by [Alex Mansfield](http://sackclothstudios.com/)

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' Plugin Dashboard
2. Search for 'Flowplayer5'
3. Click 'Install Now'
4. Activate the plugin on the WordPress Plugin Dashboard

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' Plugin Dashboard
2. Navigate to the 'Upload' area
3. Select `flowplayer5.zip` from your computer
4. Upload
5. Activate the plugin on the WordPress Plugin Dashboard

= Using FTP =

1. Download `flowplayer5.zip`
2. Extract the `flowplayer5` directory to your computer
3. Upload the `flowplayer5` directory to your `wp-content/plugins` directory
4. Activate the plugin on the WordPress Plugins dashboard

Configuration

You can configure Google Analytics, a Commercial Flowplayer license key and a custom watermark logo in the plugin's
global options. You can purchase a commercial license at [flowplayer.org](http://flowplayer.org/download).

== Frequently Asked Questions ==

= Why use Flowplayer when there is video support in WordPress 3.6? =

Flowplayer 5 for WordPress provides a video management system where you can manage all of your video froma centeral place. This plugin also allows for simple customisation of the videos. We are continuasly adding further features to the plugin.

= Why are the subtitles no longer available? =

WordPress 3.6 updated jQuery to version 1.10 and included jQuery Migrate. Flowplayer 5.4.3 when using subtitles is not compatible with jQuery Migrate. When version 5.5 is released the subtitles will be added back.

= What happens when I disable the plugin? =

Nothing, other then it being disabled.

= What happens when I uninstall the plugin? =

Why would you want to do that? :-) If you do need to uninstall the plugin all of the data (Flowplayer videos and settings) will be deleted so that you do not have unnecessary data left on your database. Your media files will not be deleted. If you want to backup the Flowplayer videos that you have created you can easily export them under Tools -> Export -> Videos.

== Screenshots ==

1. Posting a video
2. Plugin Settings

== Changelog ==

= 1.1.0 =
* add extra column to show the shortcode in the overview
* add a button in the posts pages so add shortcodes easily
* fixed typos and updated pot file

= 1.0.0 =
* complete rewrite of plugin - now you can manage all of your videos in one place
* updated the Flowplayer code to version 5.4.3
* added preload option
* added CDN option
* added a few more flowplayer options
* added embed options
* removed subtitles temporarily till Flowplayer version 5.5 is released

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