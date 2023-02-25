<?php
/*
Plugin Name: Mobile Featured Image
Plugin URI: https://dossett.dev
Description: Adds a "Mobile Featured Image" field to all posts in the post post type.
Version: 1.0
Author: Jordan 🤠
Author URI:https://github.com/devdossett
*/

// Add the "Mobile Featured Image" field to the post editor screen
function add_mobile_featured_image_meta_box() {
    add_meta_box(
        'mobile-featured-image',
        __( 'Mobile Featured Image', 'textdomain' ),
        'mobile_featured_image_meta_box_callback',
        'post',
        'side'
    );
}

function mobile_featured_image_meta_box_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mobile_featured_image_nonce' );
    $mobile_featured_image_url = get_post_meta( $post->ID, 'mobile_featured_image_url', true );
    ?>
    <div>
        <label for="mobile_featured_image_url"><?php _e( 'Mobile Featured Image', 'textdomain' ); ?></label>
        <br>
        <?php if ( $mobile_featured_image_url ) : ?>
            <img src="<?php echo $mobile_featured_image_url; ?>" style="max-width: 100%;">
            <br><br>
        <?php endif; ?>
        <button type="button" class="button" id="mobile_featured_image_upload_button"><?php _e( 'Select Image', 'textdomain' ); ?></button>
        <input type="hidden" name="mobile_featured_image_url" id="mobile_featured_image_url" value="<?php echo $mobile_featured_image_url; ?>">
    </div>
    <script>
        jQuery(document).ready(function($){
            var mediaUploader;
            $('#mobile_featured_image_upload_button').click(function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: '<?php _e( 'Choose Image', 'textdomain' ); ?>',
                    button: {
                        text: '<?php _e( 'Choose Image', 'textdomain' ); ?>'
                    },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#mobile_featured_image_url').val(attachment.url);
                    $('img').attr('src', attachment.url).css('max-width', '100%');
                });
                mediaUploader.open();
            });
        });
    </script>
    <?php
}

// Save the "Mobile Featured Image" field value when the post is saved or updated
function save_mobile_featured_image_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['mobile_featured_image_nonce'] ) || ! wp_verify_nonce( $_POST['mobile_featured_image_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'post' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['mobile_featured_image_url'] ) ) {
        return;
    }
    $mobile_featured_image_url = sanitize_text_field( $_POST['mobile_featured_image_url'] );
    update_post_meta( $post_id, 'mobile_featured_image_url', $mobile_featured_image_url );
}

add_action( 'add_meta_boxes', 'add_mobile_featured_image_meta_box' );
add_action( 'save_post', 'save_mobile_featured_image_meta_box_data' );

add_action( 'genesis_entry_header', 'custom_header_image_section', 5 );
function custom_header_image_section() {
    // Get the "Mobile Featured Image" field value for the current post
    $mobile_featured_image_url = get_post_meta( get_the_ID(), 'mobile_featured_image_url', true );

    // Define the desktop image URL (992 px and above)
    $desktop_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

    // Output the header image section with a picture tag
    echo '<div class="custom-header-image-section">';
    echo '<picture>';
    
    // Output the mobile image source tag only if the "Mobile Featured Image" field is set
    if ( ! empty( $mobile_featured_image_url ) ) {
        echo '<source srcset="' . $mobile_featured_image_url . '" media="(max-width: 991px)">';
    }
    
    echo '<img src="' . $desktop_image_url . '" alt="' . get_the_title() . '">';
    echo '</picture>';
    echo '</div>';
}