(function($) {

  //Make it work with both FP5 and FP6
  var forEach = function(col, cb) {
    if (typeof Array.prototype.forEach === 'function') return Array.prototype.forEach.call(col, cb);
    else if (!$) throw new Error('jQuery is needed for < IE9');
    $.each(col, function(i, itm) {
      cb(itm, i);
    });
  };
  var on = function(el, ev, slctr, hndlr) {
    if (typeof flowplayer.bean === 'object') return flowplayer.bean.on(el, ev, slctr, hndlr); // FP6
    return el.on(ev, slctr, hndlr);
  };
  var find = function(el, slctr) {
    if (typeof flowplayer.Sizzle === 'function') return flowplayer.Sizzle(slctr, el);
    return $(el).find(slctr);
  };
  var d = function(el, atr) {
    if (typeof el.data === 'function') return el.data(atr);
    return el.getAttribute('data-' + atr.replace(/([A-Z])/g, function($1){return "-"+$1.toLowerCase();}));
  };
  var createElement = function(tagName, attrs, innerText) {
    if (flowplayer.common) return flowplayer.common.createElement(tagName, attrs, innerText);
    return $('<' + tagName + '/>').attr(attrs).text(innerText)[0];
  };
  var remove = function(el) {
    if (flowplayer.common) return flowplayer.common.removeNode(el);
    return $(el).remove();
  };
  var removeClass = function(el, cls) {
    if (flowplayer.common) return flowplayer.common.removeClass(el, cls);
    return $(el).removeClass(cls);
  };
  var addClass = function(el, cls) {
    if (flowplayer.common) return flowplayer.common.addClass(el, cls);
    return $(el).addClass(cls);
  };

  flowplayer(function(api, root) {
    //only register once
    if (api.pluginQualitySelectorEnabled) return;
    api.pluginQualitySelectorEnabled = true;
    var explicitSrc = false;
    var canPlay = function(type) {
      var videoTag = document.createElement('video');
      return !!videoTag.canPlayType(type).replace('no', '');
    };
    if (flowplayer.support.video && (!flowplayer.support.inlineVideo || canPlay('application/x-mpegurl'))) return;
    var getQualityFromSrc = function(src, qualities) {
      var m = /-(\d+p)(\.(mp4|webm))?$/.exec(src);
      if (!m) return;
      if (qualities.indexOf(m[1]) === -1) return;
      return m[1];
    };
    if (!api.conf.qualities) return;
    api.conf.qualities = api.conf.qualities.split(',');

    on(root, 'click', '.fp-quality-selector li', function() {
      if (!/\bactive\b/.test(this.className)) {
        var currentTime = api.finished ? 0 : api.video.time,
            quality = $(this).data('quality'),
            currentQuality = Math.min(api.video.height, api.video.width) + 'p',
            isDefaultQuality = quality === api.conf.defaultQuality,
            src;
        if (currentQuality === api.conf.defaultQuality) {
          src = api.video.src.replace(/(.+?)((\.(mp4|webm)$|$))/, "$1-" + quality + "$2");
        }
        else {
          src = api.video.src.replace(/(-\d+p)?((\.(mp4|webm)$|$))/, isDefaultQuality ? "$2" : "-" + quality + "$2");
        }
        if (api.conf.rtmp) src = src.replace(api.conf.rtmp, '').replace(/^\//, '');
        api.quality = quality;
        explicitSrc = true;
        api.load(src, function() {
          //Make sure api is not in finished state anymore
          explicitSrc = false;
          api.finished = false;
          if (currentTime && !api.live) {
            api.seek(currentTime, function() {
              api.resume();
            });
          }
        });
      }
    });
    
    var findOptimalQuality = function(previousQuality, newQualities) {
      var a = parseInt(previousQuality, 0),
          ret;
      forEach(newQualities, function(quality, i) {
        if (i == newQualities.length - 1 && !ret) { // The best we can do
          ret = quality;
        }
        if (parseInt(quality) <= a && parseInt(newQualities[i+1]) > a) { // Is between
          ret = quality;
        }
      });
      return ret;
    };

    api.bind('load', function(ev, api, video) {
      if (!api.quality) return; // Let's go with default quality
      var playlistItemEl = find(root, '.fp-playlist [data-index="' + video.index + '"]')[0],
          // If has playlist and playlist has qualities set, use qualities from playlist item
          qualities = api.conf.qualities = !api.conf.playlist.length ?
                                              api.conf.qualities :
                                              api.conf.playlist[video.index].qualities || (d(playlistItemEl, 'qualities') ?
                                                                                            d(playlistItemEl, 'qualities').split(',') :
                                                                                            api.conf.qualities),
          desiredQuality = findOptimalQuality(api.quality, qualities),
          isDefaultQuality = !api.conf.playlist.length ?
                                 desiredQuality == api.conf.defaultQuality :
                                 desiredQuality == (api.conf.playlist[video.index].defaultQuality || (d(playlistItemEl, 'defaultQuality') ?
                                                                                                      d(playlistItemEl, 'defaultQuality') :
                                                                                                      api.conf.defaultQuality)),
          src = video.src.replace(/(-\d+p)?\.(mp4|webm)$/, isDefaultQuality ? ".$2" : "-" + desiredQuality + ".$2");
      if (!explicitSrc && video.src !== src) {
        ev.preventDefault();
        api.loading = false;
        api.load(src);
      }
    });
    var removeAllQualityClasses = function() {
      forEach(api.conf.qualities, function(quality) {
        removeClass(root, 'quality-' + quality);
      });
    };
    api.bind('ready', function(ev, api, video) {
      var quality = getQualityFromSrc(video.src, api.conf.qualities) || Math.min(video.height, video.width) + 'p';
      removeAllQualityClasses();
      api.quality = quality;
      addClass(root, 'quality-' + quality);
      var ui = find(root, '.fp-ui')[0];
      remove(find(ui, '.fp-quality-selector')[0]);
      if (api.conf.qualities.length < 2) return;
      var selector = createElement('ul', {'class': 'fp-quality-selector'});
      ui.appendChild(selector);
      forEach(api.conf.qualities, function(q, i) {
        selector.appendChild(createElement('li', {'data-quality': q, 'class': q == quality ? 'active': ''}, q));
      });
    });
    api.bind('unload', function() {
      removeAllQualityClasses();
      remove(find(root, '.fp-quality-selector')[0]);
    });

  });
})(window.jQuery);