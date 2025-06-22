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


function jahbulonn_create_ai_tips_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ai_tips';

    // Check if the table already exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            match VARCHAR(255) NOT NULL,
            date DATETIME NOT NULL,
            tip_type VARCHAR(100) NOT NULL,
            odds FLOAT NOT NULL,
            stake FLOAT NOT NULL,
            result VARCHAR(50),
            return_amt FLOAT,
            profit FLOAT,
            tip_result VARCHAR(20),
            month VARCHAR(20),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        dbDelta($sql);
        error_log('✅ ai_tips table created successfully!');
    } else {
        error_log('ℹ️ ai_tips table already exists.');
    }
}

add_action('admin_init', 'jahbulonn_create_ai_tips_table_once');

function jahbulonn_create_ai_tips_table_once() {
    // Only for admin user and local testing
    if (current_user_can('administrator') && isset($_GET['create_ai_tips']) && $_GET['create_ai_tips'] === 'yes') {
        jahbulonn_create_ai_tips_table();
    }
}



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

// Add custom menu page for setting session timeout ================ start ========================
function user_session_timeout_menu() {
    add_menu_page(
        'User Session Timeout',
        'User Session',
        'manage_options',
        'user-session-timeout',
        'user_session_timeout_page'
    );
}
add_action('admin_menu', 'user_session_timeout_menu');

// Display users with session timeout options
function user_session_timeout_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_time'])) {
        foreach ($_POST['timeout'] as $user_id => $timeout) {
            update_user_meta($user_id, 'session_timeout', intval($timeout));
        }
        echo '<div class="updated"><p>Session timeout updated successfully.</p></div>';
    }

    $users = get_users(['role' => 'subscriber']);

    echo '<div class="wrap"><h1>User Session Timeout</h1>';
    echo '<form method="POST"><table class="widefat"><thead><tr><th>User Name</th><th>Email</th><th>Role</th><th>Assigned Time (Minutes)</th></tr></thead><tbody>';
    
    foreach ($users as $user) {
        $timeout = get_user_meta($user->ID, 'session_timeout', true) ?: 30; // Default 30 mins
        echo '<tr>';
        echo '<td>' . esc_html($user->user_login) . '</td>';
        echo '<td>' . esc_html($user->user_email) . '</td>';
        echo '<td>' . esc_html(implode(', ', $user->roles)) . '</td>';
        echo '<td><input type="number" name="timeout[' . $user->ID . ']" value="' . esc_attr($timeout) . '" min="1"></td>';
        echo '</tr>';
    }

    echo '</tbody></table><br><button type="submit" name="save_time" class="button button-primary">Save Changes</button></form></div>';
}

// Start session tracking on login
function track_user_session($user_login, $user) {
    $user_id = $user->ID;
    $timeout = get_user_meta($user_id, 'session_timeout', true) ?: 30; // Default to 30 mins

    // Set session start time
    update_user_meta($user_id, 'session_start_time', time());

    // Store timeout in session
    $_SESSION['session_timeout'] = $timeout * 60; // Convert minutes to seconds
}
add_action('wp_login', 'track_user_session', 10, 2);

// Auto logout user if timeout reached and change role to "Pending" (for subscribers only)
function auto_logout_user() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
 
        // Check if the user has the 'subscriber' role only
        if (in_array('subscriber', (array) $user->roles)) {
            $start_time = get_user_meta($user_id, 'session_start_time', true);
            $timeout = get_user_meta($user_id, 'session_timeout', true) ?: 30; // Default to 30 mins
 
            // Check if the session time has exceeded
            if ($start_time && (time() - $start_time) > ($timeout * 60)) {
                // Change user role to "Pending"
                $user->set_role('pending'); // Make sure 'pending' role exists
 
                // Logout and redirect
                wp_logout();
                wp_redirect(home_url('/session-expired'));
                exit;
            }
        }
    }
}
add_action('init', 'auto_logout_user');
 

// Countdown timer showing for visitors 

// Auto-refresh the page before session expiry
function auto_refresh_before_logout() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $timeout = get_user_meta($user_id, 'session_timeout', true) ?: 30; // Default 30 mins
        $refresh_time = ($timeout - 1) * 60 * 1000; // Refresh 1 min before timeout

        echo "<script>
            setTimeout(function() {
                location.reload();
            }, $refresh_time);
        </script>";
    }
}
add_action('wp_head', 'auto_refresh_before_logout');


// Add custom menu page for setting session timeout ================ end ========================

// Countdown timer showing for visitors ============================ start ======================

function display_floating_session_countdown() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);

        // Check if the user is an administrator
        if (in_array('administrator', (array) $user->roles)) {
            return; // Do not show for administrators
        }

        $timeout = get_user_meta($user_id, 'session_timeout', true) ?: 30; // Default 30 minutes
        $start_time = get_user_meta($user_id, 'session_start_time', true);
        
        if (!$start_time) {
            return;
        }

        // Calculate remaining time in seconds
        $remaining_time = ($start_time + ($timeout * 60)) - time();
        if ($remaining_time < 0) {
            $remaining_time = 0;
        }

        ?>
        <!-- Floating Countdown Timer -->
        <div id="session-countdown" style="
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            z-index: 9999;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        ">
            <span style="color: #ffcc00;">⏳ Session Expires In:</span> 
            <span id="countdown-timer"><?php echo esc_html($remaining_time); ?></span> sec
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var countdownTime = <?php echo esc_js($remaining_time); ?>;
                var countdownElement = document.getElementById("countdown-timer");

                function updateCountdown() {
                    if (countdownTime <= 0) {
                        window.location.href = "<?php echo esc_url(home_url('/session-expired')); ?>"; // Redirect on expiry
                        return;
                    }
                    countdownElement.innerText = countdownTime;
                    countdownTime--;
                }

                setInterval(updateCountdown, 1000);
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'display_floating_session_countdown');

// ============================ end ======================



// Create ai_tips table on theme activation


