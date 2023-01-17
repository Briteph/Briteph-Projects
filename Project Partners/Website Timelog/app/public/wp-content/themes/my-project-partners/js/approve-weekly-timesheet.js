// create state
var state = [];


//grab cancel buttons
var closeButtons = document.querySelectorAll('.close-modal');

// get popups
var approvePopup = document.getElementById('approve-popup');

// get buttons that expand some part of the UI/DOM on click
var expandWeekend = document.getElementById('expand-weekend');
var expandRoles = document.getElementById('open-roles');

var workdayStartStamp = document.getElementById('workday-start');


// SVG
var dismissIcon = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0 6C0 2.68629 2.68629 0 6 0H14C17.3137 0 20 2.68629 20 6V14C20 17.3137 17.3137 20 14 20H6C2.68629 20 0 17.3137 0 14V6Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L10 8.58579L14.2929 4.29289C14.6834 3.90237 15.3166 3.90237 15.7071 4.29289C16.0976 4.68342 16.0976 5.31658 15.7071 5.70711L11.4142 10L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L10 11.4142L5.70711 15.7071C5.31658 16.0976 4.68342 16.0976 4.29289 15.7071C3.90237 15.3166 3.90237 14.6834 4.29289 14.2929L8.58579 10L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="#9CA3AF"/>
</svg>`;

// add event listeners to cancel buttons 
closeButtons.forEach(button => button.addEventListener('click', handleCloseButtonClick));

expandWeekend.addEventListener('click', handleExpandWeekendButtonClick);

function handleExpandWeekendButtonClick(event){
    event.preventDefault();
    const calendar = document.getElementById('timesheet-calendar');
    calendar.classList.contains('expanded') ? calendar.classList.remove('expanded') : calendar.classList.add('expanded');
}
function handleExpandRolesButtonClick(event){
    event.preventDefault();
    const footer = document.getElementById('calendar-footer');
    footer.classList.contains('expanded') ? footer.classList.remove('expanded') : footer.classList.add('expanded');
}

function getTimelogById(id = false){
    return id ? state.find(log => log.id === id) : false;
}

function openPopup(modal){
     // set aria hidden to false because we opened the popup
     modal.setAttribute("aria-hidden", "false");
     modal.classList.add('open');
}

// handle close button clicks
function handleCloseButtonClick(event){
    const isOutside = !event.target.closest('.modal-inner');
    
    if (isOutside || event.currentTarget.classList.contains('cancel-button')){
        // if click was on the overlay or on cancel button, then close modals
        closePopup();
    }
}

// close modal/popup
function closePopup(){
    // get all modals
    const modals = document.querySelectorAll('.modal-outer');
    // remove delete confirmation if needed
    // ratingFormWrapper.style.transform = 'initial';

    // close all modals
    modals.forEach(modal => {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
    });
}

function toggleFormProcessing(form, buttonText = 'Processing' ){
    const formButtons = form.querySelectorAll('button');
    const submitButtonText = form.querySelector('button .button-text');
    if( form.classList.contains('processing') ){
        form.classList.remove('processing');
        submitButtonText.textContent = buttonText;
        formButtons.forEach(button => {
            button.disabled = false;
        });
    }
    else {
        form.classList.add('processing');
        submitButtonText.textContent = buttonText;
        formButtons.forEach(button => {
            button.disabled = true;
        });
    }
}

function addNotification(success, message) {
    console.log('adding notification');
    dismissNotifications();
    const responseMessage = document.createElement("div");
    responseMessage.innerText = message;
    responseMessage.classList.add("notification");

    const dismissButton = document.createElement('button');
    dismissButton.classList.add('dismiss-button');
    dismissButton.setAttribute('type', 'button');
    dismissButton.setAttribute('aria-label', 'Dismiss notification');
    dismissButton.innerHTML = dismissIcon;
    dismissButton.addEventListener('click', dismissNotifications, { once: true } );

    responseMessage.appendChild(dismissButton);
    if (!success) {
      responseMessage.classList.add("error");
    }
    else {
      responseMessage.classList.add("success");
    }
    document.getElementById('single-timesheet-container').appendChild(responseMessage);
}

function dismissNotifications(){
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        notification.classList.add('dismissing');
        notification.ontransitionend = () => {
            notification.remove();
        };  
    });
}

// delete DOM elements before displaying/rendering state
function destroyTimelogs(){
    const elementsToDestroy = document.querySelectorAll('.timelog-element');
    elementsToDestroy.forEach(element => {
        element.remove();
    });
}

// render state
function displayTimelogs(){
    //grab height of DOM elements
    const hourHeight = document.querySelector('.hour').offsetHeight;

    // remove all dom elements
    destroyTimelogs();

    // render all timelogs in state
    state.forEach(timelog => {

        // we create a dom node
        const node = document.createElement('div');
        node.id = timelog.id;
        if(timelog.isPlanned){
            node.className = 'single-timelog planned green timelog-element';
        }
        else if(typeof timelog.activity === 'number'){
            node.className = 'single-timelog unbilled danger timelog-element';
        }
        else {
            node.className = 'single-timelog unplanned amber timelog-element';
        }

        //calculate and set height for DOM node
        const nodeHeight = (hourHeight / 60) * timelog.duration;
        node.style.height = `${nodeHeight}px`;

        //calculate and set position for DOM node
        const startHour = timelog.startTime.substring(0,2).replace(/^0+/, '') > 0 ? parseInt(timelog.startTime.substring(0,2).replace(/^0+/, '')) : 0;
        const startMinute = timelog.startTime.substring(3,5).replace(/^0+/, '');
        const startHeight = document.querySelector(`.hour:nth-child(${startHour+1})`).offsetTop + ((hourHeight / 60) * startMinute);
        console.log(`Here's your node!! StartHour: ${startHour}, startMinute: ${startMinute}, and startHeight: ${startHeight}. Cheers!`);
        
        node.style.top = `${startHeight}px`;
        node.innerHTML = timelog.activityTitle;

        // get name of the day
        const dayName = timelog.startDate.toLocaleDateString('en-GB', { weekday: 'long' }).toLowerCase();

        // insert node to DOM
        document.querySelector(`.day.${dayName}`).insertAdjacentElement('beforeend', node);
        

    });
    updateProgressBars();
}

function updateProgressBars(){
/*     
    // set up reducer function that tallies up the logged minutes for each planned role
    function durationReducer(totalDurations, role){
        console.log(role);
        if(role.activity.toString().startsWith('role')){
            totalDurations[role.activity] = totalDurations[role.activity] + role.duration || role.duration;
        }
        return totalDurations;
    }
    // set up an object which contains the unique roles that have time logged against them as properties, with the total duration of each as the property value
    const currentStateOfRoles = state.reduce(durationReducer, {});
    console.log(currentStateOfRoles);
    // check if there are properties aka planned roles with time logged
    if(Object.keys(currentStateOfRoles).length){
        console.log('bigger than 0');
        // if there's time logged against planned activities, loop through them
        const progressRoles = document.querySelectorAll('.progress-role');
        progressRoles.forEach(progressRole => {
            console.log(progressRole);
            if(currentStateOfRoles[progressRole.dataset.role]){
                // calculate the percentage done
                const percentage = Math.floor(currentStateOfRoles[progressRole.dataset.role] / progressRole.dataset.minutes * 100);
                // update the DOM to reflect the changes
                progressRole.querySelector('.progress-percentage').textContent = percentage < 100 ? `${percentage}%` : '100%';
                progressRole.dataset.percentage = percentage < 100 ? percentage : 100;
                // TODO: handle 100% completion
                progressRole.querySelector('svg circle.progress-ring-circle').style.setProperty('--dashArrayFirstOffset', percentage);
                const remainingMinutes = progressRole.dataset.minutes - currentStateOfRoles[progressRole.dataset.role];
                progressRole.querySelector('span.hours-remaining').textContent = Math.round(remainingMinutes / 6) / 10;
            }
            else {
                // update the DOM to reflect the changes
                progressRole.querySelector('.progress-percentage').textContent = `0%`;
                progressRole.dataset.percentage = 0;
                // TODO: handle 100% completion
                progressRole.querySelector('svg circle.progress-ring-circle').style.setProperty('--dashArrayFirstOffset', 0);
                progressRole.querySelector('span.hours-remaining').textContent = progressRole.querySelector('span.hours-remaining').dataset.hours;
            }
        });
        
    }
    else {
        // if there's no time logged against any planned activity, reset each to 0
        document.querySelectorAll('.progress-percentage').forEach(el => el.textContent = '0%');
        document.querySelectorAll('svg circle.progress-ring-circle').forEach(el => el.style.setProperty('--dashArrayFirstOffset', 0));
        document.querySelectorAll('span.hours-remaining').forEach(el => el.textContent = el.dataset.hours);
    } */
}


function getTaskById(id = false){
    return id ? taskState.find(log => log.id === parseInt(id)) : false;
}


function scrollToStartOfWorkday(){
    if(workdayStartStamp) {
        const top = workdayStartStamp.offsetTop - 160;
        window.scrollTo(0, top);
    }

}
window.addEventListener('load', scrollToStartOfWorkday());
// handle "mark as complete" click