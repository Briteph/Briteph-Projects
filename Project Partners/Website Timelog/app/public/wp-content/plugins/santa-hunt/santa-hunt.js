function santaInit(){
    const santaItems = document.querySelectorAll('.santa-item');

    const santaIntroCloseButtons = document.querySelectorAll('.close-santa-popup');

    const santaIntroPopup = document.getElementById('santa-intro-popup');

    santaItems.forEach(item => item.addEventListener('click', handleSantaItemClick));

    santaIntroCloseButtons.forEach(button => button.addEventListener('click', closePopupSanta));

    document.querySelectorAll('.modal-outer').forEach(overlay => overlay.addEventListener('click', closePopupSanta));

    if(santaIntroPopup){
        window.addEventListener('load', function () {
            var santaW = window.innerWidth , santaH = window.innerHeight,
            sizes = ["Small", "Medium", "Large"],
            types = ["round", "star", "real", "sharp", "ring"],
            snowflakes = 50;
    
            for (var i = 0; i < snowflakes; i++) {
                var snowflakeDiv = document.createElement('div');
                var sizeIndex = Math.ceil(Math.random() * 3) -1; //get random number between 0 and 2
                var size = sizes[sizeIndex]; //get random size
                var typeIndex = Math.ceil(Math.random() * 5) -1;
                var type = types[typeIndex];
                TweenMax.set(snowflakeDiv, {attr: {class: type + size + ' snowflakeTween'}, x: R(-(santaW / 2),santaW / 2), y: R(-200,-150) });
                santaIntroPopup.appendChild(snowflakeDiv);
                snowing(snowflakeDiv);
            }
            
            function snowing(element) {
            TweenMax.to(element, R(5,12), {y: santaH+100, ease: Linear.easeNone, repeat:-1, delay: -15});
            TweenMax.to(element, R(4,8), {x: '+=100', repeat: -1, yoyo: true, ease: Sine.easeInOut});
            TweenMax.to(element, R(2,8), {rotation: R(0,360), repeat: -1, yoyo: true, ease:Sine.easeInOut, delay: -5});
            };
    
            function R(min,max) {
            return min + Math.random() * (max-min)
            };
        });
    }
}

function handleSantaItemClick(e){
    const item = {
        name: e.currentTarget.dataset.name,
        niceName: e.currentTarget.dataset.nicename,
        nonce:  document.getElementById('santa-nonce').value
    };
    console.log(item);
    //fetch response
    fetch('/wp-admin/admin-ajax.php?action=santa_hunt_collect', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(item)
    }).then(response => {
        return response.json();
    }).then(jsonResponse => {
        //log response
        console.log({jsonResponse});
    });

    const santaPopup = document.getElementById('santa-popup');
    santaPopup.dataset.itemcount = Number(santaPopup.dataset.itemcount) + 1;

    santaPopup.querySelector('h3').innerText = `${item.niceName} collected 🎉`;

    santaPopup.querySelector('p').innerText = santaPopup.dataset.itemcount < 5 ? `Find ${5 - santaPopup.dataset.itemcount} more items to save Christmas!` : `Ho-ho-ho! You saved Christmas!`;

    santaPopup.querySelector(`.santa-svgs.${item.name}`).classList.add('collected');
    
    // set aria hidden to false because we opened the popup
    santaPopup.setAttribute("aria-hidden", "false");
    santaPopup.classList.add('open');

    event.currentTarget.remove();
}

function closePopupSanta(e){
    if(e.target.classList.contains('modal-outer') || e.currentTarget.classList.contains('close-santa-popup')){
        // get all modals
        const modals = document.querySelectorAll('.modal-outer');
        // remove delete confirmation if needed

        // close all modals
        modals.forEach(modal => {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        });
        if(document.querySelectorAll('.snowflakeTween').length > 0){
            gsap.killTweensOf('.snowflakeTween');
            const santaIntroPopup = document.getElementById('santa-intro-popup');
            if(santaIntroPopup) santaIntroPopup.remove();
        }
    }
}

santaInit();