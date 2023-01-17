// state of scoro tasks
// this includes all tasks relating to planned and unplanned client side work
// we'll create rating forms from these
var taskState = [];


// util function
function getTaskById(id = false){
    return id ? taskState.find(taskInState => taskInState.id === id) : false;
}

// display all forms
function renderRatingForms(){
    // destroy all forms so we can rerender state from scratch
    destroyRatingForms();
    const newTaskState = taskState.reduce((unique, task) => {
      if(!unique.some(obj => obj.sow === task.sow)) {
        unique.push(task);
      }
      return unique;
    },[]);
    taskState = newTaskState;

    // create form from each task in state
    for (let i = 0; i < taskState.length; i++) {
        const task = taskState[i];
        const ratingForm = document.createElement('form');
        const sowString = (task.sow && task.sow != 'null') ? task.sow : `your project`; 
        const isPP = (task.companyName == 'Project Partners') ? '' : `<p class="mt-0 mb-0 grey small italic">${task.companyName} won't be able to see this.</p>`;
        ratingForm.classList.add('rating-form', 'subtask-form');
        if (i < 1) ratingForm.classList.add('active');
        ratingForm.setAttribute('method', 'POST');
        ratingForm.innerHTML = `<h2 class="h6 mt-0">How would you rate your Service Performance working on ${sowString} at ${task.companyName}?</h2>
        <div class="star-rating" id="star-rating">
                        <input type="radio" value="5" name="rating" id="rating-5-${task.id}" required>
                        <label for="rating-5-${task.id}"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z"/>
</svg></label>
                        <input type="radio" value="4" name="rating" id="rating-4-${task.id}">
                        <label for="rating-4-${task.id}"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z"/>
</svg></label>
                        <input type="radio" value="3" name="rating" id="rating-3-${task.id}">
                        <label for="rating-3-${task.id}"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z"/>
</svg></label>
                        <input type="radio" value="2" name="rating" id="rating-2-${task.id}">
                        <label for="rating-2-${task.id}"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z"/>
</svg></label>
                        <input type="radio" value="1" name="rating" id="rating-1-${task.id}">
                        <label for="rating-1-${task.id}"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z"/>
</svg></label>
                    </div>
                    ${isPP}
                    <div class="comment-input">
                        <h3 class="h6 comments-label">Comments</h3>
                        <textarea name="comments" id="comments-${task.id}" spellcheck="true" rows="3">${task.comments}</textarea>
                    </div>

            <div class="modal-footer" id="edit-timelog-footer">
                <input type="hidden" name="formId" value="${task.id}">
                <button class="alt-btn light cancel-button close-modal" type="button" aria-label="Cancel sending for approval">Cancel</button>
                    <button class="primary-normal save-rating" type="submit" aria-label="Go to next step"><svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                     <span class="button-text">Continue</span></button>
            </div>
        `;
    
      // prefill rating if there's one
      if(task.rating){
        ratingForm.querySelector(`input[value="${task.rating}"]`).checked = true;
      }
      // add submit handler
      ratingForm.addEventListener('submit', handleRatingFormSubmission);
      // add cancel button handler
      ratingForm.querySelector('.close-modal').addEventListener('click', closePopup);
      console.log(ratingForm);
      // insert to DOM
      document.getElementById('rating-form-wrapper').prepend(ratingForm);
    }
}

// destroy all subtask forms (all forms except the weekly timesheet one)
function destroyRatingForms(){
    const ratingForms = document.querySelectorAll('.rating-form.subtask-form');
    ratingForms.forEach(form => {
      form.remove();
    })
  }