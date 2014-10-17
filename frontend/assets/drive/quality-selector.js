(function($) {
  flowplayer(function(api, root) {
    //only register once
    if (api.pluginQualitySelectorEnabled) return;
    api.pluginQualitySelectorEnabled = true;
    var canPlay = function(type) {
      var videoTag = document.createElement('video');
      return !!videoTag.canPlayType(type).replace('no', '');
    };
    if (flowplayer.support.video && (!flowplayer.support.inlineVideo || canPlay('application/x-mpegurl'))) return;
    var getQualityFromSrc = function(src) {
      var m = /-(\d+p)\.(mp4|webm)$/.exec(src)
      if (!m) return;
      return m[1];
    };
    if (!api.conf.qualities) return;
    api.conf.qualities = api.conf.qualities.split(',');

    root.on('click', '.fp-quality-selector li', function() {
      var currentTime = api.finished ? 0 : api.video.time,
          quality = $(this).data('quality'),
          isDefaultQuality = quality === api.conf.defaultQuality,
          src = api.video.src.replace(/(-\d+p)?\.(mp4|webm)$/, isDefaultQuality ? ".$2" : "-" + quality + ".$2");
      api.quality = quality;
      api.load(src, function() {
        //Make sure api is not in finished state anymore
        api.finished = false;
        if (currentTime) {
          api.seek(currentTime, function() {
            api.resume();
          });
        }
      });
    });
    
    var findOptimalQuality = function(previousQuality, newQualities) {
      var a = parseInt(previousQuality, 0),
          ret;
      $.each(newQualities, function(i, quality) {
        if (i == newQualities.length - 1) { // The best we can do
          ret = quality;
          return false;
        }
        if (parseInt(quality) <= a && parseInt(newQualities[i+1]) > a) { // Is between
          ret = quality;
          return false;
        }
      });
      return ret;
    };

    api.bind('load', function(ev, api, video) {
      if (!api.quality) return; // Let's go with default quality
      var playlistItemEl = root.find('.fp-playlist [data-index="' + video.index + '"]'),
          // If has playlist and playlist has qualities set, use qualities from playlist item
          qualities = api.conf.qualities = !api.conf.playlist.length ?
                                              api.conf.qualities :
                                              api.conf.playlist[video.index].qualities || (playlistItemEl.data('qualities') ?
                                                                                            playlistItemEl.data('qualities').split(',') :
                                                                                            api.conf.qualities),
          desiredQuality = findOptimalQuality(api.quality, qualities),
          isDefaultQuality = !api.conf.playlist.length ?
                                 desiredQuality == api.conf.defaultQuality :
                                 desiredQuality == (api.conf.playlist[video.index].defaultQuality || (playlistItemEl.data('defaultQuality') ?
                                                                                                      playlistItemEl.data('defaultQuality') :
                                                                                                      api.conf.defaultQuality)),
          src = video.src.replace(/(-\d+p)?\.(mp4|webm)$/, isDefaultQuality ? ".$2" : "-" + desiredQuality + ".$2");
      if (video.src !== src) {
        ev.preventDefault();
        api.loading = false;
        api.load(src);
      }
    });
    api.bind('ready', function(ev, api, video) {
      var quality = getQualityFromSrc(video.src) || Math.min(video.height, video.width) + 'p';
      $.each(api.conf.qualities, function(i, quality) {
        root.removeClass('quality-' + quality);
      });
      api.quality = quality;
      root.addClass('quality-' + quality);
      var ui = root.find('.fp-ui');
      ui.find('.fp-quality-selector').remove();
      if (api.conf.qualities.length < 2) return;
      var selector = $('<ul class="fp-quality-selector"></ul>').appendTo(ui);
      $.each(api.conf.qualities, function(i, q) {
        selector.append($('<li>' + q + '</li>').data('quality', q).toggleClass('active', q == quality));
      });
    });

  });
})(window.jQuery);