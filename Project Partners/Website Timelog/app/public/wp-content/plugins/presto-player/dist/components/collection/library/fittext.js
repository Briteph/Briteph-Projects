/*!
 * FitText-UMD
 *
 * Copyright 2011, Dave Rupert http://daverupert.com
 * Released under the WTFPL license
 * http://sam.zoy.org/wtfpl/
 * Modified by Slawomir Kolodziej http://slawekk.info
 * Modified by Peace Chen to support modules
 *
 * Date: Tue Jan 12 2016 10:03:36 GMT-0600 (CST)
 */

const extend = (obj, ext) => {
  for (var key in ext) if (ext.hasOwnProperty(key)) obj[key] = ext[key];
  return obj;
};

export default (el, kompressor, options) => {
  var settings = extend(
    {
      minFontSize: -1 / 0,
      maxFontSize: 1 / 0,
      container: el,
    },
    options,
  );

  const fit = function (el) {
    const compressor = kompressor || 1;

    const resizer = function () {
      el.style.fontSize =
        Math.max(Math.min((settings?.container?.clientWidth || 0) / (compressor * 10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)) + 'px';
    };

    // Call once to set.
    resizer();

    if ('ResizeObserver' in window === false) {
      // Loads polyfill asynchronously, only if required.
      const module = import('@juggle/resize-observer').then(() => {
        window.ResizeObserver = module.ResizeObserver;
        // Bind events
        const ro = new ResizeObserver(resizer);
        ro.observe(settings.container);
      });
    } else {
      // Bind events
      const ro = new ResizeObserver(resizer);
      ro.observe(settings.container);
    }
  };

  if (el.length) for (let i = 0; i < el.length; i++) fit(el[i]);
  else fit(el);

  // return set of elements
  return el;
};
