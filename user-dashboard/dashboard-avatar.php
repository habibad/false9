<div class="userAvatar d-flex align-items-center gap-2 p-1">
    <span class="text-white">Welcome, <?php echo esc_html($current_user->display_name); ?></span>
    <div class="jahbulonn-profile-picture-container">
        <img src="<?php $custom_picture = get_user_meta(get_current_user_id(), 'profile_picture', true);
                    echo $custom_picture ? $custom_picture : get_avatar_url(get_current_user_id());
                    ?>" alt="Profile Picture" class="jahbulonn-profile-picture" />
    </div>
</div>

<style>
.userAvatar {
    position: fixed;
    top: 35px;
    right: 5px;
    z-index: 999;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 20px;
    padding: 5px 10px;
}

.userAvatar img {
    border-radius: 50%;
    width: 30px;
    height: 30px;
    object-fit: cover;
    border: 2px solid white;
}

.userAvatar span {
    font-size: 14px;
    color: white;
    margin-bottom: 20px;
}
</style>