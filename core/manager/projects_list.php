<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (ACL) Restrict access to admins/managers only (access level)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $projectId = (int) $_POST['project_id'];

    // Delete the project from the 'projects' table
    $stmtProject = $conn->prepare("DELETE FROM projects WHERE id = ?");
    if ($stmtProject) {
        $stmtProject->bind_param("i", $projectId);
        $stmtProject->execute();
        $stmtProject->close();
    }

    // Optionally show a success message
    // Note: SweetAlert2 is not included in this file, so ensure it's loaded in the view.
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Project deleted successfully.',
        }).then(() => window.location.reload());
    </script>";

    // Redirect to avoid re-submission
    header("Location: ../auth/dashboard.php?page=projects_list");
    exit;
}

require_once '../../design/views/manager/projects_list_view.php';