<?php
$page_title = "Student Dashboard";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

// CHECK IF LOGGED IN
if(!isLoggedIn()) {
    redirect('../auth/login.php');
}

$student_id = $_SESSION['student_id'];
$student = getStudentById($student_id);

// GET STUDENT STATISTICS
$total_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE student_id = '$student_id'")->fetch_assoc()['count'];
$selected_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE student_id = '$student_id' AND status = 'Selected'")->fetch_assoc()['count'];
$pending_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE student_id = '$student_id' AND status = 'Under Review'")->fetch_assoc()['count'];
$rejected_apps = $conn->query("SELECT COUNT(*) as count FROM applications WHERE student_id = '$student_id' AND status = 'Rejected'")->fetch_assoc()['count'];

// GET RECENT APPLICATIONS
$recent_apps = $conn->query("
    SELECT a.*, j.title, j.company_name 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    WHERE a.student_id = '$student_id' 
    ORDER BY a.apply_date DESC 
    LIMIT 5
");

$page_seo = generateSEO([
    'title' => 'Student Dashboard | Internship Tracker',
    'description' => 'Manage your internship applications and profile.',
    'keywords' => 'dashboard, applications, internship',
    'robots' => 'noindex, follow',
]);

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<style>
    .stat-card {
        border-radius: 10px;
        padding: 2rem;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .stat-card h3 {
        color: #007bff;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #6c757d;
        margin: 0;
    }

    .stat-card.selected {
        border-left-color: #28a745;
    }

    .stat-card.selected h3 {
        color: #28a745;
    }

    .stat-card.pending {
        border-left-color: #ffc107;
    }

    .stat-card.pending h3 {
        color: #ffc107;
    }

    .stat-card.rejected {
        border-left-color: #dc3545;
    }

    .stat-card.rejected h3 {
        color: #dc3545;
    }

    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.9rem;
    }
</style>

<main class="container my-5">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">Welcome, <?php echo htmlspecialchars($student['name']); ?>! 👋</h1>
                <p class="lead">Track your internship applications and manage your profile</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="profile.php" class="btn btn-light btn-lg">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3><?php echo $total_apps; ?></h3>
                <p><i class="fas fa-file-alt"></i> Total Applications</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card selected">
                <h3><?php echo $selected_apps; ?></h3>
                <p><i class="fas fa-check-circle"></i> Selected</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card pending">
                <h3><?php echo $pending_apps; ?></h3>
                <p><i class="fas fa-hourglass"></i> Pending</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card rejected">
                <h3><?php echo $rejected_apps; ?></h3>
                <p><i class="fas fa-times-circle"></i> Rejected</p>
            </div>
        </div>
    </div>

    <!-- Recent Applications Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Recent Applications</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($recent_apps->num_rows > 0):
                        while($app = $recent_apps->fetch_assoc()): 
                            $status_class = '';
                            if($app['status'] == 'Selected') $status_class = 'bg-success';
                            elseif($app['status'] == 'Rejected') $status_class = 'bg-danger';
                            else $status_class = 'bg-warning';
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($app['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($app['apply_date'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo $status_class; ?> text-white">
                                    <?php echo $app['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="my_applications.php" 
   class="btn btn-sm btn-info">
    <i class="fas fa-eye"></i> View
</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                No applications yet. <a href="jobs.php">Browse jobs</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
<a href="my_applications.php" class="btn btn-primary">
    <i class="fas fa-arrow-right"></i> View All Applications
</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                    <h5>Browse Available Jobs</h5>
                    <p class="text-muted">Find internship opportunities</p>
                    <a href="jobs.php" class="btn btn-primary">Browse Jobs</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-3x text-success mb-3"></i>
                    <h5>Update Your Profile</h5>
                    <p class="text-muted">Keep your information current</p>
                    <a href="profile.php" class="btn btn-success">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>