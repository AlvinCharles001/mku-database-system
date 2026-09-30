<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Lecturer ID is missing.");
}

$lecturer_id = $_GET['id'];


// Delete lecturer
$sql = "DELETE FROM Lecturer
        WHERE Lecturer_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $lecturer_id);

if ($stmt->execute()) {

    header("Location: lecturers.php");
    exit();

} else {

    echo "Error deleting lecturer.";

}

?>