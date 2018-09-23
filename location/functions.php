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

include( 'cuztom/cuztom.php' );

