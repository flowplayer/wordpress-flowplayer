/**
 * Display Settings
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd
 
 * @since    1.0.0
 */

jQuery(document).ready(function($){
// Add Splash Image
    var fp5_splash_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-splash-image', function(e){

        e.preventDefault();

        if ( fp5_splash_frame ) {
            fp5_splash_frame.open();
            return;
        }

        fp5_splash_frame = wp.media.frames.fp5_splash_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: splash_image.title,
            library: {
                type: 'image'
            },
            button: {
                text: splash_image.button
            }
        });

        fp5_splash_frame.on('select', function(){

            var media_attachment = fp5_splash_frame.state().get('selection').first().toJSON();

            $('#fp5-splash-image').val(media_attachment.url);
            CreatePreview();
        });

        fp5_splash_frame.open();
    });

// Add mp4 video
    var fp5_mp4_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-mp4', function(e){

        e.preventDefault();

        if ( fp5_mp4_frame ) {
            fp5_mp4_frame.open();
            return;
        }

        fp5_mp4_frame = wp.media.frames.fp5_mp4_frame = wp.media({
            className: 'media-frame tgm-media-frame',
            frame: 'select',
            multiple: false,
            title: mp4_video.title,
            library: {
                type: 'video/mp4'
            },
            button: {
                text:  mp4_video.button
            }
        });

        fp5_mp4_frame.on('select', function(){
            var media_attachment = fp5_mp4_frame.state().get('selection').first().toJSON();
            $('#fp5-mp4-video').val(media_attachment.url);
            CreatePreview();
        });
        fp5_mp4_frame.open();
    });

// Add webm video
    var fp5_webm_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-webm', function(e){
        e.preventDefault();

        if ( fp5_webm_frame ) {
            fp5_webm_frame.open();
            return;
        }

        fp5_webm_frame = wp.media.frames.fp5_webm_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: webm_video.title,
            library: {
                type: 'video/webm'
            },
            button: {
                text:  webm_video.button
            }
        });

        fp5_webm_frame.on('select', function(){
            var media_attachment = fp5_webm_frame.state().get('selection').first().toJSON();

            $('#fp5-webm-video').val(media_attachment.url);
            CreatePreview();
        });

        fp5_webm_frame.open();
    });

// Add ogg video
    var fp5_ogg_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-ogg', function(e){
        e.preventDefault();

        if ( fp5_ogg_frame ) {
            fp5_ogg_frame.open();
            return;
        }

        fp5_ogg_frame = wp.media.frames.fp5_ogg_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: ogg_video.title,
            library: {
                type: 'video/ogg'
            },
            button: {
                text:  ogg_video.button
            }
        });

        fp5_ogg_frame.on('select', function(){
            var media_attachment = fp5_ogg_frame.state().get('selection').first().toJSON();

            $('#fp5-ogg-video').val(media_attachment.url);
            CreatePreview();
        });

        fp5_ogg_frame.open();
    });

// Add vtt subtitles
    var fp5_webvtt_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-vtt', function(e){
        e.preventDefault();

        if ( fp5_webvtt_frame ) {
            fp5_webvtt_frame.open();
            return;
        }

        fp5_webvtt_frame = wp.media.frames.fp5_webvtt_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: webvtt.title,
            library: {
                type: 'text/vtt'
            },
            button: {
                text:  webvtt.button
            }
        });

        fp5_webvtt_frame.on('select', function(){
            var media_attachment = fp5_webvtt_frame.state().get('selection').first().toJSON();

            $('#fp5-vtt-subtitles').val(media_attachment.url);
            CreatePreview();
        });

        fp5_webvtt_frame.open();
    });

    // Update skin image according to selection
    $('#fp5-select-skin option').each(function () {
        if ($(this).is(':selected')) {
            $("." + $(this).val()).show();
        }
    });
    $("select#fp5-select-skin").change(function () {
        $("img").hide();
        $("." + $(this).val()).show();
    });

    // Create html5 preview and calculate width and height of preview
    var CreatePreview;
    $('#video video').remove();
    $(".media-url").blur(CreatePreview=function() {
        $('#video video').remove();
        $('#video').append('<video controls id="preview">' +
            '<source type="video/mp4" src="' + $('#fp5-mp4-video').val() + '"/>' +
            '<source type="video/webm" src="' + $('#fp5-webm-video').val() + '"/>' +
            '<source type="video/webm" src="' + $('#fp5-ogg-video').val() + '"/>' +
            '</video>');

        var preview = $("#preview");

        preview.bind("loadeddata", function () {
            $("#fp5-width").val(this.videoWidth);
            $("#fp5-height").val(this.videoHeight);
        });

    });

    // Check state of height box depending on aspect rato checkbox
    var ratioCheckbox = $("#fp5-aspect-ratio");

    ratioCheckbox.change(function () {
        if (ratioCheckbox.attr('checked')) {
            $('#fp5-height').removeAttr("readonly");
            $('#fp5-width').removeAttr("readonly");
            ratioCheckbox.val("true");
        } else {
            $('#fp5-height').attr("readonly", "true");
            $('#fp5-width').attr("readonly", "true");
            ratioCheckbox.val("false");
        }
    });
    window.onload = function () {
        if (ratioCheckbox.attr('checked')) {
            $('#fp5-height').removeAttr("readonly");
            $('#fp5-width').removeAttr("readonly");
        } else {
            $('#fp5-height').attr("readonly", "true");
            $('#fp5-width').attr("readonly", "true");
        }
    };


// Settings Page
// Add Logo
    var fp5_logo_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5_settings_upload_button', function(e){
        e.preventDefault();

        if ( fp5_logo_frame ) {
            fp5_logo_frame.open();
            return;
        }

        fp5_logo_frame = wp.media.frames.fp5_logo_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: logo.title,
            library: {
                type: 'image'
            },
            button: {
                text:  logo.button
            }
        });

        fp5_logo_frame.on('select', function(){
            var media_attachment = fp5_logo_frame.state().get('selection').first().toJSON();

            $('.fp5_upload_field').val(media_attachment.url);
            $('.fp5_settings_upload_preview').attr('src',media_attachment.url);
        });

        fp5_logo_frame.open();
    });
});