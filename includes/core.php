<?php
add_shortcode("flowplayer", "fp5_shortcode");

function fp5_shortcode($atts) {
    extract(shortcode_atts(array('mp4' => '', 'webm' => '', 'ogg' => '', 'skin' => 'minimalist', 'splash' => '',
        'autoplay' => 'false', 'loop' => 'false', 'subtitles' => '', 'width' => '', 'height' => '', 'fixed' => 'false'), $atts));

    $options = get_option('fp5_options');
    $key = $options['key'];
    $logo = $options['logo'];
    $analytics = $options['ga_accountId'];
    $logoInOrigin = $options['logoInOrigin'];

    fp5_printScripts($key, $skin);

    $out = '<script>';

//    $out =
//        '<script>
//            jQuery("head").append( jQuery(\'<link id="'. $skin .'Link" rel="stylesheet" type="text/css" />\').attr("href", "http://releases.flowplayer.org/'.FP5_FLOWPLAYER_VERSION.'/skin/' . $skin . '.css") );';

    if ($splash != '') {
        $out .= '
            jQuery(function() {
                jQuery(".flowplayer").css("background", "url('.$splash.') center no-repeat");
            });
        ';
    }

    if ($key != '' && $logoInOrigin) {
        $out .= 'jQuery("head").append(jQuery(\'<style>.flowplayer .fp-logo { display: block; opacity: 1; }</style>\'));';
    }

    $ratio = 0;
    if ($width != '' && $height != '') {
        $ratio = intval($height) / intval($width);
    }

    $out .= '
        </script>
        <div ';
    if ($fixed == 'true' && $width != '' && $height != '') {
        $out .= 'style="width:'.$width.'px;height:'.$height.'px;" ';
    }
    if ($fixed == 'false' && $width != '') {
        $out .= 'style="max-width:'.$width.'px" ';
    }
    $out .=
        'class="flowplayer ' . $skin . ($splash != "" ? " is-splash" : "") . '"' .
        ($key != '' ? ' data-key="'.$key.'"' : '') .
        ($key != '' && $logo != '' ? ' data-logo="'.$logo.'"' : '') .
        ($analytics != '' ? ' data-analytics="'.$analytics.'"' : '') .
        ($ratio != 0 ? ' data-ratio="'.$ratio.'"' : '') .
        '><video' .
        fp5_getopt($autoplay, "autoplay") .
        fp5_getopt($loop, "loop") .
        '>';
    $out .= fp5_getVideoLink($mp4);
    $out .= fp5_getVideoLink($webm);
    $out .= fp5_getVideoLink($ogg);
    $out .= $subtitles != "" ? '<track src="'.$subtitles.'"/>' : '';
    $out .=
        '</video>
        </div>';
    return $out;
}

function fp5_getopt($var, $option) {
    return ($var == 'true' ? " ".$option : "");
}

function fp5_getVideoLink($type) {
    if ($type == '') return '';
    return '<source type="video/mp4" src="' . $type . '" />';
}

function fp5_printScripts($key, $skin) {
    wp_enqueue_style("fp_skins", "http://releases.flowplayer.org/".FP5_FLOWPLAYER_VERSION."/skin/all-skins.css");

    wp_register_script('fp5_embedder', "http://releases.flowplayer.org/".FP5_FLOWPLAYER_VERSION."/".($key != '' ? "commercial/" : "")."flowplayer.min.js", array('jquery'), null, true);
    wp_print_scripts('fp5_embedder');
}

?>