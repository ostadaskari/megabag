<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
    <svg width="22" height="22" fill="currentColor" class="bi bi-envelope-paper mx-1 me-2" viewBox="0 0 16 16">
        <path d="M4 0a2 2 0 0 0-2 2v1.133l-.941.502A2 2 0 0 0 0 5.4V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.4a2 2 0 0 0-1.059-1.765L14 3.133V2a2 2 0 0 0-2-2zm10 4.267.47.25A1 1 0 0 1 15 5.4v.817l-1 .6zm-1 3.15-3.75 2.25L8 8.917l-1.25.75L3 7.417V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1zm-11-.6-1-.6V5.4a1 1 0 0 1 .53-.882L2 4.267zm13 .566v5.734l-4.778-2.867zm-.035 6.88A1 1 0 0 1 14 15H2a1 1 0 0 1-.965-.738L8 10.083zM1 13.116V7.383l4.778 2.867L1 13.117Z"/>
    </svg>
    Invite Users</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div class="container px-0">
    <?php foreach ($errors as $e): ?>
        <script>Swal.fire('Error', '<?php echo addslashes($e); ?>', 'error');</script>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <script>Swal.fire('Success', '<?php echo addslashes($success); ?>', 'success');</script>
    <?php endif; ?>

    <div id="Invait-User" class="tab-content">
        <div class="row">
            <div class="col-12 px-0">
                <div class="form-box border rounded shadow-sm bg-light p-1">
                    <form method="POST" id="inviteForm">
                        <div class="d-flex flex-row align-items-center gap-2">
                            <label for="nicknameInput" class="form-label mb-0" style="width: 200px;">Nickname:</label>
                            <input type="text" name="nickname" class="form-control" style="height: 38px;" id="nicknameInput" placeholder="Nickname for Invite" required>

                            <select name="role" class="form-control ml-1" id="roleSelector" style="width: 150px; height: 38px;" required>
                                <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                <option value="manager" <?php echo (isset($_POST['role']) && $_POST['role'] === 'manager') ? 'selected' : ''; ?>>Manager</option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>

                            <button class="btn btn-primary mx-1" style="height: 38px;" type="submit">Generate</button>
                        </div>
                    </form>

                    <?php if ($inviteLink): ?>
                        <div id="codeOutput" class="output-label d-flex align-items-center gap-2">
                            <span>URL:</span>
                            <input class="form-control" type="text" id="copyText" value="<?php echo htmlspecialchars($inviteLink); ?>" readonly />
                            <button type="button" id="btn-copy" class="btn btn-outline-secondary d-flex align-items-center p-2" onclick="copyInviteLinkFromInput()">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 mt-1">
                <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="height:65vh;">

                    <table class="table table-bordered table-striped table-hover mb-0 text-center" style="min-width: 800px;">
                        <thead class="table-invitionLink sticky-top" style="top:-3px; z-index: 1;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nickname</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                                <th scope="col">Invite Code</th>
                                <th scope="col">Creator</th>
                                <th scope="col">Used By</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Registered At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invites)): ?>
                                <?php foreach ($invites as $index => $invite): ?>
                                    <tr>
                                        <td><?php echo (($currentPage - 1) * $itemsPerPage) + $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($invite['nickname']); ?></td>
                                        <td><?php echo htmlspecialchars($invite['role']); ?></td>
                                        <td>
                                            <?php if ($invite['is_used']): ?>
                                                <span class="badge bg-danger rounded-pill">Used</span>
                                            <?php else: ?>
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="copy-link" 
                                                onclick="copyInviteLink('<?php echo htmlspecialchars($invite['code']); ?>')" 
                                                style="cursor:pointer;" 
                                                title="Click to copy invite link">
                                                <svg width="24" height="24" fill="goldenrod" class="bi bi-envelope hoverSvg" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                                </svg>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($invite['created_by_nickname'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($invite['used_by_nickname'] ?: '-'); ?></td>
                                        <td>
                                            <div title="<?php echo htmlspecialchars(date('Y/n/d, G:i', strtotime($invite['generated_at']))); ?>">
                                                <svg width="24" height="24" fill="mediumblue" class="bi bi-clock hoverSvg" viewBox="0 0 16 16">
                                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                                                </svg>
                                            </div>
                                        </td>
                                        <td>
                                            <div title="<?php echo htmlspecialchars(date('Y/n/d, G:i', strtotime($invite['used_at']))); ?>">
                                                <?php if ($invite['is_used']): ?>
                                                    <svg width="24" height="24" fill="mediumseagreen" class="bi bi-clock-history hoverSvg" viewBox="0 0 16 16">
                                                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                                        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                                                    </svg>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" id="delete-form-<?php echo $invite['id']; ?>" style="display:inline;">
                                                <input type="hidden" name="delete_code_id" value="<?php echo $invite['id']; ?>">
                                                <button type="button" onclick="confirmDelete(<?php echo $invite['id']; ?>, <?php echo (int)$invite['is_used']; ?>)" style="background:none;border:none;cursor:pointer;">
                                                    <svg width="22" height="22" fill="currentColor" class="bi bi-fire text-danger hoverSvg" viewBox="0 0 16 16">
                                                        <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16m0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No invite codes have been generated yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Section -->
                <?php if ($totalPages > 1): ?>
                    <div class="row my-2">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">
                                
                                <!-- First Page -->
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=invite_users&p=1" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                        First
                                    </a>
                                <?php else: ?>
                                    <span class="btn px-3 px-custom d-flex align-items-center btnNP borderRight disabled">
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                        First
                                    </span>
                                <?php endif; ?>

                                <!-- Previous Page -->
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=invite_users&p=<?= $currentPage - 1 ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                        <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                    </svg>
                                        Prev
                                    </a>
                                <?php else: ?>
                                    <span class="btn px-3 px-custom d-flex align-items-center btnNP borderRight disabled">
                                    <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                        <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                    </svg>
                                        Prev
                                    </span>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <div class="px-4 px-custom d-flex gap-2">
                                    <?php
                                        $range = 2;
                                        $start = max(1, $currentPage - $range);
                                        $end = min($totalPages, $currentPage + $range);
                                        for ($i = $start; $i <= $end; $i++):
                                    ?>
                                        <?php if ($i == $currentPage): ?>
                                            <span class="px-1 fw-bold text-danger"><?= $i ?></span>
                                        <?php else: ?>
                                            <a href="?page=invite_users&p=<?= $i ?>" class="px-1 fw-bold"><?= $i ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>

                                <!-- Next Page -->
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=invite_users&p=<?= $currentPage + 1 ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft">
                                        Next
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                        </svg>
                                    </a>
                                <?php else: ?>
                                    <span class="btn px-3 px-custom d-flex align-items-center btnNP borderLeft disabled">
                                        Next
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>

                                <!-- Last Page -->
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=invite_users&p=<?= $totalPages ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft">
                                        last
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </a>
                                <?php else: ?>
                                    <span class="btn px-3 px-custom d-flex align-items-center btnNP borderLeft disabled">
                                        last
                                        <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Copies the invite link from the generated input field.
 */
function copyInviteLinkFromInput() {
    const copyText = document.getElementById("copyText");
    copyText.select();
    document.execCommand("copy");

    Swal.fire("Copied!", "Invite link copied to clipboard.", "success");
}

/**
 * Copies a specific invite link from the table to the clipboard.
 * @param {string} code The invite code to be copied.
 */
function copyInviteLink(code) {
    const fullLink = `http://localhost/megabag/rgs.php?code=${code}`;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(fullLink).then(() => {
            Swal.fire('Copied!', 'Invite link copied to clipboard.', 'success');
        }).catch(err => {
            // Fallback to execCommand if navigator.clipboard fails
            const tempInput = document.createElement('input');
            tempInput.value = fullLink;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            Swal.fire('Copied!', 'Invite link copied to clipboard.', 'success');
        });
    } else {
        // Fallback for browsers that don't support the Clipboard API
        const tempInput = document.createElement('input');
        tempInput.value = fullLink;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        Swal.fire('Copied!', 'Invite link copied to clipboard.', 'success');
    }
}

/**
 * Prompts the user to confirm the deletion of an invite code.
 * @param {number} id The ID of the invite code to delete.
 * @param {number} isUsed A boolean flag (0 or 1) indicating if the code has been used.
 */
function confirmDelete(id, isUsed) {
    if (isUsed) {
        Swal.fire('Cannot Delete', 'This invite code has already been used.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "This invite code will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
}

</script>