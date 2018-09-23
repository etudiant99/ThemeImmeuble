            <?php get_header(); ?> <!-- ouvrir header,php -->
            <div id="main" class="col-sm-12">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                    <div class="post" id="post-<?php the_ID(); ?>">
                        <h2><?php the_title(); ?></h2>
                        <div class="post_content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php edit_post_link('Modifier cette page', '<p>', '</p>'); ?>
                <?php endif; ?>
            </div>
            <?php get_footer(); ?>