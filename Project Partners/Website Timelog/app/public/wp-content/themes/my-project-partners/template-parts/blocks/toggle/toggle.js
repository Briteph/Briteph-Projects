document.querySelectorAll('.toggle-content').forEach(function(element) {
    element.setAttribute('data-maxheight', element.offsetHeight);
    element.classList.add('initialized');
});

function toggleExpand(element){  
    element.classList.toggle("toggled");
    toggleContent = element.nextElementSibling;
    console.log(toggleContent);

    if (element.getAttribute('aria-expanded') === 'false'){
        element.setAttribute('aria-expanded', 'true');
        element.setAttribute('aria-pressed', 'true');
        toggleContent.style.height = toggleContent.getAttribute('data-maxheight') + 'px';
    }
    else {
        element.setAttribute('aria-expanded', 'false');
        element.setAttribute('aria-pressed', 'false');
        toggleContent.style.height = '0px';
    }

  }