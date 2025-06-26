<?php
// function send_custom_email_phpmailer($to, $subject, $message) {
//     // Use the WordPress built-in PHPMailer class
//     if ( class_exists( 'PHPMailer' ) ) {
//         $mail = new PHPMailer\PHPMailer\PHPMailer();
        
//         try {
//             // Set SMTP configuration (for Gmail or local SMTP server)
//             $mail->isSMTP();                                      // Use SMTP
//             $mail->Host = 'smtp.gmail.com';                         // Set to Gmail's SMTP server or your local SMTP
//             $mail->SMTPAuth = true;                                // Enable SMTP authentication
//             $mail->Username = 'your-email@gmail.com';              // Your SMTP username
//             $mail->Password = 'your-email-password';               // Your SMTP password
//             $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;    // Use TLS encryption
//             $mail->Port = 587;                                    // Use port 587 for TLS

//             // Set sender and recipient info
//             $mail->setFrom('your-email@gmail.com', 'Your Name');   // Sender's email
//             $mail->addAddress($to);                                // Recipient's email

//             // Set email format and content
//             $mail->isHTML(true);                                  // Set email format to HTML
//             $mail->Subject = $subject;
//             $mail->Body    = $message;

//             // Send the email
//             if ($mail->send()) {
//                 echo 'Message has been sent';
//             } else {
//                 echo 'Message could not be sent';
//             }
//         } catch (Exception $e) {
//             echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//         }
//     }
// }

// Enqueue styles and scripts
function child_enqueue_files() {
    // Parent and child theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));

    // Additional styles
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css');
    wp_enqueue_style('login-custom', get_stylesheet_directory_uri() . '/assets/css/login.css');
}
add_action('wp_enqueue_scripts', 'child_enqueue_files');
// Safe manual trigger using admin_init


add_action('wp_ajax_load_ai_tips', 'load_ai_tips_callback');
add_action('wp_ajax_nopriv_load_ai_tips', 'load_ai_tips_callback');

function load_ai_tips_callback() {
    global $wpdb, $current_user;
    wp_get_current_user();

    $table_name = $wpdb->prefix . 'ai_tips';
    $date = sanitize_text_field($_POST['date']);
    $membership_levels = array('free', 'standard', 'premium');
    $user_level = 'free';

    foreach ($membership_levels as $level) {
        if (current_user_can($level)) {
            $user_level = $level;
        }
    }

    $tips = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name 
             WHERE DATE(date) = %s 
             AND FIND_IN_SET(%s, membership)
             ORDER BY id DESC",
            $date,
            $user_level
        )
    );

    if ($tips) {
        foreach ($tips as $tip) {
            $flag = "🇳🇱"; // Static for now; optional to make dynamic later
            echo '
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 tips-card-container">
                    <div class="card-header">
                        ' . strtoupper($user_level) . ' TIP
                    </div>
                    <div class="tipsCard-body">
                        <h5 class="card-title">'. esc_html($tip->match) .'</h5>
                        <p class="mb-1 text-muted">'. $flag .' '. date("jS F Y · H:i", strtotime($tip->date)) .'</p>
                        <p class="mb-2"><strong>AI Tip:</strong> '. esc_html($tip->tip_type) .'</p>
                        <div class="d-flex justify-content-center gap-3">
                            <span class="badge bg-dark text-white fs-6">💰 '. esc_html($tip->odds) .'</span>
                            <span class="badge bg-success text-white fs-6">Stake: $'. esc_html($tip->stake) .'</span>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<div class="col-12 text-muted text-center">No tips found for this date.</div>';
    }

    wp_die();
}


//echo get_template_directory_uri() . '/js/profile-dashboard-ajax.js';
//all update ajax code for the users dashboard
// Enqueue AJAX script for profile dashboard
// function enqueue_profile_dashboard_ajax() {
//     wp_enqueue_script('profile-dashboard-ajax', get_template_directory_uri() . '/js/profile-dashboard-ajax.js', array('jquery'), null, true);
    
//     // Localize script to add ajax_url and nonce
//     wp_localize_script('profile-dashboard-ajax', 'ajax_obj', array(
//         'ajax_url' => admin_url('admin-ajax.php'),
//         'nonce' => wp_create_nonce('profile_dashboard_nonce') // Nonce for security
//     ));
// }
// add_action('wp_enqueue_scripts', 'enqueue_profile_dashboard_ajax');

// In functions.php
function enqueue_profile_dashboard_ajax() {
    if (is_page_template('user-dashboard/dashboard.php')) {
        wp_enqueue_script(
    'profile-dashboard-ajax',
    get_stylesheet_directory_uri() . '/js/profile-dashboard-ajax.js',
    array('jquery'),
    null,
    true
);


        wp_localize_script('profile-dashboard-ajax', 'ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('profile_dashboard_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_profile_dashboard_ajax');



// Change display name
add_action('wp_ajax_update_display_name', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_dashboard_nonce')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $user_id = get_current_user_id();
    $new_name = sanitize_text_field($_POST['display_name']);

    if (strlen($new_name) < 3) {
        wp_send_json_error(['message' => 'Name too short.']);
    }

    wp_update_user([
        'ID' => $user_id,
        'display_name' => $new_name,
        'nickname' => $new_name,
    ]);

    wp_send_json_success();
});

// Change password
add_action('wp_ajax_update_password', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_dashboard_nonce')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $user_id = get_current_user_id();
    $new_password = sanitize_text_field($_POST['password']);

    if (strlen($new_password) < 6) {
        wp_send_json_error(['message' => 'Password too short.']);
    }

    wp_set_password($new_password, $user_id);
    wp_send_json_success();
});


// Update Profile Picture
function update_profile_picture() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_dashboard_nonce')) {
        echo json_encode(array('success' => false, 'message' => 'Security check failed.'));
        wp_die();
    }

    if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
        $uploaded_file = $_FILES['profile_picture'];
        $upload_dir = wp_upload_dir();

        // Check if the file is an image
        $file_type = wp_check_filetype($uploaded_file['name']);
        if (in_array($file_type['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
            $file_path = $upload_dir['path'] . '/' . basename($uploaded_file['name']);
            if (move_uploaded_file($uploaded_file['tmp_name'], $file_path)) {
                $file_url = $upload_dir['url'] . '/' . basename($uploaded_file['name']);
                update_user_meta(get_current_user_id(), 'profile_picture', $file_url);
                echo json_encode(array('success' => true, 'file_url' => $file_url));
            } else {
                echo json_encode(array('success' => false, 'message' => 'There was an error uploading your file.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Please upload a valid image file (JPEG, PNG, or GIF).'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'No file uploaded.'));
    }

    wp_die();
}
add_action('wp_ajax_update_profile_picture', 'update_profile_picture');

// Update Display Name
function update_display_name() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_dashboard_nonce')) {
        echo json_encode(array('success' => false, 'message' => 'Security check failed.'));
        wp_die();
    }

    if (isset($_POST['display_name']) && !empty($_POST['display_name'])) {
        $new_display_name = sanitize_text_field($_POST['display_name']);
        wp_update_user(array('ID' => get_current_user_id(), 'display_name' => $new_display_name));
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Display name cannot be empty.'));
    }

    wp_die();
}
add_action('wp_ajax_update_display_name', 'update_display_name');

// Update Password
function update_password() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'profile_dashboard_nonce')) {
        echo json_encode(array('success' => false, 'message' => 'Security check failed.'));
        wp_die();
    }

    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $new_password = sanitize_text_field($_POST['password']);
        wp_set_password($new_password, get_current_user_id());
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Password cannot be empty.'));
    }

    wp_die();
}
add_action('wp_ajax_update_password', 'update_password');

//all update ajax code for the users dashboard end

// Enqueue assets for the user dashboard
function enqueue_user_dashboard_assets() {
    if (is_page_template('user-dashboard.php')) {
        wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css');
        wp_enqueue_style('meteor', get_stylesheet_directory_uri() . '/assets/css/meteor.min.css');
        wp_enqueue_style('simple-line', get_stylesheet_directory_uri() . '/assets/plugins/line-icons/simple-line-icons.css');
        wp_enqueue_style('dark-layer', get_stylesheet_directory_uri() . '/assets/css/layers/dark-layer.css');
        wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/assets/plugins/fontawesome/css/font-awesome.min.css');

        // JavaScript files
        wp_enqueue_script('jquery-ui', get_stylesheet_directory_uri() . '/assets/plugins/jquery-ui/jquery-ui.min.js', array('jquery'), null, true);
        wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), null, true);
        wp_enqueue_script('waves', get_stylesheet_directory_uri() . '/assets/plugins/waves/waves.min.js', array('jquery'), null, true);
        wp_enqueue_script('meteor', get_stylesheet_directory_uri() . '/assets/js/meteor.min.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_user_dashboard_assets');

// Hide admin bar for subscribers
add_action('after_setup_theme', function () {
    if (current_user_can('subscriber')) {
        show_admin_bar(false);
    }
});

// Allow admins to access WP Admin and restrict subscribers
add_action('admin_init', function () {
    if (is_admin() && !wp_doing_ajax() && current_user_can('subscriber')) {
        wp_redirect(home_url('/user-dashboard/'));
        exit;
    }
});

// Redirect non-logged-in users away from user-dashboard
function restrict_dashboard_access() {
    if (is_page_template('user-dashboard.php') && !is_user_logged_in()) {
        wp_redirect(home_url('/user-login/')); // Redirect to login page
        exit;
    }
}
add_action('template_redirect', 'restrict_dashboard_access');

// Custom login redirect based on user role
function custom_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        // Subscribers go to the user dashboard
        if (in_array('subscriber', $user->roles)) {
            return home_url('/user-dashboard/');
        }
        // Admins and others go to WP Admin
        return admin_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// Ensure logout redirects users correctly
function custom_logout_redirect() {
    wp_redirect(home_url('/user-login/')); // Redirect to login page after logout
    exit();
}
add_action('wp_logout', 'custom_logout_redirect');


function handle_user_dashboard_form() {
    if (isset($_POST['user_dashboard_form'])) {
        // Verify Nonce (Security Check)
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'user_dashboard_action')) {
            wp_die('Security check failed!');
        }

        // Get Current User
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        // Sanitize Inputs
        $user_name = sanitize_text_field($_POST['user_name']);
        $user_email = sanitize_email($_POST['user_email']);

        // Update User Data
        wp_update_user([
            'ID'           => $user_id,
            'display_name' => $user_name,
            'user_email'   => $user_email
        ]);

        // Redirect After Submission
        wp_redirect(add_query_arg('updated', 'true', get_permalink()));
        exit;
    }
}
add_action('init', 'handle_user_dashboard_form');

//menu
function custom_user_menu_item($items, $args) {
    if ($args->theme_location !== 'primary') {
        return $items;
    }

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        
        if (in_array('subscriber', $current_user->roles)) {
            $dashboard_url = site_url('/user-dashboard/');
            $logout_url = wp_logout_url(site_url('/user-login/')); // Redirect after logout
            
            $items .= '<li class="menu-item"><a href="' . esc_url($dashboard_url) . '">Dashboard</a></li>';
        }
    } else {
        $login_url = site_url('/user-login/');
        $register_url = site_url('/user-register/');

        $items .= '<li class="menu-item"><a href="' . esc_url($login_url) . '">Login</a></li>';
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'custom_user_menu_item', 10, 2);



add_filter('show_admin_bar', function($show) {
    return current_user_can('administrator') ? $show : false;
});
