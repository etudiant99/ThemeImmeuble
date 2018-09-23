<?php get_header(); ?> <!-- ouvrir header,php -->
    <div id="main" class="row">
            <div class="col-sm-8">
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                    <div class="post" id="post-<?php the_ID(); ?>">
                        <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                        <p class="postmetadata">
                            <?php the_time('j F Y') ?>
                             par <?php the_author() ?>
                             <?php comments_popup_link('| Pas de commentaires', '| 1 Commentaire', '| % Commentaires','',''); ?>
                            <?php edit_post_link('Editer', ' &#124; ', ''); ?>
                        </p>
                        <div class="post_content">
                            <?php the_terms( $post->ID, 'marques', 'Marque : ' ); ?>
                            <?php the_terms( $post->ID, 'modeles', 'Modèle : ' ); ?>
                            <?php the_terms( $post->ID, 'annees', 'Année : ' ); ?>
                            <?php the_terms( $post->ID, 'pneus', 'Pneu : ' ); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else : ?>
                        <h2 class="center">Aucun article trouvé. Essayer une autre recherche ?</h2>
                        <?php include (TEMPLATEPATH . '/searchform.php'); ?>
                <?php endif; ?>
            </div>
            <?php get_sidebar(); ?>
    </div>
    <?php get_footer(); ?>