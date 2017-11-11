/*!

   Viewport autoplay  plugin for Flowplayer HTML5

   Copyright (c) 2016-2017, Flowplayer Drive Oy

   Released under the MIT License:
   http://www.opensource.org/licenses/mit-license.php

   Requires Flowplayer HTML5 version 7.x or greater
   7514b13aead3e995cfed89f3e6f4037e760dd8fc

*/
(function() {

  var requestAnimationFrame = window.requestAnimationFrame || setTimeout;

  var extension = function(flowplayer) {
    var common = flowplayer.common
      , support = flowplayer.support;
    flowplayer(function(api, root) {
      if (!api.conf.viewportAutoplay || !support.firstframe && !support.mutedAutoplay) return;
      api.conf.autoplay = false;
      var scrollPaused = true;

      if (api.conf.muted && support.volume) {
        common.addClass(root, 'is-muted-autoplaying');
        var ap = document.createElement('div');
        ap.className = 'fp-autoplay-overlay';
        ap.innerHTML = 'Click to unmute.. <a class="pause"></a>';
        root.appendChild(ap);

        ap.addEventListener('click', function(ev) {
          ev.stopPropagation();
          ev.preventDefault();
          if (common.hasClass(ev.target, 'pause')) {
            api.toggle();
          } else {
            api.mute(false);
            common.removeClass(root, 'is-muted-autoplaying');
            if (support.mutedAutoplay) common.find('.fp-engine', root)[0].muted = false;
            root.removeChild(ap);
          }
        });
      }

      function startPlaybackIfInViewport() {
        if (isElementInViewport(root)) {
          if (support.mutedAutoplay && api.video.time < 0.3 && !api.splash) {
            common.find('.fp-engine', root)[0].muted = true;
          }
          if (scrollPaused) {
            if (api.splash) {
              api.load(null, function () {scrollPaused = false;});
            }
            else {
              api.one('resume', function () {scrollPaused = false;});
              api.resume();
            }
          }
        }
        else {
          if (api.splash) scrollPaused = true;
          else {
            // issue #9
            if (api.playing) {
              api.one('pause', function () {scrollPaused = true;});
            }
            api.pause();
          }
        }
      }

      api.on('ready', startPlaybackIfInViewport);

      flowplayer.bean.on(window, 'scroll.autoplay', function() {
        requestAnimationFrame(startPlaybackIfInViewport);
      });
    });

  };

  function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    var offset = rect.height * 0.8;
    return (
      rect.top + offset >= 0 &&
      rect.left >= 0 &&
      rect.bottom - offset <= (window.innerHeight || document.documentElement.clientHeight)  &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  if (typeof module === 'object' && module.exports) module.exports = extension;
  else if (typeof window.flowplayer === 'function') extension(window.flowplayer);

})();
