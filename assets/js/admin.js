/**
 * Handle: fp5Admin
 * Version: 0.0.1
 * Deps: jquery
 * Enqueue: true
 */

var Flowplayer5Admin = function () {};

Flowplayer5Admin.prototype = {
    options: {},
    aspectRatio: 0,

    generateShortCode: function () {
        var attrs = '';
        jQuery.each(this['options'], function(name, value){
            if (value != '') {
                attrs += ' ' + name + '="' + value + '"';
            }
        });
        return '[flowplayer' + attrs + ']';
    },

    getName: function (val) {
        return val.substring(4, val.length - 1);
    },

    sendToEditor: function (form) {
        var collection = jQuery(form).find("option[id^=fp5],input[id^=fp5]:not(input:checkbox),input[id^=fp5]:checkbox:checked");
        var $this = this;
        collection.each(function () {
            if (this.name) {
                var name = $this.getName(this.name);
                $this['options'][name] = this.value;
            }
        });

        // handle skin dropdown selection separately
        var selectedSkinElem = jQuery("#fp5_selectSkin option:selected");
        var name = $this.getName(selectedSkinElem.attr("class"));
        $this['options'][name] = selectedSkinElem.val();

        delete $this['options'].ratio;
        send_to_editor(this.generateShortCode());
        return false;
    },

    chooseMediaFor: function (type, target, field) {
        var orig_send_to_editor = window.send_to_editor;
        window.send_to_editor = this.mediaSelectedHandler(type, field);

        tb_show('', 'media-upload.php?type=' + type + '&amp;fp5_target=' + target + '&amp;TB_iframe=true');

        //restore send_to_editor() when tb closed
        jQuery("#TB_window").bind('tb_unload', function () {
            window.send_to_editor = orig_send_to_editor;
        });
        return false;
    },

    showPreview: function () {
        var $this = this;

        function createVideoSource(field, type) {
            var src = jQuery(field).val();
            if (! src) return '';
            return '<source src="' + src + '" type="' + type + '" />';
        }

        var html = "";
        html += '<video id="fp5_videoPreview" width="320" height="240" controls="controls">';
        html += createVideoSource("#fp5_webm", "video/webm");
        html += createVideoSource("#fp5_mp4", "video/mp4");
        html += createVideoSource("#fp5_ogg", "video/ogg");
        html += '</video>';
        var preview = jQuery(html);

        preview.bind("loadedmetadata", function () {
            jQuery("#fp5_width").val(this.videoWidth);
            jQuery("#fp5_height").val(this.videoHeight);
            jQuery("#fp5_sendToEditor").removeAttr("disabled");
            $this.aspectRatio = this.videoHeight / this.videoWidth;
        });

        preview.appendTo(jQuery("#preview"));
        jQuery("#preview").css("background-color", 'transparent');
    },

    mediaSelectedHandler: function (type, field) {
        return function (html) {
            var url = type == 'image' ? jQuery('img', html).attr('src') : jQuery(html).attr('href');
            if (! url) return;

            function hasExtension(choices) {
                for (var i = 0; i < choices.length; i++) {
                    if (url.indexOf(choices[i]) > 0) {
                        return true;
                    }
                }
                return false;
            }

            if (hasExtension([".webm"])) {
                jQuery("#fp5_webm").val(url);
            } else if (hasExtension([".ogg"])) {
                jQuery("#fp5_ogg").val(url);
            } else if (hasExtension([".mp4", ".mov", ".mpeg"])) {
                jQuery("#fp5_mp4").val(url);
            } else if (field) {
                field.val(url);
            }

            tb_remove();

            if (type == "video") {
                fp5Admin.showPreview();
            }
        };
    },

    getScaledHeight: function () {
        return parseInt(this.aspectRatio * jQuery("#fp5_width").val());
    }
};

var fp5Admin = new Flowplayer5Admin();

jQuery(document).ready(function () {
    jQuery('#fp5_chooseSplash').click(function () {
        fp5Admin.chooseMediaFor('image', 'fp5_splash', jQuery("#fp5_splash"));
    });
    jQuery('#fp5_chooseMedia').click(function () {
        fp5Admin.chooseMediaFor('video', 'fp5_video');
    });
    jQuery('#fp5_chooseLogo').click(function () {
        fp5Admin.chooseMediaFor('image', 'fp5_logo', jQuery("#logo"));
    });
    jQuery('#fp5_sendToEditor').click(function () {
        fp5Admin.sendToEditor(this.form);
    });

    jQuery("#fp5_ogg, #fp5_webm, #fp5_mp4").change(function () { fp5Admin.showPreview() });

    jQuery("#fp5_functional, #fp5_playful").css("display", "none");
    jQuery("#fp5_selectSkin").change(function () {
        var selected = jQuery("#fp5_selectSkin option:selected")[0].id;
        jQuery("#fp5_functional, #fp5_playful, #fp5_minimalist").css("display", "none");
        console.log(selected.substr(0, selected.length - 3));
        jQuery("#" + selected.substr(0, selected.length - 3)).css("display", "block");
    });

    var ratioCheckbox = jQuery("#fp5_ratio");
    ratioCheckbox.change(function () {
        if (ratioCheckbox.attr("checked")) {
            jQuery('#fp5_height').attr("readonly", "true");
            jQuery("#fp5_height").val(fp5Admin.getScaledHeight());
            ratioCheckbox.val("true");
        } else {
            jQuery('#fp5_height').removeAttr("readonly");
            ratioCheckbox.val("false");
        }
    });

    jQuery("#fp5_width").change(function () {
        if (jQuery("#fp5_ratio").attr("checked")) {
            jQuery("#fp5_height").val(fp5Admin.getScaledHeight());
        }
    });

    jQuery("#fp5_sendToEditor").attr("disabled", "disabled");
});