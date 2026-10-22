/* Circumcision in London. Progressive enhancement only.
   Everything below is optional: with JavaScript off the page still reads,
   navigates and submits. No third-party libraries, no render-blocking work. */
(function () {
  'use strict';

  var d = document;

  /* ---------------------------------------------------------- mobile nav */
  var burger = d.getElementById('burger');
  var mobileNav = d.getElementById('mobileNav');

  /* iOS Safari ignores overflow:hidden on body, so the page kept scrolling
     behind the open menu and jumped back to the top on close. Pin the body at
     its current offset instead, and put it back exactly where it was. */
  var lockedAt = 0;

  function setNav(open) {
    if (!burger || !mobileNav) return;
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    mobileNav.classList.toggle('is-open', open);

    if (open) {
      lockedAt = window.pageYOffset || d.documentElement.scrollTop || 0;
      d.body.style.top = -lockedAt + 'px';
      d.body.classList.add('nav-open');
    } else if (d.body.classList.contains('nav-open')) {
      d.body.classList.remove('nav-open');
      d.body.style.top = '';
      window.scrollTo(0, lockedAt);
    }
  }

  if (burger) {
    burger.addEventListener('click', function () {
      setNav(burger.getAttribute('aria-expanded') !== 'true');
    });
    mobileNav.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (!link) return;
      // release the lock before the browser starts navigating
      setNav(false);
    });
    d.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setNav(false);
    });
    // width-driven, so a resized desktop window behaves like a phone
    window.addEventListener('resize', function () {
      if (window.innerWidth > 1180) setNav(false);
    });
    window.addEventListener('orientationchange', function () { setNav(false); });
  }

  /* ------------------------------------------------------- sticky header */
  var header = d.getElementById('siteHeader');
  var toTop = d.getElementById('toTop');

  function onScroll() {
    var y = window.pageYOffset || d.documentElement.scrollTop;
    if (header) header.classList.toggle('is-stuck', y > 8);
    // back-to-top only once the visitor has actually scrolled (audit issue 02)
    if (toTop) toTop.classList.toggle('is-visible', y > window.innerHeight * 1.2);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* --------------------------------------------------------- hero video */
  /* preload="none" in the markup, so the film is never on the critical path.
     The poster is the video's own first frame, so the swap is invisible.
     Skipped entirely for reduced-motion and for devices asking to save data. */
  var hero = d.getElementById('heroVideo');
  if (hero) {
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var saveData = navigator.connection && navigator.connection.saveData;

    if (reduced || saveData) {
      hero.remove();
    } else {
      var start = function () {
        hero.load();
        var pr = hero.play();
        if (pr && pr.catch) pr.catch(function () { /* autoplay blocked: poster stays */ });
      };
      hero.addEventListener('playing', function () { hero.classList.add('is-playing'); }, { once: true });

      if ('requestIdleCallback' in window) requestIdleCallback(start, { timeout: 1200 });
      else window.addEventListener('load', start);

      // stop decoding while the hero is off screen or the tab is hidden
      if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (e.isIntersecting) { if (hero.paused) hero.play().catch(function () {}); }
            else if (!hero.paused) hero.pause();
          });
        }, { threshold: 0.05 }).observe(hero);
      }
      d.addEventListener('visibilitychange', function () {
        if (d.hidden) hero.pause();
        else if (hero.classList.contains('is-playing')) hero.play().catch(function () {});
      });
    }
  }

  /* ------------------------------------------------ click-to-play videos */
  /* Aftercare video lists render a poster link, not a <video>, so no video
     bytes load until the visitor asks. On activation the link is swapped for
     a native player with controls. Without JS the link opens the MP4. */
  function startVideo(facade) {
    if (facade.classList.contains('is-active')) return;
    var src = facade.getAttribute('data-src');
    if (!src) return;
    var link = facade.querySelector('.cil-video-facade__link');
    var error = facade.querySelector('.cil-video-facade__error');
    var video = d.createElement('video');
    video.className = 'cil-video-facade__player';
    video.controls = true;
    video.playsInline = true;
    video.preload = 'auto';
    var poster = facade.getAttribute('data-poster');
    if (poster) video.poster = poster;
    if (link && link.getAttribute('aria-label')) video.setAttribute('aria-label', link.getAttribute('aria-label').replace(/^Play video: /, ''));
    var source = d.createElement('source');
    source.src = src;
    source.type = facade.getAttribute('data-type') || 'video/mp4';
    video.appendChild(source);
    function fail() {
      facade.classList.remove('is-loading');
      facade.classList.add('has-error');
      if (error) error.hidden = false;
    }
    source.addEventListener('error', fail);
    video.addEventListener('error', fail);
    video.addEventListener('playing', function () { facade.classList.remove('is-loading'); });
    video.addEventListener('canplay', function () { facade.classList.remove('is-loading'); });
    facade.classList.add('is-active', 'is-loading');
    if (link) link.replaceWith(video); else facade.insertBefore(video, facade.firstChild);
    /* Only one aftercare video plays at a time. */
    d.querySelectorAll('.cil-video-facade__player').forEach(function (other) {
      if (other !== video && !other.paused) other.pause();
    });
    video.addEventListener('play', function () {
      d.querySelectorAll('.cil-video-facade__player').forEach(function (other) {
        if (other !== video && !other.paused) other.pause();
      });
    });
    video.focus();
    var p = video.play();
    if (p && p.catch) p.catch(function () { facade.classList.remove('is-loading'); });
  }

  d.querySelectorAll('[data-cil-video]').forEach(function (facade) {
    var link = facade.querySelector('.cil-video-facade__link');
    if (!link) return;
    link.setAttribute('role', 'button');
    link.addEventListener('click', function (e) {
      if (e.metaKey || e.ctrlKey || e.shiftKey) return;
      e.preventDefault();
      startVideo(facade);
    });
    link.addEventListener('keydown', function (e) {
      if (e.key === ' ' || e.key === 'Spacebar') {
        e.preventDefault();
        startVideo(facade);
      }
    });
  });

  /* ----------------------------------------- location map Earth-style zoom */
  /* Visual zoom over a legitimate Google Maps iframe (no tile scraping).
     prefers-reduced-motion skips the fly-in and shows the settled map. */
  (function () {
    var maps = d.querySelectorAll('[data-cil-map-zoom]');
    if (!maps.length) return;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function settle(el) {
      el.classList.add('is-settled');
      el.classList.remove('is-zooming');
    }
    if (reduce || !('IntersectionObserver' in window)) {
      maps.forEach(settle);
      return;
    }
    var mio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        el.classList.add('is-zooming');
        window.setTimeout(function () { settle(el); }, 2600);
        mio.unobserve(el);
      });
    }, { threshold: 0.35, rootMargin: '0px 0px -10% 0px' });
    maps.forEach(function (el) { mio.observe(el); });
  })();

  /* ------------------------------------------------------- scroll reveal */
  var reveals = d.querySelectorAll('[data-reveal]');
  if (!reveals.length) {
    /* nothing to do */
  } else if (!('IntersectionObserver' in window)) {
    d.documentElement.classList.add('reveal-off');
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);
        setTimeout(function () { el.classList.add('is-in'); }, delay);
        io.unobserve(el);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    reveals.forEach(function (el) { io.observe(el); });

    // safety net: anything still hidden after 3s is shown regardless
    setTimeout(function () {
      d.querySelectorAll('[data-reveal]:not(.is-in)').forEach(function (el) {
        var r = el.getBoundingClientRect();
        if (r.top < window.innerHeight) el.classList.add('is-in');
      });
    }, 3000);
  }

  /* -------------------------------------------------------- measurement */
  /* Key events the audit asks to be attributable: phone taps, WhatsApp taps,
     booking clicks, form submits. Pushed to the dataLayer, so connecting GA4
     later is a container change and not a code change. */
  window.dataLayer = window.dataLayer || [];
  function track(name, params) {
    window.dataLayer.push(Object.assign({ event: name }, params || {}));
    if (typeof window.gtag === 'function') window.gtag('event', name, params || {});
  }

  d.addEventListener('click', function (e) {
    var el = e.target.closest('[data-track]');
    if (!el) return;
    var id = el.getAttribute('data-track');
    var type = id.split('-')[0];
    track(
      type === 'call' ? 'phone_tap'
        : type === 'whatsapp' ? 'whatsapp_tap'
        : type === 'book' ? 'booking_click'
        : type === 'guide' ? 'guide_request'
        : 'link_click',
      { location: id, page: location.pathname }
    );
  });

  /* -------------------------------------------------------------- forms */
  d.querySelectorAll('form[data-form]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var status = form.querySelector('.form-status');
      var required = form.querySelectorAll('[required]');
      var bad = null;

      required.forEach(function (f) {
        if (!bad && (!f.value.trim() || (f.type === 'checkbox' && !f.checked))) bad = f;
      });

      if (bad) {
        e.preventDefault();
        bad.focus();
        if (status) { status.dataset.state = 'err'; status.textContent = form.getAttribute('data-msg-invalid') || 'Please complete the highlighted field.'; }
        return;
      }

      track('form_submit', { form: form.getAttribute('data-form'), page: location.pathname });

      // No back end is wired up yet (see README). Until one is, the form hands
      // the enquiry to the clinic inbox rather than silently doing nothing.
      if (!form.getAttribute('action')) {
        e.preventDefault();
        if (status) {
          status.dataset.state = 'ok';
          status.textContent = form.getAttribute('data-msg-sent') || 'Opening your email app so this reaches the clinic. If nothing happens, call 020 8951 3794.';
        }
        var fd = new FormData(form);
        var lines = [];
        fd.forEach(function (v, k) { if (k !== 'consent') lines.push(k + ': ' + v); });
        location.href =
          'mailto:info@beverleyclinic.co.uk?subject=' +
          encodeURIComponent('Website enquiry: ' + form.getAttribute('data-form')) +
          '&body=' + encodeURIComponent(lines.join('\n'));
      }
    });
  });
})();
