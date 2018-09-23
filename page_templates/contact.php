<?php
/*
 * @package WordPress
 * @subpackage Tuto_Wordpress_Finalclap
 *
 * Template Name: Formulaire de contact
**/
get_header();
?>

<?php
# ========================
# Traitement du formulaire (envoi du mail)
# ========================
if( isset($_POST['contact_traitement']) ){
	?>
	<div class="post">
	<h1>Courriel pas envoyé !</h1>
	<p>Il s'agit simplement d'un exemple, la partie traitement n'est pas opérationnelle, ça n'est pas l'objet de ce chapitre.</p>
	<pre>$_POST : <?php print_r($_POST); ?></pre>
	</div>
	<?php
}


# =======================
# Affichage du formulaire
# =======================
else {
?>
<div class="post">
	<?php if (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	<?php endif; ?>
	
	<!-- J'utilise ici les règles CSS du formulaire d'envoi de commentaire, on ne va pas réinventer la roue -->
	<div id="comments">
	<div id="respond">
		<div class="comment-form" style="margin: 20px 0 0;">
		<form method="post" action="">
			<p>Votre <b>Nom</b> :
			<input type="text" size="40" value="" name="nom"/></p>
			
			<p>Votre <b>Courriel</b> <em>(obligatoire)</em> :
			<input type="text" size="40" value="" name="email"/></p>
			
			<p><b>Objet</b> <em>(obligatoire)</em> :
			<input type="text" size="40" value="" name="objet"/></p>
			
			<p>Votre <b>Message</b> :
			<textarea rows="10" cols="40" name="message"></textarea></p>
						
			<input type="hidden" name="contact_traitement" value="true" />
			<input type="submit" value="Envoyer" style="width: 120px;"/>
		</form>
		</div>
	</div>
	</div>
</div>
<?php } ?>

<?php get_footer(); ?>