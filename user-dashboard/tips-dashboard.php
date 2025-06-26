<?php
/**
 * Template Name: AI Tips Admin Dashboard
 */
if (!is_user_logged_in()) {
    wp_redirect(esc_url(home_url()));
    exit;
}
include "header-user-dashboard.php";
?>
<?php require "dashboard-avatar.php"; ?>
<main class="jahbulonn-main" id="jahbulonn-dashboard">
    <?php require "dashboard-mobile-navbar.php"; ?>
    <div class="container-fluids">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <?php include "menu-items.php"; ?>
            </div>

            <div class="col-md-9 col-lg-10 col-12 p-4">
                <div class="jahbulonn-dashboard-content">

                    <h1>AI Tips Management</h1>
    
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'ai_tips';
    
                    // Insert or Update AI Tip
                    if (isset($_POST['submit_tip'])) {
                        // Ensure membership is handled correctly
                        $membership = isset($_POST['membership']) ? implode(',', $_POST['membership']) : ''; // Handle multiple memberships
    
                        $data = [
                            'match' => sanitize_text_field($_POST['match']),
                            'date' => sanitize_text_field($_POST['date']),
                            'tip_type' => sanitize_text_field($_POST['tip_type']),
                            'odds' => floatval($_POST['odds']),
                            'stake' => floatval($_POST['stake']),
                            'result' => sanitize_text_field($_POST['result']),
                            'return_amt' => floatval($_POST['return_amt']),
                            'profit' => floatval($_POST['profit']),
                            'tip_result' => sanitize_text_field($_POST['tip_result']), // Initially set to 'SELECT'
                            'month' => sanitize_text_field($_POST['month']),
                            'membership' => $membership // Save the membership data (comma-separated values)
                        ];
    
                        if (!empty($_POST['tip_id'])) {
                            $wpdb->update($table_name, $data, ['id' => intval($_POST['tip_id'])]);
                        } else {
                            $wpdb->insert($table_name, $data);
                        }
                    }
    
                    // Delete AI Tip
                    if (isset($_GET['delete_id'])) {
                        $wpdb->delete($table_name, ['id' => intval($_GET['delete_id'])]);
                    }
    
                    // Fetch all entries
                    $entries = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
                    ?>
    
                    <!-- AI Tip Form -->
                    <div class="jahbulonn-profile-container">
                        <div class="jahbulonn-profile-header">
                            <h2>Submit or Edit AI Tip</h2>
                        </div>
                        <form method="post" id="ai-tip-form">
                            <input type="hidden" name="tip_id" id="tip_id" value="">
                            <div class="jahbulonn-profile-form">
                                <div class="jahbulonn-profile-form-row">
                                    <input type="text" name="match" id="match" class="jahbulonn-profile-input" placeholder="Match (e.g., Team A vs Team B)" required />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="datetime-local" name="date" id="date" class="jahbulonn-profile-input" required />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="text" name="tip_type" id="tip_type" class="jahbulonn-profile-input" placeholder="Tip Type (e.g., OVER 2.5)" required />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="number" step="0.01" name="odds" id="odds" class="jahbulonn-profile-input" placeholder="Odds" required />
                                    <input type="number" step="0.01" name="stake" id="stake" class="jahbulonn-profile-input" placeholder="Stake" required />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="text" name="result" id="result" class="jahbulonn-profile-input" placeholder="Result (e.g., 2 - 1)" />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="number" step="0.01" name="return_amt" id="return_amt" class="jahbulonn-profile-input" placeholder="Return" />
                                    <input type="number" step="0.01" name="profit" id="profit" class="jahbulonn-profile-input" placeholder="Profit (%)" />
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <!-- Default is SELECT, no WON/LOST option initially -->
                                    <select name="tip_result" id="tip_result" class="jahbulonn-profile-input">
                                        <option value="SELECT" selected>Select</option>
                                        <option value="WON">WON</option>
                                        <option value="LOST">LOST</option>
                                    </select>
                                </div>
                                <div class="jahbulonn-profile-form-row">
                                    <input type="text" name="month" id="month" class="jahbulonn-profile-input" placeholder="Month (e.g., June)" />
                                </div>
    
                                <!-- Membership Type (Checkbox options) -->
                                <div class="jahbulonn-profile-form-row">
                                    <label for="membership">Select Membership Types</label><br>
                                    <input type="checkbox" id="free" name="membership[]" value="free">
                                    <label for="free">Free</label><br>
                                    <input type="checkbox" id="standard" name="membership[]" value="standard">
                                    <label for="standard">Standard</label><br>
                                    <input type="checkbox" id="premium" name="membership[]" value="premium">
                                    <label for="premium">Premium</label>
                                </div>
    
                                <div class="jahbulonn-profile-form-row">
                                    <button type="submit" name="submit_tip" class="jahbulonn-profile-button">Save Tip</button>
                                </div>
                            </div>
                        </form>
                    </div>
    
                    <!-- Tip List -->
                    <div class="jahbulonn-profile-container">
                        <div class="jahbulonn-profile-header">
                            <h2>All AI Tips</h2>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th><th>Match</th><th>Date</th><th>Tip</th><th>Odds</th><th>Profit</th><th>Result</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entries as $entry): ?>
                                    <tr>
                                        <td><?php echo $entry->id; ?></td>
                                        <td><?php echo esc_html($entry->match); ?></td>
                                        <td><?php echo esc_html($entry->date); ?></td>
                                        <td><?php echo esc_html($entry->tip_type); ?></td>
                                        <td><?php echo $entry->odds; ?></td>
                                        <td><?php echo $entry->profit; ?>%</td>
                                        <td><?php echo esc_html($entry->tip_result); ?></td>
                                        <td>
                                            <button onclick="editTip(<?php echo htmlspecialchars(json_encode($entry)); ?>)" class="btn btn-sm btn-info">Edit</button>
                                            <a href="?delete_id=<?php echo $entry->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require "dashboard-offcanvas.php"; ?>
</main>
<script>
function editTip(data) {
    document.getElementById('tip_id').value = data.id;
    document.getElementById('match').value = data.match;
    document.getElementById('date').value = data.date;
    document.getElementById('tip_type').value = data.tip_type;
    document.getElementById('odds').value = data.odds;
    document.getElementById('stake').value = data.stake;
    document.getElementById('result').value = data.result;
    document.getElementById('return_amt').value = data.return_amt;
    document.getElementById('profit').value = data.profit;
    document.getElementById('tip_result').value = data.tip_result;
    document.getElementById('month').value = data.month;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
<?php include "footer-user-dashboard.php"; ?>
