<?php

namespace TUTOR_CERT;

if (!defined('ABSPATH'))
    exit;

class Instructor_Signature {
    private $file_name_string = 'tutor_pro_custom_signature_file';
    private $file_id_string = 'tutor_pro_custom_signature_id';
    private $image_meta = 'tutor_pro_custom_signature_image_id';
    private $image_post_identifier = 'tutor_pro_custom_signature_image';

    function __construct($register_handlers = true) {
        if ($register_handlers) {

            add_action('tutor_profile_edit_input_after', array($this, 'custom_signature_field'));

            add_action('tutor_profile_update_before', array($this, 'save_custom_signature'));
        }
    }

    public function custom_signature_field($user) {

        if (!$user || !is_object($user) || !tutor_utils()->is_instructor($user->ID,true)) {
            // It is non instructor user
            return;
        }

        $signature = $this->get_instructor_signature($user->ID);
        $placeholder_signature = tutor_pro()->url . 'addons/tutor-certificate/assets/images/instructor-signature.svg';

        include TUTOR_CERT()->path . '/views/signature-field.php';
    }

    public function save_custom_signature($user_id) {
        $media_id = tutor_utils()->array_get( $this->file_id_string, $_POST, '' );

        if (!is_numeric( $media_id )) {
            // Unlink signature from user meta
            delete_user_meta($user_id, $this->image_meta);
        } else {
            update_user_meta( $user_id, $this->image_meta, $media_id );
        }
    }

    public function get_instructor_signature($user_id) {
        // Get personal signature image from user meta
        $id = get_user_meta($user_id, $this->image_meta, true);
        $valid = is_numeric($id);

        return [
            'id' => $valid ? $id : null,
            'url' => $valid ? wp_get_attachment_url($id) : null
        ];
    }
}
