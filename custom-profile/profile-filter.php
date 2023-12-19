<?php

if (!defined('ABSPATH')) {
    exit;
}

class ProfileFilter {
    
    /** Constructor
     */
    public function __construct() {

        add_filter( 'page_template', array($this, 'getProfileTemplate' ));
        add_filter( 'theme_page_templates', array($this, 'filterProfilePageTemplates' ), 10, 3);
        add_action( 'init', array($this, 'loadProfileStyle') );

        add_action( 'wp_enqueue_scripts', array($this, 'loadProfileScripts') );

        add_action( 'wp_ajax_nopriv_apply_advanced_filter', array( $this, 'apply_advanced_filter' ));
        add_action( 'wp_ajax_apply_advanced_filter', array( $this, 'apply_advanced_filter' ));
    }

    /**
     * @param mixed $template
     * 
     * @return string
     */
    public function getProfileTemplate( $template ) {
        if(is_page_template('filter-page.php')){
            $template = plugin_dir_path(__FILE__) . "pages/filter-page.php";
        }
        return $template;
    }
    
    /**
     * @param mixed $templates
     * 
     * @return [type]
     */
    public function filterProfilePageTemplates( $templates ) {
        $templates['filter-page.php'] = __('Profile Page');
        return $templates;
    }
    
   public function loadProfileStyle() {
        wp_register_style('wp_filter_style', plugins_url('assets/css/profilepage.css', __FILE__ ));
        wp_enqueue_style('wp_filter_style');
    }

    public function loadProfileScripts() {
        if(is_page_template('filter-page.php')){
            wp_enqueue_script('wp_filter_scripts', plugins_url('assets/js/profilebundle.js', __FILE__ ), array('jquery'), '', true);
            wp_localize_script( 'wp_filter_scripts', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'template_url' => get_template_directory_uri() ));
        }
    }

    /**
     * @return json
     */
        function apply_advanced_filter() {

            $profileArgs = array(
                'post_type' => 'profile',
                'post_status' => 'publish',
                'posts_per_page' => -1, // Retrieves all profiles
                'orderby'=> 'post_date',
                'order' => 'DESC'
            );
            
            $queryProfile = new WP_Query($profileArgs);
            
            if ($queryProfile->have_posts()) {
                $profiles = array();
                $counterNum = 1;
                while ($queryProfile->have_posts()) {
                    $queryProfile->the_post();
                    
                    // Get post ID, title, content, Years of experience, rating, no of jobs completed
                    $profileID = get_the_ID();
                    $profileTitle = esc_html(get_the_title());
                    $profileDOB = get_post_meta($profileID, 'profile_dob', true);
                    $profileYearsExp = get_post_meta($profileID, 'years_of_exp', true);
                    $profileRating = get_post_meta($profileID, 'profileratings', true);
                    $profileNoJobs = get_post_meta($profileID, 'no_jobs_completed', true);
                    $profileLink = esc_url(get_permalink($profileID));
                    $skillsTaxonomy = 'skills';
                    $educationTaxonomy = 'education';
    
                    $profileAge = (date('Y') - date('Y',strtotime($profileDOB))); // calculates age based on DOB
                
                    // Get the skills assigned to the profile
                    $skillsTerms = get_the_terms($profileID, $skillsTaxonomy);
    
                     // Get the education assigned to the profile
                    $educationTerms = get_the_terms($profileID, $educationTaxonomy);
          
                    if ($skillsTerms && !is_wp_error($skillsTerms)) {
                        $skillsArr = array();
                        foreach ($skillsTerms as $skillsTerm) {
                            $skillsName = $skillsTerm->name; // Get the name of the skill
                            $skillsArr[] = $skillsName;
                        }
                    }
    
                    if ($educationTerms && !is_wp_error($educationTerms)) {
                        $educationArr = array();
                        foreach ($educationTerms as $educationTerm) {
                            $educationName = $educationTerm->name; // Get the name of the Education
                            //$educationslug = $educationTerm->slug; // Get the slug of the Education
                            $educationArr[] = $educationName;
                        }
                    }
    
                    $skillsArr =  implode(",",$skillsArr);
                    $educationArr = implode(",",$educationArr);
            
                    // Create an array with desired profile data
                    $profileData = array(
                        'profileID' => $counterNum,
                        'profiletitle' => $profileTitle,
                        'profileAge' => $profileAge,
                        'profileYearsExp' => $profileYearsExp,
                        'profileRating' => $profileRating,
                        'profileNoJobs' => $profileNoJobs,
                        'profileSkills' => $skillsArr,
                        'profileEducation' => $educationArr,
                        'profileLink' => $profileLink
                    );
            
                    // Add profile data to the profiles array
                    $profiles[] = $profileData;
    
                    $counterNum++;
                }
            
                // Reset post data
                wp_reset_postdata();
            }
                  
            if( isset($_POST) && isset($_POST['action']) && isset($_POST['form']) ) {
                $formData = array();
                $searchQuery = [];
    
                $form = $_POST['form'];
                $state = $_POST['state'];
    
                if($state == 'onLoad') {
                    $formData = $profiles;
                } else {
    
                foreach( $form as $key => $val ) {
                    if(is_string($val)) {
                        $searchQuery[$key] = $val;
                    } else {
                        $searchnew = implode(",", $val);
                        $searchQuery[$key] = $searchnew;
                    }
                }
    
                $formData = array_filter($profiles, function ($array) use ($searchQuery) {
                    return (
                            $array['profiletitle'] == $searchQuery['keyword'] ||
                            $array['profileSkills'] == $searchQuery['skills'] ||
                            $array['profileEducation'] == $searchQuery['education'] ||
                            $array['profileAge'] == (int)$searchQuery['age'] ||
                            $array['profileRating'] == (int)$searchQuery['stars'] ||
                            $array['profileNoJobs'] == $searchQuery['noOfJobs'] ||
                            $array['profileYearsExp'] == $searchQuery['experience']
                    );
                });
            }
               
            echo json_encode(array_values(array_filter($formData)));
                    
            } else {
                wp_send_json_error( json_encode('Error: Invalid data!'));
            }
            wp_die();
        } // apply_advanced_filter ends
   
}

$profileFilterObj = new ProfileFilter();