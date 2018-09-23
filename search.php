<?php get_header(); ?> <!-- ouvrir header,php -->
    <div id="main" class="row">
    <?php echo do_shortcode('[formulaire_de_recherche]'); ?>
            <div class="col-sm-8">
                <?php
                if( have_posts() ):
                    while( have_posts() ): the_post(); ?>
                    <div class="col-sm-7">
                        <div id="post-<?php the_ID(); ?>">
                            <div class="post_content">
                                <?php
                                $custom_fields = get_post_custom($post->ID);
                                //var_dump($custom_fields);

                                the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
                                ?>
                                <div class="post-info"><?php the_time('d F Y'); ?> | par <?php the_author(); ?> | <?php the_terms( $post->ID, 'typology'); ?></div>
                                <?php
                                $prix = get_post_meta($post->ID,'hebergement_prix',true);
                                $adresse = get_post_meta($post->ID,'hebergement_adresse',true);
                                $ville = get_post_meta($post->ID,'hebergement_ville',true);
                                $pays = get_post_meta($post->ID,'hebergement_pays',true);
                                
                                echo '$ '.$prix.'/mois';
                                echo '<br />'.$adresse.', '.$ville.', '.$pays;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else : ?>
                        <h2 class="center">Aucun hébergement trouvé.<br />Essayer une autre recherche ?</h2>
                <?php endif; ?>
            </div>
    </div>
    <?php get_footer(); ?>