<?php
$page_title = "View Student Profile";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

$student_id = intval($_GET['id'] ?? 0);
if (!$student_id) redirect('manage_students.php');

$student = getStudentById($student_id);
if (!$student) {
    redirect('manage_students.php');
}

$student_applications = $conn->query("
    SELECT a.*, j.title, j.company_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    WHERE a.student_id = $student_id 
    ORDER BY a.apply_date DESC
");

$page_seo = generateSEO(['title' => 'View Student | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="display-6 fw-bold mb-2"><i class="fas fa-user"></i> <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?></h1>
                <p class="lead mb-0">Full student profile and application history.</p>
            </div>
            <a href="manage-students.php" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to Students</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-user"></i> Personal Information</h6>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name'] ?? '-'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? '-'); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($student['contact'] ?? '-'); ?></p>
                    <p><strong>DOB:</strong> <?php echo htmlspecialchars($student['dob'] ?? '-'); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender'] ?? '-'); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address'] ?? '-'); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-graduation-cap"></i> Academic Information</h6>
                    <p><strong>College:</strong> <?php echo htmlspecialchars($student['college_name'] ?? '-'); ?></p>
                    <p><strong>Enrollment No:</strong> <?php echo htmlspecialchars($student['enrollment_no'] ?? '-'); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course'] ?? '-'); ?></p>
                    <p><strong>Year of Study:</strong> <?php echo htmlspecialchars($student['year_of_study'] ?? '-'); ?></p>
                    <p><strong>Aggregate:</strong> <?php echo htmlspecialchars($student['aggregate'] ?? '-'); ?></p>
                    <p><strong>10th / 12th:</strong> <?php echo htmlspecialchars($student['marks_10th'] ?? '-'); ?>% / <?php echo htmlspecialchars($student['marks_12th'] ?? '-'); ?>%</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-briefcase"></i> Professional Information</h6>
                    <p><strong>Technical Skills:</strong> <?php echo htmlspecialchars($student['technical_skills'] ?? '-'); ?></p>
                    <p><strong>Soft Skills:</strong> <?php echo htmlspecialchars($student['soft_skills'] ?? '-'); ?></p>
                    <p><strong>Certifications:</strong> <?php echo htmlspecialchars($student['certifications'] ?? '-'); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($student['experience'] ?? '-'); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-map-marker-alt"></i> Job Preferences</h6>
                    <p><strong>Desired Role:</strong> <?php echo htmlspecialchars($student['desired_role'] ?? '-'); ?></p>
                    <p><strong>Preferred Location:</strong> <?php echo htmlspecialchars($student['preferred_location'] ?? '-'); ?></p>
                    <p><strong>Willing to Relocate:</strong> <?php echo htmlspecialchars($student['willing_to_relocate'] ?? '-'); ?></p>
                    <p><strong>Placement Status:</strong>
                        <span class="badge <?php echo ($student['placed_status'] === 'Placed') ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars($student['placed_status'] ?? 'Not Placed'); ?>
                        </span>
                    </p>
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mt-3"><i class="fas fa-file-upload"></i> Documents</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php
                $docs = ['photo_path' => 'Photo', 'resume_path' => 'Resume', 'certificates_path' => 'Certificates', 'idproof_path' => 'ID Proof'];
                foreach ($docs as $field => $label):
                    if (!empty($student[$field])): ?>
                        <a href="../<?php echo htmlspecialchars($student[$field]); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file"></i> <?php echo $label; ?>
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline-secondary btn-sm disabled"><i class="fas fa-file"></i> <?php echo $label; ?> (not uploaded)</span>
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-file-alt"></i> Application History</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Job</th><th>Company</th><th>Applied</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php if ($student_applications && $student_applications->num_rows > 0): ?>
                    <?php while ($app = $student_applications->fetch_assoc()):
                        $badge = match($app['status']) { 'Selected' => 'success', 'Rejected' => 'danger', default => 'warning' };
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['title']); ?></td>
                        <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                        <td><?php echo date('d M Y', strtotime($app['apply_date'])); ?></td>
                        <td><span class="badge bg-<?php echo $badge; ?>"><?php echo $app['status']; ?></span></td>
                        <td><a href="review-application.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Review</a></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">No applications submitted yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>