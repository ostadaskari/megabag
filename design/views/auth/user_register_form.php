<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .error { color: red; font-size: 14px; margin-top: 4px; }
        input.valid { box-shadow: 0 0 5px green; }
        input.invalid { box-shadow: 0 0 5px red; }
    </style>
</head>
<body>

<h2>Register</h2>

<?php foreach ($errors as $err): ?>
    <script>Swal.fire('Error', '<?php echo $err; ?>', 'error');</script>
<?php endforeach; ?>

<?php if (!$inviteValid): ?>
    <p style="color:red;">Invite code is invalid or used. <a href="login.php">Login now</a>.</p>
<?php else: ?>

<form method="POST" id="registerForm" autocomplete="off">
    <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">

    <label>Name:</label><br>
    <input type="text" name="name" required><br>

    <label>Family Name:</label><br>
    <input type="text" name="family" required><br>

    <label>Nickname:</label><br>
    <input type="text" name="nickname" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" id="email" required><br>
    <div id="emailError" class="error"></div>

    <label>Username:</label><br>
    <input type="text" name="username" id="username" required><br>
    <div id="usernameError" class="error"></div>

    <label>Password:</label><br>
    <input type="password" name="password" id="password" required><br>

    <label>Confirm Password:</label><br>
    <input type="password" id="confirmPassword" required><br>
    <div id="confirmError" class="error"></div>

    <br><button type="submit">Register</button>
</form>

<script>
// Check email format
document.getElementById("email").addEventListener("blur", function () {
    const email = this.value;
    const errorDiv = document.getElementById("emailError");
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        errorDiv.innerText = "Invalid email format.";
        this.classList.add("invalid");
    } else {
        errorDiv.innerText = "";
        this.classList.remove("invalid");
        this.classList.add("valid");
    }
});

// Check confirm password
document.getElementById("confirmPassword").addEventListener("input", function () {
    const pass = document.getElementById("password").value;
    const confirm = this.value;
    const errorDiv = document.getElementById("confirmError");
    if (pass !== confirm) {
        this.classList.remove("valid");
        this.classList.add("invalid");
        errorDiv.innerText = "Passwords do not match.";
    } else {
        this.classList.remove("invalid");
        this.classList.add("valid");
        errorDiv.innerText = "";
    }
});

// AJAX username check
document.getElementById("username").addEventListener("blur", function () {
    const username = this.value;
    const errorDiv = document.getElementById("usernameError");
    const input = this;

    if (username.length < 3) {
        errorDiv.innerText = "Username too short.";
        input.classList.add("invalid");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../ajax/check_username.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (this.responseText === "exists") {
            errorDiv.innerText = "Username already exists.";
            input.classList.add("invalid");
        } else {
            errorDiv.innerText = "";
            input.classList.remove("invalid");
            input.classList.add("valid");
        }
    };
    xhr.send("username=" + encodeURIComponent(username));
});
</script>

<?php endif; ?>

</body>
</html>
