<?php
/**
 * Template part for displaying the content of a single post or Learndash course.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */
?>

<div class="page-container" style="max-width: 800px">
  <header>
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); 

		$cat_name = get_the_category()[0]->cat_name;
		// If post is an event, show the event date and start time.
		if( 'event' === get_post_type() ) :
		?>
    <p class="event-date mb-1half grey">
	<svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#161428"/></g></svg>
      <?php 
			echo date('j F Y', strtotime(get_field('start_date_and_time'))) . ' at ';
			echo date('g:i a', strtotime(get_field('start_date_and_time')));
		?>
    </p>
    <?php
		endif;
		if ( 
			( ( 'post' === get_post_type() ) || ( 'event' === get_post_type() ) ) &&
			( 'Videos' !== $cat_name )
		) : 
			if ( 'event' !== get_post_type() ) :
	?>
    <div class="entry-meta mb-2">
      - <?php
	my_project_partners_posted_on();
	my_project_partners_posted_by();
	?>
    </div><!-- .entry-meta -->
    <?php endif; ?>
    <div class="mb-2">
      <?php the_post_thumbnail('large', ['class' => 'd-block rounded']) ?>
    </div>
  </header>
  <?php endif; ?>

  <div class="entry-content mb-3">
    <?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'my-project-partners' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		?>
  </div><!-- .entry-content -->
  <!-- Links to prev & next posts -->
  <?php
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'my-project-partners' ),
			'after'  => '</div>',
		)
	);
	?>

</div><!-- .page-container -->