

<?php include(__DIR__ . '/../partials/header.php'); ?>
<?php include(__DIR__ . '/../partials/navbar.php'); ?>

    <!--########## main ##########-->
    <div class="container p-0 main">
      <div class="row d-flex flex-row" style="height: 100%">
         <?php include(__DIR__ . '/../partials/sidebar.php'); ?>

            <section class="content w-100 p-4">
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
            </section>
      </div>
    </div>

<?php include(__DIR__ . '/../partials/footer.php'); ?>

