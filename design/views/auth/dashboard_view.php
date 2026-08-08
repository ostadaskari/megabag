<?php include(__DIR__ . '/../partials/header.php'); ?>
<?php include(__DIR__ . '/../partials/navbar.php'); ?>

    <!--########## main ##########-->
    <div class="container p-0 main">
      <div class="row d-flex flex-row" style="height: 100%">
         <?php include(__DIR__ . '/../partials/sidebar.php'); ?>

            <div class="content w-100 p-2">
                <?php if ($msg): ?>
                <script>
                    Swal.fire({
                        icon: '<?= htmlspecialchars($type) ?>',
                        title: '<?= ucfirst($type) ?>',
                        text: '<?= htmlspecialchars($msg) ?>',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
                <?php endif; ?>

                    <!-- 🔽 The dynamic include block -->

                <?php
                    if ($content_file && file_exists( $content_file)) {
                        include( $content_file);
                    } elseif ($page === 'home') {
                        echo "<h2>Welcome, " . htmlspecialchars($username) . "!</h2><p>Your role: <strong>" . htmlspecialchars($role) . "</strong></p>";
                    } else {
                        echo "<p>Page not found or access denied.</p>";
                    }
                ?>

                <!--  The dynamic include block -->
                </div>
                
      </div>
    </div>


<?php include(__DIR__ . '/../partials/footer.php'); ?>



// timeout Session 
<div id="sessionBadge" style="position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.85); color: white; padding: 8px 12px; border-radius: 20px; font-family: monospace; font-size: 13px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2); cursor: pointer; transition: all 0.3s;">
    🔐 <span id="badgeTimer">--:--</span>
    <div id="badgeDetails" style="display: none; margin-top: 5px; font-size: 11px;">
        <div>User: <?= htmlspecialchars($username) ?></div>
        <div>Expires: <span id="expiryTime"><?= $expiry_time ?></span></div>
        <button onclick="refreshSession(event)" style="margin-top: 5px; padding: 3px 8px; font-size: 10px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>

<script>
// Get values from PHP
const sessionTimeout = <?= $session_timeout * 1000 ?>; // 14000 seconds in ms
const sessionStart = <?= $_SESSION['last_activity'] * 1000 ?>;
let sessionExpiry = sessionStart + sessionTimeout;

const badge = document.getElementById('sessionBadge');
const badgeTimer = document.getElementById('badgeTimer');
const badgeDetails = document.getElementById('badgeDetails');
const expiryTime = document.getElementById('expiryTime');

// Calculate initial remaining time
let remainingTime = sessionExpiry - Date.now();

function updateBadgeTimer() {
    const now = Date.now();
    remainingTime = sessionExpiry - now;
    
    if (remainingTime <= 0) {
        badgeTimer.textContent = 'EXPIRED';
        badge.style.background = '#dc3545';
        
        setTimeout(() => {
            window.location.href = '../auth/login.php?expired=1';
        }, 2000);
        
        clearInterval(interval);
        return;
    }
    
    const hours = Math.floor(remainingTime / 3600000);
    const minutes = Math.floor((remainingTime % 3600000) / 60000);
    const seconds = Math.floor((remainingTime % 60000) / 1000);
    
    // Display format: HH:MM:SS or MM:SS
    if (hours > 0) {
        badgeTimer.textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    } else {
        badgeTimer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
    
    // Visual feedback
    if (remainingTime < 300000) { // 5 min
        badge.style.background = '#dc3545';
        badge.style.animation = 'pulse 0.5s infinite';
    } else if (remainingTime < 900000) { // 15 min
        badge.style.background = '#e0a800';
        badge.style.animation = 'none';
    } else {
        badge.style.background = 'rgba(0,0,0,0.85)';
        badge.style.animation = 'none';
    }
}

// Toggle details on click
badge.addEventListener('click', function(e) {
    if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
        return;
    }
    const isHidden = badgeDetails.style.display === 'none' || badgeDetails.style.display === '';
    badgeDetails.style.display = isHidden ? 'block' : 'none';
    badge.style.width = isHidden ? '180px' : 'auto';
});

// Manual refresh
function refreshSession(event) {
    event.stopPropagation();
    
    const btn = event.target.tagName === 'BUTTON' ? event.target : event.target.closest('button');
    btn.disabled = true;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    
    fetch('session_refresh.php')
        .then(response => response.json())
        .then(data => {
            console.log('Refresh response:', data);
            
            if (data.success) {
                // Update session expiry
                sessionExpiry = data.expires_at * 1000;
                
                // Calculate new expiry time in HH:MM format
                const newExpiryDate = new Date(sessionExpiry);
                expiryTime.textContent = newExpiryDate.getHours().toString().padStart(2, '0') + ':' + 
                                         newExpiryDate.getMinutes().toString().padStart(2, '0');
                
                // Update timer
                updateBadgeTimer();
                
                // Success feedback
                badge.style.background = '#28a745';
                setTimeout(() => {
                    updateBadgeTimer(); // This will set the right color
                }, 1500);
                
                // Show success
                showToast('Session extended to ' + expiryTime.textContent, 'success');
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error: ' + error.message, 'error');
        })
        .finally(() => {
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }, 1000);
        });
}

// Toast function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 70px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        z-index: 10000;
        font-size: 12px;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initial update
updateBadgeTimer();
const interval = setInterval(updateBadgeTimer, 1000);
</script>