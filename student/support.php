<?php
$page_title = "Help & Support";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isLoggedIn()) redirect('../auth/login.php');

$student_id = $_SESSION['student_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $conn->real_escape_string(trim($_POST['subject']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    if (empty($subject) || empty($message)) {
        $error = 'Subject and message are required.';
    } else {
        $sql = "INSERT INTO support_tickets (student_id, subject, message, status) VALUES ('$student_id', '$subject', '$message', 'Open')";
        if ($conn->query($sql)) {
            $success = 'Support ticket submitted successfully! We will respond soon.';
        } else {
            $error = 'Failed to submit ticket. Please try again.';
        }
    }
}

$page_seo = generateSEO(['title' => 'Help & Support | Internship Tracker', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="hero-section p-4 mb-4 rounded">
        <h1 class="display-6 fw-bold"><i class="fas fa-headset"></i> Help & Support</h1>
        <p class="lead mb-0">Have an issue? Submit a ticket and our team will get back to you.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header"><i class="fas fa-ticket-alt"></i> Submit a Support Ticket</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="Brief description of your issue" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="6" placeholder="Describe your issue in detail..." required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><i class="fas fa-question-circle"></i> Frequently Asked Questions</div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <?php
                        $faqs = [
                            ['q' => 'How do I apply for a job?', 'a' => 'Complete your profile with your resume and technical skills first, then browse jobs and click "Apply Now".'],
                            ['q' => 'Can I withdraw my application?', 'a' => 'Yes, you can withdraw applications that are still "Under Review". Go to My Applications and click Withdraw.'],
                            ['q' => 'How long does it take to get a response?', 'a' => 'Application review timelines vary by company. You can track your status in My Applications.'],
                            ['q' => 'Can I apply to multiple jobs?', 'a' => 'Yes, you can apply to as many jobs as you like, as long as the deadline has not passed.'],
                        ];
                        foreach ($faqs as $i => $faq): ?>
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $i; ?>">
                                    <?php echo $faq['q']; ?>
                                </button>
                            </h2>
                            <div id="faq<?php echo $i; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted"><?php echo $faq['a']; ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="my_tickets.php" class="btn btn-outline-primary">
                    <i class="fas fa-list"></i> View My Tickets
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>