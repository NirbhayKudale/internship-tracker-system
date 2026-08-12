<?php
$page_title = "Support Tickets";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isAdminLoggedIn()) redirect('../auth/admin_login.php');

$tickets = $conn->query("
    SELECT t.*, s.name, s.email 
    FROM support_tickets t 
    JOIN students s ON t.student_id = s.id 
    ORDER BY CASE WHEN t.status = 'Open' THEN 1 ELSE 2 END, t.created_at DESC
");

$open_count   = $conn->query("SELECT COUNT(*) as c FROM support_tickets WHERE status='Open'")->fetch_assoc()['c'];
$closed_count = $conn->query("SELECT COUNT(*) as c FROM support_tickets WHERE status='Closed'")->fetch_assoc()['c'];

$page_seo = generateSEO(['title' => 'Support Tickets | Admin', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <h1 class="display-6 fw-bold mb-2"><i class="fas fa-headset"></i> Support Tickets</h1>
        <p class="lead mb-0">Review and respond to student support requests.</p>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card text-center border-danger" style="border-top: 4px solid;">
                <div class="card-body">
                    <i class="fas fa-envelope-open-text fa-2x text-danger mb-2"></i>
                    <h3 class="fw-bold text-danger"><?php echo $open_count; ?></h3>
                    <p class="text-muted mb-0">Open Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-center border-success" style="border-top: 4px solid;">
                <div class="card-body">
                    <i class="fas fa-check-double fa-2x text-success mb-2"></i>
                    <h3 class="fw-bold text-success"><?php echo $closed_count; ?></h3>
                    <p class="text-muted mb-0">Closed Tickets</p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($tickets && $tickets->num_rows > 0): ?>
        <?php while ($ticket = $tickets->fetch_assoc()):
            $is_open = $ticket['status'] === 'Open';
        ?>
        <div class="card mb-4" id="ticket-<?php echo $ticket['ticket_id']; ?>">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <i class="fas fa-user"></i>
                    <strong class="ms-1"><?php echo htmlspecialchars($ticket['name']); ?></strong>
                    <span class="text-white-50">(<?php echo htmlspecialchars($ticket['email']); ?>)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-white-50"><?php echo date('d M Y, h:i A', strtotime($ticket['created_at'])); ?></small>
                    <span class="badge <?php echo $is_open ? 'bg-danger' : 'bg-success'; ?>">
                        <?php echo $ticket['status']; ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['subject']); ?></p>
                <div class="mb-3">
                    <label class="fw-semibold text-muted small text-uppercase mb-1">Student Message</label>
                    <div class="bg-light p-3 rounded"><?php echo nl2br(htmlspecialchars($ticket['message'])); ?></div>
                </div>

                <?php if ($is_open): ?>
                <div class="reply-form" data-ticket-id="<?php echo $ticket['ticket_id']; ?>">
                    <label class="fw-semibold text-muted small text-uppercase mb-1">Your Reply</label>
                    <textarea class="form-control reply-text" rows="3" placeholder="Type your response to the student..."></textarea>
                    <button class="btn btn-success btn-sm mt-2 send-reply-btn">
                        <i class="fas fa-paper-plane"></i> Send Reply & Close Ticket
                    </button>
                </div>
                <?php elseif (!empty($ticket['admin_reply'])): ?>
                <div>
                    <label class="fw-semibold text-muted small text-uppercase mb-1">
                        <i class="fas fa-reply text-success"></i> Your Reply
                    </label>
                    <div class="p-3 rounded" style="background-color: #d4edda; border-left: 4px solid #28a745;">
                        <?php echo nl2br(htmlspecialchars($ticket['admin_reply'])); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-headset fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No support tickets</h4>
            <p class="text-muted">Students haven't submitted any tickets yet.</p>
        </div>
    <?php endif; ?>
</main>

<script>
document.querySelectorAll('.send-reply-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.reply-form');
        const ticketId = form.dataset.ticketId;
        const replyText = form.querySelector('.reply-text').value.trim();

        if (!replyText) {
            showToast('Please enter a reply message', 'warning');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        fetch('../api/reply_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ticket_id=' + ticketId + '&reply=' + encodeURIComponent(replyText)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message, 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reply & Close Ticket';
            }
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>