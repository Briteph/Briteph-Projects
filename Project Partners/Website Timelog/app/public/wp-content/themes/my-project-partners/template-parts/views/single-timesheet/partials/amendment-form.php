<?php

/**

 * Request timesheet amendment form

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */
$is_approved = $args['is_approved'];
$ratings = $args['ratings'];
if (!$is_approved) { ?>

    <form class="timesheet-amendment mt-0" id="timesheet-amendment" method="POST">
        <div class="comment-input mt-0">
            <h3 class="h5 mb-half mt-0">Comments</h3>
            <textarea name="amendment-comments" id="amendment-comments" spellcheck="true" rows="3"></textarea>
        </div>
        <?php 
        // set up nonce
        wp_nonce_field('amend_timesheet', 'amend-nonce'); ?>
        <input type="hidden" name="ratings" id="ratings" value="<?php foreach($ratings as $rating){ echo $rating->item_id . ','; } ?>">
        <button type="submit" class="button btn full mt-1" id="timesheet-amend-btn" aria-label="Request amendment">
            <svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="button-text">Request amendment</span>
        </button>
    </form>
<?php
}