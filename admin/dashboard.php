<?php
$page_title = "Admin Dashboard";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

// CHECK IF ADMIN LOGGED IN
if(!isset($_SESSION['admin_id'])) {
    redirect('../auth/admin_login.php');
}

// GET ADMIN STATISTICS
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_jobs = $conn->query("SELECT COUNT(*) as count FROM jobs")->fetch_assoc()['count'];
$total_applications = $conn->query("SELECT COUNT(*) as count FROM applications")->fetch_assoc()['count'];
$selected = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status = 'Selected'")->fetch_assoc()['count'];
$pending = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status = 'Under Review'")->fetch_assoc()['count'];
$rejected = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status = 'Rejected'")->fetch_assoc()['count'];
$open_tickets = $conn->query("SELECT COUNT(*) as count FROM support_tickets WHERE status = 'Open'")->fetch_assoc()['count'];

// GET RECENT DATA
$recent_students = $conn->query("SELECT * FROM students ORDER BY id DESC LIMIT 5");
$recent_applications = $conn->query("
    SELECT a.*, s.name as student_name, j.title as job_title 
    FROM applications a 
    JOIN students s ON a.student_id = s.id 
    JOIN jobs j ON a.job_id = j.id 
    ORDER BY a.id DESC 
    LIMIT 5
");

$page_seo = generateSEO([
    'title' => 'Admin Dashboard | Internship Tracker',
    'description' => 'Manage internships, students, and applications.',
    'keywords' => 'admin, dashboard, management',
    'robots' => 'noindex, nofollow',
]);

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<style>
    .admin-stat-card {
        border-radius: 10px;
        padding: 2rem;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-top: 4px solid;
    }

    .admin-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .admin-stat-card h3 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .admin-stat-card.blue {
        border-top-color: #007bff;
    }

    .admin-stat-card.blue h3 {
        color: #007bff;
    }

    .admin-stat-card.green {
        border-top-color: #28a745;
    }

    .admin-stat-card.green h3 {
        color: #28a745;
    }

    .admin-stat-card.red {
        border-top-color: #dc3545;
    }

    .admin-stat-card.red h3 {
        color: #dc3545;
    }

    .admin-stat-card.yellow {
        border-top-color: #ffc107;
    }

    .admin-stat-card.yellow h3 {
        color: #ffc107;
    }

    .admin-stat-card.purple {
        border-top-color: #6f42c1;
    }

    .admin-stat-card.purple h3 {
        color: #6f42c1;
    }

    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }
</style>

<main class="container-fluid my-4">
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">Admin Dashboard 🛡️</h1>
                <p class="lead">Manage your internship platform</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-light btn-lg">
                    <i class="fas fa-bell"></i> Notifications
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-5">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card blue">
                <h3><?php echo $total_students; ?></h3>
                <p><i class="fas fa-users"></i> Students</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card green">
                <h3><?php echo $total_jobs; ?></h3>
                <p><i class="fas fa-briefcase"></i> Jobs Posted</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card red">
                <h3><?php echo $total_applications; ?></h3>
                <p><i class="fas fa-file-alt"></i> Applications</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card yellow">
                <h3><?php echo $pending; ?></h3>
                <p><i class="fas fa-hourglass"></i> Pending</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card green">
                <h3><?php echo $selected; ?></h3>
                <p><i class="fas fa-check-circle"></i> Selected</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="admin-stat-card purple">
                <h3><?php echo $open_tickets; ?></h3>
                <p><i class="fas fa-ticket"></i> Support Tickets</p>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                            <h5>Post New Job</h5>
                            <p class="text-muted">Create internship opportunities</p>
                            <a href="post-job.php" class="btn btn-primary btn-sm">Post Job</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                            <h5>Manage Jobs</h5>
                            <p class="text-muted">Edit or delete job postings</p>
                            <a href="manage-jobs.php" class="btn btn-success btn-sm">Manage</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h5>Manage Students</h5>
                            <p class="text-muted">View & manage student accounts</p>
                            <a href="manage-students.php" class="btn btn-info btn-sm">Manage</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                            <h5>Manage Applications</h5>
                            <p class="text-muted">Review & update applications</p>
                            <a href="manage-applications.php" class="btn btn-warning btn-sm">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students Table -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Students</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
<tbody>
    <?php if($recent_students && $recent_students->num_rows > 0): ?>
        <?php while($student = $recent_students->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($student['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                <td>
                    <a href="view-student.php?id=<?php echo $student['id']; ?>" 
                       class="btn btn-sm btn-primary">View</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4" class="text-center text-muted">No students found</td></tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Applications Table -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-list-check"></i> Recent Applications</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Job</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($app = $recent_applications->fetch_assoc()): 
                                $status_class = '';
                                if($app['status'] == 'Selected') $status_class = 'bg-success';
                                elseif($app['status'] == 'Rejected') $status_class = 'bg-danger';
                                else $status_class = 'bg-warning';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(substr($app['student_name'], 0, 15)); ?></td>
                                    <td><?php echo htmlspecialchars(substr($app['job_title'], 0, 15)); ?></td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo $app['status']; ?></span></td>
                                    <td>
                                        <a href="review-application.php?id=<?php echo $app['id']; ?>" 
                                           class="btn btn-sm btn-info">Review</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>