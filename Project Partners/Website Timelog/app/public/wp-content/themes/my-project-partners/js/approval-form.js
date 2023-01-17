// get form
var approveForm = document.getElementById('timesheet-approval');
// get submit button to disable it
var approveButton = document.getElementById('timesheet-approve-btn');


var amendPopup = document.getElementById('amend-popup');
var amendButton = document.getElementById('open-amend-popup');

var amendForm = document.getElementById('timesheet-amendment');

var openApprovePopupButton = document.getElementById('open-approve-popup');

var confettiCount = 300;
var confettiDefaults = {
  origin: { y: 0.7 }
};


if(amendButton) amendButton.addEventListener('click', handleAmendButtonClick);

if(amendForm) amendForm.addEventListener('submit', handleAmendFormSubmission);

function handleAmendButtonClick(){
  openPopup(amendPopup);
}

function openPopup(modal = popup){
    // set aria hidden to false because we opened the popup
    modal.setAttribute("aria-hidden", "false");
    modal.classList.add('open');
}

// close modal/popup
function closePopup(){
    // get all modals
    const modals = document.querySelectorAll('.modal-outer');

    // close all modals
    modals.forEach(modal => {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
    });
}

function fire(particleRatio, opts) {
  confetti(Object.assign({}, confettiDefaults, opts, {
    particleCount: Math.floor(confettiCount * particleRatio),
    zIndex:99999
  }));
}

function fireConfetti(){
    fire(0.25, {
        spread: 26,
        startVelocity: 55,
      });
      fire(0.2, {
        spread: 60,
      });
      fire(0.35, {
        spread: 100,
        decay: 0.91,
        scalar: 0.8
      });
      fire(0.1, {
        spread: 120,
        startVelocity: 25,
        decay: 0.92,
        scalar: 1.2
      });
      fire(0.1, {
        spread: 120,
        startVelocity: 45,
      });
}

if (approveButton) {
  approveButton.addEventListener('click', (e) =>{
    if (!document.querySelector('input[name="rating"]:checked')){
      e.preventDefault();
      let validationError = document.createElement("div");
      validationError.innerText = 'Please select a rating';
      validationError.classList.add("validation-error");
      validationError.id = 'rating-validation-error';
      document.getElementById('star-rating').insertAdjacentElement('afterend', validationError);

      document.getElementById('star-rating').addEventListener('click', (e) => {
        document.getElementById('rating-validation-error').remove();
      }, {once : true});

    }
  });
}

if(openApprovePopupButton){
    openApprovePopupButton.addEventListener('click', (e) =>{
        closePopup();
        openPopup(document.getElementById('approve-popup'));
    });
}


if (approveForm) {
  // add form submit listener
  approveForm.addEventListener('submit', (e) => {
    e.preventDefault();
    // disable approve button
    approveButton.disabled = true;
      fetch('/wp-admin/admin-ajax.php?action=approve_timesheet', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(new FormData(approveForm))
      }).then(response => {
        return response.json();

      }).then(jsonResponse => {
        console.log(jsonResponse);
        if(jsonResponse.success){
            closePopup();
            openPopup(document.getElementById('success-popup'));
            fireConfetti();
        }
        else {
            console.log(jsonResponse);
            addNotification(jsonResponse.success, jsonResponse.data.message);
        }

      });
  });
}


function addNotification(success, message) {
  let responseMessage = document.createElement("div");
  responseMessage.innerText = message;
  responseMessage.classList.add("timesheet-message");
  if (!success) {
    responseMessage.classList.add("error");
    approveButton.disabled = false;
  }
  document.getElementById('app-content').appendChild(responseMessage);
  setTimeout(function(){
    window.location.replace("https://my.project.partners/approve-timesheets");
  }, 2000)
}


function handleAmendFormSubmission(e){
  e.preventDefault();
  const buttonText = amendForm.querySelector('button[type=submit] .button-text').textContent;
  toggleFormProcessing(amendForm);
  //fetch response
  fetch('/wp-admin/admin-ajax.php?action=request_amend_timesheet', {
  method: 'POST',
  headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
  },
  body: new URLSearchParams(new FormData(amendForm))
  }).then(response => {
      console.log(response);
  return response.json();

  }).then(jsonResponse => {
  toggleFormProcessing(amendForm, buttonText);
  //log response
  console.log({jsonResponse});

  if (jsonResponse.success) closePopup();

  addNotification(jsonResponse.success, jsonResponse.data.message);
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