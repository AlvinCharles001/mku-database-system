<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Student ID is missing.");
}

$student_id = $_GET['id'];

$sql = "DELETE FROM Student
        WHERE Student_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {

    header("Location: students.php");
    exit();

} else {

    echo "Error deleting student.";
}

?>