<?php
$page_title = "Browse Jobs";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isLoggedIn()) redirect('../auth/login.php');

$student_id = $_SESSION['student_id'];
$student = getStudentById($student_id);

// Get applied job IDs for this student
$applied_ids = [];
$res = $conn->query("SELECT job_id FROM applications WHERE student_id = $student_id");
while ($row = $res->fetch_assoc()) $applied_ids[] = $row['job_id'];

// Search
$search = trim($_GET['search'] ?? '');
$where = $search ? "WHERE title LIKE '%$search%' OR company_name LIKE '%$search%' OR description LIKE '%$search%'" : '';
$jobs = $conn->query("SELECT * FROM jobs $where ORDER BY last_date_to_apply ASC");

$page_seo = generateSEO(['title' => 'Browse Jobs | Internship Tracker', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="hero-section p-4 mb-4 rounded">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold"><i class="fas fa-briefcase"></i> Browse Internships</h1>
                <p class="lead mb-0">Find and apply for internship opportunities.</p>
            </div>
            <div class="col-md-4">
                <form method="GET" action="">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search jobs..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-light" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($search): ?>
        <p class="text-muted mb-3">Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong> — <a href="jobs.php">Clear</a></p>
    <?php endif; ?>

    <!-- Profile completeness warning -->
    <?php if (empty($student['resume_path']) || empty($student['technical_skills'])): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Complete your profile first!</strong> You need to upload your resume and add technical skills before applying.
            <a href="profile.php" class="btn btn-warning btn-sm ms-2">Complete Profile</a>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if ($jobs && $jobs->num_rows > 0): ?>
            <?php while ($job = $jobs->fetch_assoc()):
                $is_applied  = in_array($job['id'], $applied_ids);
                $is_expired  = strtotime($job['last_date_to_apply']) < strtotime(date('Y-m-d'));
                $days_left   = (int) ceil((strtotime($job['last_date_to_apply']) - time()) / 86400);
            ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <?php if ($is_expired): ?>
                                <span class="badge bg-danger">Expired</span>
                            <?php elseif ($days_left <= 3): ?>
                                <span class="badge bg-warning text-dark">Last <?php echo $days_left; ?> day(s)</span>
                            <?php else: ?>
                                <span class="badge bg-success"><?php echo $days_left; ?> days left</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-primary fw-semibold mb-2"><i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                        <p class="text-muted small mb-3"><?php echo nl2br(htmlspecialchars(substr($job['description'], 0, 150))); ?>...</p>
                        <p class="small mb-3"><i class="fas fa-calendar-alt text-danger"></i> <strong>Apply by:</strong> <?php echo date('d M Y', strtotime($job['last_date_to_apply'])); ?></p>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        <?php if ($is_applied): ?>
                            <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i> Applied</button>
                        <?php elseif ($is_expired): ?>
                            <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-ban"></i> Deadline Passed</button>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm apply-btn"
                                data-job-id="<?php echo $job['id']; ?>"
                                <?php echo (empty($student['resume_path']) || empty($student['technical_skills'])) ? 'disabled title="Complete your profile first"' : ''; ?>>
                                <i class="fas fa-paper-plane"></i> Apply Now
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Job Detail Modal -->
            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong><i class="fas fa-building"></i> Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
                            <p><strong><i class="fas fa-calendar-alt"></i> Last Date:</strong> <?php echo date('d M Y', strtotime($job['last_date_to_apply'])); ?></p>
                            <hr>
                            <h6>Job Description</h6>
                            <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <?php if (!$is_applied && !$is_expired): ?>
                                <button class="btn btn-primary apply-btn" data-job-id="<?php echo $job['id']; ?>"
                                    <?php echo (empty($student['resume_path']) || empty($student['technical_skills'])) ? 'disabled' : ''; ?>>
                                    <i class="fas fa-paper-plane"></i> Apply Now
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No jobs found</h4>
                    <p class="text-muted">Try a different search term or check back later.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.querySelectorAll('.apply-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const jobId = this.dataset.jobId;
        const self = this;
        self.disabled = true;
        self.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';

        fetch('../api/apply_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'job_id=' + jobId
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
                self.disabled = false;
                self.innerHTML = '<i class="fas fa-paper-plane"></i> Apply Now';
            }
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>