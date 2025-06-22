<?php
/* Template Name: Client Details */

if (!is_user_logged_in()) {
    wp_redirect(esc_url(home_url()));
    exit;
}

get_template_part('template-parts/dashboard/header');

$current_user = wp_get_current_user();
?>

<div id="main-wrapper">
    <div class="row">
        <div class="col-md-3">
            <?php do_shortcode('client_confirmation'); ?>
        </div>
        <div class="col-md-3">

        </div>
    </div>
</div>

<?php get_template_part('template-parts/login/footer', 'login'); ?>
