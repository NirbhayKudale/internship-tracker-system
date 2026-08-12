<?php
$page_title = "My Tickets";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isLoggedIn()) redirect('../auth/login.php');

$student_id = $_SESSION['student_id'];
$tickets = $conn->query("SELECT * FROM support_tickets WHERE student_id = $student_id ORDER BY created_at DESC");

$page_seo = generateSEO(['title' => 'My Tickets | Internship Tracker', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="hero-section p-4 mb-4 rounded">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="display-6 fw-bold"><i class="fas fa-ticket-alt"></i> My Support Tickets</h1>
                <p class="lead mb-0">Track your submitted support tickets and admin replies.</p>
            </div>
            <div class="col-auto">
                <a href="support.php" class="btn btn-light"><i class="fas fa-plus"></i> New Ticket</a>
            </div>
        </div>
    </div>

    <?php if ($tickets && $tickets->num_rows > 0): ?>
        <?php while ($ticket = $tickets->fetch_assoc()):
            $is_closed = $ticket['status'] === 'Closed';
        ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-envelope"></i>
                    <strong class="ms-2"><?php echo htmlspecialchars($ticket['subject']); ?></strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-white-50"><?php echo date('d M Y, h:i A', strtotime($ticket['created_at'])); ?></small>
                    <span class="badge <?php echo $is_closed ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $ticket['status']; ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Student message -->
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase mb-1">Your Message</label>
                    <div class="bg-light p-3 rounded">
                        <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                    </div>
                </div>

                <!-- Admin reply -->
                <?php if (!empty($ticket['admin_reply'])): ?>
                <div>
                    <label class="fw-semibold text-muted small text-uppercase mb-1">
                        <i class="fas fa-reply text-success"></i> Admin Reply
                    </label>
                    <div class="p-3 rounded" style="background-color: #d4edda; border-left: 4px solid #28a745;">
                        <?php echo nl2br(htmlspecialchars($ticket['admin_reply'])); ?>
                    </div>
                </div>
                <?php elseif (!$is_closed): ?>
                <div class="alert alert-info mb-0 py-2">
                    <i class="fas fa-clock"></i> <small>Awaiting admin response...</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No tickets yet</h4>
            <p class="text-muted">Need help? Submit a support ticket.</p>
            <a href="support.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create Ticket</a>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>