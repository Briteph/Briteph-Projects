<?php

/**

 * Template part for the Weekly Calendar view of internal Partner schedule.

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<?php
// STEP 1: get Scoro user ID from logged in email address

$partner = creator_get_current_user_partner_id();
$partner_id = $partner->Partner_ID;


// STEP 2: check if user has access to specific weekly task

// check if theres a valid task query string
if (isset($_GET['start'])) {
    // sanitize start & end query strings
    $start_date_str = sanitize_text_field($_GET['start']);
    // $end_date_str = sanitize_text_field($_GET['end']);
    $start_date = new DateTime($start_date_str . ' 00:00:00');
    if ($start_date->format('D') !== 'Mon') {
        $start_date = new DateTime($start_date_str . ' 00:00:00 Monday ago');
    }
    $end_date = new DateTime($start_date->format('Y-m-d') . ' 23:59:59');
    $end_date->modify('+6 days');

    // get date period
    $current_week = iterator_to_array(
        new DatePeriod(
            $start_date,
            new DateInterval('P1D'),
            $end_date
        )
    );
    // check if it's indeed a workweek (not just some random dates) to avoid accidental timesheet duplication
    // check if user has access to view this task 
    if (count($current_week) > 4 && (pp_is_user_role('administrator') || pp_is_user_role('pp_partner'))) {

        // get all roles 
        //$roles = scoro_get_roles($partner_id);
        // print_r($roles);
        $roles = creator_get_roles($partner_id);
        // var_dump($roles);
        // $new_json = json_encode(creator_get_roles($partner_id), JSON_FORCE_OBJECT);
        // echo $new_json;

        $total_minutes = 0;
        // var_dump($roles);

        foreach ($roles as $key => $role) {
            $fte = (float) $role->FTE;
            //var_dump($key);
            // if permanent role, set workdays to 5
            if ($role->Permanent_Role) {
                $days_to_work_this_week = 5;
                $role->days_to_work = 5;
            } else {
                // if role is NOT permanent, check if role/placement is active (needs logged time) this week
                if (!$days_to_work_this_week || $days_to_work_this_week < 1) {
                    $days_to_work_this_week = 0;
                }
                $role->days_to_work = 0;
                if ($role->Dates) {
                    $dates = get_each_date_from_periods($role->Dates);
                    foreach ($dates as $date) {
                        if (in_array($date, $current_week)) {
                            $days_to_work_this_week++;
                            $role->days_to_work++;
                            if ($role->days_to_work > 5) $role->days_to_work = 5;
                        }
                    }
                } else {
                    $role->days_to_work = 0;
                }
                if ($days_to_work_this_week > 5) $days_to_work_this_week = 5;
            }
            $role->weekly_duration = $role->days_to_work * 8 * 60 * $fte;
            //var_dump($key);
            //var_dump($role);
            //echo '<br><br>';
            if ($role->days_to_work < 1) {
                unset($roles[$key]);
            }
            // var_dump($roles);
            $total_minutes = $total_minutes + $role->weekly_duration;
        }
        $roles = array_values($roles);

        //var_dump($roles);

        $total_hours = round($total_minutes / 60, 2);


        // get all tasks in date range from Scoro
        $tasks = creator_get_partner_tasks_by_date($start_date->format('d-M-Y'), $end_date->format('d-M-Y'), $partner_id);
        // $tasks = scoro_get_partner_tasks_by_date($start_date->format('Y-m-d'), $end_date->format('Y-m-d'), $partner_id);
        // $main_task1 = $tasks1[0];
        // echo json_encode($main_task1->time_entries);

        // $tasks will be an array of timesheets/tasks for each planned role
        // $main_task will be the generic weekly timesheet

        // check if tasks exist for this week
        if ($tasks || count($tasks) > 0) {
            // get main task and all client-side tasks for ratings
            $main_task = $tasks[0];
            $main_task->time_entries = creator_get_partner_time_entries($main_task->Event_ID);

            /*             foreach($tasks as $key => $task) {
                if (scoro_get_custom_field($task, 'c_weekly_timesheet') === 1){
                    $main_task = $task;
                    array_splice($tasks, $key, 1 );
                    $client_tasks = $tasks;
                    break;
                }
            } */
        } else {
            if ($start_date < new DateTime('+3 weeks')) {
                // if there are no tasks
                // TODO: complete this section
                // set up primary timesheet data
                $main_task_data = [];
                $main_task_data['event_name'] = 'Weekly timesheet: ' . $current_week[0]->format('d-m-y') . ' for partner ' . $partner_id;
                $main_task_data['start_datetime'] = $current_week[0]->format('c');
                $main_task_data['end_datetime'] = $current_week[6]->format('c');
                $main_task_data['datetime_due'] = $current_week[6]->format('c');
                $main_task_data['custom_fields']['c_partner'] = $partner_id;
                $main_task_data['custom_fields']['c_companyname'] = 'Project Partners';
                $main_task_data['custom_fields']['c_rolename'] = $partner->Position;
                $main_task_data['custom_fields']['c_sow'] = 'Weekly timesheet';
                // get duration into HH:ii:ss format (str_pad to account for single digits)
                $main_task_data['duration_planned'] = str_pad(floor($total_minutes / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($total_minutes % 60, 2, '0', STR_PAD_LEFT) . ':00';
                // need to check if we are in summer time or not for Scoro
                $date_now = new DateTime();
                // if there's only one role, prepopulate timesheet
                if (count($roles) === 1) {
                    // calculate daily duration
                    $daily_total_minutes_for_role = floor($roles[0]->weekly_duration / $days_to_work_this_week);
                    $role_time_entries = [];
                    for ($i = 0; $i < $days_to_work_this_week; $i++) {
                        $role_time_entry = array(
                            'description' => 'role-' . $roles[0]->Item_ID,
                            'activity_id' => SCORO_PLANNED_ACTIVITY_ID,
                            'start_datetime' => $current_week[$i]->format('Y-m-d') . 'T08:00:00' . $date_now->format('P'),
                            'end_datetime' => $current_week[$i]->format('Y-m-d') . 'T' . str_pad(8 + floor($daily_total_minutes_for_role / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($daily_total_minutes_for_role % 60, 2, '0', STR_PAD_LEFT) . ':00' . $date_now->format('P')
                        );
                        $role_time_entries[] = $role_time_entry;
                    }
                    $main_task_data['time_entries'] = $role_time_entries;
                }

                // create a parent weekly task/timesheet (generic, not role specific; for internal use and reporting)
                $main_task = scoro_modify_task($main_task_data);

                /* $tasks = [];
                foreach($roles as $role) {
                    // create a secondary timesheet for each planned role
                    $planned_duration = str_pad(floor($role->weekly_duration / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($role->weekly_duration % 60, 2, '0', STR_PAD_LEFT) . ':00';
                    $task = scoro_create_weekly_secondary_timesheet($partner_id, $start_date, $end_date, $role->item_id, $planned_duration);
                    $tasks[]= $task;
                } */
            }
        }

        // get planned and unplanned activity types

        // $planned_activities = scoro_get_activities('Planned');
        $unplanned_activities = scoro_get_activities('Non-billable');

        // get all sows from scoro
        $all_sows = scoro_get_all_current_sows();
    }
} else {
    echo '<div>
            <h2 class="h4 mb-0">It seems like you dont have a line manager assigned yet - so no need to log your time. </h2>
            <p class="mt-1">If you think this is a mistake, please contact <a href="mailto:info@project.partners">info@project.partners</a>.</p>
        </div>';
    echo '<style>div#single-timesheet-container{display:none!important;}</style>';
}


$ratings = scoro_get_ratings_of_task($main_task->Event_ID);

// var_dump($roles);

?>

<div id="single-timesheet-container" class="timesheet-container partner edit-timesheet-page">


    <div class="week-prehead flex mobile-stack space-between">
        <div class="title">
            <!-- display task dates -->
            <div class="grey dates">
                <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428" />
                    </g>
                </svg>
                <span><?php echo $start_date->format('d.m.Y') . '-' . $end_date->format('d.m.Y'); ?></span>
            </div>
            <h1 class="h3 timesheet-name desktop-only">Weekly Timesheet</h1>
        </div>
    </div>

    <div class="week" id="timesheet-calendar">
        <div class="header flex">
            <div class="timestamp-spacer"></div>
            <?php foreach ($current_week as $day) { ?>
                <div class="day-header <?php echo esc_attr(strtolower($day->format('l'))); ?>">
                    <h6 class="mt-0 mb-0"><span class="day-name small semibold grey"><?php echo esc_html($day->format('l')); ?></span><span class="date"><?php echo esc_html(substr($day->format('Y-m-d'), -2)); ?></span></h6>
                </div>

            <?php } ?>
        </div>

        <div class="body flex" id="calendar-body">
            <button type="button" class="expand-weekend" id="expand-weekend">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7071 12.7071C14.3166 13.0976 13.6834 13.0976 13.2929 12.7071L10 9.41421L6.70711 12.7071C6.31658 13.0976 5.68342 13.0976 5.29289 12.7071C4.90237 12.3166 4.90237 11.6834 5.29289 11.2929L9.29289 7.29289C9.68342 6.90237 10.3166 6.90237 10.7071 7.29289L14.7071 11.2929C15.0976 11.6834 15.0976 12.3166 14.7071 12.7071Z" fill="#6E55FF" />
                </svg>
            </button>
            <div class="timestamps">
                <div class="timestamp"></div>
                <div class="timestamp">01:00</div>
                <div class="timestamp">02:00</div>
                <div class="timestamp">03:00</div>
                <div class="timestamp">04:00</div>
                <div class="timestamp">05:00</div>
                <div class="timestamp">06:00</div>
                <div class="timestamp">07:00</div>
                <div class="timestamp">08:00</div>
                <div class="timestamp" id="workday-start">09:00</div>
                <div class="timestamp">10:00</div>
                <div class="timestamp">11:00</div>
                <div class="timestamp">12:00</div>
                <div class="timestamp">13:00</div>
                <div class="timestamp">14:00</div>
                <div class="timestamp">15:00</div>
                <div class="timestamp">16:00</div>
                <div class="timestamp">17:00</div>
                <div class="timestamp">18:00</div>
                <div class="timestamp">19:00</div>
                <div class="timestamp">20:00</div>
                <div class="timestamp">21:00</div>
                <div class="timestamp">22:00</div>
                <div class="timestamp">23:00</div>
            </div>
            <?php foreach ($current_week as $day) { ?>
                <div class="day <?php echo esc_attr(strtolower($day->format('l'))); ?>" data-date="<?php echo esc_attr($day->format('Y-m-d')); ?>">

                    <?php for ($x = 0; $x <= 22; $x++) { ?>
                        <div class="hour add-new"></div>
                    <?php
                    } ?>
                </div>
            <?php
            }
            ?>

        </div>


    </div>

</div>


<div class="calendar-footer" id="calendar-footer">
    <div class="total-time">
        <h6 class="mt-0 mb-0">Total work hours</h6>
        <p class="small grey mt-0"><span id="weekly-total-hours">0</span> of <?php echo $total_hours; ?> planned hours</p>
    </div>
    <div class="time-needed grid">
        <?php foreach ($roles as $role) { ?>
            <div class="progress-role flex" data-role="role-<?php echo esc_attr($role->Item_ID); ?>" data-minutes="<?php echo esc_attr($role->weekly_duration); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('weekly_role_duration_' . $role->weekly_duration)); ?>">
                <div class="progress-bar-wrap">
                    <svg class="progress-ring" width="40" height="40">
                        <circle class="progress-ring-bg" stroke="rgba(22, 20, 40, .1)" stroke-width="4" fill="transparent" stroke-linecap="round" r="18" cx="20" cy="20" />
                        <circle class="progress-ring-circle progress-ring__circle" stroke="#43D787" stroke-width="4" fill="transparent" stroke-linecap="round" r="18" cx="20" cy="20" />
                    </svg>
                    <span class="small grey progress-percentage">0%</span>
                </div>
                <div class="progress-text">
                    <h6 class="uppercase small grey mb-0 mt-0"><?php echo esc_html($role->Company_Name); ?></h6>
                    <?php $role->weekly_hours = round($role->weekly_duration / 60, 1); ?>
                    <p class="small grey"><span class="hours-remaining" data-hours="<?php echo esc_attr($role->weekly_hours); ?>"><?php echo esc_html($role->weekly_hours); ?></span> of <?php echo esc_html($role->weekly_hours); ?> planned hours</p>
                </div>
            </div>
        <?php } ?>
        <button type="button" class="open-roles" id="open-roles">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7071 12.7071C14.3166 13.0976 13.6834 13.0976 13.2929 12.7071L10 9.41421L6.70711 12.7071C6.31658 13.0976 5.68342 13.0976 5.29289 12.7071C4.90237 12.3166 4.90237 11.6834 5.29289 11.2929L9.29289 7.29289C9.68342 6.90237 10.3166 6.90237 10.7071 7.29289L14.7071 11.2929C15.0976 11.6834 15.0976 12.3166 14.7071 12.7071Z" fill="#6E55FF" />
            </svg>
        </button>
    </div>
    <?php if ($main_task->Status == 'additional5') { ?>
        <div class="completed-task">
            This timesheet is now completed. Great job!
        </div>
    <?php } else { ?>
        <button type="button" id="mark-complete" class="button btn uppercase small" aria-label="Send weekly timesheets for approval">Complete</button>
    <?php
    }
    ?>
</div>

<?php
$edit_nonce = wp_create_nonce('modify_task_' . $main_task->Event_ID);
?>

<?php if ($main_task->Status != 'additional5') { ?>
    <div class="modal-outer edit-modal close-modal" id="edit-timelog-popup" aria-hidden="true">
        <div class="modal-inner">
            <form class="edit-timesheet-form" id="edit-timesheet-form" method="POST">
                <h2 class="mt-0">Log time</h2>
                <div class="date-time-wrap flex mobile-stack">
                    <div class="date-entry">
                        <label class="block date" for="date">Date</label>
                        <input class="date-time" type="date" id="date" name="date" value="<?php echo esc_attr($current_week[0]->format('Y-m-d')); ?>" min="<?php echo esc_attr($current_week[0]->format('Y-m-d')); ?>" max="<?php echo esc_attr($current_week[6]->format('Y-m-d')); ?>">
                    </div>

                    <div class="time-entry">
                        <label class="date block start-time" for="start-time">Time</label>
                        <div class="time-entry-flex flex">
                            <input class="date-time" type="time" id="start-time" name="startTime" value="09:00">
                            <label class="between-inputs" for="end-time">to</label>
                            <input class="date-time" type="time" id="end-time" name="endTime" value="10:00">
                        </div>
                    </div>
                </div>
                <div class="popup-row">
                    <h3 class="semibold">Is this Planned work?</h3>
                    <div class="is-planned-wrap flex">
                        <div id="toggle">
                            <input class="switch" name="isPlanned" type="checkbox" id="is-planned" data-on="Planned work" data-off="Other activity" checked />
                            <label class="switch" for="is-planned">Toggle</label>
                        </div>
                        <span class="grey" id="switch-status">Planned work</span>
                    </div>
                </div>
                <div class="popup-row">
                    <div class="planned activity-select-wrapper" id="planned-wrapper">
                        <label class="bold-label" for="planned-activity">Activity</label>
                        <select id="planned-activity" name="plannedActivity" required>
                            <?php foreach ($roles as $key => $role) { ?>
                                <option value="role-<?php echo esc_attr($role->Item_ID); ?>" data-sow="<?php echo esc_attr($role->SOW); ?>" data-company="<?php echo esc_attr($role->Company_Name); ?>" <?php if ($key === 0) echo 'selected'; ?>><?php echo esc_html($role->Service . ' - ' . $role->Company_Name . ' - ' . $role->SOW); ?></option>
                            <?php } ?>
                        </select>
                        <p class="small italics"><span class="grey">Not seeing your project?</span> <a href="mailto:helpdesk@project.partners?subject=Can't%20find%20my%20project%20in%20Time%20Machine" target="_blank" class="bold">Get help</a></p>
                    </div>
                    <div class="unplanned activity-select-wrapper hidden" id="unplanned-wrapper">
                        <label class="bold-label" for="unplanned-activity">Activity</label>
                        <select id="unplanned-activity" name="unplannedActivity">
                            <option value="" selected>Select other activity</option>
                            <optgroup label="Other">
                                <?php foreach ($unplanned_activities as $activity) { ?>
                                    <option value="<?php echo esc_attr($activity->activity_id); ?>" data-billable="0"><?php echo esc_html($activity->name); ?></option>
                                <?php } ?>
                            </optgroup>
                            <optgroup label="Projects">
                                <?php foreach ($all_sows as $sow) {
                                    $sow_id = $sow->Item_ID;
                                ?>
                                    <option value="quote-<?php echo esc_attr(intval($sow->c_quote_id)); ?>" data-billable="<?php echo esc_attr($sow->c_billable); ?>" data-sow="<?php echo esc_attr($sow_id); ?>" data-company="<?php echo esc_attr($sow->c_companyname); ?>"><?php echo esc_html($sow->c_projectname . ' - ' . $sow->c_companyname); ?></option>
                                <?php } ?>
                            </optgroup>
                        </select>
                        <p class="small italics"><span class="grey">Not sure which project to select?</span> <a href="/statements-of-work/" target="_blank" class="bold">Find it here</a></p>
                    </div>
                </div>
                <div class="client-work activity-select-wrapper hidden" id="client-work-wrapper">
                    <label class="bold-label" for="client-work">Client</label>
                    <select id="client-work" name="client-work">
                        <option value="" selected>Select client activity</option>
                        <?php foreach ($client_tasks as $task) { ?>
                            <option value="<?php echo esc_attr($task->company_id); ?>"><?php echo esc_html($task->company_name); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" id="timelogid" name="timelogid" value="new">
                <input id="taskId" type="hidden" name="taskId" value="<?php echo esc_attr($main_task->Event_ID); ?>">
                <input id="editNonce" type="hidden" name="editNonce" value="<?php echo esc_attr($edit_nonce); ?>">

                <div class="modal-footer" id="edit-timelog-footer">
                    <button class="danger " type="button" aria-label="Delete timesheet" id="delete-timelog">Delete
                    </button>
                    <button class="alt-btn light cancel-button close-modal" type="button" aria-label="Cancel the editing of timesheet" id="cancel-edit">Cancel</button>
                    <button class="primary-normal" type="submit" aria-label="Save timesheet" id="save-timesheet"><svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="button-text">Save timelog</span></button>
                </div>
            </form>
            <div id="delete-confirmation">
                <div class="flex confirmation">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="20" fill="#FEE2E2" />
                        <path d="M20 17V19M20 23H20.01M13.0718 27H26.9282C28.4678 27 29.4301 25.3333 28.6603 24L21.7321 12C20.9623 10.6667 19.0378 10.6667 18.268 12L11.3398 24C10.57 25.3333 11.5322 27 13.0718 27Z" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="confirmation-text">
                        <h3 class="mb-0 mt-half">Delete time entry</h3>
                        <p class="mt-half">Are you sure you want to delete this time entry?<br>This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="alt-btn light cancel-button" type="button" aria-label="Cancel the deletion of timesheet" id="cancel-delete">Cancel</button>
                    <button class="danger " type="button" aria-label="Delete timesheet" id="delete-confirm">
                        <svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg> <span class="button-text">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php if ($main_task->Status != 'additional5') { ?>
        <div class="modal-outer edit-modal close-modal" id="approve-popup" aria-hidden="true">
            <div class="modal-inner rating-forms-modal">
                <div id="rating-form-wrapper">
                    <?php
                    $partner_score = scoro_get_custom_field($main_task, 'c_partner_rating');
                    ?>

                    <form class="rating-form role-form main-form" id="send-for-approve-task-<?php echo esc_attr($main_task->Event_ID); ?>" method="POST">
                        <h2 class="h6">How are you feeling this week?</h2>
                        <div class="star-rating" id="star-rating">
                            <input type="radio" value="5" name="rating" id="rating-5-<?php echo esc_attr($main_task->Event_ID); ?>" required <?php if (floor($partner_score) == 5) echo 'checked'; ?>>
                            <label for="rating-5-<?php echo esc_attr($main_task->Event_ID); ?>"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z" />
                                </svg></label>
                            <input type="radio" value="4" name="rating" id="rating-4-<?php echo esc_attr($main_task->Event_ID); ?>" <?php if (floor($partner_score) == 4) echo 'checked'; ?>>
                            <label for="rating-4-<?php echo esc_attr($main_task->Event_ID); ?>"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z" />
                                </svg></label>
                            <input type="radio" value="3" name="rating" id="rating-3-<?php echo esc_attr($main_task->Event_ID); ?>" <?php if (floor($partner_score) == 3) echo 'checked'; ?>>
                            <label for="rating-3-<?php echo esc_attr($main_task->Event_ID); ?>"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z" />
                                </svg></label>
                            <input type="radio" value="2" name="rating" id="rating-2-<?php echo esc_attr($main_task->Event_ID); ?>" <?php if (floor($partner_score) == 2) echo 'checked'; ?>>
                            <label for="rating-2-<?php echo esc_attr($main_task->Event_ID); ?>"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z" />
                                </svg></label>
                            <input type="radio" value="1" name="rating" id="rating-1-<?php echo esc_attr($main_task->Event_ID); ?>" <?php if (floor($partner_score) == 1) echo 'checked'; ?>>
                            <label for="rating-1-<?php echo esc_attr($main_task->Event_ID); ?>"><svg width="28" height="27" viewBox="0 0 28 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.4294 0.756228C13.609 0.203442 14.391 0.203444 14.5706 0.75623L17.4575 9.64114C17.5378 9.88835 17.7682 10.0557 18.0282 10.0557H27.3703C27.9515 10.0557 28.1932 10.7995 27.723 11.1411L20.165 16.6323C19.9547 16.7851 19.8667 17.0559 19.947 17.3031L22.8339 26.188C23.0135 26.7408 22.3809 27.2005 21.9106 26.8589L14.3527 21.3677C14.1424 21.2149 13.8576 21.2149 13.6473 21.3677L6.08937 26.8589C5.61914 27.2005 4.98646 26.7408 5.16607 26.188L8.05295 17.3031C8.13328 17.0559 8.04528 16.7851 7.83499 16.6323L0.277032 11.1411C-0.193196 10.7995 0.0484717 10.0557 0.629706 10.0557H9.97185C10.2318 10.0557 10.4622 9.88835 10.5425 9.64114L13.4294 0.756228Z" />
                                </svg></label>
                        </div>
                        <div class="comment-input">
                            <h3 class="h6 comments-label">Comments</h3>
                            <textarea name="comments" id="comments-<?php echo esc_attr($main_task->Event_ID); ?>" spellcheck="true" rows="3"><?php echo esc_html(scoro_get_custom_field($main_task, 'c_partner_comment')); ?></textarea>
                        </div>
                        <?php $ta_end_date = clone $start_date;
                        $ta_end_date->modify('+1 week');
                        ?>
                        <div class="modal-footer" id="edit-timelog-footer">
                            <input type="hidden" name="ratingStartDate" id="ratingStartDate" value="<?php echo esc_attr($start_date->format('Y-m-d')); ?>">
                            <input type="hidden" name="ratingEndDate" id="ratingEndDate" value="<?php echo esc_attr($ta_end_date->format('Y-m-d')); ?>">
                            <input type="hidden" name="partnerId" id="partnerId" value="<?php echo esc_attr($partner_id); ?>">
                            <input type="hidden" name="partnerNonce" id="partnerNonce" value="<?php echo esc_attr(wp_create_nonce('partner_permission_' . $partner_id)); ?>">
                            <button class="alt-btn light cancel-button close-modal" type="button" aria-label="Cancel sending for approval">Cancel</button>
                            <button class="primary-normal save-rating" type="submit" aria-label="Save timesheet and send for approval"><svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="button-text">Save timesheet</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php


        get_template_part('/template-parts/views/single-timesheet/partials/success-popup', null, array(
            'headline'  => 'Timesheet sent for approval.',
            'text'      => 'Great job! You are done for this week ✓.',
            'button'    => array(
                'label' => 'Back to Dashboard',
                'url'   => site_url()
            )
        ));
    }
    ?>

    <?php
    if ($main_task->Status == 'task_status3') { ?>
        <div class="modal-outer edit-modal close-modal open" id="amend-comments" aria-hidden="false">
            <div class="modal-inner">
                <?php get_template_part('/template-parts/views/single-timesheet/partials/amendment-tip'); ?>

                <?php
                if (strlen(scoro_get_custom_field($main_task, 'c_comments')) > 0) { ?>

                    <h4 class="mt-0 mb-0 normal-size">Comments about your week (overall):</h4>
                    <p class="mt-0"><?php echo esc_html(scoro_get_custom_field($main_task, 'c_comments')); ?></p>

                    <?php
                }

                foreach ($ratings as $rating) {
                    if ($rating->status == 'status_10') { ?>

                        <h4 class="mt-0 mb-0 normal-size">Comments about <?php echo esc_html($rating->c_sow); ?>:</h4>
                        <p class="mt-0"><?php echo esc_html($rating->c_ta_comments); ?></p>
                <?php
                    }
                } ?>
            </div>
        </div>
    <?php
    }

    //var_dump($roles);
    ?>


    <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_directory_uri() . '/css/slimselect.css'); ?>">

<?php }
?>

<script type="text/javascript" id="existing-tasks-script">
    //console.log('hello1');

    function loadExistingTasks() {

        // Scoro Client Work activity IDs (for ease of change)
        const clientWorkActivityId = <?php echo SCORO_CLIENT_WORK_ACTIVITY_ID; ?>;
        const plannedActivityId = <?php echo SCORO_PLANNED_ACTIVITY_ID; ?>;

        const previousTimelogs = <?php echo json_encode($main_task->time_entries); ?>;
        //console.log(previousTimelogs);
        const existingTasks = <?php echo json_encode($ratings); ?>;
        const mainTaskId = <?php echo $main_task->Event_ID; ?>;

        const roles = <?php echo json_encode($roles); ?>

        // process existing timelogs and push them into state
        if (previousTimelogs) {

            previousTimelogs.forEach(previousTimelog => {
                // get start and end dates
                console.log(previousTimelog);
                const startDateTime = new Date(previousTimelog.Start_Date_Time);
                const endDateTime = new Date(previousTimelog.End_Date_time);
                // set up timelog object that we'll push to state
                const timelog = {
                    // id: parseInt(previousTimelog.Time_Entry_ID),
                    id: previousTimelog.Time_Entry_ID,
                    date: startDateTime.toISOString().split('T')[0],
                    // get dates into the right formats
                    startTime: `${("0" + startDateTime.getHours()).slice(-2)}:${("0" + startDateTime.getMinutes()).slice(-2)}`,
                    endTime: `${("0" + endDateTime.getHours()).slice(-2)}:${("0" + endDateTime.getMinutes()).slice(-2)}`,
                    activity: previousTimelog.Activity_ID,
                    activityTitle: previousTimelog.Title,
                    startDate: startDateTime,
                    endDate: endDateTime,
                    taskId: previousTimelog.Event_ID,
                    editNonce: '<?php echo $edit_nonce; ?>',
                    // duration in minutes
                    duration: Math.round((endDateTime - startDateTime) / 60000),
                    sow: false
                }
                console.log(timelog);
                // the backend activity IDs are slightly different from the app, so need to convert them
                if (parseInt(previousTimelog.Activity_ID) === plannedActivityId) {
                    // if it's planned activity
                    timelog.activity = previousTimelog.Description;
                    // console.log(timelog.activity);
                    const activityOption = document.querySelector(`option[value=${timelog.activity}]`);
                    timelog.companyName = activityOption.dataset.company;
                    timelog.isPlanned = true;
                    timelog.isClientWork = true;
                    timelog.activityTitle = activityOption.textContent;
                    timelog.sow = document.querySelector(`.activity-select-wrapper option[value="${timelog.activity}"]`).dataset.sow;

                } else {
                    // if it's unplanned activity
                    timelog.isPlanned = false;
                    if (parseInt(previousTimelog.Activity_ID) === <?php echo SCORO_CLIENT_WORK_ACTIVITY_ID; ?>) {
                        // if it's unplanned client work
                        timelog.activity = previousTimelog.Description;
                        const activityOption = document.querySelector(`option[value=${timelog.activity}]`);
                        timelog.companyName = activityOption.dataset.company;
                        timelog.isClientWork = activityOption.dataset.isClient === '1' ? true : false;
                        timelog.activityTitle = activityOption.textContent;
                        timelog.sow = document.querySelector(`.activity-select-wrapper option[value="${timelog.activity}"]`).dataset.sow;

                    } else {
                        // if it's unplanned "Other" e.g. holidays
                        timelog.activity = previousTimelog.Activity_ID;
                        timelog.companyName = false;
                        timelog.isClientWork = false;
                    }

                }

                // push to state
                state.push(timelog);
            });
            displayTimelogs();
        }

        // we need to push existing "subtimesheets" and push them to the app to create rating forms
        if (existingTasks) {
            existingTasks.forEach(scoroTask => {
                const relatedRole = scoroTask.c_role;
                const scoroTaskId = relatedRole > 0 ? `role-${relatedRole}` : `quote-${scoroTask.c_quote}`;
                // we only need billable subtimesheets
                if (!getTaskById(scoroTaskId)) {
                    let sowString = scoroTask.c_sow;
                    sowString = (sowString && sowString != 'null') ? sowString : '';
                    const task = {
                        type: relatedRole > 0 ? 'role' : 'quote',
                        id: relatedRole > 0 ? `role-${relatedRole}` : `quote-${Math.floor(scoroTask.c_quote)}`,
                        companyName: scoroTask.c_companyname,
                        sow: sowString,
                        mainTaskId: mainTaskId,
                        existing: true,
                        subTaskId: scoroTask.item_id,
                        comments: scoroTask.c_partner_comments || '',
                        rating: Number(scoroTask.c_partner_rating),
                    };
                    if (state.filter(log => log.activity === task.id).length > 0) taskState.push(task);
                }
            });
        }

        // we also need to create a rating form from timelogs that have been saved to the timesheet, but the user hasn't submitted the timesheet yet
        state.forEach(log => {
            // if activity starts with role or quote
            const activitySplit = log.activity.toString().split('-');
            if (activitySplit[0] === 'role' || activitySplit[0] === 'quote') {
                // if activity is billable, get the relevant option DOM element (this has a dataset with data such as company name)
                const taskOption = document.querySelector(`option[value=${log.activity}]`);

                //const isBillable = (activitySplit[0] === 'role' || taskOption.dataset.billable > 0) ? true : false;
                const isBillable = (activitySplit[0] === 'role' || activitySplit[0] === 'quote') ? true : false;
                if (isBillable) {
                    const task = {
                        type: activitySplit[0],
                        id: log.activity,
                        companyName: taskOption.dataset.company,
                        sow: (taskOption.dataset.sow && taskOption.dataset.sow != 'null') ? taskOption.dataset.sow : '',
                        mainTaskId: mainTaskId,
                        existing: true,
                        subTaskId: false,
                        comments: '',
                        rating: ''
                    };
                    // console.log(taskOption.dataset.company);
                    // console.log(taskOption.dataset.sow);
                    if (!getTaskById(task.id)) taskState.push(task);
                }

            }

        });

        // create rating forms for fixed roles, regardless of everything
        roles.forEach(role => {
            const task = {
                type: 'role',
                id: `role-${role.item_id}`,
                companyName: role.c_companyname,
                sow: role.c_sow,
                mainTaskId: mainTaskId,
                existing: false,
                subTaskId: false,
                comments: '',
                rating: '',
            };
            if (getTaskById(task.id)) taskState.push(task);

        });

        function getCustomFieldValue(object, customFieldName) {
            const index = object.custom_fields.findIndex(customField => customField.id === customFieldName);
            return customFieldValue = object.custom_fields[index].value;
        }

        renderRatingForms();

    }
    if (typeof barba !== 'undefined') {
        loadExistingTasks();
    } else {
        window.addEventListener("load", function(event) {

            loadExistingTasks();

        });
    }
</script>