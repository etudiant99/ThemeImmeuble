<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
    <div>
        <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
        <p>
            <label for="prix-maxi">Prix maximum</label>
	       <input type="number" name="prix-maxi" min="0" value="<?php 
	       if ( isset( $_GET['prix-maxi'] ) && $_GET['prix-maxi'] ) {
	           echo intval( $_GET['prix-maxi'] );
	       } ?>" id="prix-maxi">
        </p>        
        <input type="submit" id="searchsubmit" value="Chercher" />
    </div>
</form>