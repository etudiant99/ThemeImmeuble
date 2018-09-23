        <?php get_header(); ?> <!-- ouvrir header,php -->
            <div id="main" class="content-full-width">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                        <div id="post-<?php the_ID(); ?>">
                            <div class="post_content">
                                <?php
                                $custom_fields = get_post_custom($post->ID);
                                //var_dump($custom_fields);

                                the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
                                the_terms( $post->ID, 'typology', '<br />' );                                
                                ?>
                            </div>
                        </div>
                        <div class="comments-template">
                            <?php comments_template(); ?>
                        </div>
                    <?php endwhile; ?>
                    <?php previous_post_link() ?> <?php next_post_link() ?>
                    <?php else : ?>
                        <p>Désolé, aucun article ne correspond à vos critères.</p>
                <?php endif; ?>
            </div>
            <?php get_footer(); ?>