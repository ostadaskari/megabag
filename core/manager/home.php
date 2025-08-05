<?php
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db/db.php';




// --- Render view ---
include('../../design/views/manager/home_view.php');
