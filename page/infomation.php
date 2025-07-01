<?php
session_start();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['students'][$id])) {
        unset($_SESSION['students'][$id]);
        // Re-index the array to avoid gaps in the keys
        $_SESSION['students'] = array_values($_SESSION['students']);
    }
    // Redirect to avoid resubmission on refresh
    header('Location: infomation.php');
    exit;
}
?>
<?php
include '../include/menu.php';
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Build_form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet">
    <style>
        body, h5, label, option, button, .card, .card-header, .card-footer, .form-control, .form-select {
            font-family: 'Koulen', 'Khmer OS', 'Arial', sans-serif !important;
        }
    </style>
</head>
<body class="bg-light">


<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            តារាងបញ្ជីសិស្សដែលបានបញ្ចប់ការសិក្សា
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-warning text-center">
                    <tr>
                        <th>ល.រ</th>
                        <th>ឈ្មោះសិស្ស</th>
                        <th>ឆ្នាំសិក្សា</th>
                        <th>បង់ជាឆ្នាំ/ឆមាស</th>
                        <th>ចំណាត់ថ្នាក់</th>
                        <th>តម្លៃសិក្សា</th>
                        <th>បញ្ចុះតម្លៃសរុប</th>
                        <th>សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['students']) && count($_SESSION['students']) > 0) { ?>
                        <?php foreach ($_SESSION['students'] as $i => $student) { ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td>ឆ្នាំទី<?php echo htmlspecialchars($student['major']); ?></td>
                                <td><?php echo $student['term_type'] == 'Year' ? 'គិតជាឆ្នាំ' : 'គិតជាឆមាស'; ?></td>
                                <td><?php echo htmlspecialchars($student['class_level']); ?></td>
                                <td>
                                    <?php if (isset($student['final_fee'])) { ?>
                                        <?php echo number_format($student['final_fee'], 2); ?>$
                                        <br>
                                        <small class="text-muted">
                                            <span>មូលដ្ឋាន: <?php echo number_format($student['base_fee'], 2); ?>$</span>
                                            <?php if ($student['year_discount'] > 0) { ?>
                                                <br><span>បញ្ចុះឆ្នាំ: -<?php echo number_format($student['year_discount'], 2); ?>$</span>
                                            <?php } ?>
                                            <?php if ($student['rank_discount_amount'] > 0) { ?>
                                                <br><span>បញ្ចុះចំណាត់ថ្នាក់: -<?php echo number_format($student['rank_discount_amount'], 2); ?>$</span>
                                            <?php } ?>
                                        </small>
                                    <?php } else { ?>
                                        N/A
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                        $total_discount = 0;
                            if (isset($student['year_discount']) && isset($student['rank_discount_amount'])) {
                                $total_discount = $student['year_discount'] + $student['rank_discount_amount'];
                            }
                            echo number_format($total_discount, 2).'$';
                            ?>
                                </td>
                                <td>
                                    <a href="home.php?action=edit&id=<?php echo $i; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="infomation.php?action=delete&id=<?php echo $i; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center">មិនទាន់មានទិន្នន័យ</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center text-muted">
            វិទ្យាស្ថានជាតិវិទ្យាសាស្រ្តកុំព្យូទ័រ និងព័ត៌មានវិទ្យា (NTTI)
        </div>
    </div>
</div>

<!-- ✅ Bootstrap JS for navbar -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
