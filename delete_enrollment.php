<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Enrollment ID is missing.");
}

$enrollment_id = $_GET['id'];

$sql = "DELETE FROM Enrollment
        WHERE Enrollment_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $enrollment_id);

if ($stmt->execute()) {

    header("Location: enrollments.php");
    exit();

} else {

    echo "Error deleting enrollment.";

}

?>