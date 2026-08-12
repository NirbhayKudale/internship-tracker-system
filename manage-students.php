<?php
$page_title = "Manage Students";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

// View applications for a job (AJAX-style panel)
$selected_student_id = isset($_GET['view']) ? intval($_GET['view']) : null;
$selected_student = null;
$student_applications = null;

if ($selected_student_id) {
    $selected_student = getStudentById($selected_student_id);
    $student_applications = $conn->query("
        SELECT a.*, j.title, j.company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.student_id = $selected_student_id 
        ORDER BY a.apply_date DESC
    ");
}

$search = trim($_GET['search'] ?? '');
$course_filter = trim($_GET['course'] ?? '');
$where_parts = [];
if ($search) $where_parts[] = "(name LIKE '%$search%' OR email LIKE '%$search%')";
if ($course_filter) $where_parts[] = "course LIKE '%$course_filter%'";
$where = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

$students = $conn->query("SELECT * FROM students $where ORDER BY id DESC");

$page_seo = generateSEO(['title' => 'Manage Students | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container-fluid my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <h1 class="display-6 fw-bold mb-2"><i class="fas fa-users"></i> Manage Students</h1>
        <p class="lead mb-0">View and manage registered student accounts.</p>
    </div>

    <?php if ($selected_student): ?>
    <!-- ===== STUDENT PROFILE VIEW ===== -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user"></i> Profile: <?php echo htmlspecialchars($selected_student['name']); ?></span>
            <a href="manage-students.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Close</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-user"></i> Personal Information</h6>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($selected_student['name'] ?? '-'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($selected_student['email'] ?? '-'); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($selected_student['contact'] ?? '-'); ?></p>
                    <p><strong>DOB:</strong> <?php echo htmlspecialchars($selected_student['dob'] ?? '-'); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($selected_student['gender'] ?? '-'); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($selected_student['address'] ?? '-'); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-graduation-cap"></i> Academic Information</h6>
                    <p><strong>College:</strong> <?php echo htmlspecialchars($selected_student['college_name'] ?? '-'); ?></p>
                    <p><strong>Enrollment No:</strong> <?php echo htmlspecialchars($selected_student['enrollment_no'] ?? '-'); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($selected_student['course'] ?? '-'); ?></p>
                    <p><strong>Year of Study:</strong> <?php echo htmlspecialchars($selected_student['year_of_study'] ?? '-'); ?></p>
                    <p><strong>Aggregate:</strong> <?php echo htmlspecialchars($selected_student['aggregate'] ?? '-'); ?></p>
                    <p><strong>10th / 12th:</strong> <?php echo htmlspecialchars($selected_student['marks_10th'] ?? '-'); ?>% / <?php echo htmlspecialchars($selected_student['marks_12th'] ?? '-'); ?>%</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-briefcase"></i> Professional Information</h6>
                    <p><strong>Technical Skills:</strong> <?php echo htmlspecialchars($selected_student['technical_skills'] ?? '-'); ?></p>
                    <p><strong>Soft Skills:</strong> <?php echo htmlspecialchars($selected_student['soft_skills'] ?? '-'); ?></p>
                    <p><strong>Certifications:</strong> <?php echo htmlspecialchars($selected_student['certifications'] ?? '-'); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($selected_student['experience'] ?? '-'); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2"><i class="fas fa-map-marker-alt"></i> Job Preferences</h6>
                    <p><strong>Desired Role:</strong> <?php echo htmlspecialchars($selected_student['desired_role'] ?? '-'); ?></p>
                    <p><strong>Preferred Location:</strong> <?php echo htmlspecialchars($selected_student['preferred_location'] ?? '-'); ?></p>
                    <p><strong>Willing to Relocate:</strong> <?php echo htmlspecialchars($selected_student['willing_to_relocate'] ?? '-'); ?></p>
                    <p><strong>Placement Status:</strong>
                        <span class="badge <?php echo ($selected_student['placed_status'] === 'Placed') ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars($selected_student['placed_status'] ?? 'Not Placed'); ?>
                        </span>
                    </p>
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mt-3"><i class="fas fa-file-upload"></i> Documents</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php
                $docs = ['photo_path' => 'Photo', 'resume_path' => 'Resume', 'certificates_path' => 'Certificates', 'idproof_path' => 'ID Proof'];
                foreach ($docs as $field => $label):
                    if (!empty($selected_student[$field])): ?>
                        <a href="../<?php echo htmlspecialchars($selected_student[$field]); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file"></i> <?php echo $label; ?>
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline-secondary btn-sm disabled"><i class="fas fa-file"></i> <?php echo $label; ?> (not uploaded)</span>
                    <?php endif;
                endforeach; ?>
            </div>

            <h6 class="text-primary border-bottom pb-2 mt-3"><i class="fas fa-file-alt"></i> Application History</h6>
            <?php if ($student_applications && $student_applications->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Job</th><th>Company</th><th>Status</th><th>Applied</th></tr></thead>
                        <tbody>
                        <?php while ($app = $student_applications->fetch_assoc()):
                            $badge = match($app['status']) { 'Selected' => 'success', 'Rejected' => 'danger', default => 'warning' };
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['title']); ?></td>
                                <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                                <td><span class="badge bg-<?php echo $badge; ?>"><?php echo $app['status']; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($app['apply_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No applications submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search/Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="course" class="form-control" placeholder="Filter by course..." value="<?php echo htmlspecialchars($course_filter); ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i> Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header"><i class="fas fa-list"></i> All Students (<?php echo $students->num_rows; ?>)</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while ($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($student['name'] ?? '-'); ?></strong></td>
                            <td><?php echo htmlspecialchars($student['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($student['course'] ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php echo ($student['placed_status'] === 'Placed') ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo htmlspecialchars($student['placed_status'] ?? 'Not Placed'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="?view=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No students found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>