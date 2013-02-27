<?php

function fp5_postOptionsForm() {
    ?>
    <div class="options" xmlns="http://www.w3.org/1999/html">
        <div class="optgroup">
            <label for="fp5_selectSkin">
                <?php _e('Select skin')?>
            </label>
            <select id="fp5_selectSkin" class="option">
                <option class="fp5[skin]" id="fp5_minimalistSel" value="minimalist" selected="selected">Minimalist</option>
                <option class="fp5[skin]" id="fp5_functionalSel" value="functional">Functional</option>
                <option class="fp5[skin]" id="fp5_playfulSel" value="playful">Playful</option>
            </select>
            <div class="option">
                <img id="fp5_minimalist" src="<?php print(FP5_PLUGIN_URL.'assets/img/minimalist.png')  ?>" />
                <img id="fp5_functional" src="<?php print(FP5_PLUGIN_URL.'assets/img/functional.png')  ?>" />
                <img id="fp5_playful" src="<?php print(FP5_PLUGIN_URL.'assets/img/playful.png')  ?>" />
            </div>
        </div>
        <div class="optgroup separated">
            <label for="fp5_videoAttributes">
                <?php _e('Video attributes')?> <a href="http://flowplayer.org/docs/index.html#video-attributes" target="_blank"><?php _e('(Info)')?></a>
            </label>
            <div class="wide"></div>
            <div id="fp5_videoAttributes" class="option">
                <label for="fp5_autoplay"><?php _e('Autoplay?')?></label>
                <input type="checkbox" name="fp5[autoplay]" id="fp5_autoplay" value="true" />
            </div>
            <div class="option">
                <label for="fp5_loop"><?php _e('Loop?')?></label>
                <input type="checkbox" name="fp5[loop]" id="fp5_loop" value="true" />
            </div>
        </div>

        <div class="optgroup">
            <div class="option wide">
                <label for="fp5_splash">
                    <a href="http://flowplayer.org/docs/index.html#splash" target="_blank"><?php _e('Splash image')?></a><br/><?php _e('(optional)')?>
                </label>
                <input class="mediaUrl" type="text" name="fp5[splash]" id="fp5_splash" />
                <input id="fp5_chooseSplash" type="button" value="<?php _e('Media Library'); ?>" />
            </div>
        </div>

        <div class="optgroup separated">
            <label class="head" for="fp5_videos">
                <?php _e('URLs for videos, at least one is needed. You need a video format supported by your web browser, otherwise the preview below does not work.')?>
                <a href="http://flowplayer.org/docs/#video-formats" target="_blank"><?php _e('About video formats')?></a>.
            </label>
            <div id="fp5_videos" class="option wide">
                <label for="fp5_webm"><?php _e('webm')?></label>
                <input class="mediaUrl" type="text" name="fp5[webm]" id="fp5_webm" />
                <input id="fp5_chooseMedia" type="button" value="<?php _e('Media Library'); ?>" />
            </div>
            <div class="option wide">
                <label for="fp5_mp4"><?php _e('mp4')?></label>
                <input class="mediaUrl" type="text" name="fp5[mp4]" id="fp5_mp4" />
            </div>
            <div class="option wide">
                <label for="fp5_ogg"><?php _e('ogg')?></label>
                <input class="mediaUrl" type="text" name="fp5[ogg]" id="fp5_ogg" />
            </div>
        </div>

        <div class="optgroup">
            <div class="option">
                <div id="preview" class="preview">Preview</div>
            </div>
            <div class="details separated">
                <label for="fp5_width"><?php _e('Maximum dimensions for the player are determined from the provided video files. You can change
                this size below. Fixing the player size disables scaling for different screen sizes.')?></label>
                <div class="wide"></div>
                <div class="option">
                    <label for="fp5_width"><?php _e('Max width')?></label>
                    <input class="small" type="text" id="fp5_width" name="fp5[width]" />
                </div>
                <div class="option">
                    <label class="checkbox" for="fp5_ratio"><?php _e('Use video\'s aspect ratio')?></label>
                    <input class="checkbox" type="checkbox" id="fp5_ratio" name="fp5[ratio]" value="true" checked="checked"/>
                </div>
                <div class="option">
                    <label for="fp5_height"><?php _e('Max height')?></label>
                    <input class="small" type="text" id="fp5_height" name="fp5[height]" readonly="true"/>
                </div>
                <div class="option">
                    <label class="checkbox" for="fp5_fixed"><?php _e('Use fixed player size')?></label>
                    <input class="checkbox" type="checkbox" id="fp5_fixed" name="fp5[fixed]" value="true" />
                </div>
            </div>
        </div>

        <div class="optgroup separated">
            <label class="head" for="fp5_subtitles">
                <?php _e('You can include subtitles by supplying an URL to a WEBVTT file')?>
                <a href="http://flowplayer.org/docs/subtitles.html" target="_blank">Visit the subtitles documentation</a>.
            </label>
            <div class="option wide">
                <label class="head" for="fp5_subtitles">
                    <?php _e('WEBVTT URL: ')?>
                </label>
                <input class="mediaUrl" type="text" name="fp5[subtitles]" id="fp5_subtitles" />
            </div>
        </div>

        <div class="option wide">
            <input class="button-primary" id="fp5_sendToEditor" type="button" value="<?php _e('Send to Editor &raquo;'); ?>" />
        </div>
    </div>
<?php
}

function fp5_globalOptionsPage() {
    // Retrieve plugin configuration options from database
    $options = get_option('fp5_options');
    ?>
    <div id="fp5_global" class="wrap">
        <h2>Flowplayer global options</h2>
        <form method="post" action="admin-post.php">
            <input type="hidden" name="action"
                   value="fp5_saveGlobalOptions" />

            <?php wp_nonce_field('fp5'); ?>

            <div class="options">
                <div clss="optgroup separated">
                    <label class="head" for="fp5_licenseKey">
                        <?php _e('Commercial version & logo: Commercial version removes the Flowplayer logo and allows you to use your own logo image.
                You can purchase a license and obtain a license key in ')?>
                        <a href="http://flowplayer.org/download/" target="_blank">flowplayer.org</a>.
                    </label>
                    <div class="option wide" id="fp5_licenseKey">
                        <label for="key"><?php _e('License key: ')?></label>
                        <input type="text" id="key" name="key" value="<?php echo esc_html($options['key']); ?>"/>
                    </div>
                    <div class="option wide">
                        <label for="logo"><?php _e('Logo URL')?></label>
                        <input class="mediaUrl" type="text" name="logo" id="logo"  value="<?php echo esc_html($options['logo']); ?>" />
                        <input id="fp5_chooseLogo" type="button" value="<?php _e('Media Library'); ?>" />
                    </div>
                    <div class="option wide">
                        <label class="checkbox" for="logoInOrigin">
                            <?php _e('Show logo also in the origin site')?>
                        </label>
                        <input class="checkbox" type="checkbox" id="logoInOrigin" name="logoInOrigin" value="true" <?php echo $options['logoInOrigin'] ? "checked=\"true\"" : ""?> />
                        <p class="checkpox">
                            <?php _e('Uncheck this and the logo is only shown in ')?>
                            <a href="http://flowplayer.org/docs/#custom-logo" target="_blank"><?php _e('virally embedded players')?></a>.
                        </p>
                    </div>
                </div>

                <div class="optgroup separated">
                    <label class="head" for="fp5_analytics">
                        <?php _e('You can track video traffic using Google Analytics (GA). Specify your GA account ID here.')?>
                        <a href="http://flowplayer.org/docs/analytics.html" target="_blank">Visit flowplayer.org for more info</a>.
                    </label>
                    <div class="option wide" id="fp5_analytics">
                        <label for="ga_accountId"><?php _e('GA account: ')?></label>
                        <input type="text" id ="ga_accountId" name="ga_accountId" value="<?php echo esc_html($options['ga_accountId']); ?>"/>
                    </div>
                </div>
            </div>

            <input type="submit" value="Submit" class="button-primary"/>
        </form>
    </div>
<?php
}

function fp5_initGlobalOptions() {
    $options = get_option('fp5_options');
    if ($options == false) {
        $new_options['ga_accountId'] = "";
        $new_options['key'] = "";
        $new_options['logo'] = "";
        $new_options['version'] = FP5_PLUGIN_VERSION;
        $new_options['logoInOrigin'] = true;
        add_option('fp5_options', $new_options);
    } else {
        if (! isset($options['logoInOrigin'])) {
            $options['logoInOrigin'] = true;
            update_option('fp5_options', $options);
        }
    }
}

function fp5_saveGlobalOptions() {

    if (! current_user_can('manage_options')) {
        wp_die('Not allowed');
    }

    // Check the nonce field
    check_admin_referer('fp5');

    // Retrieve original plugin options array
    $options = get_option('fp5_options');

    if(isset($_POST['logoInOrigin']) && $_POST['logoInOrigin'] == "true") {
        $options['logoInOrigin'] = true;
    } else {
        $options['logoInOrigin'] = false;
    }

    foreach ( array('ga_accountId', 'logo', 'key') as $option_name ) {
        if ( isset( $_POST[$option_name] ) ) {
            $options[$option_name] = sanitize_text_field( $_POST[$option_name] );
        }
    }
    // Store updated options array to database
    update_option('fp5_options', $options);

    // Redirect the page to the configuration form that was processed
    wp_redirect(add_query_arg('page', 'fp5_options', admin_url( 'options-general.php' ) ) );
    exit;
}

function fp5_handleAdminMenu() {
    // meta boxes are shown when writing a post. We provide one for easy entry of params to be used in the Flowplayer shortcode.
    add_meta_box('flowplayer5', 'Add Flowplayer to post', 'fp5_postOptionsForm', 'post', 'normal');
    add_meta_box('flowplayer5', 'Add Flowplayer to page', 'fp5_postOptionsForm', 'page', 'normal');

    // an admin page for specifying global options
    add_options_page('Flowplayer global options', 'Flowplayer', 'manage_options', 'fp5_options', 'fp5_globalOptionsPage');
}

function fp5_enqueueAdminScripts()
{
    wp_enqueue_script('fp5_admin', FP5_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), '1.0.0');

    // for showing the Media Library in a Thickbox
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}

function fp5_adminHead () {
    if ($GLOBALS['editing']) {
        fp5_enqueueAdminScripts();
    }
}

function fp5_globalSettingsHead() {
    fp5_enqueueAdminScripts();
}

function fp5_adminStyles() {
    wp_enqueue_style('fp5_admin', FP5_PLUGIN_URL.'assets/css/admin.css');
    wp_enqueue_style('thickbox');
}

function fp5_adminInit() {
    add_action('admin_post_fp5_saveGlobalOptions', 'fp5_saveGlobalOptions');
}

add_action('admin_init', 'fp5_adminInit');
add_action('admin_init', 'fp5_globalSettingsHead');

add_action('admin_menu', 'fp5_handleAdminMenu');
add_filter('admin_print_scripts', 'fp5_adminHead');
add_action('admin_print_styles', 'fp5_adminStyles');


/*
 * Hack the Media Library button text
 */
function fp5_optionsSetup() {
    global $pagenow;
    if ( 'media-upload.php' == $pagenow ) {
        // Now we'll replace the 'Insert into Post Button' inside Thickbox
        add_filter( 'gettext', 'fp5_replaceThickboxText'  , 1, 3 );
    }
}

add_action( 'admin_init', 'fp5_optionsSetup' );

function fp5_replaceThickboxText($translated_text, $text, $domain) {
    if ('Insert into Post' == $text) {

        if (strpos(wp_get_referer(), 'fp5_logo') != '' ) {
            return __('Use as logo', 'fp5');
        }
        if (strpos(wp_get_referer(), 'fp5_splash') != '' ) {
            return __('Use as splash', 'fp5');
        }
        if (strpos(wp_get_referer(), 'fp5_video') != '' ) {
            return __('Use with Flowplayer', 'fp5');
        }
    }
    return $translated_text;
}

?>