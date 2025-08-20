<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>

    <!-- link CSS -->
    <link rel="stylesheet" href="../../design/assets/css/style.css" />
        <link rel="stylesheet" href="../../design/assets/css/login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<body>
    <div class="container" id="containerLogin">
        <div class="topLog">
            <div class="bottomLog">
                <div class="centerLogin shadow-sm">
                    <h2 class="h2Form">Login to warehousing system</h2>
                    <form id="loginForm" class="form-section" method="POST" action="login.php">
                        <div class="w-100 divInput">
                            <svg width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                            <input type="text" name="username" class="form-control" placeholder="Username" required/>
                        </div>
                        <div class="w-100 divInput position-relative">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                            </svg>
                            <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Password" required>
                            <span class="toggle-password" toggle="#loginPassword">
                                <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 3a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-between w-100 my-2">
                            <input type="text" name="captcha" class="form-control input-captcha" placeholder="Enter CAPTCHA" required>
                            <div class="divCaptcha">
                                <img src="captcha.php" class="imgCaptcha" alt="CAPTCHA">
                                <svg width="20" height="20" fill="var(--main-bg1-color)" class="bi bi-arrow-clockwise refresh-captcha" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="btn loginTo mt-4">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for fade-in effect on page load -->
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const container = document.querySelector("#containerLogin");
            if (container) {
                setTimeout(() => {
                    container.classList.add("active");
                }, 300); 
            }
        });
    </script>

    <!-- Script for toggle eye -->
    <script src="../../design/assets/js/log-reg.js"></script>
    
    <!-- Script for CAPTCHA refresh -->
    <script>
        document.querySelector('.refresh-captcha').addEventListener('click', function() {
            document.querySelector('.imgCaptcha').src = 'captcha.php?' + Math.random();
        });
    </script>
    
    <!-- PHP for displaying alerts -->
    <?php if (isset($_GET['registered'])): ?>
    <script>
        Swal.fire('Success', 'Registration successful. You can now login.', 'success');
    </script>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <script>
                Swal.fire('Error', '<?php echo addslashes($err); ?>', 'error');
            </script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
