<?php
/**
 * Register taxonomies and term meta
 */

// Register Host Taxonomy
function rsm_register_host_taxonomy() {
    $labels = array(
        'name' => 'Hosts',
        'singular_name' => 'Host',
        'search_items' => 'Search Hosts',
        'all_items' => 'All Hosts',
        'edit_item' => 'Edit Host',
        'update_item' => 'Update Host',
        'add_new_item' => 'Add New Host',
        'new_item_name' => 'New Host Name',
        'menu_name' => 'Hosts',
    );
    
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'host'),
        'show_in_rest' => true,
    );
    
    register_taxonomy('show_host_tax', array('radio_show'), $args);
}
add_action('init', 'rsm_register_host_taxonomy');

// Add image upload field to host taxonomy
function rsm_add_host_image_field() {
    ?>
    <div class="form-field term-group">
        <label for="host-image-id"><?php _e('Host Image', 'radio-station-manager'); ?></label>
        <input type="hidden" id="host-image-id" name="host-image-id" class="custom_media_url" value="">
        <div id="host-image-wrapper"></div>
        <p>
            <input type="button" class="button button-secondary rsm_media_button" id="rsm_media_button" name="rsm_media_button" value="<?php _e('Add Image', 'radio-station-manager'); ?>" />
            <input type="button" class="button button-secondary rsm_media_remove" id="rsm_media_remove" name="rsm_media_remove" value="<?php _e('Remove Image', 'radio-station-manager'); ?>" />
        </p>
    </div>
    <?php
}
add_action('show_host_tax_add_form_fields', 'rsm_add_host_image_field', 10, 2);

// Edit host image field
function rsm_edit_host_image_field($term) {
    $image_id = get_term_meta($term->term_id, 'host-image-id', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="host-image-id"><?php _e('Host Image', 'radio-station-manager'); ?></label>
        </th>
        <td>
            <input type="hidden" id="host-image-id" name="host-image-id" value="<?php echo esc_attr($image_id); ?>">
            <div id="host-image-wrapper">
                <?php if ($image_id) { ?>
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                <?php } ?>
            </div>
            <p>
                <input type="button" class="button button-secondary rsm_media_button" id="rsm_media_button" name="rsm_media_button" value="<?php _e('Add Image', 'radio-station-manager'); ?>" />
                <input type="button" class="button button-secondary rsm_media_remove" id="rsm_media_remove" name="rsm_media_remove" value="<?php _e('Remove Image', 'radio-station-manager'); ?>" />
            </p>
        </td>
    </tr>
    <?php
}
add_action('show_host_tax_edit_form_fields', 'rsm_edit_host_image_field', 10, 2);

// Save host image
function rsm_save_host_image($term_id) {
    if (isset($_POST['host-image-id']) && '' !== $_POST['host-image-id']) {
        $image = sanitize_text_field($_POST['host-image-id']);
        update_term_meta($term_id, 'host-image-id', $image);
    } else {
        delete_term_meta($term_id, 'host-image-id');
    }
}
add_action('created_show_host_tax', 'rsm_save_host_image', 10, 2);
add_action('edited_show_host_tax', 'rsm_save_host_image', 10, 2);

// Enqueue media uploader scripts for host taxonomy
function rsm_enqueue_admin_scripts() {
    if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] != 'show_host_tax') {
        return;
    }
    wp_enqueue_media();
    ?>
    <script>
    jQuery(document).ready(function($) {
        function ct_media_upload(button_class) {
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
            
            $('body').on('click', button_class, function(e) {
                var button_id = '#' + $(this).attr('id');
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(button_id);
                _custom_media = true;
                
                wp.media.editor.send.attachment = function(props, attachment) {
                    if (_custom_media) {
                        $('#host-image-id').val(attachment.id);
                        $('#host-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                        $('#host-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                    } else {
                        return _orig_send_attachment.apply(button_id, [props, attachment]);
                    }
                }
                
                wp.media.editor.open(button);
                return false;
            });
        }
        
        ct_media_upload('.rsm_media_button.button');
        
        $('body').on('click', '.rsm_media_remove', function() {
            $('#host-image-id').val('');
            $('#host-image-wrapper').html('');
        });
        
        $(document).ajaxComplete(function(event, xhr, settings) {
            var queryStringArr = settings.data.split('&');
            if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                var xml = xhr.responseXML;
                $response = $(xml).find('term_id').text();
                if ($response != "") {
                    $('#host-image-wrapper').html('');
                }
            }
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'rsm_enqueue_admin_scripts');