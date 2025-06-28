<?php
/**
 * Template Name: AI Tips Statistics Page
 */
get_header();
?>



<div class="ai-tips-container">
    <div class="container">
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
        
        <h2 class="month-title">
            <span class="month-highlight"><?php echo $month; ?></span> Full Statistics
        </h2>
        
        <?php foreach ($entries as $entry): ?>
        <div class="ai-tips-card">
            <!-- Match Header -->
            <div class="match-header">
                <h3 class="match-title"><?php echo esc_html($entry->match); ?></h3>
                <div class="match-info">
                    <div class="match-details">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAyMCAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIwIiBoZWlnaHQ9IjUiIGZpbGw9IiNGRjAwMDAiLz4KPHJlY3QgeT0iNSIgd2lkdGg9IjIwIiBoZWlnaHQ9IjUiIGZpbGw9IiNGRkZGRkYiLz4KPHJlY3QgeT0iMTAiIHdpZHRoPSIyMCIgaGVpZ2h0PSI1IiBmaWxsPSIjMDAwMEZGIi8+Cjwvc3ZnPgo=" alt="Netherlands Flag" class="flag-icon">
                        KNVB Cup - <?php echo date('jS F Y - H:i', strtotime($entry->date)); ?>
                    </div>
                </div>
            </div>

            <!-- AI Prediction Banner -->
            <div class="prediction-banner">
                <p class="prediction-text">AI Prediction: <?php echo esc_html($entry->tip_type); ?></p>
            </div>

            <!-- Statistics Row -->
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-label">Odds</div>
                    <div class="stat-value"><?php echo esc_html($entry->odds); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Stake</div>
                    <div class="stat-value"><?php echo esc_html($entry->stake); ?> $</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Result</div>
                    <div class="stat-value"><?php echo esc_html($entry->result); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Return</div>
                    <div class="stat-value"><?php echo esc_html($entry->return_amt); ?> $</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Profit</div>
                    <div class="stat-value <?php echo ($entry->profit > 0) ? 'profit-positive' : 'profit-negative'; ?>">
                        <?php echo ($entry->profit > 0 ? '+' : '') . esc_html($entry->profit); ?>%
                    </div>
                </div>
            </div>

            <!-- Result Banner -->
            <div class="result-banner <?php echo ($entry->tip_result === 'WON') ? 'result-won' : 'result-lost'; ?>">
                Tip Result: <?php echo esc_html($entry->tip_result); ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php endforeach; ?>
    </div>
</div>

<style>
    @media (min-width: 922px) {
    .site-content .ast-container {
        display: contents;
        width: 100%;
        padding: 0;
        margin: 0;
    }
}
.ai-tips-container {
    width: 100%;
    background-color: #1a1a2e;
    min-height: 100vh;
    padding: 20px 0;
}

.ai-tips-card {
    background-color: #16213e;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 30px;
    border: 1px solid #0f3460;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.match-header {
    padding: 20px 25px;
    border-bottom: 1px solid #0f3460;
}

.match-title {
    color: white;
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 8px 0;
}

.match-info {
    color: #8892b0;
    font-size: 14px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.prediction-banner {
    background: linear-gradient(135deg, #e91e63, #ad1457);
    padding: 15px 25px;
    text-align: left;
}

.prediction-text {
    color: white;
    font-size: 18px;
    font-weight: bold;
    margin: 0;
}

.stats-row {
    display: flex;
    /* grid-template-columns: repeat(5, 1fr); */
    background-color: #16213e;
    color: white;
}

.stat-item {
    padding: 0 5px;
    width: fit-content;
    text-align: center;
    /* border-right: 1px solid #0f3460; */
}

.stat-item:last-child {
    border-right: none;
}

.stat-label {
    font-size: 14px;
    color: #8892b0;
    margin-bottom: 8px;
    font-weight: 500;
}

.stat-value {
    font-size: 20px;
    font-weight: bold;
    color: white;
}

.profit-positive {
    color: #4caf50 !important;
}

.profit-negative {
    color: #f44336 !important;
}

.result-banner {
    text-align: center;
    padding: 18px;
    font-size: 16px;
    font-weight: bold;
    color: white;
}

.result-won {
    background: linear-gradient(135deg, #4caf50, #388e3c);
}

.result-lost {
    background: linear-gradient(135deg, #f44336, #d32f2f);
}

.month-title {
    text-align: center;
    margin: 50px 0 30px 0;
    font-size: 36px;
    font-weight: bold;
    color: white;
}

.month-highlight {
    color: #ff007a;
    text-decoration: underline;
}

.flag-icon {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    margin-right: 8px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .ai-tips-container {
        padding: 15px 10px;
    }
    
    .match-header {
        padding: 15px 20px;
    }
    
    .match-title {
        font-size: 18px;
    }
    
    .match-info {
        font-size: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .prediction-banner {
        padding: 12px 20px;
    }
    
    .prediction-text {
        font-size: 16px;
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1px;
    }
    
    .stat-item {
        width: 100%;
        padding: 15px;
        border-right: none;
        border-bottom: 1px solid #0f3460;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: left;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    .stat-label {
        margin-bottom: 0;
    }
    
    .stat-value {
        font-size: 18px;
    }
    
    .month-title {
        font-size: 28px;
        margin: 30px 0 20px 0;
    }
}

/* Tablet Responsive */
@media (min-width: 769px) and (max-width: 1024px) {
    .stats-row {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .stat-item {
        padding: 18px 12px;
    }
    
    .stat-value {
        font-size: 18px;
    }
    
    .match-title {
        font-size: 20px;
    }
}
@media (min-width: 1024px) {
    .ai-tips-container {
        max-width: 100%;
    }
    
}

/* Large Desktop */
@media (min-width: 1200px) {
    .ai-tips-container {
        max-width: 100%;
    }
    .container {
        max-width: 1400px;
    }
    
    .stat-item {
        padding: 25px 20px;
    }
    
    .stat-value {
        font-size: 22px;
    }
}
</style>

<?php get_footer(); ?>