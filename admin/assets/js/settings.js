/**
 * JavaScript for Settings page
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd

 * @since   1.3.0
 */

jQuery(document).ready(function($){

    // Add Logo
    var fp5_logo_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5_settings_logo_upload_button', function(e){
        e.preventDefault();

        if ( fp5_logo_frame ) {
            fp5_logo_frame.open();
            return;
        }

        fp5_logo_frame = wp.media.frames.fp5_logo_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_logo.title,
            library: {
                type: 'image'
            },
            button: {
                text: fp5_logo.button
            }
        });

        fp5_logo_frame.on('select', function(){
            var media_attachment = fp5_logo_frame.state().get('selection').first().toJSON();

            $('.fp5_logo_upload_field').val(media_attachment.url);
            $('.fp5_settings_upload_preview').attr('src',media_attachment.url);
        });

        fp5_logo_frame.open();
    });

    // Add JS
    var fp5_asf_js_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5_settings_asf_js_upload_button', function(e){
        e.preventDefault();

        if ( fp5_asf_js_frame ) {
            fp5_asf_js_frame.open();
            return;
        }

        fp5_asf_js_frame = wp.media.frames.fp5_asf_js_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_asf_js.title,
            library: {
                type: 'application/javascript'
            },
            button: {
                text: fp5_asf_js.button
            }
        });

        fp5_asf_js_frame.on('select', function(){
            var media_attachment = fp5_asf_js_frame.state().get('selection').first().toJSON();

            $('.fp5_asf_js_upload_field').val(media_attachment.url);
            $('.fp5_settings_upload_preview').attr('src',media_attachment.url);
        });

        fp5_asf_js_frame.open();
    });

    // Add VAST JS
    var fp5_vast_js_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5_settings_vast_js_upload_button', function(e){
        e.preventDefault();

        if ( fp5_asf_js_frame ) {
            fp5_asf_js_frame.open();
            return;
        }

        fp5_vast_js_frame = wp.media.frames.fp5_vast_js_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_vast_js.title,
            library: {
                type: 'application/javascript'
            },
            button: {
                text: fp5_vast_js.button
            }
        });

        fp5_vast_js_frame.on('select', function(){
            var media_attachment = fp5_vast_js_frame.state().get('selection').first().toJSON();

            $('.fp5_vast_js_upload_field').val(media_attachment.url);
            $('.fp5_settings_upload_preview').attr('src',media_attachment.url);
        });

        fp5_vast_js_frame.open();
    });

});
