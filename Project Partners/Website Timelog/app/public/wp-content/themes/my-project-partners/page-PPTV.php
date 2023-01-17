<?php
/**
 * Template Name: PPTV
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_Project_Partners
 */

get_header();

?>

<main id="primary" class="site-main">
  <div class="page-container mb-3">

    <header class="entry-header mb-4">
      <h1 class="entry-title h2">Project Partners TV</h1>
      <p><?php the_field( 'page_description' ); ?></p>
    </header><!-- .entry-header -->

    <section>
      <!-- ARTICLES GRID START -->
      <div class="articles-grid">

        <!-- HARD-CODED ARTICLE START -->
        <div class="article-card rounded shadow">
          <img class="img" src="<?php echo get_template_directory_uri() . '/images/blog_img.jpg' ?>" alt="">
          <div class="card-content">
            <h4 class="title">Episode 1</h4>
            <p class="excerpt">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, Lorem, ipsum dolor sit amet
              consectetur
              adipisicing elit. Commodi aspernatur modi suscipit optio sit
            </p>
          </div>
          <a class="btn rounded" href="">Watch Now</a>
        </div>
        <!-- HARD-CODED ARTICLE END -->
        <!-- HARD-CODED ARTICLE START -->
        <div class="article-card rounded shadow">
          <img class="img" src="<?php echo get_template_directory_uri() . '/images/blog_img.jpg' ?>" alt="">
          <div class="card-content">
            <h4 class="title">Episode 2</h4>
            <p class="excerpt">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, Lorem, ipsum dolor sit amet
              consectetur
              adipisicing elit. Commodi aspernatur modi suscipit optio sit
            </p>
          </div>
          <a class="btn rounded" href="">Watch Now</a>
        </div>
        <!-- HARD-CODED ARTICLE END -->
        <!-- HARD-CODED ARTICLE START -->
        <div class="article-card rounded shadow">
          <img class="img" src="<?php echo get_template_directory_uri() . '/images/blog_img.jpg' ?>" alt="">
          <div class="card-content">
            <h4 class="title">Episode 3</h4>
            <p class="excerpt">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, Lorem, ipsum dolor sit amet
              consectetur
              adipisicing elit. Commodi aspernatur modi suscipit optio sit
            </p>
          </div>
          <a class="btn rounded" href="">Watch Now</a>
        </div>
        <!-- HARD-CODED ARTICLE END -->
        <!-- HARD-CODED ARTICLE START -->
        <div class="article-card rounded shadow">
          <img class="img" src="<?php echo get_template_directory_uri() . '/images/blog_img.jpg' ?>" alt="">
          <div class="card-content">
            <h4 class="title">Episode 4</h4>
            <p class="excerpt">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, Lorem, ipsum dolor sit amet
              consectetur
              adipisicing elit. Commodi aspernatur modi suscipit optio sit
            </p>
          </div>
          <a class="btn rounded" href="">Watch Now</a>
        </div>
        <!-- HARD-CODED ARTICLE END -->

      </div>
      <!-- ARTICLES GRID END -->
    </section>
  </div>
</main><!-- #main -->

<?php
get_footer();