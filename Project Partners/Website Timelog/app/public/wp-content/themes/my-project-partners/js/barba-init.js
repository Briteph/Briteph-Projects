const barbaBaseUrl = window.location.host;

// create a reusable effect that swaps text
gsap.registerEffect({
  name: "removeText",
  effect: (targets, config) => {
    let tl = gsap.timeline({delay: 0});
    tl.to(targets, {opacity: 0, duration: 0.5});
    return tl;
  },
  defaults: {duration: 1}, 
  extendTimeline: true
});

gsap.registerEffect({
  name: "addText",
  effect: (targets, config) => {
    let tl = gsap.timeline({delay: 0});
    tl.add(() => targets[0].innerText = config.text);
    tl.to(targets, {opacity: 1, duration: 0.5});
    return tl;
  },
  defaults: {duration: 1}, 
  extendTimeline: true
});


barba.init({
  prefetchIgnore: true,
  cacheIgnore: true,
  prevent: ({ href }) => href && href.includes('quizzes'),
  transitions: [
    {
      name: 'basic',
      leave: function (data) {
        gsap.to(data.current.container, 0.5, {opacity: 0, onComplete: this.async(),});
      },
      enter: function (data) {
        // Remove the old container
        data.current.container.parentNode.removeChild(data.current.container);
        gsap.from(data.next.container, 0.5, {opacity: 0, onComplete: this.async(),});
      },
    },
  ],
  views: [{
    namespace: 'page-log-timesheet',
    beforeEnter(data){
      const head = document.getElementsByTagName('head')[0];

      /* let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/slimselect.js';
      head.appendChild(customScript); */

      let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/taskstate.js?ver=1.0.16';
      head.appendChild(customScript);

      customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/confetti.min.js';
      head.appendChild(customScript);

      customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/weekly-timesheet.js?ver=1.1.693';
      head.appendChild(customScript);

      customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/partner-calendar-onboarding.js';
      head.appendChild(customScript);

    },
    beforeLeave(data) {
      document.querySelectorAll('.destroy-on-leave').forEach(el => el.remove());
    },
    afterEnter(data){
      const oldScript = document.getElementById('existing-tasks-script');
      eval(oldScript.innerHTML);
    }
  }, {
    namespace: 'page-view-timesheet',
    beforeEnter(data){
      const head = document.getElementsByTagName('head')[0];

      // get HTML of new page
      const nextHtml = data.next.html;
      const parser = new DOMParser();
      const htmlDoc = parser.parseFromString(nextHtml, 'text/html');
      let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/confetti.min.js';
      head.appendChild(customScript);

      if(document.getElementById('timesheet-calendar') || !data.current.container){

        customScript = document.createElement('script');
        customScript.classList.add('destroy-on-leave');
        customScript.src= '/wp-content/themes/my-project-partners/js/approve-weekly-timesheet.js?ver=1.0.79';
        head.appendChild(customScript);
      }

      customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/approval-form.js?ver=1.0.04';
      head.appendChild(customScript);

    },
    beforeLeave(data) {
      document.querySelectorAll('.destroy-on-leave').forEach(el => el.remove());
    },
    afterEnter(data){
      const oldScript = document.getElementById('existing-tasks-script');
      if(oldScript) eval(oldScript.innerHTML);
    }
  },
  {
    namespace: 'page-sows',
    beforeEnter(data){
      const head = document.getElementsByTagName('head')[0];

      let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/js/list.js';
      head.appendChild(customScript);

    },
    beforeLeave(data) {
      document.querySelectorAll('.destroy-on-leave').forEach(el => el.remove());
    }
  },
  {
    namespace: 'page',
    beforeEnter(data){
      const head = document.getElementsByTagName('head')[0];

      let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= '/wp-content/themes/my-project-partners/template-parts/blocks/toggle/toggle.js';
      head.appendChild(customScript);
    }
  },
  {
    namespace: 'sfwd-quiz',
    beforeEnter(data){
      const head = document.getElementsByTagName('head')[0];

      let customScript = document.createElement('script');
      customScript.classList.add('destroy-on-leave');
      customScript.src= 'https://my.project.partners/wp-content/plugins/sfwd-lms/includes/lib/wp-pro-quiz/js/wpProQuiz_front.min.js';
      head.appendChild(customScript);

    },
    beforeLeave(data) {
      document.querySelectorAll('.destroy-on-leave').forEach(el => el.remove());
    }
  }],
  timeout: 35000
});

barba.hooks.leave((data) => {
  gsap.effects.removeText("#page-title");
});

barba.hooks.beforeEnter((data) => {

 if (data.current.container) {
    // only run during a page transition - not initial load
    const currentMenuItems = document.querySelectorAll(`nav ul li.current-menu-item`);
    currentMenuItems.forEach(currentMenuItem => currentMenuItem.classList.remove(`current-menu-item`));

    // get URL of new page
    const parentPath = data.next.url.path.split(`/`)[1];
    const menuUrl = parentPath.length > 0 ? `https://${barbaBaseUrl}/${parentPath}/` : `https://${barbaBaseUrl}/`;
    const navMenuItems = document.querySelectorAll(`nav ul li a[href="${menuUrl}"]`);

    // select links with this URL and mark them as currently active pages
    navMenuItems.forEach(navMenuItem => navMenuItem.parentNode.classList.add('current-menu-item'));

    // get HTML of new page
    const nextHtml = data.next.html;
    const parser = new DOMParser();
    const htmlDoc = parser.parseFromString(nextHtml, 'text/html');

    // select html
    gsap.effects.addText("#page-title", {text: htmlDoc.querySelector('title').textContent.split('– Project Partners')[0]});

    let santaScript = document.createElement('script');
    santaScript.classList.add('destroy-on-leave');
    santaScript.id = 'barba-santa-js';
    santaScript.src= '/wp-content/plugins/santa-hunt/santa-hunt.js?ver=1.2.5';
    head.appendChild(santaScript);
  }

});

barba.hooks.afterEnter((data) => {
  if (data.current.container) {
    santaInit();
  }
});

barba.hooks.after(() => {
  ga('set', 'page', window.location.pathname);
  ga('send', 'pageview');
});