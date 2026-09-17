/* Cliniko embedded bookings: accept only expected resize/page messages. */
(function () {
  'use strict';

  var cfg = window.cilClinikoBookings || {};
  var allowedOrigin = cfg.origin || 'https://beverley-clinic.au1.cliniko.com';
  var iframeId = cfg.iframeId || 'cliniko-64894483';
  var maxHeight = 20000;

  window.addEventListener('message', function (event) {
    if (event.origin !== allowedOrigin) {
      return;
    }
    if (typeof event.data !== 'string') {
      return;
    }

    var iframe = document.getElementById(iframeId);
    if (!iframe) {
      return;
    }

    var resize = event.data.match(/^cliniko-bookings-resize:(\d+(?:\.\d+)?)$/);
    if (resize) {
      var height = Number(resize[1]);
      if (!isFinite(height) || height < 1) {
        return;
      }
      if (height > maxHeight) {
        height = maxHeight;
      }
      iframe.style.height = Math.round(height) + 'px';
      return;
    }

    if (/^cliniko-bookings-page(?:$|:)/.test(event.data)) {
      if (typeof iframe.scrollIntoView === 'function') {
        iframe.scrollIntoView();
      }
    }
  });
})();
