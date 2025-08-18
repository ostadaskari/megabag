 <div class="d-flex flex-row align-items-center justify-content-between titleTop">       
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-ban mx-1 me-2" viewBox="0 0 16 16">
        <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
    </svg>
    Ban List</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>
<div class="container px-0">
    <!-- band list -->
    <div class="tab-content" id="BandList">
        <!-- Table View -->
        <div class="row" id="tableRow">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="max-height:80vh;overflow-y: auto;">
                        <table class="table table-bordered table-hover mb-0 text-center table-striped">
                            <thead class="table-light position-sticky top-0" style="z-index: 1;">
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Is Active</th>
                                    <th>Ban At</th>
                                    <th>Exp At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ban_list) > 0): ?>
                                    <?php $i = 1; foreach ($ban_list as $ban): ?>
                                        <tr>
                                            <td><?= ($limit * ($page - 1)) + $i++ ?></td>
                                            <td><?= htmlspecialchars($ban['username']) ?></td>
                                            <td>
                                                <?= $ban['is_active'] ? '<span class="badge bg-danger">Active</span>' : '<span class="badge bg-secondary">Expired</span>' ?>
                                            </td>
                                            <td><?= date('Y/n/d ,G:i',strtotime($ban['created_at'])) ?></td>
                                            <td><?= date('Y/n/d ,G:i',strtotime($ban['expires_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">No bans found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination Section -->
        <?php if ($totalPages > 1): ?>
            <div class="row my-2">
                <div class="col-12 d-flex justify-content-center">
                    <div class="d-flex align-items-center justify-content-between rounded border gap-2" style="background-color: #b5d4e073;padding: 3px;">
                        
                        <!-- First Page -->
                        <?php if ($page > 1): ?>
                            <a href="../auth/dashboard.php?page=ban_list&pg=1" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"/>
                                </svg>
                                First
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline-secondary px-3 px-custom d-flex align-items-center btnNP borderRight disabled">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0M4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5"/>
                                </svg>
                                First
                            </span>
                        <?php endif; ?>

                        <!-- Previous Page -->
                        <?php if ($page > 1): ?>
                            <a href="../auth/dashboard.php?page=ban_list&pg=<?= $page - 1 ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderRight">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                </svg>
                                Prev
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline-secondary px-3 px-custom d-flex align-items-center btnNP borderRight disabled">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                    <path d="m3.86 8.753 5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                </svg>
                                Prev
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <div class="px-4 px-custom d-flex gap-2">
                            <?php
                                $range = 2;
                                $start = max(1, $page - $range);
                                $end = min($totalPages, $page + $range);
                                for ($i = $start; $i <= $end; $i++):
                            ?>
                                <?php if ($i == $page): ?>
                                    <span class="px-1 fw-bold text-danger"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="../auth/dashboard.php?page=ban_list&pg=<?= $i ?>" class="px-1 fw-bold"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Next Page -->
                        <?php if ($page < $totalPages): ?>
                            <a href="../auth/dashboard.php?page=ban_list&pg=<?= $page + 1 ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft">
                                Next
                                <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline-secondary px-3 px-custom d-flex align-items-center btnNP borderLeft disabled">
                                Next
                                <svg width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                </svg>
                            </span>
                        <?php endif; ?>

                        <!-- Last Page -->
                        <?php if ($page < $totalPages): ?>
                            <a href="../auth/dashboard.php?page=ban_list&pg=<?= $totalPages ?>" class="btn btn-outline-primary px-3 px-custom d-flex align-items-center btnNP borderLeft">
                                last
                                <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0M11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline-secondary px-3 px-custom d-flex align-items-center btnNP borderLeft disabled">
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
    <!-- end band list -->
</div>