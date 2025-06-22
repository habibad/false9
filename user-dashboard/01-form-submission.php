<?php
/**
 * Template Name: Form Submission
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
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-1"></i></div>
                        <p>The first section of this form is to be filled out by the Enrollee (you) or their Authorized Representative, such as a Power of Attorney or Legal Guardian.</p>
                        <p>If an Authorized Representative is filling out the form, they must fill out the information under the Enrollee's information AND attach documentation showing their legal authorization.</p>
                    </div>
                    <br>
                    <div class="zoom-container" style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/File.jpeg" alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls" style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in" style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom In</button>
                        <button class="zoom-out" style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom Out</button>
                        <button class="reset-zoom" style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻ Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom: 100%</span>
                    </div>
                </div>

                <!-- Zoom Section 2 -->
                <div class="zoom-section">
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>On the second page of this document, you (or your provider) will include the name of the drug you need a Formulary Exception for as well as strength and quantity, if known.</p>
                        <p>Select the option under "Type of Coverage Determination Request" that best applies to you. If you are unsure, ask your provider.</p>
                    </div><br>

                    <div class="zoom-container" style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/user_cropped_screenshot (1).jpeg" alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls" style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in" style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom In</button>
                        <button class="zoom-out" style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom Out</button>
                        <button class="reset-zoom" style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻ Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom: 100%</span>
                    </div>
                </div>

            <!-- Zoom Section 3 -->
                <div class="zoom-section">
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>In this section, you may check the box to request an expedited decision if you or your prescriber believe your request is urgent.</p>
                    </div><br>

                    <div class="zoom-container" style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Files.jpeg" alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls" style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in" style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom In</button>
                        <button class="zoom-out" style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom Out</button>
                        <button class="reset-zoom" style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻ Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom: 100%</span>
                    </div>
                </div>

            <!-- Zoom Section 4 -->
                <div class="zoom-section">
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>On page 3 of the document, you will read the statement then sign and date to confirm you agree.</p>
                        <p>This section can be completed by the Enrollee (you), an Authorized Representative, or your prescriber.</p>
                    </div><br>

                    <div class="zoom-container" style="overflow: hidden; width: 100%; max-width: 900px; margin: auto; border: 2px solid #ddd; border-radius: 8px; background: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
                        <img class="form-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/File (1).jpeg" alt="Form Image"
                            style="padding: 80px 20px; max-width: 100%; height: auto; display: block; margin: auto; user-select: none; -webkit-user-drag: none; transition: transform 0.2s ease; cursor: grab;">
                    </div><br>

                    <div class="zoom-controls" style="text-align: center; margin-bottom: 15px; max-width: 900px; margin: auto;">
                        <button class="zoom-in" style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom In</button>
                        <button class="zoom-out" style="background: #f44336; color: white; padding: 8px 15px; border-radius: 4px;">🔍 Zoom Out</button>
                        <button class="reset-zoom" style="background: #4caf50; color: white; padding: 8px 15px; border-radius: 4px;">↻ Reset</button>
                        <span class="zoom-level" style="margin-left: 15px; font-weight: bold; color: #666;">Zoom: 100%</span>
                    </div>
                </div>

            <!-- Zoom Section 4 -->
                <div class="zoom-section">
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>The rest of the document is to be completed by your prescribing doctor.</p>
                        <p>When you have completed your sections of the form, you may either email the form to your doctor, or print the document and give it to them.</p>
                    </div><br>
                </div>
                <div class="zoom-section">
                    <div style="background: #ffffff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3; max-width: 900px; margin: auto; font-size:19px;">
                        <div><i class="fa-solid fa-2"></i></div>
                        <p>When your prescriber has completed the form, they will send it to Aetna SilverScript to be processed through your Part D insurance.</p>
                        <p>You will receive a decision from your insurance carrier within 72 hours of your doctor submitting the form, unless an expedited decision was requested.</p>
                    </div><br>
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

button{
    display: none;
}
/* 
.col-md-3.col-lg-2.d-none.d-md-block.sidebar{
    position: fixed;
} */


</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.zoom-section').forEach(section => {
        const container = section.querySelector('.zoom-container');
        const image = section.querySelector('.form-image');
        const zoomInBtn = section.querySelector('.zoom-in');
        const zoomOutBtn = section.querySelector('.zoom-out');
        const resetBtn = section.querySelector('.reset-zoom');
        const zoomLevel = section.querySelector('.zoom-level');

        let scale = 1, translateX = 0, translateY = 0;
        let isDragging = false, startX = 0, startY = 0;
        let initialTranslateX = 0, initialTranslateY = 0;

        const minScale = 0.5, maxScale = 3, scaleStep = 0.2;

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
