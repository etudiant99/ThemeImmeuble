        <?php get_header(); ?> <!-- ouvrir header,php -->
            <div id="main" class="col-sm-8">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                        <div class="post" id="post-<?php the_ID(); ?>">
                            <h2><?php the_title(); ?></h2>
                            <p class="postmetadata">
                                <?php the_time('j F Y') ?> par <?php the_author() ?>
                                <?php edit_post_link('Editer', ' &#124; ', ''); ?>
                            </p>
                            <div class="post_content">
                                <?php the_content(); ?>
                            </div>
                            <div class="post_content">
                                <?php the_terms( $post->ID, 'marques', 'Marque : ' ); ?>
                                <?php the_terms( $post->ID, 'modeles', 'Modèle : ' ); ?>
                                <?php the_terms( $post->ID, 'annees', 'Année : ' ); ?>
                                <?php the_terms( $post->ID, 'pneus', 'Pneu : ' ); ?>
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
            <?php get_sidebar(); ?>
            <?php get_footer(); ?>