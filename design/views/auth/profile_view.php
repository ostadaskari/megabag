<?php
// Generate a single CSRF token for the entire page
$csrf_token = generate_csrf_token();
?>
 <div class="d-flex flex-row align-items-center justify-content-between titleTop">       
    <h2 class="d-flex align-items-center">
   <svg width="24" height="24" fill="currentColor" class="bi bi-person-square mx-1 me-2" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
</svg>
    Edit Profile</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<!-- profile -->
<div class="tab-content mt-2 mt-md-0" id="profile">
<div class="row">
<div class="col-12 col-md-8 px-1 mb-3">
<div class="p-3 border rounded shadow-sm bg-light h-100">
<!-- Title -->
<div class="d-flex align-items-center mb-3">
<svg width="20" height="20" fill="currentColor" class="bi bi-person-square" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
</svg>
<h4 class="mx-2 mb-0">Profile Information</h4>
</div>

<!-- Form -->
<form method="POST" class="d-flex flex-column justify-content-between flex-grow-1">
    <!-- csrf -->
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
<div class="row g-3">
  <div class="col-12 col-lg-6 px-1 mb-3">
    <label for="firstName" class="form-label ">First Name</label>
    <input type="text" class="form-control my-2" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Enter first name" autocomplete="off">
  </div>
  <div class="col-12 col-lg-6 px-1 mb-3">
    <label for="lastName" class="form-label ">Last Name</label>
    <input type="text" class="form-control my-2" name="family" value="<?= htmlspecialchars($user['family']) ?>" placeholder="Enter last name" autocomplete="off">
  </div>
  <div class="col-12 col-lg-6 px-1 mb-3">
    <label for="nickName" class="form-label ">Nickname</label>
    <input type="text" class="form-control my-2" name="nickname" value="<?= htmlspecialchars($user['nickname']) ?>" placeholder="Enter nickname" autocomplete="off">
  </div>
  <div class="col-12 px-1 mb-3">
    <label for="profileEmail" class="form-label ">Email Address</label>
    <input type="email" class="form-control my-2" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Enter your email" autocomplete="off">
  </div>
</div>

<!-- Save Button -->
<div class="text-end mt-3">
  <button type="submit" name="form" value="info"  class="btn btn-saveChanges px-3">Save Changes</button>
</div>
</form>
</div>
</div>

<div class="col-12 col-md-4 px-1 mb-3">
<!-- changesPass -->
<div class="p-3 border rounded shadow-sm bg-light h-100">
<div class="d-flex flex-row align-items-center mb-3">
      <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square hoverSvg" viewBox="0 0 16 16">
          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
      </svg>
      <h4 class="mx-2 mb-0">Change Password</h4>
  </div>
  
  <form method="POST" >
        <!-- csrf -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <div class="row mb-3">
      <div class="col-12 mb-3">
        <label for="currentPassword" class="form-label ">Current Password</label>
        <input type="password" class="form-control my-2" id="currentPassword" name="current_password"  placeholder="Enter current password" autocomplete="new-password">
      </div>
      <div class="col-12 mb-3">
        <label for="newPassword" class="form-label ">New Password</label>
        <input type="password" class="form-control my-2" id="newPassword" name="new_password" placeholder="Enter new password" autocomplete="new-password">
      </div>
      <div class="col-12 mb-3">
        <label for="confirmPassword" class="form-label ">Confirm New Password</label>
        <input type="password" class="form-control my-2" id="confirmPassword" name="confirm_password"  placeholder="Confirm new password" autocomplete="new-password">
      </div>
    </div>
  
    <div class="text-end">
      <button type="submit" name="form" value="password" class="btn px-2 btn-changesPass">Change Password</button>
    </div>
  </form>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const fields = ["currentPassword", "newPassword", "confirmPassword"];
  
      fields.forEach(function (fieldId) {
        const input = document.getElementById(fieldId);
  
        // Wrap input in a div with position: relative
        const wrapper = document.createElement("div");
        wrapper.classList.add("password-wrapper");
  
        // Clone the input and replace
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
  
        // Set type to password
        input.type = "password";
  
        // Create the toggle eye icon
        const toggle = document.createElement("span");
        toggle.classList.add("toggle-eye");
  
        // Default icon (eye-slash)
        toggle.innerHTML = `
          <svg width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
            <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
          </svg>
        `;
  
        toggle.addEventListener("click", function () {
          if (input.type === "password") {
            input.type = "text";
            toggle.innerHTML = `
              <svg width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
              </svg>
            `;
          } else {
            input.type = "password";
            toggle.innerHTML = `
              <svg width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
              </svg>
            `;
          }
        });
  
        // Append eye icon to wrapper
        wrapper.appendChild(toggle);
      });
    });
  </script>
    
    
</div>
</div>
</div>
</div>
<!-- end profile -->




        <!-- SweetAlert for success -->
<?php if (!empty($success) || !empty($errors)): ?>
    <script>
        // Combine all logic into a single script block
        const url = new URL(window.location.href);

        <?php if (!empty($success)): ?>
            // Display the success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?= json_encode($success) ?>
            });
            // After displaying the message, clean the URL
            url.searchParams.delete('success');

        <?php elseif (!empty($errors)): ?>
            // Display the error message
            Swal.fire({
                icon: 'error',
                title: 'Errors',
                html: <?= json_encode('<ul><li>' . implode('</li><li>', $errors) . '</li></ul>') ?>
            });
            // After displaying the message, clean the URL
            url.searchParams.delete('errors');
        <?php endif; ?>

        // Update the URL in the browser without reloading the page
        history.replaceState(null, '', url);
    </script>
<?php endif; ?>

    
