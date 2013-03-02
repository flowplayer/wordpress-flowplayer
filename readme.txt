=== Plugin Name ===
Contributors: anssi, grapplerulrich
Donate link: http://flowplayer.org/download
Tags: video, html5 video, flowplayer
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin for showing videos with FlowPlayer 5. From the makers of Flowplayer.

== Description ==

The main features of this plugin are:

* Post editor extension to easily post a video together with your WordPress post or page
* Skin selection with three default Flowplayer skins: Minimalist, Functional and Playful
* Show your video in any desired player size
* Playback options: autoPlay, loop
* Include a [splash image](http://flowplayer.org/docs/index.html#splash) for your video
* Subtitles support
* Google Analytics support for tracking video audience and traffic
* Video selection using the WordPress' Media Library
* Previews the selected video and detects the video dimensions for configuring the correct player size
* Commercial Flowplayer version can be enabled by supplying a [license key](http://flowplayer.org/download)
* Use your own logo watermark images when using the commercial Flowplayer version

== Installation ==

1. Upload the plugin folder to your /wp-content/plugins/ directory
1. Activate the plugin and configure the plugin global settings, see below for details.

Configuration

You can configure Google Analytics, a Commercial Flowplayer license key and a custom watermark logo in the plugin's
global options. You can purchase a commercial license in [flowplayer.org](http://flowplayer.org/download).

== Frequently Asked Questions ==

= Why is the "Send to Editor" button inactive? =

The button becomes active once the video preview is shown. For the preview to work you need to provide a video file that
is supported by your web browser. See here for some info on video formats on different browsers: http://flowplayer.org/docs/#video-formats

== Screenshots ==

1. Plugin global options
2. Posting a video

== Changelog ==

= 0.4 =
* fixed the new "show logo on origin site" checkbox that was introduced in version 0.3
* now possible to add several players with different skins in one post/page
* fixed: the "Send to Editor" button became non-functional if the media library window was closed without choosing media

= 0.3 =
* now in the posting UI the height of the player is calculated based on video's aspect ratio
* added option to show the logo also in the origin site, and not just only in virally embedded players

= 0.2 =
* fixed to work when this plugin is symlinked in the wp-content/plugins directory
* fixed link to plugins configuration page
* fixed player scaling, does not use a fixed player size any more
* added an option to make the player size fixed

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.4 =
* fixes a critical issue with the media library

= 0.2 =
Bugs fixed. Player size is no longer fixed: Works better on different screen sizes.

= 0.1 =
This is the first stable release.