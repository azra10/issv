<?php
/*
Plugin Name: ISS Assignment
Description: Custom Post Types for "Grading" website.
Author: Azra Syed
*/

/* register a new post type*/
add_action( 'init', 'iss_assignment_cpt' );

/* Meta box setup function. */
add_action( 'load-post.php', 'iss_assignment_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'iss_assignment_post_meta_boxes_setup' );

/* Meta box setup function. */
function iss_assignment_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'iss_assignment_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'iss_assignment_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function iss_assignment_add_post_meta_boxes() {

  add_meta_box(
    'iss_assignment_post_class',      // Unique ID
    esc_html__( 'Assignment Class', 'example' ),    // Title
    'iss_assignment_post_class_meta_box',   // Callback function
    'iss_assignment',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}

/* Display the post meta box. */
function iss_assignment_post_class_meta_box( $post ) { 
 wp_nonce_field( basename( __FILE__ ), 'iss_assignment_post_class_nonce' );
 $issclasslist = ISS_Class::GetClassList();	
 $selected = esc_attr( get_post_meta( $post->ID, 'iss_assignment_post_class', true ) );
 if ( isset( $_GET['class']) && ! empty ($_GET['class'])) $selected = $_GET['class'];  
 ?>	

<!--  Select Basic -->
<div class="form-group">
    <label class="col-md-4 control-label" for="iss_assignment_post_class">Class</label>
    <div class="col-md-7">
        <select class="form-control" id="iss_assignment_post_class" name="iss_assignment_post_class"
            title="Choose Islamic School Grade" required="">
        <?php foreach ($issclasslist as $id=> $value) { ?> 
        <option value="<?php echo $id;?>"
                <?php echo ($id == $selected) ? ' selected' : '';?>><?php echo $value;?>
        </option> 
            <?php } ?> 
    </select>
    </div>
</div>
<?php
  /*<p>
    <label for="iss_assignment_post_class"><?php _e( "Add a custom CSS class, which will be applied to WordPress' post class.", 'example' ); ?></label>
    <br />
    <input class="widefat" type="text" name="iss_assignment_post_class" id="iss_assignment_post_class" value="<?php echo esc_attr( get_post_meta( $post->ID, 'iss_assignment_post_class', true ) ); ?>" size="30" />
  </p>*/
 }

/* Save the meta box's post metadata. */
function iss_assignment_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['iss_assignment_post_class_nonce'] ) || !wp_verify_nonce( $_POST['iss_assignment_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['iss_assignment_post_class'] ) ? sanitize_html_class( $_POST['iss_assignment_post_class'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'iss_assignment_post_class';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}


add_filter( 'manage_iss_assignment_posts_columns', 'set_custom_edit_iss_assignment_columns' );
add_action( 'manage_iss_assignment_posts_custom_column' , 'custom_iss_assignment_column', 10, 2 );

function set_custom_edit_iss_assignment_columns($columns) {
  //iss_write_log($columns);
    //unset( $columns['author'] );
    $columns['iss_assignment_post_class'] = 'Class';
    $columns['publisher'] = 'Publisher';

    return $columns;
}

function custom_iss_assignment_column( $column, $post_id ) {
   iss_write_log($column);
   switch ( $column ) {

        case 'publisher' :
            $terms = get_the_term_list( $post_id , 'publisher' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
            else
                echo 'Unable to get author(s)';
            break;

        case 'iss_assignment_post_class' :
            echo get_post_meta( $post_id , 'iss_assignment_post_class' , true ); 
            break;

    }
}

function iss_assignment_cpt() {

register_post_type( 'iss_assignment', array(
  'labels' => array(
    'name' => 'Assignments',
    'singular_name' => 'Assignment',
   ),
  'description' => 'Assignment for students',
  'public' => true,
  'menu_position' => 4,
 // 'taxonomies' => array( 'category' ) ,
  'capability_type' => 'iss_assignment',
  'map_meta_cap'=> true,
  'supports' => array( 'title', 'editor')
));
}