/**
 * JavaScript for Flowplayer Videos
 *
 * @package   Flowplayer 5 for Wordpress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      https://flowplayer.org/
 * @copyright 2013 Flowplayer Ltd

 * @since    1.0.0
 */

jQuery(document).ready(function ($) {

    // Meta Box tabs
    $(".fp5-video-meta-box div.hidden").removeClass('hidden');
    $(".fp5-video-meta-box").tabs();

    // Create html5 preview and calculate width and height of preview
    var CreatePreview;
    $('#video video').remove();
    $(".media-url").blur(CreatePreview = function () {
        $('#video video').remove();
        $('#video').append('<video controls="controls" preload="metadata" id="preview">' +
            '<source type="video/webm" src="' + $('#fp5-webm-video').val() + '"/>' +
            '<source type="video/mp4" src="' + $('#fp5-mp4-video').val() + '"/>' +
            '</video>');

        var preview = $("#preview");

        preview.bind("loadeddata", function () {
            if ( ! mode_checkbox.is( ':checked' ) ) {
                $("#fp5-width").val(this.videoWidth);
                $("#fp5-height").val(this.videoHeight);
            }
            $("#fp5-duration").val(this.duration);
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

    // Flowplayer Drive
    $(".fp5-add-drive").colorbox({
        inline: true,
        width: '100%',
        height: '100%',
        transition: "none",
        opacity: '.7'
    });
    $('.choose-video').click(function () {
        var that = $(this);
        var hlsCheckbox;
        if( that.attr('data-hls').length > 0 ){
            hlsCheckbox = true;
        } else {
            hlsCheckbox = false;
        }
        $('input#fp5-splash-image').val(that.attr('data-img'));
        $('input#fp5-mp4-video').val(that.attr('data-mp4'));
        $('input#fp5-hls-video').val(that.attr('data-hls'));
        $('input#fp5-webm-video').val(that.attr('data-webm'));
        $('input#fp5-flash-video').val(that.attr('data-flash'));
        $('input#fp5-user-id').val(that.attr('data-user-id'));
        $('input#fp5-video-id').val(that.attr('data-video-id'));
        $('input#fp5-video-name').val(that.attr('data-video-name'));
        $('input#fp5-data-rtmp').val(that.attr('data-rtmp'));
        $('input#fp5-qualities').val(that.attr('data-qualities'));
        $('input#fp5-default-quality').val(that.attr('data-default-quality'));
        $("input#fp5-hls-plugin").prop('checked',hlsCheckbox);
        $("input[name='post_title']").val(that.attr('data-video-name'));
        $("#title-prompt-text").addClass('screen-reader-text');
        $.colorbox.close();
        CreatePreview();
    });

    // Add Splash Image
    var fp5_splash_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-splash-image', function (e) {

        e.preventDefault();

        if ( fp5_splash_frame ) {
            fp5_splash_frame.open();
            return;
        }

        fp5_splash_frame = wp.media.frames.fp5_splash_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_splash_image.title,
            library: {
                type: 'image'
            },
            button: {
                text: fp5_splash_image.button
            }
        });

        fp5_splash_frame.on('select', function () {

            var media_attachment = fp5_splash_frame.state().get('selection').first().toJSON();

            $('#fp5-splash-image').val(media_attachment.url);
            CreatePreview();
        });

        fp5_splash_frame.open();
    });

    // Add mp4 video
    var fp5_mp4_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-mp4', function (e) {

        e.preventDefault();

        if ( fp5_mp4_frame ) {
            fp5_mp4_frame.open();
            return;
        }

        fp5_mp4_frame = wp.media.frames.fp5_mp4_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_mp4_video.title,
            library: {
                type: 'video/mp4'
            },
            button: {
                text: fp5_mp4_video.button
            }
        });

        fp5_mp4_frame.on('select', function () {
            var media_attachment = fp5_mp4_frame.state().get('selection').first().toJSON();
            $('#fp5-mp4-video').val(media_attachment.url);
            CreatePreview();
        });
        fp5_mp4_frame.open();
    });

    // Add webm video
    var fp5_webm_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-webm', function (e) {
        e.preventDefault();

        if ( fp5_webm_frame ) {
            fp5_webm_frame.open();
            return;
        }

        fp5_webm_frame = wp.media.frames.fp5_webm_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_webm_video.title,
            library: {
                type: 'video/webm'
            },
            button: {
                text: fp5_webm_video.button
            }
        });

        fp5_webm_frame.on('select', function(){
            var media_attachment = fp5_webm_frame.state().get('selection').first().toJSON();

            $('#fp5-webm-video').val(media_attachment.url);
            CreatePreview();
        });

        fp5_webm_frame.open();
    });

    // Add flash video
    var fp5_flash_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-flash', function (e) {

        e.preventDefault();

        if ( fp5_flash_frame ) {
            fp5_flash_frame.open();
            return;
        }

        fp5_flash_frame = wp.media.frames.fp5_flash_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_flash_video.title,
            library: {
                type: ['video/mp4', 'video/x-flv']
            },
            button: {
                text: fp5_flash_video.button
            }
        });

        fp5_flash_frame.on('select', function () {
            var media_attachment = fp5_flash_frame.state().get('selection').first().toJSON();
            $('#fp5-flash-video').val(media_attachment.url);
            CreatePreview();
        });
        fp5_flash_frame.open();
    });

    // Add hls video
    var fp5_hls_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-hls', function (e) {

        e.preventDefault();

        if ( fp5_hls_frame ) {
            fp5_hls_frame.open();
            return;
        }

        fp5_hls_frame = wp.media.frames.fp5_hls_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_hls_video.title,
            library: {
                type: ['application/x-mpegurl']
            },
            button: {
                text: fp5_hls_video.button
            }
        });

        fp5_hls_frame.on('select', function () {
            var media_attachment = fp5_hls_frame.state().get('selection').first().toJSON();
            $('#fp5-hls-video').val(media_attachment.url);
            switchHLSCheckbox(media_attachment.url);
            CreatePreview();
        });
        fp5_hls_frame.open();
    });

    // Add vtt subtitles
    var fp5_webvtt_frame;

    $(document.body).on('click.fp5OpenMediaManager', '.fp5-add-vtt', function (e) {
        e.preventDefault();

        if ( fp5_webvtt_frame ) {
            fp5_webvtt_frame.open();
            return;
        }

        fp5_webvtt_frame = wp.media.frames.fp5_webvtt_frame = wp.media({
            className: 'media-frame fp5-media-frame',
            frame: 'select',
            multiple: false,
            title: fp5_webvtt.title,
            library: {
                type: 'text/vtt'
            },
            button: {
                text: fp5_webvtt.button
            }
        });

        fp5_webvtt_frame.on('select', function(){
            var media_attachment = fp5_webvtt_frame.state().get('selection').first().toJSON();

            $('#fp5-vtt-subtitles').val(media_attachment.url);
            CreatePreview();
        });

        fp5_webvtt_frame.open();
    });

    // Check HLS if the setting has not been set. For existing videos that do not have the setting set.
    if ($(".fp5-hls-notset").length > 0){
        switchHLSCheckbox($("#fp5-hls-video").val());
    }

});

// Check if HLS supports CORS
var createCORSRequest = function(method, url) {
  var xhr = new XMLHttpRequest();
  if ("withCredentials" in xhr) {
    // Most browsers.
    xhr.open(method, url, true);
  } else if (typeof XDomainRequest != "undefined") {
    // IE8 & IE9
    xhr = new XDomainRequest();
    xhr.open(method, url);
  } else {
    // CORS not supported.
    xhr = null;
  }
  return xhr;
};

var switchHLSCheckbox = function(url) {
  var checkbox = document.getElementById("fp5-hls-plugin");
  if( url.length > 0 ) {
    var method = 'GET';
    var xhr = createCORSRequest(method, url);

    xhr.onload = function() {
      checkbox.checked = true;
    };

    xhr.onerror = function() {
      checkbox.checked = false;
    };

    xhr.send();
  } else {
      checkbox.checked = false;
  }
};
