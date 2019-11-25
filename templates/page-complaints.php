<?php get_header(); ?>

    <main id="main" class="site-main" role="main">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php echo the_breadcrumbs(); ?>
                </ol>
            </nav>
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content">
                        <div class="page-title">
                            <h2><?php the_title() ?></h2>
                        </div>
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-## -->

            <?php endwhile; ?>

            <?php complaintsForm();  ?>
        </div>
    </main>

<?php get_footer(); ?>