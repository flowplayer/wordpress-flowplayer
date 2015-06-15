/* jshint newcap:false, undef:true, browser:true */
/* global flowplayer:false */
flowplayer(function(api, root) {
  "use strict";
  var common = flowplayer.common,
      explicitSrc = false;

  //only register once
  if (api.pluginQualitySelectorEnabled) return;
  api.pluginQualitySelectorEnabled = true;

  if (!flowplayer.support.inlineVideo) return; // No inline video 

  if (api.conf.qualities) {
    api.qualities = typeof api.conf.qualities === 'string' ? api.conf.qualities.split(',') : api.conf.qualities;
    api.defaultQuality = api.conf.defaultQuality;
  }

  flowplayer.bean.on(root, 'click', '.fp-quality-selector li', function(ev) {
    if (!/\bactive\b/.test(this.className)) {
      var currentTime = api.finished ? 0 : api.video.time,
          quality = ev.currentTarget.getAttribute('data-quality'),
          src,
          re;
      src = processClip(api.video, quality);
      api.quality = quality;
      if (!src) return;
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

  api.bind('load', function(ev, api, video) {
    api.qualities = video.qualities || api.qualities || [];
    api.defaultQuality = video.defaultQuality || api.defaultQuality;
    if (typeof api.qualities === 'string') api.qualities = api.qualities.split(',');
    if (!api.quality) return; // Let's go with default quality
    var desiredQuality = findOptimalQuality(api.quality, api.qualities),
        newClip = processClip(video, desiredQuality, !explicitSrc);
    if (!explicitSrc && newClip) {
      ev.preventDefault();
      api.loading = false;
      api.load(newClip);
    }
  });

  api.bind('ready', function(ev, api, video) {
    var quality = /mpegurl/i.test(video.type) ? 'abr' :  getQualityFromSrc(video.src, api.qualities) || Math.min(video.height, video.width) + 'p';
    removeAllQualityClasses();
    api.quality = quality;
    common.addClass(root, 'quality-' + quality);
    var ui = common.find('.fp-ui', root)[0];
    common.removeNode(common.find('.fp-quality-selector', ui)[0]);
    if (api.qualities.length < 2) return;
    var selector = common.createElement('ul', {'class': 'fp-quality-selector'});
    ui.appendChild(selector);
    if (hasABRSource(video) && canPlay('application/x-mpegurl') || api.conf.swfHls) {
      selector.appendChild(common.createElement('li', {'data-quality': 'abr', 'class': quality === 'abr' ? 'active' : ''}, 'Auto'));
    }
    api.qualities.forEach(function(q, i) {
      selector.appendChild(common.createElement('li', {'data-quality': q, 'class': q == quality ? 'active': ''}, q));
    });
  });
  api.bind('unload', function() {
    removeAllQualityClasses();
    common.removeNode(common.find('.fp-quality-selector', root)[0]);
  });


  function hasABRSource(video) {
    return video.sources.some(function(src) {
      return /mpegurl/i.test(src.type);
    });
  }


  function canPlay(type) {
    var videoTag = document.createElement('video');
    return !!videoTag.canPlayType(type).replace('no', '');
  }

  function getQualityFromSrc(src, qualities) {
    var m = /-(\d+p)(\.(mp4|webm))?$/.exec(src);
    if (!m) return;
    if (qualities.indexOf(m[1]) === -1) return;
    return m[1];
  }

  function removeAllQualityClasses() {
    if (!api.qualities || !api.qualities.length) return;
    api.qualities.forEach(function(quality) {
      common.removeClass(root, 'quality-' + quality);
    });
  }

  function findOptimalQuality(previousQuality, newQualities) {
    if (previousQuality === 'abr') return 'abr';
    var a = parseInt(previousQuality, 0),
        ret;
    newQualities.forEach(function(quality, i) {
      if (i == newQualities.length - 1 && !ret) { // The best we can do
        ret = quality;
      }
      if (parseInt(quality) <= a && parseInt(newQualities[i+1]) > a) { // Is between
        ret = quality;
      }
    });
    return ret;
  }

  function processClip(video, quality, clean) {
    var changed = false, re,
        isDefaultQuality = quality === api.defaultQuality,
        currentQuality = api.quality || Math.min(api.video.height, api.video.width) + 'p';
    if (currentQuality === api.defaultQuality) {
      re = /(.+?)((\.(mp4|webm)$|$))/;
    }
    else {
      re = /(-\d+p)?((\.(mp4|webm)$|$))/;
    }
    var newSources = video.sources.map(function(src) {
      if (quality === 'abr' || (clean && isDefaultQuality) || /mpegurl/i.test(src.type)) return src;
      var n = {
        type: src.type,
        src: src.src.replace(re, currentQuality === api.defaultQuality ? "$1-" + quality + "$2" : isDefaultQuality ? "$2" : "-" + quality + "$2")
      };
      if (n.src !== src.src) changed = true;
      return n;
    });
    var newSourcesStr = JSON.stringify(newSources);
    newSources.sort(function(a, b) {
      var re = /mpegurl/i, ret;
      if (quality === 'abr') ret = re.test(b.type) - re.test(a.type);
      else ret = re.test(a.type) - re.test(b.type);
      return ret;
    });
    changed = changed || JSON.stringify(newSources) !== newSourcesStr;
    var clip = flowplayer.extend({}, video, {
      sources: newSources
    });


    return changed ? clip : false;
  }

});