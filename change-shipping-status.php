<?php require_once('header.php'); ?>

<?php
if (!isset($_POST['ship']) || !isset($_POST['id'])) {
    echo 'Lỗi hệ thống';
    exit;
} else {
    // Check if the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE id=?");
    $statement->execute(array($_POST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        echo 'Lỗi không tìm thấy hóa đơn';
        exit;
    }
}
?>

<?php
// Sử dụng prepared statement để tránh SQL Injection
$statement = $pdo->prepare("UPDATE tbl_payment SET tss_id=? WHERE id=?");
$statement->execute(array($_POST['ship'], $_POST['id']));

header('location: customer-order.php');
exit;
?>
