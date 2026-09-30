<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Department ID is missing.");
}

$department_id = $_GET['id'];

// Check whether students are using this department
$check_sql = "SELECT COUNT(*) AS student_count
              FROM Student
              WHERE Department_ID = ?";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param("i", $department_id);

$check_stmt->execute();

$check_result = $check_stmt->get_result();

$check = $check_result->fetch_assoc();


// Stop deletion if students are linked
if ($check['student_count'] > 0) {

    die(
        "This department cannot be deleted because students are currently assigned to it."
        . "<br><br>"
        . "<a href='departments.php'>← Back to Departments</a>"
    );
}


// Delete department
$sql = "DELETE FROM Department
        WHERE Department_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $department_id);

if ($stmt->execute()) {

    header("Location: departments.php");
    exit();

} else {

    echo "Error deleting department.";

}

?>