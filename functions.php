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



//subscription status dashboard

function create_subscription_tables() {
    global $wpdb;
    
    // User subscriptions table
    $subscriptions_table = $wpdb->prefix . 'user_subscriptions';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $subscriptions_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        package_type varchar(20) NOT NULL,
        stripe_subscription_id varchar(255),
        stripe_customer_id varchar(255),
        start_date datetime NOT NULL,
        end_date datetime NOT NULL,
        status varchar(20) DEFAULT 'active',
        amount decimal(10,2) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Payment transactions table
    $transactions_table = $wpdb->prefix . 'payment_transactions';
    
    $sql2 = "CREATE TABLE $transactions_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        subscription_id int(11),
        stripe_payment_id varchar(255),
        amount decimal(10,2) NOT NULL,
        currency varchar(3) DEFAULT 'USD',
        status varchar(20) NOT NULL,
        payment_date datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    dbDelta($sql2);
}
add_action('after_switch_theme', 'create_subscription_tables');

// Define subscription packages
function get_subscription_packages() {
    return array(
        'free' => array(
            'name' => 'Free Plan',
            'price' => 0,
            'duration' => 30, // days
            'features' => array('Access to free tips only', 'Basic support'),
            'stripe_price_id' => '' // No Stripe for free
        ),
        'standard' => array(
            'name' => 'Standard Plan',
            'price' => 29.99,
            'duration' => 30,
            'features' => array('Access to standard tips', 'Email support', '5 tips per day'),
            'stripe_price_id' => 'price_standard_monthly' // Replace with actual Stripe price ID
        ),
        'premium' => array(
            'name' => 'Premium Plan',
            'price' => 49.99,
            'duration' => 30,
            'features' => array('Access to all tips', 'Priority support', 'Unlimited tips', 'Analytics'),
            'stripe_price_id' => 'price_premium_monthly' // Replace with actual Stripe price ID
        )
    );
}

// Check user's current subscription
function get_user_subscription($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'user_subscriptions';
    
    $subscription = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE user_id = %d AND status = 'active' AND end_date > NOW() ORDER BY id DESC LIMIT 1",
        $user_id
    ));
    
    return $subscription;
}

// Check if user has access to specific membership level
function user_has_access($user_id, $required_level) {
    $subscription = get_user_subscription($user_id);
    
    if (!$subscription) {
        return $required_level === 'free';
    }
    
    $levels = array('free' => 1, 'standard' => 2, 'premium' => 3);
    $user_level = isset($levels[$subscription->package_type]) ? $levels[$subscription->package_type] : 1;
    $required_level_num = isset($levels[$required_level]) ? $levels[$required_level] : 1;
    
    return $user_level >= $required_level_num;
}

// Add custom user capabilities
function add_subscription_capabilities($user_id, $package_type) {
    $user = get_user_by('id', $user_id);
    if ($user) {
        // Remove existing capabilities
        $user->remove_cap('free');
        $user->remove_cap('standard');
        $user->remove_cap('premium');
        
        // Add new capability
        $user->add_cap($package_type);
        
        // Add higher level access
        if ($package_type === 'premium') {
            $user->add_cap('standard');
            $user->add_cap('free');
        } elseif ($package_type === 'standard') {
            $user->add_cap('free');
        }
    }
}

// AJAX handler for loading AI tips with membership check
function load_ai_tips_with_membership() {
    if (!is_user_logged_in()) {
        wp_die('Unauthorized');
    }
    
    $date = sanitize_text_field($_POST['date']);
    $user_id = get_current_user_id();
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'ai_tips';
    
    // Get all tips for the date
    $tips = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE DATE(date) = %s ORDER BY date ASC",
        $date
    ));
    
    if (empty($tips)) {
        echo '<div class="col-12 text-center p-5 text-white">No tips available for this date.</div>';
        wp_die();
    }
    
    $output = '<div class="row">';
    
    foreach ($tips as $tip) {
        $memberships = explode(',', $tip->membership);
        $has_access = false;
        
        // Check if user has access to any of the required membership levels
        foreach ($memberships as $membership) {
            if (user_has_access($user_id, trim($membership))) {
                $has_access = true;
                break;
            }
        }
        
        $output .= '<div class="col-md-6 col-lg-4 mb-4">';
        $output .= '<div class="tip-card ' . ($has_access ? 'accessible' : 'locked') . '">';
        
        if ($has_access) {
            $output .= generate_tip_card_content($tip);
        } else {
            $output .= generate_locked_tip_card($tip, $memberships);
        }
        
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    echo $output;
    wp_die();
}
add_action('wp_ajax_load_ai_tips', 'load_ai_tips_with_membership');

// Generate accessible tip card content
function generate_tip_card_content($tip) {
    $status_class = '';
    if ($tip->tip_result === 'WON') {
        $status_class = 'won';
    } elseif ($tip->tip_result === 'LOST') {
        $status_class = 'lost';
    } else {
        $status_class = 'pending';
    }
    
    return '
        <div class="tip-header">
            <h5>' . esc_html($tip->match) . '</h5>
            <span class="tip-status ' . $status_class . '">' . esc_html($tip->tip_result) . '</span>
        </div>
        <div class="tip-content">
            <div class="tip-info">
                <span class="tip-type">' . esc_html($tip->tip_type) . '</span>
                <span class="odds">Odds: ' . esc_html($tip->odds) . '</span>
            </div>
            <div class="tip-details">
                <p><strong>Stake:</strong> $' . esc_html($tip->stake) . '</p>
                <p><strong>Potential Return:</strong> $' . esc_html($tip->return_amt) . '</p>
                <p><strong>Profit:</strong> ' . esc_html($tip->profit) . '%</p>
                ' . ($tip->result ? '<p><strong>Result:</strong> ' . esc_html($tip->result) . '</p>' : '') . '
            </div>
            <div class="tip-footer">
                <small>Match Time: ' . date('H:i', strtotime($tip->date)) . '</small>
            </div>
        </div>';
}

// Generate locked tip card content
function generate_locked_tip_card($tip, $memberships) {
    $membership_text = implode(', ', array_map('ucfirst', $memberships));
    
    return '
        <div class="tip-header locked">
            <h5>' . esc_html($tip->match) . '</h5>
            <span class="locked-badge">🔒 LOCKED</span>
        </div>
        <div class="tip-content locked-content">
            <div class="lock-message">
                <i class="fas fa-lock"></i>
                <p>This tip requires <strong>' . $membership_text . '</strong> membership</p>
                <a href="' . home_url('/subscription-packages') . '" class="upgrade-btn">Upgrade Now</a>
            </div>
        </div>';
}

// Stripe webhook handler
function handle_stripe_webhook() {
    if (!isset($_GET['stripe_webhook'])) {
        return;
    }
    
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $endpoint_secret = 'whsec_your_webhook_secret'; // Replace with your webhook secret
    
    try {
        require_once get_template_directory() . '/stripe-php/init.php'; // Include Stripe PHP library
        
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        
        switch ($event['type']) {
            case 'checkout.session.completed':
                handle_successful_payment($event['data']['object']);
                break;
            case 'invoice.payment_succeeded':
                handle_subscription_renewal($event['data']['object']);
                break;
            case 'customer.subscription.deleted':
                handle_subscription_cancellation($event['data']['object']);
                break;
        }
        
        http_response_code(200);
        
    } catch(\UnexpectedValueException $e) {
        http_response_code(400);
        exit();
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
        http_response_code(400);
        exit();
    }
    
    exit();
}
add_action('init', 'handle_stripe_webhook');

// Handle successful payment
function handle_successful_payment($session) {
    global $wpdb;
    
    $user_id = $session['client_reference_id'];
    $subscription_id = $session['subscription'];
    $customer_id = $session['customer'];
    $amount = $session['amount_total'] / 100; // Convert from cents
    
    // Get package type from metadata
    $package_type = $session['metadata']['package_type'] ?? 'standard';
    
    // Create subscription record
    $subscriptions_table = $wpdb->prefix . 'user_subscriptions';
    $start_date = current_time('mysql');
    $end_date = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $wpdb->insert($subscriptions_table, array(
        'user_id' => $user_id,
        'package_type' => $package_type,
        'stripe_subscription_id' => $subscription_id,
        'stripe_customer_id' => $customer_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => 'active',
        'amount' => $amount
    ));
    
    $subscription_db_id = $wpdb->insert_id;
    
    // Create transaction record
    $transactions_table = $wpdb->prefix . 'payment_transactions';
    $wpdb->insert($transactions_table, array(
        'user_id' => $user_id,
        'subscription_id' => $subscription_db_id,
        'stripe_payment_id' => $session['payment_intent'],
        'amount' => $amount,
        'status' => 'completed'
    ));
    
    // Update user capabilities
    add_subscription_capabilities($user_id, $package_type);
    
    // Send confirmation email
    send_subscription_confirmation_email($user_id, $package_type);
}

// Handle subscription renewal
function handle_subscription_renewal($invoice) {
    global $wpdb;
    
    $subscription_id = $invoice['subscription'];
    $customer_id = $invoice['customer'];
    $amount = $invoice['amount_paid'] / 100;
    
    // Find existing subscription
    $subscriptions_table = $wpdb->prefix . 'user_subscriptions';
    $subscription = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $subscriptions_table WHERE stripe_subscription_id = %s",
        $subscription_id
    ));
    
    if ($subscription) {
        // Extend subscription by 30 days
        $new_end_date = date('Y-m-d H:i:s', strtotime($subscription->end_date . ' +30 days'));
        
        $wpdb->update($subscriptions_table, 
            array('end_date' => $new_end_date, 'status' => 'active'),
            array('id' => $subscription->id)
        );
        
        // Create transaction record
        $transactions_table = $wpdb->prefix . 'payment_transactions';
        $wpdb->insert($transactions_table, array(
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'stripe_payment_id' => $invoice['payment_intent'],
            'amount' => $amount,
            'status' => 'completed'
        ));
        
        // Update user capabilities
        add_subscription_capabilities($subscription->user_id, $subscription->package_type);
    }
}

// Handle subscription cancellation
function handle_subscription_cancellation($subscription) {
    global $wpdb;
    
    $subscriptions_table = $wpdb->prefix . 'user_subscriptions';
    $db_subscription = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $subscriptions_table WHERE stripe_subscription_id = %s",
        $subscription['id']
    ));
    
    if ($db_subscription) {
        // Mark subscription as cancelled
        $wpdb->update($subscriptions_table,
            array('status' => 'cancelled'),
            array('id' => $db_subscription->id)
        );
        
        // Remove user capabilities (keep only free access)
        add_subscription_capabilities($db_subscription->user_id, 'free');
    }
}

// Send subscription confirmation email
function send_subscription_confirmation_email($user_id, $package_type) {
    $user = get_user_by('id', $user_id);
    $packages = get_subscription_packages();
    $package = $packages[$package_type];
    
    $subject = 'Subscription Confirmation - ' . $package['name'];
    $message = "
        <h2>Welcome to {$package['name']}!</h2>
        <p>Dear {$user->display_name},</p>
        <p>Thank you for subscribing to our {$package['name']}. Your subscription is now active!</p>
        <p><strong>Package Details:</strong></p>
        <ul>
            <li>Plan: {$package['name']}</li>
            <li>Price: $" . number_format($package['price'], 2) . "</li>
            <li>Duration: {$package['duration']} days</li>
        </ul>
        <p>You can now access your tips from your dashboard.</p>
        <p><a href='" . home_url('/ai-tips-viewer') . "'>View Your Tips</a></p>
        <p>Thank you for choosing our service!</p>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($user->user_email, $subject, $message, $headers);
}

// Daily cron job to check expired subscriptions
function check_expired_subscriptions() {
    global $wpdb;
    
    $subscriptions_table = $wpdb->prefix . 'user_subscriptions';
    
    // Get expired subscriptions
    $expired_subscriptions = $wpdb->get_results(
        "SELECT * FROM $subscriptions_table WHERE status = 'active' AND end_date < NOW()"
    );
    
    foreach ($expired_subscriptions as $subscription) {
        // Mark as expired
        $wpdb->update($subscriptions_table,
            array('status' => 'expired'),
            array('id' => $subscription->id)
        );
        
        // Remove user capabilities (keep only free access)
        add_subscription_capabilities($subscription->user_id, 'free');
        
        // Send expiration email
        send_subscription_expiration_email($subscription->user_id, $subscription->package_type);
    }
}

// Schedule daily cron job
if (!wp_next_scheduled('check_expired_subscriptions_hook')) {
    wp_schedule_event(time(), 'daily', 'check_expired_subscriptions_hook');
}
add_action('check_expired_subscriptions_hook', 'check_expired_subscriptions');

// Send subscription expiration email
function send_subscription_expiration_email($user_id, $package_type) {
    $user = get_user_by('id', $user_id);
    $packages = get_subscription_packages();
    $package = $packages[$package_type];
    
    $subject = 'Subscription Expired - ' . $package['name'];
    $message = "
        <h2>Your subscription has expired</h2>
        <p>Dear {$user->display_name},</p>
        <p>Your {$package['name']} subscription has expired. You now have access to free tips only.</p>
        <p>To continue enjoying premium features, please renew your subscription:</p>
        <p><a href='" . home_url('/subscription-packages') . "'>Renew Subscription</a></p>
        <p>Thank you for using our service!</p>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($user->user_email, $subject, $message, $headers);
}

// Shortcode to display user's current subscription status
function subscription_status_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your subscription status.</p>';
    }
    
    $user_id = get_current_user_id();
    $subscription = get_user_subscription($user_id);
    $packages = get_subscription_packages();
    
    if (!$subscription) {
        return '
            <div class="subscription-status free">
                <h3>Free Plan</h3>
                <p>You are currently on the free plan.</p>
                <a href="' . home_url('/subscription-packages') . '" class="btn btn-primary">Upgrade Now</a>
            </div>';
    }
    
    $package = $packages[$subscription->package_type];
    $days_left = ceil((strtotime($subscription->end_date) - time()) / (60 * 60 * 24));
    
    return '
        <div class="subscription-status ' . $subscription->package_type . '">
            <h3>' . $package['name'] . '</h3>
            <p><strong>Status:</strong> ' . ucfirst($subscription->status) . '</p>
            <p><strong>Expires:</strong> ' . date('F j, Y', strtotime($subscription->end_date)) . ' (' . $days_left . ' days left)</p>
            <p><strong>Amount:</strong> $' . number_format($subscription->amount, 2) . '</p>
            ' . ($days_left <= 7 ? '<div class="renewal-reminder"><p><strong>Your subscription expires soon!</strong></p><a href="' . home_url('/subscription-packages') . '" class="btn btn-warning">Renew Now</a></div>' : '') . '
        </div>';
}
add_shortcode('subscription_status', 'subscription_status_shortcode');


//custom stripe integration

// AJAX handler to create Stripe checkout session
function create_stripe_checkout_session() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'checkout_nonce')) {
        wp_die(json_encode(['success' => false, 'message' => 'Security check failed']));
    }
    
    if (!is_user_logged_in()) {
        wp_die(json_encode(['success' => false, 'message' => 'Please log in to continue']));
    }
    
    $package_type = sanitize_text_field($_POST['package_type']);
    $package_name = sanitize_text_field($_POST['package_name']);
    $price = floatval($_POST['price']);
    $user_id = intval($_POST['user_id']);
    
    // Validate package
    $packages = get_subscription_packages();
    if (!isset($packages[$package_type])) {
        wp_die(json_encode(['success' => false, 'message' => 'Invalid package selected']));
    }
    
    $package = $packages[$package_type];
    
    try {
        // Include Stripe PHP library
        require_once get_template_directory() . '/stripe-php/init.php';
        
        // Set Stripe secret key
        \Stripe\Stripe::setApiKey(get_option('stripe_secret_key', 'sk_test_your_key_here'));
        
        $user = get_userdata($user_id);
        
        // Create or retrieve Stripe customer
        $customer = create_or_get_stripe_customer($user);
        
        // Create checkout session
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'client_reference_id' => $user_id,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $package_name,
                        'description' => 'AI Tips ' . $package_name . ' - 30 days access',
                    ],
                    'unit_amount' => $price * 100, // Convert to cents
                    'recurring' => [
                        'interval' => 'month',
                        'interval_count' => 1,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => home_url('/subscription-packages?success=true'),
            'cancel_url' => home_url('/subscription-packages?canceled=true'),
            'metadata' => [
                'package_type' => $package_type,
                'user_id' => $user_id,
                'package_name' => $package_name,
            ],
            'subscription_data' => [
                'metadata' => [
                    'package_type' => $package_type,
                    'user_id' => $user_id,
                ],
            ],
        ]);
        
        wp_die(json_encode([
            'success' => true,
            'session_id' => $session->id
        ]));
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log('Stripe API Error: ' . $e->getMessage());
        wp_die(json_encode([
            'success' => false,
            'message' => 'Payment processing error. Please try again.'
        ]));
    } catch (Exception $e) {
        error_log('Checkout Error: ' . $e->getMessage());
        wp_die(json_encode([
            'success' => false,
            'message' => 'Something went wrong. Please try again.'
        ]));
    }
}
add_action('wp_ajax_create_checkout_session', 'create_stripe_checkout_session');

// Create or get existing Stripe customer
function create_or_get_stripe_customer($user) {
    // Check if user already has a Stripe customer ID
    $stripe_customer_id = get_user_meta($user->ID, 'stripe_customer_id', true);
    
    if ($stripe_customer_id) {
        try {
            // Retrieve existing customer
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);
            return $customer;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Customer doesn't exist, create new one
            error_log('Stripe customer not found: ' . $e->getMessage());
        }
    }
    
    // Create new customer
    $customer = \Stripe\Customer::create([
        'email' => $user->user_email,
        'name' => $user->display_name,
        'metadata' => [
            'user_id' => $user->ID,
            'username' => $user->user_login,
        ],
    ]);
    
    // Save customer ID to user meta
    update_user_meta($user->ID, 'stripe_customer_id', $customer->id);
    
    return $customer;
}

// Admin settings page for Stripe keys
function add_stripe_settings_page() {
    add_options_page(
        'Stripe Settings',
        'Stripe Settings',
        'manage_options',
        'stripe-settings',
        'stripe_settings_page'
    );
}
add_action('admin_menu', 'add_stripe_settings_page');

function stripe_settings_page() {
    if (isset($_POST['submit'])) {
        update_option('stripe_publishable_key', sanitize_text_field($_POST['stripe_publishable_key']));
        update_option('stripe_secret_key', sanitize_text_field($_POST['stripe_secret_key']));
        update_option('stripe_webhook_secret', sanitize_text_field($_POST['stripe_webhook_secret']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $publishable_key = get_option('stripe_publishable_key', '');
    $secret_key = get_option('stripe_secret_key', '');
    $webhook_secret = get_option('stripe_webhook_secret', '');
    ?>
    
    <div class="wrap">
        <h1>Stripe Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">Publishable Key</th>
                    <td>
                        <input type="text" name="stripe_publishable_key" value="<?php echo esc_attr($publishable_key); ?>" class="regular-text" />
                        <p class="description">Your Stripe publishable key (pk_test_... or pk_live_...)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Secret Key</th>
                    <td>
                        <input type="password" name="stripe_secret_key" value="<?php echo esc_attr($secret_key); ?>" class="regular-text" />
                        <p class="description">Your Stripe secret key (sk_test_... or sk_live_...)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Webhook Secret</th>
                    <td>
                        <input type="password" name="stripe_webhook_secret" value="<?php echo esc_attr($webhook_secret); ?>" class="regular-text" />
                        <p class="description">Your Stripe webhook endpoint secret (whsec_...)</p>
                    </td>
                </tr>
            </table>
            
            <h2>Webhook Setup Instructions</h2>
            <ol>
                <li>Go to your Stripe Dashboard → Developers → Webhooks</li>
                <li>Click "Add endpoint"</li>
                <li>Enter this URL: <code><?php echo home_url('/?stripe_webhook=1'); ?></code></li>
                <li>Select these events:
                    <ul>
                        <li>checkout.session.completed</li>
                        <li>invoice.payment_succeeded</li>
                        <li>customer.subscription.deleted</li>
                        <li>customer.subscription.updated</li>
                    </ul>
                </li>
                <li>Copy the webhook signing secret and paste it above</li>
            </ol>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Enhanced webhook handler with better error handling
function handle_stripe_webhook_enhanced() {
    if (!isset($_GET['stripe_webhook'])) {
        return;
    }
    
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $endpoint_secret = get_option('stripe_webhook_secret', '');
    
    if (empty($endpoint_secret)) {
        error_log('Stripe webhook secret not configured');
        http_response_code(400);
        exit();
    }
    
    try {
        require_once get_template_directory() . '/stripe-php/init.php';
        
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        
        // Log the event for debugging
        error_log('Stripe webhook received: ' . $event['type']);
        
        switch ($event['type']) {
            case 'checkout.session.completed':
                handle_successful_payment_enhanced($event['data']['object']);
                break;
                
            case 'invoice.payment_succeeded':
                handle_subscription_renewal_enhanced($event['data']['object']);
                break;
                
            case 'customer.subscription.deleted':
            case 'customer.subscription.updated':
                handle_subscription_change($event['data']['object']);
                break;
                
            default:
                error_log('Unhandled webhook event type: ' . $event['type']);
        }
        
        http_response_code(200);
        echo json_encode(['status' => 'success']);
        
    } catch(\UnexpectedValueException $e) {
        error_log('Invalid webhook payload: ' . $e->getMessage());
        http_response_code(400);
        echo json_encode(['error' => 'Invalid payload']);
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
        error_log('Webhook signature verification failed: ' . $e->getMessage());
        http_response_code(400);
        echo json_encode(['error' => 'Invalid signature']);
    } catch(Exception $e) {
        error_log('Webhook processing error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Processing error']);
    }
    
    exit();
}
add_action('init', 'handle_stripe_webhook_enhanced');

// Enhanced successful payment handler
function handle_successful_payment_enhanced($session) {
    global $wpdb;
    
    $user_id = $session['client_reference_id'];
    $customer_id = $session['customer'];
    $subscription_id = $session['subscription'] ?? null;
    $amount = $session['amount_total'] / 100;
    
    // Get package type from metadata
    $package_type = $session['metadata']['package_type'] ?? 'standard';
    
    // Validate user exists
    if (!get_userdata($user_id)) {
        error_log('Invalid user ID in webhook: ' . $user_id);
        return;
    }
    
    // Check if this session was already processed
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}payment_transactions WHERE stripe_payment_id = %s",
        $session['id']
    ));
    
    if ($existing) {
        error_log('Duplicate webhook event for session: ' . $session['id']);
        return;
    }
    
    // Deactivate any existing active subscriptions for this user
    $wpdb->update(
        $wpdb->prefix . 'user_subscriptions',
        ['status' => 'replaced'],
        ['user_id' => $user_id, 'status' => 'active']
    );
    
    // Create new subscription record
    $start_date = current_time('mysql');
    $end_date = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $subscription_data = [
        'user_id' => $user_id,
        'package_type' => $package_type,
        'stripe_subscription_id' => $subscription_id,
        'stripe_customer_id' => $customer_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => 'active',
        'amount' => $amount
    ];
    
    $result = $wpdb->insert($wpdb->prefix . 'user_subscriptions', $subscription_data);
    
    if ($result === false) {
        error_log('Failed to create subscription record for user: ' . $user_id);
        return;
    }
    
    $subscription_db_id = $wpdb->insert_id;
    
    // Create transaction record
    $transaction_data = [
        'user_id' => $user_id,
        'subscription_id' => $subscription_db_id,
        'stripe_payment_id' => $session['id'],
        'amount' => $amount,
        'status' => 'completed'
    ];
    
    $wpdb->insert($wpdb->prefix . 'payment_transactions', $transaction_data);
    
    // Update user capabilities
    add_subscription_capabilities($user_id, $package_type);
    
    // Send confirmation email
    send_subscription_confirmation_email($user_id, $package_type);
    
    error_log('Successfully processed payment for user: ' . $user_id . ', package: ' . $package_type);
}

// Enhanced subscription renewal handler
function handle_subscription_renewal_enhanced($invoice) {
    global $wpdb;
    
    $subscription_id = $invoice['subscription'];
    $customer_id = $invoice['customer'];
    $amount = $invoice['amount_paid'] / 100;
    
    // Find existing subscription
    $subscription = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}user_subscriptions WHERE stripe_subscription_id = %s ORDER BY id DESC LIMIT 1",
        $subscription_id
    ));
    
    if (!$subscription) {
        error_log('Subscription not found for renewal: ' . $subscription_id);
        return;
    }
    
    // Check if this invoice was already processed
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}payment_transactions WHERE stripe_payment_id = %s",
        $invoice['id']
    ));
    
    if ($existing) {
        error_log('Duplicate renewal webhook for invoice: ' . $invoice['id']);
        return;
    }
    
    // Extend subscription
    $current_end = $subscription->end_date;
    $new_end_date = date('Y-m-d H:i:s', strtotime($current_end . ' +30 days'));
    
    $wpdb->update(
        $wpdb->prefix . 'user_subscriptions',
        [
            'end_date' => $new_end_date,
            'status' => 'active'
        ],
        ['id' => $subscription->id]
    );
    
    // Create transaction record
    $wpdb->insert($wpdb->prefix . 'payment_transactions', [
        'user_id' => $subscription->user_id,
        'subscription_id' => $subscription->id,
        'stripe_payment_id' => $invoice['id'],
        'amount' => $amount,
        'status' => 'completed'
    ]);
    
    // Ensure user capabilities are active
    add_subscription_capabilities($subscription->user_id, $subscription->package_type);
    
    error_log('Successfully renewed subscription for user: ' . $subscription->user_id);
}

// Handle subscription changes (cancellation, updates)
function handle_subscription_change($subscription) {
    global $wpdb;
    
    $subscription_id = $subscription['id'];
    $status = $subscription['status'];
    
    // Find subscription in database
    $db_subscription = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}user_subscriptions WHERE stripe_subscription_id = %s",
        $subscription_id
    ));
    
    if (!$db_subscription) {
        error_log('Subscription not found for status change: ' . $subscription_id);
        return;
    }
    
    // Update subscription status
    $new_status = ($status === 'canceled') ? 'cancelled' : 'active';
    
    $wpdb->update(
        $wpdb->prefix . 'user_subscriptions',
        ['status' => $new_status],
        ['id' => $db_subscription->id]
    );
    
    // Update user capabilities
    if ($new_status === 'cancelled') {
        add_subscription_capabilities($db_subscription->user_id, 'free');
        send_subscription_cancellation_email($db_subscription->user_id, $db_subscription->package_type);
    }
    
    error_log('Updated subscription status to ' . $new_status . ' for user: ' . $db_subscription->user_id);
}

// Send subscription cancellation email
function send_subscription_cancellation_email($user_id, $package_type) {
    $user = get_user_by('id', $user_id);
    $packages = get_subscription_packages();
    $package = $packages[$package_type];
    
    $subject = 'Subscription Cancelled - ' . $package['name'];
    $message = "
        <h2>Subscription Cancelled</h2>
        <p>Dear {$user->display_name},</p>
        <p>Your {$package['name']} subscription has been cancelled. You'll continue to have access to premium features until your current billing period ends.</p>
        <p>After that, you'll have access to free tips only.</p>
        <p>You can reactivate your subscription anytime:</p>
        <p><a href='" . home_url('/subscription-packages') . "'>Reactivate Subscription</a></p>
        <p>We're sorry to see you go!</p>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($user->user_email, $subject, $message, $headers);
}