<?php
include '../include/header.php';
include '../include/footer.php';
?>
<?php
include '../include/menu.php';
?>




<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>លទ្ធផលនិស្សិត</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Google Fonts: Koulen -->
<link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet">
<style>
    body, h5, label, option, button, .card, .card-header, .card-footer, .form-select {
        font-family: 'Koulen', 'Khmer OS', 'Arial', sans-serif !important;
    }
    /* Use Arial for input fields to support lowercase Latin letters */
    .form-control, input, textarea {
        font-family: 'Koulen', 'Khmer OS', 'Arial';
    }
</style>


<?php
// Start session at the very top to avoid header issues
session_start();

// Initialize variables
$students = [];

// Tuition fee data
$tuition = [
    1 => ['year' => 380, 'semester' => 195],
    2 => ['year' => 400, 'semester' => 205],
    3 => ['year' => 450, 'semester' => 230],
    4 => ['year' => 450, 'semester' => 230],
    5 => ['year' => 230, 'semester' => 230], // Year 5 is special case
];

// Discount by class level
$rank_discount = [
    1 => 0.5,   // 50%
    2 => 0.3,   // 30%
    3 => 0.2,   // 20%
    4 => 0.1,   // 10%
    5 => 0.1,   // 10%
];

// Handle Edit and Delete actions
if (isset($_GET['action']) && isset($_GET['id']) && isset($_SESSION['students'])) {
    $id = (int) $_GET['id'];
    if ($_GET['action'] === 'delete') {
        // Remove student at index $id
        array_splice($_SESSION['students'], $id, 1);
        header('Location: home.php');
        exit;
    }
    if ($_GET['action'] === 'edit') {
        // For simplicity, just load the student data into variables for the form
        $edit_student = $_SESSION['students'][$id];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data safely
    $student_name = htmlspecialchars($_POST['student_name'] ?? '');
    $major = (int) ($_POST['major'] ?? 0);
    $term_type = htmlspecialchars($_POST['term_type'] ?? '');
    $class_level = (int) ($_POST['class_level'] ?? 0);

    // Calculate tuition
    $base_fee = 0;
    if (isset($tuition[$major])) {
        $base_fee = ($term_type === 'Year') ? $tuition[$major]['year'] : $tuition[$major]['semester'];
    }

    // Discount for full year payment
    $year_discount = ($term_type === 'Year' && $major >= 1 && $major <= 4) ? 10 : 0;

    // Rank discount
    $rank_percent = $rank_discount[$class_level] ?? 0;
    $rank_discount_amount = $base_fee * $rank_percent;

    // Final fee
    $final_fee = $base_fee - $year_discount - $rank_discount_amount;

    // For demo: store in session (so data persists after submit)
    if (!isset($_SESSION['students'])) {
        $_SESSION['students'] = [];
    }

    // If editing, update the student, else add new
    if (isset($_POST['edit_id']) && $_POST['edit_id'] !== '') {
        $edit_id = (int) $_POST['edit_id'];
        $_SESSION['students'][$edit_id] = [
            'student_name' => $student_name,
            'major' => $major,
            'term_type' => $term_type,
            'class_level' => $class_level,
            'base_fee' => $base_fee,
            'year_discount' => $year_discount,
            'rank_discount_amount' => $rank_discount_amount,
            'final_fee' => $final_fee,
        ];
    } else {
        $_SESSION['students'][] = [
            'student_name' => $student_name,
            'major' => $major,
            'term_type' => $term_type,
            'class_level' => $class_level,
            'base_fee' => $base_fee,
            'year_discount' => $year_discount,
            'rank_discount_amount' => $rank_discount_amount,
            'final_fee' => $final_fee,
        ];
    }
}
?>
<!-- FORM Content -->
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-success text-white text-center">
            បញ្ចូលព័ត៌មាននិស្សិត
        </div>
        <div class="card-body">
            <form method="post" action="">
                <?php $is_edit = isset($edit_student); ?>
                <?php if ($is_edit) { ?>
                    <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <?php } ?>
                <div class="mb-3 row align-items-center">
                    <label for="student_name" class="col-sm-4 col-form-label text-start">ឈ្មោះនិស្សិត</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="student_name" name="student_name" placeholder ="សូមបញ្ចូលឈ្មោះ" required value="<?php echo $is_edit ? htmlspecialchars($edit_student['student_name']) : ''; ?>">
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label for="major" class="col-sm-4 col-form-label text-start">ឆ្នាំសិក្សា</label>
                    <div class="col-sm-8">
                        <select class="form-select" id="major" name="major" required>
                            <option value="" disabled <?php echo !$is_edit ? 'selected' : ''; ?> hidden>ជ្រើសរើសឆ្នាំសិក្សា</option>
                            <option value="1" <?php echo $is_edit && $edit_student['major'] == 1 ? 'selected' : ''; ?>>ឆ្នាំទី១</option>
                            <option value="2" <?php echo $is_edit && $edit_student['major'] == 2 ? 'selected' : ''; ?>>ឆ្នាំទី២</option>
                            <option value="3" <?php echo $is_edit && $edit_student['major'] == 3 ? 'selected' : ''; ?>>ឆ្នាំទី៣</option>
                            <option value="4" <?php echo $is_edit && $edit_student['major'] == 4 ? 'selected' : ''; ?>>ឆ្នាំទី៤</option>
                            <option value="5" <?php echo $is_edit && $edit_student['major'] == 5 ? 'selected' : ''; ?>>ឆ្នាំទី៥</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-4 col-form-label text-start">បង់ជាឆ្នាំ ឬឆមាស</label>
                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="term_type" id="year" value="Year" required <?php echo $is_edit && $edit_student['term_type'] == 'Year' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="year">Year</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="term_type" id="semester" value="Semester" <?php echo $is_edit && $edit_student['term_type'] == 'Semester' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="semester">Semester</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label for="class_level" class="col-sm-4 col-form-label text-start">ចំណាត់ថ្នាក់</label>
                    <div class="col-sm-8">
                        <select class="form-select" id="class_level" name="class_level" required>
                            <option value="" disabled <?php echo !$is_edit ? 'selected' : ''; ?> hidden>សូមជ្រើសរើសចំណាត់ថ្នាក់</option>
                            <option value="1" <?php echo $is_edit && $edit_student['class_level'] == 1 ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo $is_edit && $edit_student['class_level'] == 2 ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?php echo $is_edit && $edit_student['class_level'] == 3 ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?php echo $is_edit && $edit_student['class_level'] == 4 ? 'selected' : ''; ?>>4</option>
                            <option value="5" <?php echo $is_edit && $edit_student['class_level'] == 5 ? 'selected' : ''; ?>>5</option>
                            <option value="non-list" <?php echo $is_edit && $edit_student['class_level'] == 'non-list' ? 'selected' : ''; ?>>ធំជាង៥</option>
                        </select>
                    </div>
                </div>
                <div class="text-start">
                    <button type="submit" class="btn btn-primary px-4">បញ្ជូន</button>
                </div>
            </form>
            <div class="mt-4">
                <p class="fw-bold">តម្លៃសិក្សារបស់និសិត្សព័ត៌មានវិទ្យា</p>
                <ol class="mb-0">
                <li>ឆ្នាំទី១ តម្លៃ​​​​​ ៣៨០$ តម្លៃសម្រាប់១ឆមាស ១៩៥$</li>
                <li>ឆ្នាំទី២ តម្លៃ​​​​​ ៤០០$ តម្លៃសម្រាប់១ឆមាស ២០៥$</li>
                <li>ឆ្នាំទី៣ តម្លៃ​​​​​ ៤៥០$ តម្លៃសម្រាប់១ឆមាស ២៣០$</li>
                <li>ឆ្នាំទី៤ តម្លៃ​​​​​ ៤៥០$ តម្លៃសម្រាប់១ឆមាស ២៣០$</li>
                <li>ឆ្នាំទី៥ (សារណា) តម្លៃ​​​​​ ២៣០$ សម្រាប់កន្លះឆ្នាំចុងក្រោយ</li>
                </ol>
            </div>
            <p class="mt-4 fw-bold"><u>ចំណាំ៖</u>​និស្សិត្តដែលបង់ថ្លៃសិក្សាមួយឆ្នាំពេញមានការបញ្ចុះតម្លៃ ១០​$ គ្រប់ឆ្នាំសិក្សា, បញ្ចុះតម្លៃ សម្រាប់និស្សិតដែលបានជាប់ចំណាត់ថ្នាក់ពី​ លេខ១​=៥០% លេខ២​=៣០% លេខ៣​=២០% លេខ៤​ និងលេខ៥=១០%</p>
            <div class="card-footer text-muted small mt-4">
                <p class="text-center mt-1 mb-0 text-success fw-bold">Copyright ©2025 All rights reserved | lihour</p>
            </div>
        </div>
    </div>
</div>

<!-- OUTPUT Content -->






</body>
</html>

