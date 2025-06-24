<div class="menu-items">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/user-dashboard/false-icon.png" alt="logo"
        class="rounded jahbulonn-logo">

    <?php if (is_page('my-dashboard')): ?>
    <a href="<?php echo home_url(); ?>/my-dashboard" class="jahbulonn-menu-item active">Dashboard</a>
    <?php else: ?>
    <a href="<?php echo home_url(); ?>/my-dashboard" class="jahbulonn-menu-item">Dashboard</a>
    <?php endif; ?>

   <!-- anik applying start from here 
     the code works for the tips in the admin and menu dashboard -->
<?php if ( current_user_can('administrator') ) : ?>
    <?php if ( is_page('tips') ) : ?>
        <a href="<?php echo home_url(); ?>/tips" class="jahbulonn-menu-item active">Tips submitting form for admin</a>
    <?php else: ?>
        <a href="<?php echo home_url(); ?>/tips" class="jahbulonn-menu-item">Tips submitting form for admin</a>
    <?php endif; ?>
<?php endif; ?>
<!-- anik applying ending here 
     the code works for the tips in the admin and menu dashboard -->




     <!-- anik applying start from here 
     the code works for the tips in the admin and menu dashboard -->
<?php if ( current_user_can('administrator') ) : ?>
    <?php if ( is_page('statistics') ) : ?>
        <a href="<?php echo home_url(); ?>/statistics" class="jahbulonn-menu-item active">statistics</a>
    <?php else: ?>
        <a href="<?php echo home_url(); ?>/statistics" class="jahbulonn-menu-item">statistics</a>
    <?php endif; ?>
<?php endif; ?>
<!-- anik applying ending here 
     the code works for the tips in the admin and menu dashboard -->
     
    <a href="<?php echo wp_logout_url(); ?>" class="jahbulonn-menu-item">Logout</a>
</div>