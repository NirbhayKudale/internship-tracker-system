<?php
$page_title = "Review Application";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

$app_id = intval($_GET['id'] ?? 0);
if (!$app_id) redirect('manage_jobs.php');

$success = $error = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = trim($_POST['status'] ?? '');
    $allowed = ['Under Review', 'Selected', 'Rejected'];
    if (in_array($new_status, $allowed)) {
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $app_id);
        if ($stmt->execute()) {
            if ($new_status === 'Selected') {
                $app_check = $conn->query("SELECT student_id FROM applications WHERE id = $app_id")->fetch_assoc();
                if ($app_check) {
                    $conn->query("UPDATE students SET placed_status = 'Placed' WHERE id = " . $app_check['student_id']);
                }
            }
            $success = 'Application status updated successfully.';
        } else {
            $error = 'Failed to update status.';
        }
    } else {
        $error = 'Invalid status.';
    }
}

// Fetch application + student + job details
$app = $conn->query("
    SELECT a.*, s.name, s.email, s.contact, s.technical_skills, s.soft_skills,
           s.resume_path, s.certificates_path, s.idproof_path, s.course, s.college_name,
           j.title, j.company_name, j.description, j.last_date_to_apply
    FROM applications a
    JOIN students s ON a.student_id = s.id
    JOIN jobs j ON a.job_id = j.id
    WHERE a.id = $app_id
")->fetch_assoc();

if (!$app) redirect('manage_jobs.php');

$page_seo = generateSEO(['title' => 'Review Application | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="display-6 fw-bold mb-2"><i class="fas fa-file-alt"></i> Review Application</h1>
                <p class="lead mb-0"><?php echo htmlspecialchars($app['title']); ?> at <?php echo htmlspecialchars($app['company_name']); ?></p>
            </div>
            <a href="view-student.php?id=<?php echo $app['student_id']; ?>" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to Profile</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> <?php echo $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-briefcase"></i> Job Details</div>
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($app['title']); ?></h5>
                    <p class="text-primary"><?php echo htmlspecialchars($app['company_name']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($app['description'])); ?></p>
                    <p class="text-muted small"><i class="fas fa-calendar"></i> Last date to apply: <?php echo date('d M Y', strtotime($app['last_date_to_apply'])); ?></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-user"></i> Applicant Details</div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($app['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($app['email']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($app['contact'] ?? '-'); ?></p>
                    <p><strong>College:</strong> <?php echo htmlspecialchars($app['college_name'] ?? '-'); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($app['course'] ?? '-'); ?></p>
                    <p><strong>Technical Skills:</strong> <?php echo htmlspecialchars($app['technical_skills'] ?? '-'); ?></p>
                    <p><strong>Soft Skills:</strong> <?php echo htmlspecialchars($app['soft_skills'] ?? '-'); ?></p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php
                        $docs = ['resume_path' => 'Resume', 'certificates_path' => 'Certificates', 'idproof_path' => 'ID Proof'];
                        foreach ($docs as $field => $label):
                            if (!empty($app[$field])): ?>
                                <a href="../<?php echo htmlspecialchars($app[$field]); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file"></i> <?php echo $label; ?>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-tasks"></i> Application Status</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Applied On:</strong></p>
                    <p class="text-muted"><?php echo date('d M Y', strtotime($app['apply_date'])); ?></p>

                    <p class="mb-1"><strong>Current Status:</strong></p>
                    <?php $badge = match($app['status']) { 'Selected' => 'success', 'Rejected' => 'danger', default => 'warning' }; ?>
                    <span class="badge bg-<?php echo $badge; ?> fs-6 mb-3"><?php echo $app['status']; ?></span>

                    <form method="POST">
                        <label class="form-label fw-semibold">Update Status</label>
                        <select name="status" class="form-select mb-3">
                            <?php foreach (['Under Review', 'Selected', 'Rejected'] as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $app['status'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>