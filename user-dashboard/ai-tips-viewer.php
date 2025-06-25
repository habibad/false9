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
    <div class="userAvatar"></div>
    <?php require "dashboard-mobile-navbar.php"; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <?php include "menu-items.php"; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 col-12 p-1 p-md-4">
                <div class="jahbulonn-dashboard-content">

                    <h1 class="text-white">AI Tips Viewer (<?php echo ucfirst($user_level); ?> Member)</h1>
                    <p class="text-muted">Browse your available AI tips by selecting a date below.</p>

                    <div class="jahbulonn-profile-container">
                        <div class="jahbulonn-profile-header">
                            <h2>Select Date</h2>
                        </div>

                        <div class="jahbulonn-profile-content">
                            <div class="jahbulonn-profile-form-row">
                                <!-- <input type="date" id="tip-date" class="jahbulonn-profile-input" value="<?php echo date('Y-m-d'); ?>" /> -->
                                <div class="rows111">

                                    <div class="cols">
                                        <div class="custom-calendar-wrapper">
                                            <div class="calendar-header">
                                                <button id="prev-month">&lt;</button>
                                                <span id="calendar-month"></span>
                                                <button id="next-month">&gt;</button>
                                            </div>
                                            <table id="calendar">
                                                <!-- Calendar will be dynamically generated here -->
                                            </table>
                                            <button id="reset-dates" class="btn btn-sm btn-outline-light mt-2">Reset
                                                Dates</button>
                                            <input type="hidden" id="tip-date" value="<?php echo date('Y-m-d'); ?>" />
                                        </div>
                                    </div>

                                    <div class="cols">
                                        <!-- Tip Cards Section -->
                                        <div class="mt-4">
                                            <div>
                                                <h2 class="text-white">Tips for <span
                                                        id="selected-date"><?php echo date('jS F Y'); ?></span></h2>
                                            </div>

                                            <div id="tips-container" class="">
                                                <!-- Tips will be loaded here via AJAX -->
                                            </div>

                                        </div>
                                    </div>

                                </div>




                            </div>
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
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function generateCalendar(month, year) {
        $('#calendar-month').text(monthNames[month] + ' ' + year);

        let firstDay = (new Date(year, month)).getDay();
        let daysInMonth = 32 - new Date(year, month, 32).getDate();

        let tbl = $('#calendar');
        tbl.html('');

        let weekdays = '<tr><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th><th>Su</th></tr>';
        tbl.append(weekdays);

        let date = 1;
        for (let i = 0; i < 6; i++) {
            let row = $('<tr></tr>');

            for (let j = 1; j <= 7; j++) {
                if (i === 0 && j < (firstDay === 0 ? 7 : firstDay)) {
                    row.append('<td></td>');
                } else if (date > daysInMonth) {
                    break;
                } else {
                    let cell = $('<td></td>').text(date).addClass('calendar-date');

                    // Highlight today's date
                    if (date === today.getDate() && year === today.getFullYear() && month === today
                        .getMonth()) {
                        cell.addClass('active-date');
                    }

                    // Capture the correct date for each cell using closure
                    (function(selectedDay) {
                        cell.on('click', function() {
                            $('.calendar-date').removeClass('active-date');
                            $(this).addClass('active-date');

                            let selectedDate = new Date(year, month, selectedDay);

                            // Build date in YYYY-MM-DD without timezone issue
                            let yyyy = selectedDate.getFullYear();
                            let mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                            let dd = String(selectedDate.getDate()).padStart(2, '0');
                            let formattedDate = `${yyyy}-${mm}-${dd}`;

                            $('#tip-date').val(formattedDate).trigger('change');
                        });
                    })(date); // passing the current date correctly

                    row.append(cell);
                    date++;
                }
            }

            tbl.append(row);
        }
    }

    $('#prev-month').on('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
    });

    $('#next-month').on('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
    });

    $('#reset-dates').on('click', function() {
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        generateCalendar(currentMonth, currentYear);
        $('#tip-date').val('<?php echo date('Y-m-d'); ?>').trigger('change');
    });

    generateCalendar(currentMonth, currentYear);

    // Existing AJAX loader
    function loadTips(date) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'load_ai_tips',
                date: date
            },
            beforeSend: function() {
                $('#tips-container').html(
                    '<div class="col-12 text-center p-5 text-white">Loading tips...</div>');
            },
            success: function(response) {
                $('#tips-container').html(response);
                const formatted = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $('#selected-date').text(formatted);
            }
        });
    }

    $('#tip-date').on('change', function() {
        loadTips($(this).val());
    });

    loadTips($('#tip-date').val());
});
</script>


<?php include "footer-user-dashboard.php"; ?>

<style>
.custom-calendar-wrapper {

    border-radius: 8px;
    padding: 16px;
    width: 100%;
    min-width: 450px;
    max-width: 500px;
    background-color: rgba(252, 110, 148, 0.1);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

#calendar-month {
    color: white;
    font-style: bold;
    font-size: 24px;
}

.calendar-header button {
    background: #FF0F50;
    border: none;
    color: white;
    padding: 5px 10px;
    border-radius: 50%;
    cursor: pointer;
}

#calendar {
    width: 100%;
    text-align: center;
    border-collapse: collapse;
    border: 1px solid transparent;
}

#calendar td {
    padding: 18px 6px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid rgba(255, 184, 203, 0.11);
}

#calendar td:hover {
    background-color: rgb(255, 184, 203);
}

.active-date {
    background-color: #FF0F50;
    color: white;
}

#reset-dates {
    background: none;
    border: none;
    color: rgb(255, 255, 255);
    cursor: pointer;
}

/* ====tips card=== */
.rows111 {
    width: 100%;
    display: flex;
    gap: 30px;
    justify-content: space-evenly;
    flex-wrap: wrap;
}

.card-header {
    background-color: rgb(202, 4, 64);
    color: white;
}

.tipsCard-body {
    background-color: #312226;
    color: white;
    padding: 10px
}

.userAvatar {
    width: 100px;
    height: 30px;
    border-radius: 5%;
    background-color: rgb(202, 4, 64);
    position: fixed;
    top: 35px;
    right: 5px;
}

@media screen and (max-width:576px) {
    .jahbulonn-profile-content {
        padding:10px 0px;
        border-radius: 0 0 12px 12px;
        background-color: #66666641;
    }

    .custom-calendar-wrapper {
        border-radius: 8px;
        padding: 0px;
        width: 100%;
        min-width: 100%;
        max-width: 100%;
        background-color: rgba(252, 110, 148, 0.1);
    }

    #calendar {
        width: 100%;
        max-width: 100%;
        padding:5px;
        text-align: center;
        border-collapse: collapse;
        border: 1px solid transparent;
    }

    #calendar th {
        padding: 1px 1px;
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid rgba(255, 184, 203, 0.11);
    }

    #calendar td ,th{
        padding: 1px 1px;
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid rgba(255, 184, 203, 0.11);
    }

    .rows111 {
        flex-direction: column;
    }
}
</style>
