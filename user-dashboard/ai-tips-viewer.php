<?php
/**
 * Template Name: AI Tips Viewer
 */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
include "header-user-dashboard.php";

$current_user = wp_get_current_user();
$membership_levels = array('free', 'standard', 'premium');
$user_level = 'free'; // default

foreach ($membership_levels as $level) {
    if (current_user_can($level)) {
        $user_level = $level;
    }
}
?>
<main class="jahbulonn-main bg-black" id="jahbulonn-dashboard">

    <?php require "dashboard-mobile-navbar.php"; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <?php include "menu-items.php"; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 col-12 p-4">
                <div class="jahbulonn-dashboard-content">

                    <h1 class="text-white">AI Tips Viewer (<?php echo ucfirst($user_level); ?> Member)</h1>
                    <p class="text-muted">Browse your available AI tips by selecting a date below.</p>

                    <div class="jahbulonn-profile-container">
                        <div class="jahbulonn-profile-header">
                            <h2>Select Date</h2>
                        </div>

                        <div class="jahbulonn-profile-content">
                            <div class="jahbulonn-profile-form-row">
                                <input type="date" id="tip-date" class="jahbulonn-profile-input" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>
                    </div>

                    <!-- Tip Cards Section -->
                    <div class="jahbulonn-profile-container mt-4">
                        <div class="jahbulonn-profile-header">
                            <h2 class="text-white">Tips for <span id="selected-date"><?php echo date('jS F Y'); ?></span></h2>
                        </div>
                        <div id="tips-container" class="row">
                            <!-- Tips will be loaded here via AJAX -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php require "dashboard-offcanvas.php"; ?>
</main>

<script>
jQuery(document).ready(function($) {
    function loadTips(date) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'load_ai_tips',
                date: date
            },
            beforeSend: function() {
                $('#tips-container').html('<div class="col-12 text-center p-5 text-white">Loading tips...</div>');
            },
            success: function(response) {
                $('#tips-container').html(response);
                const formatted = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                $('#selected-date').text(formatted);
            }
        });
    }

    $('#tip-date').on('change', function() {
        loadTips($(this).val());
    });

    loadTips($('#tip-date').val()); // initial load
});
</script>

<?php include "footer-user-dashboard.php"; ?>

<style>
#jahbulonn-dashboard {
    background-color: rgb(26, 26, 26);
}
</style>
