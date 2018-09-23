<div class="blog-post">
    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<p class="blog-post-meta"><?php the_date(); ?> par <?php the_author(); ?></p>
        <?php the_excerpt(); ?>
</div><!-- /.blog-post -->