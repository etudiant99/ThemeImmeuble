        <?php get_header(); ?> <!-- ouvrir header,php -->
            <div id="main" class="content-full-width">
            <?php echo do_shortcode('[formulaire_de_recherche]'); ?>
                <?php if(have_posts()) : ?>
                    <?php while(have_posts()) : the_post(); ?>
                        <div id="post-<?php the_ID(); ?>">
                            <div class="post_content">
                                <?php
                                $custom_fields = get_post_custom($post->ID);
                                //var_dump($custom_fields);

                                ?>
                                <div class="post-info"><?php the_time('d F Y'); ?> | par <?php the_author(); ?> | <?php the_terms( $post->ID, 'typology'); ?></div>
                                <?php
                                
                                $prix = get_post_meta($post->ID,'hebergement_prix',true);
                                $grandeur = get_post_meta($post->ID,'hebergement_grandeur',true);
                                $type = get_post_meta($post->ID,'hebergement_type',true);
                                $installations = get_post_meta($post->ID,'hebergement_installations',true);
                                $adresse = get_post_meta($post->ID,'hebergement_adresse',true);
                                $ville = get_post_meta($post->ID,'hebergement_ville',true);
                                $pays = get_post_meta($post->ID,'hebergement_pays',true);
                                $image_id = get_post_meta( $post->ID, '_image_id', true );
                                $image_src = wp_get_attachment_url( $image_id );
                                
                                ?>
                                <img id="book_image" src="<?php echo $image_src ?>" style="max-width:30%;" /><br />
                                <?php

                                
                                echo '$ '.$prix.'/mois';
                                echo '<br />'.$adresse.', '.$ville.', '.$pays;
                                echo '<br />Grandeur: '.$grandeur;
                                echo '<br />'.$type;
                                echo '<br /><span style="text-decoration: underline;">Installations</span>';
                                foreach($installations as $value)
                                {
                                    echo '<br />&nbsp;&nbsp;&nbsp;-'.$value;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="comments-template">
                            <?php comments_template(); ?>
                        </div>
                    <?php endwhile; ?>
                    <?php previous_post_link() ?> <?php next_post_link() ?>
                    <?php else : ?>
                        <p>Désolé, aucun hébergement ne correspond à vos critères.</p>
                <?php endif; ?>
            </div>
            <?php get_footer(); ?>