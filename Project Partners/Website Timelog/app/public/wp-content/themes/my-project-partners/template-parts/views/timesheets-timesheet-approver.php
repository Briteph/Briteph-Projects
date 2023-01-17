<?php

/**

 * Template part for the timesheet approval section (if needed) of the Project Partners dashboard.

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<div id="timesheets-container">

<?php

// get Scoro timesheet approver ID
$contact_id = scoro_get_current_user_contact_id();

if($contact_id){
    // get pending tasks
    //$ratings = scoro_get_ratings_of_contact($contact_id);
    

    $pending_ratings = scoro_get_ratings_of_contact_by_status($contact_id, 'status_20');
    $ratings_need_changing = scoro_get_ratings_of_contact_by_status($contact_id, 'status_10');
    $approved_ratings = scoro_get_ratings_of_contact_by_status($contact_id, 'status_30');;
    $timesheets = [];

    foreach($pending_ratings as $rating){
        if(!in_array($rating->c_task, $timesheets)){
            $rating->start_date = date_create($rating->c_date);
            $rating->end_date = date_create($rating->c_end_date);
        }
    }
    foreach($ratings_need_changing as $rating){
        if(!in_array($rating->c_task, $timesheets)){
            $rating->start_date = date_create($rating->c_date);
            $rating->end_date = date_create($rating->c_end_date);
        }
    }
    foreach($approved_ratings as $rating){
        if(!in_array($rating->c_task, $timesheets)){
            $rating->start_date = date_create($rating->c_date);
            $rating->end_date = date_create($rating->c_end_date);
        }
    }

    //var_dump($ratings);

    /* foreach($ratings as $rating){

        if(!in_array($rating->c_task, $timesheets)){
            $rating->start_date = date_create($rating->c_date);
            $rating->end_date = date_create($rating->c_end_date);

            switch ($rating->status) {
                case 'status_10':
                    $ratings_need_changing[]= $rating;
                    break;
                case 'status_30':
                    $approved_ratings[]= $rating;
                    break;
                default:
                    $pending_ratings[]= $rating;
            }
        }
    } */

    if (count($pending_ratings)) {
        ?>
        <section class="pending-timesheets mb-2 container">
            <h2 class="h3 mb-2 mt-0">Pending Timesheets</h2>
            <div class="grid">
            <?php
            
            // show pending tasks
            foreach($pending_ratings as $rating) {
    
                //get the Partner custom field
                $partner = scoro_get_partner($rating->c_partner);
                if ($partner){
                ?>
                <a class="card no-pad timesheet-card" href="./view-timesheet/?task=<?php echo esc_attr($rating->c_task); ?>">
                    <div class="badge dot amber float">Pending</div>
                    <div class="card-padding">
                        <div class="flex space-between">
                            <div class="profile flex">
                                <div class="avatar-wrap">
                                    <?php echo get_avatar($partner->c_emailaddress, 48); ?>
                                </div>
                                <div class="profile-text flex">
                                    <h4 class="h6 semibold mt-0 mb-0"><?php echo esc_html($partner->c_fullname); ?></h4>
                                    <span class="grey mt-0 mb-0 small"><?php echo esc_html($partner->c_position); ?></span>
                                </div>
                            </div>
                            <div class="company-sow flex">
                                <div class="badge rounded grey"><?php echo esc_html($rating->c_companyname); ?></div>
                                <span class="grey small"><?php echo esc_html($rating->c_sow); ?></span>
                            </div>
                        </div>
                        <div class="grey dates rating-dates small">
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428"/></g></svg>
                            <span><?php echo esc_html($rating->start_date->format('d/m/Y') . '-' . $rating->end_date->format('d/m/Y')); ?></span>
                        </div>
                    </div>
                    <div class="card-footer">Review</div>
                </a>
            <?php }
            } ?>
            
                </div>
            </section>
    
        <?php
        }
        else {
            echo "<p style='margin-bottom:3rem;'>There are no timesheets pending your approval. Great job!</p>";
        }
    
        // loop through tasks that need changing
    
        if (count($ratings_need_changing)) {
            ?>
            <section class="pending-timesheets mb-2 container">
                <h2 class="h3 mb-2 mt-0">Waiting for amendment</h2>
                <div class="grid">
                <?php
                
                // show pending tasks
                foreach($ratings_need_changing as $rating) {
                    
                    //get the Partner custom field
                    $partner = scoro_get_partner($rating->c_partner);
                    if ($partner){
                    ?>
                    <a class="card no-pad timesheet-card" href="./view-timesheet/?task=<?php echo esc_attr($rating->c_task); ?>">
                        <div class="badge dot danger float">Needs amendment</div>
                        <div class="card-padding">
                            <div class="flex space-between">
                                <div class="profile flex">
                                    <div class="avatar-wrap">
                                        <?php echo get_avatar($partner->c_emailaddress, 48); ?>
                                    </div>
                                    <div class="profile-text flex">
                                        <h4 class="h6 semibold mt-0 mb-0"><?php echo esc_html($partner->c_fullname); ?></h4>
                                        <span class="grey mt-0 mb-0 small"><?php echo esc_html($partner->c_position); ?></span>
                                    </div>
                                </div>
                                <div class="company-sow flex">
                                    <div class="badge rounded grey"><?php echo esc_html($rating->c_companyname); ?></div>
                                    <span class="grey small"><?php echo esc_html($rating->c_sow); ?></span>
                                </div>
                            </div>
                            <div class="grey dates rating-dates small">
                                <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428"/></g></svg>
                                <span><?php echo esc_html($rating->start_date->format('d/m/Y') . '-' . $rating->end_date->format('d/m/Y')); ?></span>
                            </div>
                        </div>
                        <div class="card-footer">Review</div>
                    </a>
                <?php 
                    }
                }
                ?>
                
                    </div>
                </section>
        
            <?php
            }
         
        // get approved tasks
        // $approved_tasks = scoro_get_tasks_related_to_contact($contact_id, true);
        if(count($approved_ratings)){ 
        ?>
        <section class="pending-timesheets mt-4 mb-2 container">
            <h2 class="h3 mb-2 mt-0">Recently Approved Timesheets</h2>
            <div class="grid">
    
            <?php
            // show approved tasks
            foreach($approved_ratings as $rating) {
                // convert strings to date to make them pretty
                $start_date = date_create($task->start_datetime);
                $end_date = date_create($task->datetime_due);
    
                //get the Partner custom field
                $partner = null;
                
                $partner = scoro_get_partner($rating->c_partner);
    
                if ($partner){
                ?>
                <a class="card no-pad timesheet-card" href="./view-timesheet/?task=<?php echo esc_attr($rating->c_task); ?>">
                    <div class="badge dot float green">Approved</div>
                    <div class="card-padding">
                        <div class="flex space-between">
                            <div class="profile flex">
                                <div class="avatar-wrap">
                                    <?php echo get_avatar($partner->c_emailaddress, 48); ?>
                                </div>
                                <div class="profile-text flex">
                                    <h4 class="h6 semibold mt-0 mb-0"><?php echo esc_html($partner->c_fullname); ?></h4>
                                    <span class="grey mt-0 mb-0 small"><?php echo esc_html($partner->c_position); ?></span>
                                </div>
                            </div>
                            <div class="company-sow flex">
                                <div class="badge rounded grey"><?php echo esc_html($rating->c_companyname); ?></div>
                                <span class="grey small"><?php echo esc_html($rating->c_sow); ?></span>
                            </div>
                        </div>
                        <div class="grey dates rating-dates small">
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428"/></g></svg>
                            <span><?php echo esc_html($rating->start_date->format('d/m/Y') . '-' . $rating->end_date->format('d/m/Y')); ?></span>
                        </div>
                    </div>
                    <div class="card-footer flex icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#34D399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Approved</span>
                    </div>
                </a>
            <?php }
            } ?>
    
                </div>
            </section>
    
        <?php }
}
else {
    echo '<h2 class="h6">You are not set up as a timesheet approver. Please contact <a href="mailto:info@project.partners">info@project.partners</a> if this is a mistake.</h2>';
}

?>

</div>