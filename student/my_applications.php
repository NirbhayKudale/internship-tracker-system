<?php
$page_title = "My Applications";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isLoggedIn()) redirect('../auth/login.php');

$student_id = $_SESSION['student_id'];
$sort = $_GET['sort'] ?? 'date';
$order = $sort === 'status' 
    ? "CASE a.status WHEN 'Under Review' THEN 1 WHEN 'Selected' THEN 2 WHEN 'Rejected' THEN 3 ELSE 4 END"
    : "a.apply_date DESC";

$applications = $conn->query("
    SELECT a.id as app_id, a.status, a.apply_date,
           j.title, j.company_name, j.last_date_to_apply
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.student_id = $student_id
    ORDER BY $order
");

$total     = $conn->query("SELECT COUNT(*) as c FROM applications WHERE student_id=$student_id")->fetch_assoc()['c'];
$selected  = $conn->query("SELECT COUNT(*) as c FROM applications WHERE student_id=$student_id AND status='Selected'")->fetch_assoc()['c'];
$pending   = $conn->query("SELECT COUNT(*) as c FROM applications WHERE student_id=$student_id AND status='Under Review'")->fetch_assoc()['c'];
$rejected  = $conn->query("SELECT COUNT(*) as c FROM applications WHERE student_id=$student_id AND status='Rejected'")->fetch_assoc()['c'];

$page_seo = generateSEO(['title' => 'My Applications | Internship Tracker', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="hero-section p-4 mb-4 rounded">
        <h1 class="display-6 fw-bold"><i class="fas fa-file-alt"></i> My Applications</h1>
        <p class="lead mb-0">Track all your internship applications in one place.</p>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <?php
        $stats = [
            ['label'=>'Total Applied',  'value'=>$total,    'color'=>'primary',  'icon'=>'fa-paper-plane'],
            ['label'=>'Under Review',   'value'=>$pending,  'color'=>'warning',  'icon'=>'fa-hourglass-half'],
            ['label'=>'Selected',       'value'=>$selected, 'color'=>'success',  'icon'=>'fa-check-circle'],
            ['label'=>'Rejected',       'value'=>$rejected, 'color'=>'danger',   'icon'=>'fa-times-circle'],
        ];
        foreach ($stats as $s): ?>
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center border-<?php echo $s['color']; ?>" style="border-top: 4px solid;">
                <div class="card-body py-3">
                    <i class="fas <?php echo $s['icon']; ?> fa-2x text-<?php echo $s['color']; ?> mb-2"></i>
                    <h3 class="fw-bold text-<?php echo $s['color']; ?>"><?php echo $s['value']; ?></h3>
                    <small class="text-muted"><?php echo $s['label']; ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Sort -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Applications</h5>
        <div class="btn-group btn-group-sm">
            <a href="?sort=date"   class="btn <?php echo $sort !== 'status' ? 'btn-primary' : 'btn-outline-primary'; ?>">Sort by Date</a>
            <a href="?sort=status" class="btn <?php echo $sort === 'status' ? 'btn-primary' : 'btn-outline-primary'; ?>">Sort by Status</a>
        </div>
    </div>

    <?php if ($applications && $applications->num_rows > 0): ?>
        <div id="applications-list">
        <?php while ($app = $applications->fetch_assoc()):
            $badge = match($app['status']) {
                'Selected'    => 'success',
                'Rejected'    => 'danger',
                default       => 'warning'
            };
            $icon = match($app['status']) {
                'Selected'    => 'fa-check-circle',
                'Rejected'    => 'fa-times-circle',
                default       => 'fa-hourglass-half'
            };
        ?>
        <div class="card mb-3 application-card" id="app-<?php echo $app['app_id']; ?>">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($app['title']); ?></h5>
                        <p class="text-primary mb-1"><i class="fas fa-building"></i> <?php echo htmlspecialchars($app['company_name']); ?></p>
                        <small class="text-muted"><i class="fas fa-calendar"></i> Applied: <?php echo date('d M Y', strtotime($app['apply_date'])); ?></small>
                    </div>
                    <div class="col-md-3 text-md-center my-2 my-md-0">
                        <span class="badge bg-<?php echo $badge; ?> fs-6 px-3 py-2">
                            <i class="fas <?php echo $icon; ?>"></i> <?php echo $app['status']; ?>
                        </span>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <?php if ($app['status'] === 'Under Review'): ?>
                            <button class="btn btn-danger btn-sm withdraw-btn" data-app-id="<?php echo $app['app_id']; ?>">
                                <i class="fas fa-times"></i> Withdraw
                            </button>
                        <?php elseif ($app['status'] === 'Selected'): ?>
                            <span class="text-success fw-bold"><i class="fas fa-trophy"></i> Congratulations!</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No applications yet</h4>
            <p class="text-muted">Start applying to internships to see them here.</p>
            <a href="jobs.php" class="btn btn-primary"><i class="fas fa-briefcase"></i> Browse Jobs</a>
        </div>
    <?php endif; ?>
</main>

<!-- Withdraw Confirm Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Confirm Withdrawal</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p>Are you sure you want to withdraw this application? This cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmWithdraw">Yes, Withdraw</button>
            </div>
        </div>
    </div>
</div>

<script>
let pendingAppId = null;

document.querySelectorAll('.withdraw-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        pendingAppId = this.dataset.appId;
        new bootstrap.Modal(document.getElementById('withdrawModal')).show();
    });
});

document.getElementById('confirmWithdraw').addEventListener('click', function () {
    if (!pendingAppId) return;
    fetch('../api/withdraw_application.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'application_id=' + pendingAppId
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('withdrawModal')).hide();
        if (data.success) {
            showToast(data.message, 'success');
            document.getElementById('app-' + pendingAppId)?.remove();
        } else {
            showToast(data.message, 'error');
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>