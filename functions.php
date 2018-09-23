<?php
// add a link to the WP Toolbar
function custom_toolbar_link($wp_admin_bar) {
    $accueil = get_home_url();
    $args = array(
        'id' => 'wpbeginner',
        'title' => 'Se connecter', 
        'href' => $accueil.'/wp-login.php', 
        'meta' => array(
            'class' => 'wpbeginner', 
            'title' => 'Search WPBeginner Tutorials'
            )
    );
    if (!is_user_logged_in())
        $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);
show_admin_bar(true);

add_action('widgets_init','zero_add_sidebar');
function zero_add_sidebar()
{
    register_sidebar(array(
        'id' => 'my_custom_zone',
        'name' => 'Zone de droite',
        'description' => 'Apparait à droite du site',
        'before_widget' => '<div>',
        'after_widget' => "</div>",
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => "</h2>"
    ));
}
    
register_nav_menus( array( 
        'menu-principal' => 'Menu principal'
) );


// ajout de la metabox
add_action( 'admin_init', 'my_admin_init' );
function my_admin_init(){
    add_meta_box("desc_page", "Archive à présenter", "archive_page", "page", "side", "high");
}
//fonction de la metabox
function archive_page( $post ) {
    $archive_page = get_post_meta( $post->ID, '_archive_page', true );
    ?>
    <select name="archive_page">
        <option value="">Aucune</option>
        <?php
            $post_types = get_post_types( array( 'show_ui' => true, '_builtin' => false ) );
            foreach( $post_types as $post_type )
                echo '<option value="' . esc_attr( $post_type ) . '" ' . selected( $post_type, $archive_page, false ) . '>' . esc_html( $post_type ) . '</option>'; 
        ?>
    </select>
    <p>Choisissez la cible de cette page</p>
    
    <?php
    wp_nonce_field( 'archive_page-save_' . $post->ID, 'archive_page-nonce') ;
}
//sauvegarde de la metabox
add_action( 'save_post', 'my_save_post' ); 
function my_save_post( $post_ID ){ 
    // on retourne rien du tout s'il s'agit d'une sauvegarde automatique
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_ID;
        
    if ( isset( $_POST[ 'archive_page' ] ) ) {
        check_admin_referer( 'archive_page-save_' . $_POST[ 'post_ID' ], 'archive_page-nonce' );
        if( isset( $_POST[ 'archive_page' ] ) ) {
            $target = $_POST[ 'archive_page' ];
            global $wpdb;
            $suppr = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_archive_page' AND meta_value = '%s'"), $target );
            foreach( $suppr as $s )
                delete_post_meta( $s->post_id, '_archive_page' );
            update_post_meta( $_POST[ 'post_ID' ], '_archive_page', $_POST[ 'archive_page' ] );
        }
    }
}
//présentation de l'archive
function presentation_archive() {
    $post_type_obj = get_queried_object();
    $target = $post_type_obj->name;
    $presentation = new WP_Query( array(
        'post_type' => 'page',
        'meta_query' => array(
            array(
                'key' => '_archive_page',
                'value' => $target,
                'compare' => '='
                )
            )
        ) );
    if( $presentation->have_posts() ) : $presentation->the_post();
        the_title( '<h1 class="h1">', '</h1>' );
        echo '<div class="article-elem">';
        echo the_content();
        echo '</div>';
    endif;
}
// filtre permalien
add_filter( 'page_link', 'archive_permalink', 10, 2 );
function archive_permalink( $lien, $id ) {
    if( '' != ( $archive = get_post_meta( $id, '_archive_page', true ) ) && ! is_admin() )
        return get_post_type_archive_link( $archive );
    else
        return $lien;
}
// redirect
add_action( 'template_redirect', 'redirect_to_archive' );
function redirect_to_archive() {
    if( is_page() && ! is_admin() ){
        global $post;
        if( '' != ( $archive = get_post_meta( $post->ID, '_archive_page', true ) ) ) {
            wp_redirect( get_post_type_archive_link( $archive ), 301 );
            exit();
        }
    }
}
//filtre classes nav menu
add_filter( 'nav_menu_css_class', 'add_my_archive_menu_classes', 10 , 3 );
function add_my_archive_menu_classes( $classes , $item, $args ) {
    if( '' != ( $archive = get_post_meta( $item->object_id, '_archive_page', true ) ) ) {
        if( is_post_type_archive( $archive ) )
            $classes[] = 'current-menu-item';
        if( is_singular( $archive ) )
            $classes[] = 'current-menu-ancestor';
    }
    return $classes;
}

function sf_check_rememberme(){
	global $rememberme;
	$rememberme = 1;
}
add_action("login_form", "sf_check_rememberme");


function installe() {

	$labels = array(
		'name' => __( 'Hébergements', 'plugin_hebergement' ),
		'singular_name' => __( 'Hébergement', 'plugin_hebergement' ),
        'menu_name' => __( 'Hébergements', 'plugin_hebergement' ),
        'all_items' => __( 'Hébergements','plugin_hebergement' ),
        'view_item' => __( 'Voir les hébergements','plugin_hebergement' ),
		'add_new_item' => __( 'Ajouter hébergement', 'plugin_hebergement' ),
        'add_new' => __( 'Ajouter'),
        'edit_item' => __( 'Modifier l\'hébergement','plugin_hebergement'),
        'update_item' => __( 'Sauvegarer un hébergement','plugin_hebergement'),
        'search_items' => __( 'Trouver un hébergement','plugin_hebergement'),
        'not_found' => __( 'Aucun hébergement'),
        'not_found_in_trash' => __( 'Aucun hébergement dans la corbeille')
		);

	$args = array(
		'label' => __( 'Hébergements'),
		'description' => 'prévu pour débuter un site d\'hébergement',
        'labels' => $labels,
        'supports' => array( 'title', 'editor', 'thumbnail', 'author'  ),
        'hierarchical' => false,
		'public' => true,
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'hebergement', 'with_front' => true ),
		'show_ui' => true,	
        'menu_position' => 3,
		'show_in_menu' => true,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-admin-multisite',				
		'register_meta_box_cb' => 'ajoute_mets_box',
		'taxonomies' => array( 'typology' )
	);
	register_post_type( 'hebergement', $args );
	add_shortcode( 'formulaire_de_recherche', 'le_formulaire_de_recherche' );
}

add_action( 'init', 'installe' );

/**
 * Enregistrer la taxonomie personnalisée
 */
function enregistre_taxonomies_personalisees() {

	$labels = array(
		'name' => __( 'Typologies', 'plugin_hebergement' ),
		'label' => __( 'Typologies', 'plugin_hebergement' ),
		'add_new_item' => __( 'Ajouter Typologie', 'plugin_hebergement' ),
        'search_items' => __( 'Trouver une typologie','plugin_hebergement'),
        'popular_items' => __( 'Typologies populaires','plugin_hebergement'),
        'edit_item' => __( 'Modifier la typologie','plugin_hebergement')
		);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'label' => __( 'Typologies', 'plugin_hebergement' ),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'typology', 'with_front' => true ),
		'show_admin_column' => true,
	);
	register_taxonomy( 'typology', array( 'hebergement' ), $args );
} 

add_action( 'init', 'enregistre_taxonomies_personalisees' );

/**
 * Ajouter des boîtes de méta personnalisées
 */
function ajoute_mets_box(){
	add_meta_box( 'fonctionalites_meta_box', __( 'Caractéristiques', 'plugin_hebergement' ), 'construit_fonctionalites_meta_box', 'hebergement', 'side' );
	add_meta_box( 'emplacement_meta_box', __( 'Prix & emplacement', 'plugin_hebergement' ), 'construit_emplacement_meta_box', 'hebergement', 'normal' );
}

/**
 * Afficher boîte de méta personnalisée
 */
function construit_fonctionalites_meta_box( $post ){
	// assurez-vous que la demande de formulaire provient de WordPress
	wp_nonce_field( basename( __FILE__ ), 'fonctionalites_meta_box' );
	// récupérer les valeurs de champs actuels
    $grandeur = get_post_meta( $post->ID, 'hebergement_grandeur', true );
	$type = get_post_meta( $post->ID, 'hebergement_type', true );
	$installations = get_post_meta( $post->ID, 'hebergement_installations', true );

	// un tableau de fonctionnalités par défaut
	$equipements_disponibles = array( 'essentiels', 'cuisine', 'internet', 'stationnement', 'tv', 'machine à laver', 'réseau sans fil' );
	
	?>
	<div class='inside'>

		<p><strong>Nombre de pièces</strong></p>

		<p>
			<select name="customfields[grandeur]">
                <option value="" <?php selected( $grandeur, "" ); ?>>Choisir</option>
                <option value="1½" <?php selected( $grandeur, '1½' ); ?>><?php _e( '1½', 'plugin_hebergement' ); ?></option>
                <option value="2½" <?php selected( $grandeur, '2½' ); ?>><?php _e( '2½', 'plugin_hebergement' ); ?></option>
                <option value="3½" <?php selected( $grandeur, '3½' ); ?>><?php _e( '3½', 'plugin_hebergement' ); ?></option>
                <option value="4½" <?php selected( $grandeur, '4½' ); ?>><?php _e( '4½', 'plugin_hebergement' ); ?></option>
                <option value="5½" <?php selected( $grandeur, '5½' ); ?>><?php _e( '5½', 'plugin_hebergement' ); ?></option>
            </select>
		</p>

		<p><strong>Type d'hébergement</strong></p>

		<p>
			<select name="customfields[type]">
                <option value="" <?php selected( $type, "" ); ?>>Choisir</option>
                <option value="logement entier" <?php selected( $type, 'logement entier' ); ?>><?php _e( 'Logement entier', 'plugin_hebergement' ); ?></option>
                <option value="chambre privée" <?php selected( $type, 'chambre privée' ); ?>><?php _e( 'Chambre privée', 'plugin_hebergement' ); ?></option>
                <option value="chambre partagée" <?php selected( $type, 'chambre partagée' ); ?>><?php _e( 'Chambre partagée', 'plugin_hebergement' ); ?></option>
            </select>
		</p>

		<p><strong><?php _e( 'Installations', 'plugin_hebergement' ); ?></strong></p>
		<p>
			<?php		
				foreach ( $equipements_disponibles as $f ) {
					if( !isset( $installations[$f] ) ){
						$installations[$f] = 0;
					}
					?>
					<input type="checkbox" name="customfields[installations][<?php echo $f; ?>]" value="<?php echo $f; ?>" <?php checked( $installations[$f], $f ); ?>><?php echo ucfirst($f); ?><br />
					<?php
				}
			?>
		</p>
	</div>
	<?php
}

/**
 * Afficher la boîte de méta personnalisée
 */
function construit_emplacement_meta_box( $post ){

    global $post;
    $image_src = '';
    
    $image_id = get_post_meta( $post->ID, '_image_id', true );
    $image_src = wp_get_attachment_url( $image_id );
    ?>
    <img id="book_image" src="<?php echo $image_src ?>" style="max-width:100%;" />
    <input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo $image_id; ?>" />
    <p>
        <a title="<?php esc_attr_e( 'Set book image' ) ?>" href="#" id="set-book-image"><?php _e( 'Définir image hébergement' ) ?></a>
        <a title="<?php esc_attr_e( 'Remove book image' ) ?>" href="#" id="remove-book-image" style="<?php echo ( ! $image_id ? 'display:none;' : '' ); ?>"><?php _e( 'Supprimer image hébergement' ) ?></a>
    </p>

		<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			// save the send_to_editor handler function
			window.send_to_editor_default = window.send_to_editor;
	
			$('#set-book-image').click(function(){
				
				// replace the default send_to_editor handler function with our own
				window.send_to_editor = window.attach_image;
				tb_show('', 'media-upload.php?post_id=<?php echo $post->ID ?>&amp;type=image&amp;TB_iframe=true');
				
				return false;
			});
			
			$('#remove-book-image').click(function() {
				
				$('#upload_image_id').val('');
				$('img').attr('src', '');
				$(this).hide();
				
				return false;
			});
			
			// handler function which is invoked after the user selects an image from the gallery popup.
			// this function displays the image and sets the id so it can be persisted to the post meta
			window.attach_image = function(html) {
				
				// turn the returned image html into a hidden image element so we can easily pull the relevant attributes we need
				$('body').append('<div id="temp_image">' + html + '</div>');
					
				var img = $('#temp_image').find('img');
				
				imgurl   = img.attr('src');
				imgclass = img.attr('class');
				imgid    = parseInt(imgclass.replace(/\D/g, ''), 10);
	
				$('#upload_image_id').val(imgid);
				$('#remove-book-image').show();
	
				$('img#book_image').attr('src', imgurl);
				try{tb_remove();}catch(e){};
				$('#temp_image').remove();
				
				// restore the send_to_editor handler function
				window.send_to_editor = window.send_to_editor_default;
				
			}
	
		});
		</script>
    <?php
    

    
	wp_nonce_field( basename( __FILE__ ), 'emplacement_meta_box' );

	$custom_fields = get_post_custom( $post->ID );

    $prix = isset( $custom_fields['hebergement_prix'][0] ) ? $custom_fields['hebergement_prix'][0] : '';
	$adresse = isset( $custom_fields['hebergement_adresse'][0] ) ? $custom_fields['hebergement_adresse'][0] : '';
	$ville = isset( $custom_fields['hebergement_ville'][0] ) ? $custom_fields['hebergement_ville'][0] : '';
	$pays = isset( $custom_fields['hebergement_pays'][0] ) ? $custom_fields['hebergement_pays'][0] : '';
	
	?>
	<div class="inside">
		<p><strong>Prix</strong></p>
		<p><input type="number" id="hebergement_prix" name="customfields[prix]" min="1" required="required" value="<?php echo esc_attr( $prix ); ?>" /></p>

		<p><strong>Adresse</strong></p>
		<p><input type="text" id="hebergement_adresse" name="customfields[adresse]" value="<?php echo esc_attr( $adresse ); ?>" /></p>

		<p><strong>Ville</strong></p>
		<p><input type="text" id="hebergement_ville" name="customfields[ville]" value="<?php echo esc_attr( $ville ); ?>" /></p>

		<p><strong>Pays</strong></p>
		<p><input type="text" id="hebergement_pays" name="customfields[pays]" value="<?php echo esc_attr( $pays ); ?>" /></p>
	</div>
	<?php
}

/**
 * Stocker des données de boîte de méta personnalisées
 */
function enregistre_donnees_meta_box( $post_id ){
	// vérifier la première boîte de méta personalisée
	if ( !isset( $_POST['fonctionalites_meta_box'] ) || !wp_verify_nonce( $_POST['fonctionalites_meta_box'], basename( __FILE__ ) ) ){
		return;
	}
	// vérifier la seconde boîte de méta personalisée
	if ( !isset( $_POST['emplacement_meta_box'] ) || !wp_verify_nonce( $_POST['emplacement_meta_box'], basename( __FILE__ ) ) ){
		return;
	}
	// retour si sauvegarde automatique
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}

	// Vérifiez les autorisations de l'utilisateur.
	if ( isset( $_POST['post_type'] ) && 'hebergement' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
	}

	// valeurs de champs personnalisés   
    $customfields = ( isset( $_POST['customfields'] ) ) ? (array) $_POST['customfields'] : array();
    if( count( $customfields ) == 0 ){
		return;
	}

    foreach ($customfields as $key => $value) {

    	// vérifie si le tableau est bidimensionnel
    	// et assainir les valeurs
    	if( is_array( $value ) ){
    		$value = array_map( 'sanitize_text_field', $value );

    	// si non un tableau
    	// assainir la valeur
    	}else{
    		$value = sanitize_text_field( $value );
    	}
    	update_post_meta( $post_id, 'hebergement_' . $key, $value );
        update_post_meta( $post_id, '_image_id', $_POST['upload_image_id'] );
    }

}
add_action( 'save_post', 'enregistre_donnees_meta_box' );

/**
 * Enregistrer des variables de requête personnalisés
 *
 */
function enregistre_query_vars( $vars ) {
    $vars[] = 'prix';
    $vars[] = 'grandeur';
    $vars[] = 'type';
    $vars[] = 'ville';
    $vars[] = 'pays';
    return $vars;
} 
add_filter( 'query_vars', 'enregistre_query_vars' );

/**
 * Construire une requête personnalisée en fonction de plusieurs conditions
 * L'action pre_get_posts permet aux développeurs d'accéder à l'objet $query par référence
 * toutes les modifications apportées à $query sont effectuées directement sur l'objet d'origine - aucune valeur de retour n'est demandée
 *
 */
function methode_pre_get_posts( $query ) {
	// vérifier si l'utilisateur demande une page d'administration 
	// ou la requête en cours n'est pas la requête principale
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

	// modifier la requête uniquement lorsque le type de publication est "hebergement"
	// sinon, retour
	if ( !is_post_type_archive( 'hebergement' ) ){
		return;
	}

    $meta_query = array();

	// obtenir les valeurs de query var
	// par défaut, chaîne vide
    if( !empty( get_query_var( 'ville' ) ) ){
    	$meta_query[] = array( 'key' => 'hebergement_ville', 'value' => get_query_var( 'ville' ), 'compare' => 'LIKE' );
    }
	if( !empty( get_query_var( 'prix' ) ) ){
		$meta_query[] = array( 'key' => 'hebergement_prix', 'value' => get_query_var( 'prix' ), 'compare' => '<=', 'type'    => 'NUMERIC' );
	}
	if( !empty( get_query_var( 'grandeur' ) ) ){
		$meta_query[] = array( 'key' => 'hebergement_grandeur', 'value' => get_query_var( 'grandeur' ), 'compare' => 'LIKE' );
	}
	if( !empty( get_query_var( 'type' ) ) ){
		$meta_query[] = array( 'key' => 'hebergement_type', 'value' => get_query_var( 'type' ), 'compare' => 'LIKE' );
	}
	if( !empty( get_query_var( 'pays' ) ) ){
		$meta_query[] = array( 'key' => 'hebergement_pays', 'value' => get_query_var( 'pays' ), 'compare' => 'LIKE' );
	}
    if( count( $meta_query ) > 1 ){
       	$meta_query['relation'] = 'AND';
    }
    if( count( $meta_query ) > 0 ){
    	$query->set( 'meta_query', $meta_query );
    }
}
add_action( 'methode_pre_get_posts', 'pre_get_posts', 1 );

/**
 * Créer un formulaire de recherche
 */
function le_formulaire_de_recherche( $args ){

	// La requête
    // meta_query attend des tableaux imbriqués, même si vous n'avez qu'une seule requête
    // ajouter la catégorie param
    $query = new WP_Query( array( 'post_type' => 'hebergement', 'posts_per_page' => '-1', 'meta_query' => array( array( 'key' => 'hebergement_ville' ) ) ) );

    // La boucle
    if ( $query->have_posts() ) {
        $lesprix = array();
        $grandeurs = array();
        $villes = array();
        $lespays = array();
        while ( $query->have_posts() ) {
            $query->the_post();
            $prix = get_post_meta( get_the_ID(), 'hebergement_prix', true );
            $grandeur = get_post_meta( get_the_ID(), 'hebergement_grandeur', true );
            $ville = get_post_meta( get_the_ID(), 'hebergement_ville', true );
            $pays = get_post_meta( get_the_ID(), 'hebergement_pays', true );

            // remplir un tableau de toutes les occurrences (non dupliqué)
            if( !in_array( $prix, $lesprix ) ){
                $lesprix[] = $prix;    
            }
            if( !in_array( $grandeur, $grandeurs ) ){
                $grandeurs[] = $grandeur;
            }
            if( !in_array( $ville, $villes ) ){
                $villes[] = $ville;    
            }
            if( !in_array( $pays, $lespays ) ){
                $lespays[] = $pays;    
            }
        }
    }

    /* Restaurer les données de publication d'origine */
    wp_reset_postdata();

    if( count($villes) == 0){
        return;
    }
    asort($grandeurs);
    asort($villes);
    asort($lespays);
    asort($lesprix);

    $prix_precedent = get_query_var( 'prix' );
    
    $select_prix = '<select name="prix">';
    $select_prix .= '<option value="">' . __( 'Prix maximum', 'plugin_hebergement' ) . '</option>';
    for ($i=200;$i<1001;$i=$i+50)
    {
        if ($i == $prix_precedent)
            $select_prix .= '<option value="' . $i . '" selected>' . '$ '.$i . '</option>';
        else
            $select_prix .= '<option value="' . $i . '">' . '$ '.$i . '</option>';
    }
    $select_prix .= '</select>' . "\n";

    
    $grandeur_precedente = get_query_var( 'grandeur' );
    $select_grandeur = '<select name="grandeur">';
    $select_grandeur .= '<option value="">' . __( 'Grandeur', 'plugin_hebergement' ) . '</option>';
    foreach ($grandeurs as $grandeur ) {
        if ($grandeur == $grandeur_precedente)
            $select_grandeur .= '<option value="' . $grandeur . '" selected>' . $grandeur . '</option>';
        else
            $select_grandeur .= '<option value="' . $grandeur . '">' . $grandeur . '</option>';
    }
    $select_grandeur .= '</select>' . "\n";

    $pays_precedent = get_query_var( 'pays' );
    $select_pays = '<select name="pays">';
    $select_pays .= '<option value="">' . __( 'Pays', 'plugin_hebergement' ) . '</option>';
    foreach ($lespays as $pays ) {
        if ($pays == $pays_precedent)
            $select_pays .= '<option value="' . $pays . '" selected>' . $pays . '</option>';
        else
            $select_pays .= '<option value="' . $pays . '">' . $pays . '</option>';
    }
    $select_pays .= '</select>' . "\n";

    $ville_precedente = get_query_var( 'ville' );
    $select_ville = '<select name="ville">';
    $select_ville .= '<option value="">' . __( 'Ville', 'plugin_hebergement' ) . '</option>';
    foreach ($villes as $ville ) {
        if ($ville == $ville_precedente)
            $select_ville .= '<option value="' . $ville . '" selected>' . $ville . '</option>';
        else
            $select_ville .= '<option value="' . $ville . '">' . $ville . '</option>';
    }
    $select_ville .= '</select>' . "\n";

    reset($villes);
    
    $args = array( 'hide_empty' => false );
    $typology_terms = get_terms( 'typology', $args );
    $terme_recherche = get_query_var( 'typology' );
    if( is_array( $typology_terms ) ){
    	$select_typology = '<select name="typology">';
    	$select_typology .= '<option value="" selected="selected">' . __( 'Typologie', 'plugin_hebergement' ) . '</option>';
    	foreach ( $typology_terms as $term ) {
    	   if ($term->slug == $terme_recherche)
            $select_typology .= '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
        else
            $select_typology .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
    	}
    	$select_typology .= '</select>' . "\n";
    }

    $type_habitation = array( 'Logement entier', 'Chambre privée', 'Chambre partagée' );
    $type_habitation_precedente = get_query_var( 'type' );
    $select_type = '<select name="type">';
    $select_type .= '<option value="" selected="selected">' . __( 'Type habitation', 'plugin_hebergement' ) . '</option>';
    foreach ($type_habitation as $habitation ){
        if ($habitation == $type_habitation_precedente)
            $select_type .= '<option value="' . $habitation . '" selected>' . $habitation . '</option>';
        else
            $select_type .= '<option value="' . $habitation . '">' . $habitation . '</option>';
    }
    $select_type .= '</select>' . "\n";


    $output = '<form id="smform" action="' . esc_url( home_url() ) . '" method="GET" role="search"><div class="smtextfield">';
    $output .= '<input type="hidden" name="s" placeholder="Clé de recherche..." value="' . get_search_query() . '" />';
    $output .= $select_ville;
    $output .= $select_pays;
    $output .= $select_typology;
    $output .= $select_type;
    $output .= $select_grandeur;
    $output .= $select_prix;
    $output .= '<input type="hidden" name="post_type" value="hebergement" />';
    $output .= '<input type="submit" value="Go!" class="button" /></form></div>';

    return $output;
}