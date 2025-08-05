<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registration</title>

    <!-- link CSS -->
    <link rel="stylesheet" href="../../design/assets/css/style.css" />
    <link rel="stylesheet" href="../../design/assets/css/register.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
 <div class="container" id="containerReg">
        <div class="topReg">
            <div class="bottomReg">
                <div class="centerRegistration shadow-sm">
                    <h2 class="h2Form">Registration in warehousing system</h2>

                    <?php if (!$inviteValid): ?>
                        <div class="error-message-container">
                            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.154 4.002v3.743c0 .878.966 1.154 1.258.106l.394-1.353a.5.5 0 0 1 1-.29l-.395 1.353c-.292 1.048-1.259 1.324-1.259.446V4.002z"/>
                            </svg>
                            <p class="error-message">Invite code is invalid or used. <a href="login.php">Login now</a>.</p>
                        </div>
                    <?php else: ?>

                    <form id="registerForm" class="form-section" method="POST" autocomplete="off">
                        <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>" autocomplete="off">

                        <div class="w-100 divInput">
                            <svg width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <input type="text" name="name" class="form-control" placeholder="Name" required autocomplete="off"/>
                        </div>

                        <div class="w-100 divInput">
                            <svg width="18" height="18" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                            </svg>
                            <input type="text" name="family" class="form-control" placeholder="Family Name" required autocomplete="off"/>
                        </div>
                        
                        <div class="w-100 divInput">
                            <svg width="18" height="18" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                            <input type="text" name="nickname" class="form-control" placeholder="Nickname" required autocomplete="off"/>
                        </div>

                        <div class="w-100 divInput">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                                <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671"/>
                                <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791"/>
                            </svg>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required autocomplete="off"/>
                        </div>
                        <div id="emailError" class="error"></div>

                        <div class="w-100 divInput">
                            <svg width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                            </svg>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autocomplete="off"/>
                        </div>
                        <div id="usernameError" class="error"></div>

                        <div class="w-100 divInput position-relative">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                            </svg>
                            <input type="password" name="password" id="registerPassword" class="form-control" placeholder="Password" required autocomplete="off">
                            <span class="toggle-password" toggle="#registerPassword">
                                <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 3a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                </svg>
                            </span>
                        </div>

                        <div class="w-100 divInput position-relative">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-unlock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M12 0a4 4 0 0 1 4 4v2.5h-1V4a3 3 0 1 0-6 0v2h.5A2.5 2.5 0 0 1 12 8.5v5A2.5 2.5 0 0 1 9.5 16h-7A2.5 2.5 0 0 1 0 13.5v-5A2.5 2.5 0 0 1 2.5 6H8V4a4 4 0 0 1 4-4"/>
                            </svg>
                            <input type="password" id="registerConfirm" class="form-control" placeholder="Confirm Password" required autocomplete="off">
                            <span class="toggle-password" toggle="#registerConfirm">
                                <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 3a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                </svg>
                            </span>
                        </div>
                        <div id="confirmError" class="error"></div>

                        <button type="submit" class="btn registerTo mt-3">Registration</button>
                    </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for fade-in effect on page load -->
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const container = document.querySelector("#containerReg");
            if (container) {
                setTimeout(() => {
                    container.classList.add("active");
                }, 300); 
            }

            // PHP error display using SweetAlert
            <?php foreach ($errors as $err): ?>
                Swal.fire('Error', '<?php echo addslashes($err); ?>', 'error');
            <?php endforeach; ?>
        });
    </script>
    
    <!-- Original validation scripts from the PHP code, updated for the new structure -->
    <script>
    const errorIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l-.35 3.507a.552.552 0 0 0 1.1 0l-.35-3.507A.905.905 0 0 0 8 4m0 4.5a.5.5 0 1 0 0 1h.01a.5.5 0 0 0 0-1z"/></svg>`;

    // Check email format
    document.getElementById("email").addEventListener("blur", function () {
        const email = this.value;
        const errorDiv = document.getElementById("emailError");
        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        if (!emailPattern.test(email)) {
            errorDiv.innerHTML = `${errorIcon} Invalid email format.`;
            this.classList.add("invalid");
            this.classList.remove("valid");
        } else {
            errorDiv.innerHTML = "";
            this.classList.remove("invalid");
            this.classList.add("valid");
        }
    });

    // Check confirm password
    document.getElementById("registerConfirm").addEventListener("input", function () {
        const pass = document.getElementById("registerPassword").value;
        const confirm = this.value;
        const errorDiv = document.getElementById("confirmError");
        if (pass !== confirm) {
            this.classList.remove("valid");
            this.classList.add("invalid");
            errorDiv.innerHTML = `${errorIcon} Passwords do not match.`;
        } else {
            this.classList.remove("invalid");
            this.classList.add("valid");
            errorDiv.innerHTML = "";
        }
    });

    // AJAX username check
    document.getElementById("username").addEventListener("blur", function () {
        const username = this.value;
        const errorDiv = document.getElementById("usernameError");
        const input = this;

        if (username.length < 3) {
            errorDiv.innerHTML = `${errorIcon} Username too short.`;
            input.classList.add("invalid");
            input.classList.remove("valid");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax/check_username.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.responseText === "exists") {
                errorDiv.innerHTML = `${errorIcon} Username already exists.`;
                input.classList.add("invalid");
                input.classList.remove("valid");
            } else {
                errorDiv.innerHTML = "";
                input.classList.remove("invalid");
                input.classList.add("valid");
            }
        };
        xhr.send("username=" + encodeURIComponent(username));
    });
    </script>

    <!-- script for taggle eye -->
    <script src="../../design/assets/js/log-reg.js"></script>
    <!-- end script for taggle eye -->
</body>
</html>
