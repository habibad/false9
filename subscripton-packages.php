<?php
/**
 * Template Name: Subscription Packages
 * File: subscription-packages.php
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header(); 

$packages = get_subscription_packages();
$current_user = wp_get_current_user();
$user_subscription = get_user_subscription($current_user->ID);
?>

<style>
.subscription-packages {
    padding: 60px 0;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    min-height: 100vh;
}

.packages-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.packages-header {
    text-align: center;
    margin-bottom: 50px;
    color: white;
}

.packages-header h1 {
    font-size: 3rem;
    margin-bottom: 20px;
    font-weight: 700;
}

.packages-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.package-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.package-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.2);
}

.package-card.recommended {
    border: 3px solid #ff6b6b;
    transform: scale(1.05);
}

.package-card.recommended::before {
    content: "RECOMMENDED";
    position: absolute;
    top: 20px;
    right: -40px;
    background: #ff6b6b;
    color: white;
    padding: 5px 50px;
    font-size: 12px;
    font-weight: bold;
    transform: rotate(45deg);
}

.package-card.current {
    border: 3px solid #4CAF50;
    background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%);
}

.package-header h3 {
    font-size: 1.8rem;
    margin-bottom: 10px;
    color: #333;
}

.package-price {
    font-size: 3rem;
    font-weight: 700;
    color: #2a5298;
    margin: 20px 0;
}

.package-price .currency {
    font-size: 1.5rem;
}

.package-price .period {
    font-size: 1rem;
    color: #666;
    font-weight: normal;
}

.package-features {
    list-style: none;
    padding: 0;
    margin: 30px 0;
}

.package-features li {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    color: #666;
    position: relative;
    padding-left: 30px;
}

.package-features li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #4CAF50;
    font-weight: bold;
}

.package-features li:last-child {
    border-bottom: none;
}

.package-button {
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.package-button:hover {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(42, 82, 152, 0.3);
    color: white;
    text-decoration: none;
}

.package-button.free {
    background: #6c757d;
}

.package-button.current-plan {
    background: #4CAF50;
    cursor: default;
}

.package-button.current-plan:hover {
    transform: none;
    box-shadow: none;
}

.subscription-status {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.subscription-status h2 {
    color: #333;
    margin-bottom: 20px;
}

.status-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.status-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.status-item h4 {
    color: #2a5298;
    margin-bottom: 10px;
}

.status-item p {
    color: #666;
    margin: 0;
}

.renewal-reminder {
    background: #fff3cd;
    border: 1px solid #ffecb5;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    text-align: center;
}

.renewal-reminder h3 {
    color: #856404;
    margin-bottom: 10px;
}

.faq-section {
    background: white;
    border-radius: 15px;
    padding: 40px;
    margin-top: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.faq-item {
    border-bottom: 1px solid #f0f0f0;
    padding: 20px 0;
}

.faq-item:last-child {
    border-bottom: none;
}

.faq-question {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.faq-answer {
    color: #666;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .packages-header h1 {
        font-size: 2rem;
    }
    
    .packages-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .package-card.recommended {
        transform: none;
    }
    
    .status-info {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="subscription-packages">
    <div class="packages-container">
        
        <!-- Page Header -->
        <div class="packages-header">
            <h1>Choose Your Plan</h1>
            <p>Get access to premium AI tips and boost your winning potential</p>
        </div>

        <!-- Current Subscription Status -->
        <?php if ($user_subscription): ?>
            <div class="subscription-status">
                <h2>Your Current Subscription</h2>
                <div class="status-info">
                    <div class="status-item">
                        <h4>Plan</h4>
                        <p><?php echo ucfirst($user_subscription->package_type); ?> Plan</p>
                    </div>
                    <div class="status-item">
                        <h4>Status</h4>
                        <p><?php echo ucfirst($user_subscription->status); ?></p>
                    </div>
                    <div class="status-item">
                        <h4>Expires</h4>
                        <p><?php echo date('M j, Y', strtotime($user_subscription->end_date)); ?></p>
                    </div>
                    <div class="status-item">
                        <h4>Days Left</h4>
                        <p><?php echo max(0, ceil((strtotime($user_subscription->end_date) - time()) / (60 * 60 * 24))); ?> days</p>
                    </div>
                </div>
                
                <?php 
                $days_left = ceil((strtotime($user_subscription->end_date) - time()) / (60 * 60 * 24));
                if ($days_left <= 7 && $days_left > 0): 
                ?>
                <div class="renewal-reminder">
                    <h3>⚠️ Subscription Expiring Soon!</h3>
                    <p>Your subscription expires in <?php echo $days_left; ?> day<?php echo $days_left != 1 ? 's' : ''; ?>. Renew now to continue accessing premium tips!</p>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Packages Grid -->
        <div class="packages-grid">
            <?php foreach ($packages as $package_id => $package): ?>
                <div class="package-card <?php 
                    if ($package_id === 'standard') echo 'recommended ';
                    if ($user_subscription && $user_subscription->package_type === $package_id) echo 'current';
                ?>">
                    
                    <div class="package-header">
                        <h3><?php echo $package['name']; ?></h3>
                    </div>
                    
                    <div class="package-price">
                        <?php if ($package['price'] == 0): ?>
                            <span>FREE</span>
                        <?php else: ?>
                            <span class="currency">$</span><?php echo number_format($package['price'], 0); ?>
                            <span class="period">/month</span>
                        <?php endif; ?>
                    </div>
                    
                    <ul class="package-features">
                        <?php foreach ($package['features'] as $feature): ?>
                            <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="package-action">
                        <?php if ($user_subscription && $user_subscription->package_type === $package_id && $user_subscription->status === 'active'): ?>
                            <button class="package-button current-plan">Current Plan</button>
                        <?php elseif ($package_id === 'free'): ?>
                            <a href="<?php echo home_url('/ai-tips-viewer'); ?>" class="package-button free">View Free Tips</a>
                        <?php else: ?>
                            <button class="package-button" onclick="initiatePayment('<?php echo $package_id; ?>', '<?php echo $package['name']; ?>', <?php echo $package['price']; ?>)">
                                Choose <?php echo $package['name']; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Stripe Checkout Integration -->
<script src="https://js.stripe.com/v3/"></script>
<script>
// Initialize Stripe
const stripe = Stripe('<?php echo get_option('stripe_publishable_key', 'pk_test_51RFVqpHD71XgIKUq15YD2iaxRpU05dmiJyfQF9CQvXKsHnvuB98IDj1N1JXhL1sEvToGlvWnjdqwxC8YDwMLd3TV00PjQ4dYh4'); ?>'); // Replace with your publishable key

function initiatePayment(packageType, packageName, price) {
    // Show loading state
    event.target.innerHTML = 'Processing...';
    event.target.disabled = true;
    
    // Create checkout session
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'create_checkout_session',
            package_type: packageType,
            package_name: packageName,
            price: price,
            user_id: <?php echo $current_user->ID; ?>,
            nonce: '<?php echo wp_create_nonce('checkout_nonce'); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to Stripe Checkout
            return stripe.redirectToCheckout({
                sessionId: data.session_id
            });
        } else {
            throw new Error(data.message || 'Failed to create checkout session');
        }
    })
    .then(result => {
        if (result.error) {
            throw new Error(result.error.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        
        // Reset button state
        event.target.innerHTML = 'Choose ' + packageName;
        event.target.disabled = false;
    });
}

// Handle successful payment redirect
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('success') === 'true') {
    // Show success message
    const successDiv = document.createElement('div');
    successDiv.className = 'alert alert-success';
    successDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 20px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 10px; color: #155724;';
    successDiv.innerHTML = '<strong>Success!</strong> Your subscription has been activated. <a href="<?php echo home_url('/ai-tips-viewer'); ?>">View your tips</a>';
    document.body.appendChild(successDiv);
    
    // Remove success parameter from URL
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({path: newUrl}, '', newUrl);
    
    // Auto hide success message after 5 seconds
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
}

if (urlParams.get('canceled') === 'true') {
    // Show canceled message
    const cancelDiv = document.createElement('div');
    cancelDiv.className = 'alert alert-warning';
    cancelDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 20px; background: #fff3cd; border: 1px solid #ffecb5; border-radius: 10px; color: #856404;';
    cancelDiv.innerHTML = '<strong>Payment Canceled</strong> Your subscription was not activated.';
    document.body.appendChild(cancelDiv);
    
    // Remove canceled parameter from URL
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({path: newUrl}, '', newUrl);
    
    // Auto hide canceled message after 5 seconds
    setTimeout(() => {
        cancelDiv.remove();
    }, 5000);
}
</script>

<?php get_footer(); ?>