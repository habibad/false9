<?php
/**
 * Template Name: My Dashboard
 */
if (!is_user_logged_in()) {
    wp_redirect(esc_url(home_url()));
    exit;
}
include "header-user-dashboard.php"; // Include the header for this template
?>
<?php require "dashboard-avatar.php"; ?>
<main class="jahbulonn-main bg-black" id="jahbulonn-dashboard">

    <?php require "dashboard-mobile-navbar.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <?php include "menu-items.php"; ?>
            </div>

            <div class="col-md-9 col-lg-10 col-12 p-0 p-md-4">
                <div class="jahbulonn-dashboard-content">

                    <div class="jahbulonn-profile-container">
                        <div class="jahbulonn-profile-header">
                            <h1>Profile Information</h1>
                        </div>
                        <div class="jahbulonn-profile-content">
                            <div class="jahbulonn-profile-picture-container">
                                <img src="<?php
                                $custom_picture = get_user_meta(get_current_user_id(), 'profile_picture', true);
                                echo $custom_picture ? $custom_picture : get_avatar_url(get_current_user_id());
                                ?>" alt="Profile Picture" class="jahbulonn-profile-picture" />
                            </div>
                            <div class="jahbulonn-profile-info"><strong>I'm
                                    <?php echo wp_get_current_user()->display_name; ?></strong></div>

                            <div class="jahbulonn-profile-center-wrapper">
                                <!-- Change Profile Picture Form -->
                                <form class="jahbulonn-profile-form" method="POST" enctype="multipart/form-data"
                                    id="form-picture">
                                    <label for="profile_picture" class="jahbulonn-profile-label">Change your profile
                                        picture</label>
                                    <div class="jahbulonn-profile-form-row">
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                            class="jahbulonn-profile-input fixed-input" />
                                        <button type="submit" name="change_profile_picture"
                                            class="jahbulonn-profile-button fixed-button">Change Picture</button>
                                    </div>
                                </form>

                                <!-- Change Display Name Form -->
                                <form class="jahbulonn-profile-form" method="POST" id="form-username">
                                    <label for="display_name" class="jahbulonn-profile-label">Change your display
                                        name</label>
                                    <div class="jahbulonn-profile-form-row">
                                        <input type="text" name="display_name" id="display_name"
                                            value="<?php echo wp_get_current_user()->display_name; ?>"
                                            class="jahbulonn-profile-input fixed-input" required minlength="3" />
                                        <button type="submit" name="change_display_name"
                                            class="jahbulonn-profile-button fixed-button">Change Display Name</button>
                                    </div>
                                </form>

                                <!-- Change Password Form -->
                                <form class="jahbulonn-profile-form" method="POST" id="form-password">
                                    <label for="password" class="jahbulonn-profile-label">Change your password</label>
                                    <div class="jahbulonn-profile-form-row">
                                        <input type="password" name="password" id="password"
                                            class="jahbulonn-profile-input fixed-input" required minlength="6"
                                            placeholder="New password" autocomplete="new-password" />
                                        <button type="submit" name="change_password"
                                            class="jahbulonn-profile-button fixed-button">Change Password</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require "dashboard-offcanvas.php"; ?>
</main>

<?php include "footer-user-dashboard.php"; ?>
<style>
/* Center profile container */
.jahbulonn-profile-center-wrapper {
    max-width: 600px;
    margin: 0 auto;
}

/* Profile form row: ensures elements are aligned properly on all screen sizes */
.jahbulonn-profile-form-row {
    display: flex;
    justify-content: start;
    gap: 15px;
    margin-top: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap; /* Allow wrapping of elements on smaller screens */
}

/* Fixed input styling */
.fixed-input {
    width: 100%;
    max-width: 300px;
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #f8f9fa;
    color: #333;
    box-sizing: border-box;
}

/* Fixed button styling */
.fixed-button {
    width: 100%;
    max-width: 190px;
    padding: 12px 15px;
    background-color: #FF0F50;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-sizing: border-box;
}

/* Button hover effect */
.fixed-button:hover {
    background-color: #e00f45;
}

/* Media Queries for Mobile and Tablet Devices */

/* For devices less than 768px (Tablets and smaller) */
@media (max-width: 768px) {
    .jahbulonn-profile-center-wrapper {
        max-width: 100%; /* Full width on tablets */
        padding: 0 15px; /* Add padding on small screens */
    }

    .jahbulonn-profile-form-row {
        flex-direction: row; /* Stack inputs vertically */
        gap: 10px;
    }

    .fixed-input {
        width: 100%; /* Full width for inputs */
    }
    .fixed-button {
        width: fit-content;
    }
    
}

/* For devices less than 480px (Mobile devices) */
@media (max-width: 480px) {
    .jahbulonn-profile-center-wrapper {
        padding: 0 10px; /* Reduce padding on very small screens */
    }

    .fixed-input {
        padding: 10px; /* Reduce padding for smaller screens */
    }

    .fixed-button {
        padding: 10px; /* Adjust button padding */
        font-size: 14px; /* Smaller font size for buttons */
    }
}


</style>