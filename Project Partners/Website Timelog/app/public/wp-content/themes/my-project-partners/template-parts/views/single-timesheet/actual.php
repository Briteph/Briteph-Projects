<?php

/**

 * Template part for a view that shows the actual time worked for timesheet approvers.

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<?php
// get arguments
$task = $args['task'];
$role = $args['role'];

// set up total time variable
$total_hours = 0;
$total_minutes = 0;

//get start and due date and convert strings into dates to make them pretty
$start_date = date_create($task->start_datetime);
$end_date = date_create($task->datetime_due);
?>

<!-- display task dates -->
<div class="grey dates">
    <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428"/></g></svg>
    <span><?php echo esc_html( date_format($start_date, 'd/m/y') . '-' . date_format($end_date, 'd/m/y') ); ?></span>
</div>

<!-- display task name -->
<h1 class="timesheet-name h3"><?php echo esc_html($task->event_name); ?></h1>


<?php
//get the Partner custom field
foreach($task->custom_fields as $c_field) {
    if ($c_field->id === 'c_partner') {
        $partner_id = $c_field->value;
    }
}

// get the data of the Partner assigned to do this task
$partner = scoro_get_partner($partner_id);
$is_approved = ($task->is_completed && $task->status != 'task_status3') ? $task->is_completed : false;
$needs_amendment = ($task->status == 'task_status3') ? true : false;

$role_name = $role->c_service ? $role->c_service : $partner->c_position;

?>



<div class="timesheet-details card big flex space-between actual mt-2">
    <!-- display Time Entries by day -->
    <div class="time-entries">
        <div class="profile flex mb-1">
            <div class="avatar">
                <img src="<?php echo esc_url(get_avatar_url($partner->c_emailaddress, array('size' => 48))); ?>">
            </div>
            <div class="profile-text flex">
                <h4 class="h6 semibold mt-0 mb-0"><?php echo esc_html($partner->c_fullname); ?></h4>
                <span class="grey mt-0 mb-0 small"><?php echo esc_html($role_name); ?></span>
            </div>
        </div>
    <?php
    
    // loop through time entries
    $days = [];
    foreach ($task->time_entries as $te_key => $entry) { 
        //get start and due date and convert strings into dates to make them pretty
        // $entry_date = clone $start_date;
        // $entry_date->modify('+' . $te_key . ' day');
        $entry_date = date_create($entry->start_datetime);
        
        //get duration and add it to total
        $time_array = explode(':', $entry->duration);
        $entry_hours = $time_array[0];
        $entry_minutes = $time_array[1];

        if(!array_key_exists($entry_date->format('Y_m_d'), $days)){
            $day =  new stdClass;
            $day->date = $entry_date;
            $day->day_name = esc_html(date_format($day->date, 'l'));
            $day->duration_hours = 0;
            $day->duration_minutes = 0;
            $days[$entry_date->format('Y_m_d')] = $day;
        }
        $day = $days[$entry_date->format('Y_m_d')];

        $day->duration_hours += $entry_hours;
        $day->duration_minutes += $entry_minutes;
    }
    ?>


    <?php
    ksort($days);
    $total_workdays = 0;
    foreach($days as $day){
        if($day->duration_minutes > 59){
            $day->duration_hours++;
            $day->duration_minutes = 0;
        }

        $day->total_duration_in_minutes = $day->duration_hours * 60 + $day->duration_minutes;
        $day->workday_duration = floor($day->total_duration_in_minutes / 480 * 100) / 100;
        $total_workdays = $total_workdays + $day->workday_duration;
        ?>
        <div class="time-entry">
            <h6 class="mt-0 mb-quarter semibold grey uppercase small"><?php echo $day->day_name; ?></h6>
            <div class="flex icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#161428" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <!-- <span class="duration"><?php
                    //if ($day->duration_hours > 0) {
                    //    echo esc_html($day->duration_hours . ' hours');
                    //    $total_hours += $day->duration_hours;
                    //}
                    //if ($day->duration_minutes > 0) {
                    //    echo esc_html(', ' . $day->duration_minutes . ' minutes');
                    //    $total_minutes += $day->duration_minutes;
                    //}
                    ?></span> -->
                    <span class="duration"><?php echo esc_html($day->workday_duration); ?> workday</span>
            </div>
        </div>
    <?php } ?>

        <div class="contact-partner mt-2 grey small">
            <p class="mt-0 mb-0 semibold">Something doesn't seem right?</p>
            <a class="bold" target="_blank" href="https://teams.microsoft.com/l/chat/0/0?users=<?php echo esc_attr($partner->c_emailaddress); ?>">Message <?php echo esc_html($partner->c_firstname); ?> on Teams</a>
            <?php if (strlen($partner->c_phone) > 0) { ?>
                <p>Call them on <a target="_blank" href="tel:<?php echo esc_attr($partner->c_phone); ?>"><?php echo esc_html($partner->c_phone); ?></a> or email at <a target="_blank" href="mailto:<?php echo esc_attr($partner->c_emailaddress); ?>"><?php echo esc_html($partner->c_emailaddress); ?></a></p>
            <?php }
            else { ?>
                <p>Email them at <a target="_blank" href="mailto:<?php echo esc_attr($partner->c_emailaddress); ?>"><?php echo esc_html($partner->c_emailaddress); ?></a></p>
            <?php } ?>
        </div>
    </div>

    <!-- display Time Entries by day -->
    <div class="approval-form-container">
        <div class="total-time">
            <?php
            // add up leftover minutes from each time entry to hours
            $total_hours += floor($total_minutes / 60);
            $total_minutes = $total_minutes % 60;
            ?>
            <h3 class="h5 mt-0 mb-0">Total time</h3>
            <p class="h5 mt-0 mb-0"><?php
                //echo esc_html($total_hours . ' hours');
                //if ($total_minutes > 0){
                //    echo esc_html(', ' . $total_minutes . ' minutes');
                //}
                echo esc_html($total_workdays);
                ?> workdays</p>
        </div>
        <?php get_template_part('/template-parts/views/single-timesheet/partials/approval-form', null, array('is_approved' => $is_approved)); ?>
        <?php
        if(!$is_approved){
            if($needs_amendment){
                echo '<div class="mt-1 text-center">';
                get_template_part('/template-parts/views/single-timesheet/partials/amendment-tip');
                echo '</div>';
            }
            else { ?>
            <button class="danger no-button full underline mt-1half" type="button" id="open-amend-popup">Request amendment</button>
            <?php }
        }
        else { ?>
            <div class="completed-task mt-2">
                This timesheet is now completed. Great job!
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal-outer edit-modal close-modal" id="amend-popup" aria-hidden="true">
    <div class="modal-inner rating-forms-modal">
        <?php get_template_part('/template-parts/views/single-timesheet/partials/amendment-form', null, array('is_approved' => $is_approved)); ?>
    </div>
</div>

<?php 

get_template_part('/template-parts/views/single-timesheet/partials/success-popup', null, array(
    'headline'  => 'Timesheet approved.',
    'button'    => array(
        'label' => 'Back to Timesheets',
        'url'   => site_url()
    )
));

?>
