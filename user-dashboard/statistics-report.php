<?php
/**
 * Template Name: AI Tips Statistics Page
 */
get_header();
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">AI Tips Full Statistics</h2>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'ai_tips';

    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY STR_TO_DATE(date, '%Y-%m-%d %H:%i:%s') DESC");
    
    $grouped = [];
    foreach ($results as $row) {
        $month = ucfirst(strtolower($row->month));
        $grouped[$month][] = $row;
    }

    foreach ($grouped as $month => $entries):
    ?>
        <div class="my-5">
            <h3 class="text-center" style="color: #ff007a;"> <?php echo $month; ?> Full Statistics</h3>
            <?php foreach ($entries as $entry): ?>
                <div class="card mb-4 shadow border-0" style="background-color: #111">
                    <div class="card-body p-0">
                        <div class="p-3">
                            <h5 class="text-white mb-0"><?php echo esc_html($entry->match); ?></h5>
                            <p class="text-muted small mb-1"><?php echo date('jS F Y - H:i', strtotime($entry->date)); ?></p>
                        </div>
                        <div class="bg-danger text-white px-3 py-2">
                            <strong>AI Prediction:</strong> <?php echo esc_html($entry->tip_type); ?>
                        </div>
                        <div class="d-flex justify-content-between text-white px-3 py-2" style="font-size: 0.9rem;">
                            <div><strong>Odds:</strong> <?php echo $entry->odds; ?></div>
                            <div><strong>Stake:</strong> <?php echo $entry->stake; ?> $</div>
                            <div><strong>Result:</strong> <?php echo esc_html($entry->result); ?></div>
                            <div><strong>Return:</strong> <?php echo $entry->return_amt; ?> $</div>
                            <div><strong>Profit:</strong> <span class="text-<?php echo ($entry->profit > 0) ? 'success' : 'danger'; ?>">
                                <?php echo ($entry->profit > 0 ? '+' : '') . $entry->profit; ?>%</span></div>
                        </div>
                        <div class="px-3 py-2 text-center" style="background-color: <?php echo ($entry->tip_result === 'WON') ? '#2ecc71' : '#e74c3c'; ?>; color: white;">
                            <strong>Tip Result:</strong> <?php echo esc_html($entry->tip_result); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php get_footer(); ?>
