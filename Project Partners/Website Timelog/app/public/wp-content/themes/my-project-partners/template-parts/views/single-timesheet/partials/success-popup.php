<?php

/**

 * A success popup

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<?php

if(!isset($args)){
    $args['headline'] = 'Timesheet approved.';
    $args['button']['label'] = 'Back to Timesheets.';
    $args['button']['url'] = get_permalink( $post->post_parent );
}

?>

<div class="modal-outer edit-modal" id="success-popup" aria-hidden="true">
    <div class="modal-inner text-center">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="modal-svg">
            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#34D399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h2 class="h4 mt-2 mb-2 semibold"><?php echo esc_html($args['headline']); ?></h2>
        <?php if($args['text']){
            echo '<p class="mb-2">' . esc_html($args['text']) . '</p>';
        } ?>
        <a href="<?php echo esc_url($args['button']['url']); ?>" class="button full"><?php echo esc_html($args['button']['label']); ?></a>
    </div>
</div>