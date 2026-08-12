<?php
$page_title = "My Profile";
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

if (!isLoggedIn()) redirect('../auth/login.php');

$student_id = $_SESSION['student_id'];
$student = getStudentById($student_id);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    function handleUpload($file_key, $subfolder, $student_id) {
        if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) return null;
        $file = $_FILES[$file_key];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf','docx','jpg','jpeg','png'];
        if (!in_array($ext, $allowed)) return null;
        if ($file['size'] > 10485760) return null;
        $dir = "../uploads/$subfolder/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = $student_id . '_' . time() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $filename);
        return "uploads/$subfolder/" . $filename;
    }

    $photo_path    = handleUpload('photo', 'profiles', $student_id)   ?? $student['photo_path'];
    $resume_path   = handleUpload('resume', 'resumes', $student_id)   ?? $student['resume_path'];
    $cert_path     = handleUpload('certificates', 'certificates', $student_id) ?? $student['certificates_path'];
    $idproof_path  = handleUpload('idproof', 'idproofs', $student_id) ?? $student['idproof_path'];

    $name               = $conn->real_escape_string(trim($_POST['name']));
    $dob                = $conn->real_escape_string(trim($_POST['dob']));
    $gender             = $conn->real_escape_string(trim($_POST['gender']));
    $contact            = $conn->real_escape_string(trim($_POST['contact']));
    $address            = $conn->real_escape_string(trim($_POST['address']));
    $college_name       = $conn->real_escape_string(trim($_POST['college_name']));
    $enrollment_no      = $conn->real_escape_string(trim($_POST['enrollment_no']));
    $course             = $conn->real_escape_string(trim($_POST['course']));
    $year_of_study      = $conn->real_escape_string(trim($_POST['year_of_study']));
    $aggregate          = $conn->real_escape_string(trim($_POST['aggregate']));
    $marks_10th         = $conn->real_escape_string(trim($_POST['marks_10th']));
    $marks_12th         = $conn->real_escape_string(trim($_POST['marks_12th']));
    $technical_skills   = $conn->real_escape_string(trim($_POST['technical_skills']));
    $soft_skills        = $conn->real_escape_string(trim($_POST['soft_skills']));
    $certifications     = $conn->real_escape_string(trim($_POST['certifications']));
    $experience         = $conn->real_escape_string(trim($_POST['experience']));
    $desired_role       = $conn->real_escape_string(trim($_POST['desired_role']));
    $preferred_location = $conn->real_escape_string(trim($_POST['preferred_location']));
    $willing_to_relocate = $conn->real_escape_string(trim($_POST['willing_to_relocate']));

    $sql = "UPDATE students SET
        name='$name', dob='$dob', gender='$gender', contact='$contact', address='$address',
        college_name='$college_name', enrollment_no='$enrollment_no', course='$course',
        year_of_study='$year_of_study', aggregate='$aggregate', marks_10th='$marks_10th',
        marks_12th='$marks_12th', technical_skills='$technical_skills', soft_skills='$soft_skills',
        certifications='$certifications', experience='$experience', desired_role='$desired_role',
        preferred_location='$preferred_location', willing_to_relocate='$willing_to_relocate',
        photo_path='$photo_path', resume_path='$resume_path',
        certificates_path='$cert_path', idproof_path='$idproof_path'
        WHERE id='$student_id'";

    if ($conn->query($sql)) {
        $success = 'Profile updated successfully!';
        $student = getStudentById($student_id);
    } else {
        $error = 'Failed to update profile. Please try again.';
    }
}

$page_seo = generateSEO(['title' => 'My Profile | Internship Tracker', 'robots' => 'noindex, nofollow']);
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<main class="container my-4">
    <div class="hero-section p-4 mb-4 rounded">
        <h1 class="display-6 fw-bold"><i class="fas fa-user-circle"></i> My Profile</h1>
        <p class="lead mb-0">Keep your profile complete to improve your chances of getting selected.</p>
    </div>

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

    <form method="POST" enctype="multipart/form-data">
        <!-- Personal Info -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user"></i> Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <?php foreach (['Male','Female','Other'] as $g): ?>
                                <option value="<?php echo $g; ?>" <?php echo ($student['gender'] ?? '') === $g ? 'selected' : ''; ?>><?php echo $g; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($student['contact'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Info -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-graduation-cap"></i> Academic Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">College Name</label>
                        <input type="text" name="college_name" class="form-control" value="<?php echo htmlspecialchars($student['college_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Enrollment / Roll No</label>
                        <input type="text" name="enrollment_no" class="form-control" value="<?php echo htmlspecialchars($student['enrollment_no'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Course & Specialization</label>
                        <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($student['course'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Year of Study / Passing Year</label>
                        <input type="text" name="year_of_study" class="form-control" value="<?php echo htmlspecialchars($student['year_of_study'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Aggregate % / CGPA</label>
                        <input type="text" name="aggregate" class="form-control" value="<?php echo htmlspecialchars($student['aggregate'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">10th Marks (%)</label>
                        <input type="text" name="marks_10th" class="form-control" value="<?php echo htmlspecialchars($student['marks_10th'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">12th / Diploma Marks (%)</label>
                        <input type="text" name="marks_12th" class="form-control" value="<?php echo htmlspecialchars($student['marks_12th'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Info -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-briefcase"></i> Professional Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Technical Skills <small class="text-muted">(comma separated)</small></label>
                        <textarea name="technical_skills" class="form-control" rows="3"><?php echo htmlspecialchars($student['technical_skills'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Soft Skills</label>
                        <textarea name="soft_skills" class="form-control" rows="3"><?php echo htmlspecialchars($student['soft_skills'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Certifications <small class="text-muted">(comma separated)</small></label>
                        <textarea name="certifications" class="form-control" rows="2"><?php echo htmlspecialchars($student['certifications'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Experience <small class="text-muted">(company, role, duration)</small></label>
                        <textarea name="experience" class="form-control" rows="2"><?php echo htmlspecialchars($student['experience'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Preferences -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-map-marker-alt"></i> Job Preferences</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Desired Job Role</label>
                        <input type="text" name="desired_role" class="form-control" value="<?php echo htmlspecialchars($student['desired_role'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Preferred Location(s)</label>
                        <input type="text" name="preferred_location" class="form-control" value="<?php echo htmlspecialchars($student['preferred_location'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Willing to Relocate</label>
                        <select name="willing_to_relocate" class="form-select">
                            <option value="Yes" <?php echo ($student['willing_to_relocate'] ?? '') === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="No"  <?php echo ($student['willing_to_relocate'] ?? 'No') === 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Uploads -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-file-upload"></i> Document Uploads</div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $docs = [
                        ['label' => 'Passport Photo', 'key' => 'photo', 'field' => 'photo_path', 'accept' => '.jpg,.jpeg,.png'],
                        ['label' => 'Resume (PDF/DOCX)', 'key' => 'resume', 'field' => 'resume_path', 'accept' => '.pdf,.docx'],
                        ['label' => 'Certificates', 'key' => 'certificates', 'field' => 'certificates_path', 'accept' => '.pdf,.docx,.jpg,.png'],
                        ['label' => 'ID Proof', 'key' => 'idproof', 'field' => 'idproof_path', 'accept' => '.pdf,.jpg,.png'],
                    ];
                    foreach ($docs as $doc): ?>
                    <div class="col-md-6">
                        <label class="form-label"><?php echo $doc['label']; ?></label>
                        <?php if (!empty($student[$doc['field']])): ?>
                            <div class="mb-1">
                                <a href="../<?php echo htmlspecialchars($student[$doc['field']]); ?>" target="_blank" class="badge bg-success text-decoration-none">
                                    <i class="fas fa-check-circle"></i> File uploaded — click to view
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="<?php echo $doc['key']; ?>" class="form-control" accept="<?php echo $doc['accept']; ?>">
                        <small class="text-muted">Max 10MB. <?php echo strtoupper(str_replace(',','/',$doc['accept'])); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="d-grid mb-5">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save Profile
            </button>
        </div>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>