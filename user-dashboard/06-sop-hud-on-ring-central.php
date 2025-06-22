<?php
/**
 * Template Name: SOP: HUD on Ring Central
 */
if (!is_user_logged_in()) {
    wp_redirect(esc_url(home_url()));
    exit;
}
include "header-user-dashboard.php";
?>

<main class="jahbulonn-main" id="jahbulonn-dashboard">
    <?php require "dashboard-mobile-navbar.php"; ?>

    <div class="container-fluid" style="background-color: #f7fafc;">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <?php include "menu-items.php"; ?>
            </div>

            <div class="col-md-9 col-lg-10 col-12 p-4">

                <!-- Zoom Section 1 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-1"></i></div>
                        <p>To access your HUD open up the phone tab.</p>

                    </div>
                    <br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud1.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>


                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;"></span>
                    </div>
                </div>

                <!-- Zoom Section 2 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>
                            At the right of the selection columns you will find a new column titled "HUD"</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud2.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>

                <!-- Zoom Section 3 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>
                            This plus icon allows you to add users to your HUD</p>
                        <p>Having users on your HUD allows you to see these users active ring central status. For
                            example if you have Sandy listed on your hud and Sandy is on a call it will display that she
                            is on an active call.</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud3.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>

                <!-- Zoom Section 4 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>

                        <p>This is what it will look like when a user is on an active call..</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud4.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>

                <!-- Zoom Section 5 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>

                        <p>
                            From here you can type in the extension number or the name of the person you would want
                            access to viewing the status of.</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud5.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>


                <!-- Zoom Section 6 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>

                        <p>You can add multiple users at a time.</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud6.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>


                <!-- Zoom Section 7 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>

                        <p>Click "Add"</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud7.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>



                <!-- Zoom Section 8 -->
                <div class="zoom-section">
                    <div
                        style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>

                        <p>Now these users will be in your HUD allowing for you to have quick access to view their
                            status as well as a shortcut to calling that extension.</p>
                    </div><br>

                    <div class="zoom-container"
                        style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image"
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hud/Hud8.jpeg"
                            alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls"
                        style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in"
                            style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            In</button>
                        <button class="zoom-out"
                            style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom
                            Out</button>
                        <button class="reset-zoom"
                            style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻
                            Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom:
                            100%</span>
                    </div>
                </div>






            </div>

        </div>
    </div>

    <?php require "dashboard-offcanvas.php"; ?>
</main>

<style>
.zoom-container.grabbing {
    cursor: grabbing !important;
}

.zoom-container.grabbing .form-image {
    pointer-events: none;
}

button:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .zoom-container {
        height: auto !important;
    }
}

button {
    display: none;
}

/* 
.col-md-3.col-lg-2.d-none.d-md-block.sidebar{
    position: fixed;
} */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.zoom-section').forEach(section => {
        const container = section.querySelector('.zoom-container');
        const image = section.querySelector('.form-image');
        const zoomInBtn = section.querySelector('.zoom-in');
        const zoomOutBtn = section.querySelector('.zoom-out');
        const resetBtn = section.querySelector('.reset-zoom');
        const zoomLevel = section.querySelector('.zoom-level');

        let scale = 1,
            translateX = 0,
            translateY = 0;
        let isDragging = false,
            startX = 0,
            startY = 0;
        let initialTranslateX = 0,
            initialTranslateY = 0;

        const minScale = 0.5,
            maxScale = 3,
            scaleStep = 0.2;

        function updateTransform() {
            image.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
            zoomLevel.textContent = `Zoom: ${Math.round(scale * 100)}%`;
        }

        function constrainPosition() {
            const containerRect = container.getBoundingClientRect();
            const imageRect = image.getBoundingClientRect();

            const maxTranslateX = Math.max(0, (imageRect.width - containerRect.width) / 2);
            const maxTranslateY = Math.max(0, (imageRect.height - containerRect.height) / 2);

            translateX = Math.max(-maxTranslateX, Math.min(maxTranslateX, translateX));
            translateY = Math.max(-maxTranslateY, Math.min(maxTranslateY, translateY));
        }

        function zoomIn() {
            if (scale < maxScale) {
                scale = Math.min(maxScale, scale + scaleStep);
                updateTransform();
            }
        }

        function zoomOut() {
            if (scale > minScale) {
                scale = Math.max(minScale, scale - scaleStep);
                constrainPosition();
                updateTransform();
            }
        }

        function resetZoom() {
            scale = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        }

        // Button event listeners
        zoomInBtn.addEventListener('click', zoomIn);
        zoomOutBtn.addEventListener('click', zoomOut);
        resetBtn.addEventListener('click', resetZoom);

        // Mouse wheel zoom
        container.addEventListener('wheel', function(e) {
            e.preventDefault();

            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const oldScale = scale;

            if (e.deltaY < 0) {
                scale = Math.min(maxScale, scale + scaleStep);
            } else {
                scale = Math.max(minScale, scale - scaleStep);
            }

            if (scale !== oldScale) {
                const scaleChange = scale / oldScale;
                translateX = x - (x - translateX) * scaleChange;
                translateY = y - (y - translateY) * scaleChange;

                constrainPosition();
                updateTransform();
            }
        });

        // Mouse drag functionality
        container.addEventListener('mousedown', function(e) {
            if (e.button === 0) {
                isDragging = true;
                container.classList.add('grabbing');
                startX = e.clientX;
                startY = e.clientY;
                initialTranslateX = translateX;
                initialTranslateY = translateY;
                e.preventDefault();
            }
        });

        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;

                translateX = initialTranslateX + deltaX;
                translateY = initialTranslateY + deltaY;

                constrainPosition();
                updateTransform();
            }
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                container.classList.remove('grabbing');
            }
        });

        // Touch drag (mobile)
        let touchStartX = 0;
        let touchStartY = 0;
        let initialTouchTranslateX = 0;
        let initialTouchTranslateY = 0;

        container.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                initialTouchTranslateX = translateX;
                initialTouchTranslateY = translateY;
                e.preventDefault();
            }
        });

        container.addEventListener('touchmove', function(e) {
            if (e.touches.length === 1) {
                const deltaX = e.touches[0].clientX - touchStartX;
                const deltaY = e.touches[0].clientY - touchStartY;

                translateX = initialTouchTranslateX + deltaX;
                translateY = initialTouchTranslateY + deltaY;

                constrainPosition();
                updateTransform();
                e.preventDefault();
            }
        });

        // On image load - show at normal size
        image.onload = function() {
            resetZoom();
        };

        if (image.complete) {
            resetZoom();
        }
    });
});
</script>

<?php include "footer-user-dashboard.php"; ?>