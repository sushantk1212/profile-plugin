<?php

/**
 * Plugin Name: Custom Profile Plugin
 * Description: Registers custom post type Profile, Skills, Education, Interests and hobbies taxonomies and custom fields.
 * Version: 1.0
 * Author: Sushant Khadilkar
 * Text Domain: customprofile
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CustomProfile')) {
    class CustomProfile
    {

        /**
         * Description: Constructor with activation hook, metabox and save custom fields function.
         */
        public function __construct()
        {
            register_activation_hook(__FILE__, array($this, 'activateProfilePlugin'));
            add_action('init', array($this, 'registerProfilePostType'));
            add_action('init', array($this, 'registerProfileTaxonomies'));
            add_action('add_meta_boxes', array($this, 'addProfileCustomFields'));
            add_action('save_post', array($this, 'saveProfileCustomFields'));
        }

        /**
         * Description : On activation of plugin, register post type, taxonomy and custom fields.
         */
        public function activateProfilePlugin()
        {
            $this->registerProfilePostType();
            $this->registerProfileTaxonomies();
            $this->addProfileCustomFields();
            $this->saveProfileCustomFields($post_id);
            flush_rewrite_rules();
        }

        /**
         * Description: register post type profile
         * @return [type]
         */
        public function registerProfilePostType()
        {
            $profileLabels = array(
                'name'               => _x('Profiles', 'post type general name', 'customprofile'),
                'singular_name'      => _x('Profile', 'post type singular name', 'customprofile'),
                'menu_name'          => _x('Profile', 'admin menu', 'customprofile'),
                'name_admin_bar'     => _x('Profile', 'add new on admin bar', 'customprofile'),
                'add_new'            => _x('Add New', 'profile', 'customprofile'),
                'add_new_item'       => __('Add New Profile', 'customprofile'),
                'new_item'           => __('New Profile', 'customprofile'),
                'edit_item'          => __('Edit Profile', 'customprofile'),
                'view_item'          => __('View Profile', 'customprofile'),
                'all_items'          => __('All Profiles', 'customprofile'),
                'search_items'       => __('Search Profiles', 'customprofile'),
                'parent_item_colon'  => __('Parent Profiles:', 'customprofile'),
                'not_found'          => __('No profiles found.', 'customprofile'),
                'not_found_in_trash' => __('No profiles found in Trash.', 'customprofile')
            );

            $profileArgs = array(
                'labels'             => $profileLabels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array('slug' => 'profile'),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields')
            );

            register_post_type('profile', $profileArgs);
        } // registerProfilePostType ends

        /**
         * Description: Register taxonomies Skills, Education, Hobbies and Interests
         * @return [type]
         */
        public function registerProfileTaxonomies()
        {
            $profileTaxonomies = array(
                'skills' => 'Skills',
                'education' => 'Education',
                'hobbies' => 'Hobbies',
                'interests' => 'Interests'
            );

            foreach ($profileTaxonomies as $slug => $label) {
                $labels = array(
                    'name'              => _x($label, 'taxonomy general name', 'customprofile'),
                    'singular_name'     => _x($label, 'taxonomy singular name', 'customprofile'),
                    'search_items'      => __('Search ' . $label, 'customprofile'),
                    'all_items'         => __('All ' . $label, 'customprofile'),
                    'parent_item'       => __('Parent ' . $label, 'customprofile'),
                    'parent_item_colon' => __('Parent ' . $label . ':', 'customprofile'),
                    'edit_item'         => __('Edit ' . $label, 'customprofile'),
                    'update_item'       => __('Update ' . $label, 'customprofile'),
                    'add_new_item'      => __('Add New ' . $label, 'customprofile'),
                    'new_item_name'     => __('New ' . $label . ' Name', 'customprofile'),
                    'menu_name'         => __($label, 'customprofile'),
                );

                $args = array(
                    'hierarchical'      => true,
                    'labels'            => $labels,
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'rewrite'           => array('slug' => $slug),
                );

                register_taxonomy($slug, 'profile', $args); // register taxonomies
            } // foreach ends
        } //registerProfileTaxonomies ends

        public function addProfileCustomFields()
        {
            add_meta_box('profile_fields', 'Profile Fields', array($this, 'renderProfileCustomFields'), 'profile', 'normal', 'default');
        } // add profile custom fields

        public function renderProfileCustomFields($post)
        {

            $selectedVal = 'selected="selected"';

            // Retrieve existing values for fields
            $profileDob = get_post_meta($post->ID, 'profile_dob', true); // get DOB
            $years_of_exp = get_post_meta($post->ID, 'years_of_exp', true); // get Years of Experience
            $profileRatings = get_post_meta($post->ID, 'profileratings', true); // get Profile Ratings
            $noJobsCompleted = get_post_meta($post->ID, 'no_jobs_completed', true); // get no of jobs completed

            // Output fields
?>
            <p>
                <label for="dob">DOB:</label>
                <input type="date" id="profile_dob" name="profile_dob" value="<?php echo esc_attr($profileDob); ?>">
            </p>
            <p>
                <label for="years_of_exp">Years of Experience:</label>
                <input type="number" min="1" step="1" id="years_of_exp" name="years_of_exp"
                    value="<?php echo esc_attr($years_of_exp); ?>">
            </p>
            <p>
                <label for="profileratings">Ratings:</label>
                <select name="profileratings" id="profileratings" style="width:70px">
                    <option value="1" <?php if ($profileRatings == "1") echo $selectedVal; ?>>1</option>
                    <option value="2" <?php if ($profileRatings == "2") echo $selectedVal; ?>>2</option>
                    <option value="3" <?php if ($profileRatings == "3") echo $selectedVal; ?>>3</option>
                    <option value="4" <?php if ($profileRatings == "4") echo $selectedVal; ?>>4</option>
                    <option value="5" <?php if ($profileRatings == "5") echo $selectedVal; ?>>5</option>
                </select>
            </p>
            <p>
                <label for="no_jobs_completed">No of jobs completed:</label>
                <input type="number" min="1" step="1" id="no_jobs_completed" name="no_jobs_completed"
                    value="<?php echo esc_attr($noJobsCompleted); ?>">
            </p>
<?php
        } // renderProfileCustomFields ends

        public function saveProfileCustomFields($post_id)
        {

            // Save profile custom fields data
            if (isset($_POST['profile_dob'])) {
                update_post_meta($post_id, 'profile_dob', sanitize_text_field($_POST['profile_dob']));
            }
            if (isset($_POST['years_of_exp'])) {
                update_post_meta($post_id, 'years_of_exp', sanitize_text_field($_POST['years_of_exp']));
            }
            if (isset($_POST['profileratings'])) {
                update_post_meta($post_id, 'profileratings', sanitize_text_field($_POST['profileratings']));
            }
            if (isset($_POST['no_jobs_completed'])) {
                update_post_meta($post_id, 'no_jobs_completed', sanitize_text_field($_POST['no_jobs_completed']));
            }
        } //saveProfileCustomFields ends

    } // class Custom_Profile ends


} // !class_exists ends

// Instantiate the class
$CustomProfileObj = new CustomProfile(); // created object of class CustomProfile

require_once 'profile-filter.php';
