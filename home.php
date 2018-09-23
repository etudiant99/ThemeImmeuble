<?php get_header(); ?> <!-- ouvrir header,php -->
    <div id="main" class="row">
    <?php echo do_shortcode('[formulaire_de_recherche]'); ?>
            <div class="col-sm-8">
                <?php
            global $wp_query;
            $args = array(
                'post_type' => 'Hebergement',
                'posts_per_page' => 10
            );
          $loop = new WP_Query( $args );
          if( $loop->have_posts() ):
            while( $loop->have_posts() ): $loop->the_post(); global $post; ?>
                    <div class="col-sm-7">
                            <div class="post_content">
                                <br />
                                <?php
                                $custom_fields = get_post_custom($post->ID);
                                //var_dump($custom_fields);

                                the_title( sprintf( '<h$image_src = wp_get_attachment_url( $image_id );2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
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
                    <?php endwhile; ?>
                    
                    <div class="navigation">
                        <?php posts_nav_link(' - ','page suivante','page précédente'); ?>
                    </div>
                    <?php else : ?>
                        <h2>Oooopppsss...</h2>
                        <p>Désolé, mais vous cherchez quelque chose qui ne se trouve pas ici .</p>
                        <?php include (TEMPLATEPATH . "/searchform.php"); ?>
                <?php endif; ?>
            </div>
    </div>
    <?php get_footer(); ?>
