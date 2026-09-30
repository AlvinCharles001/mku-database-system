<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Course ID is missing.");
}

$course_id = $_GET['id'];


// Check whether the course is being used in Enrollment
$check_sql = "SELECT COUNT(*) AS enrollment_count
              FROM Enrollment
              WHERE Course_ID = ?";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param("i", $course_id);

$check_stmt->execute();

$check_result = $check_stmt->get_result();

$check = $check_result->fetch_assoc();


// Stop deletion if students are enrolled
if ($check['enrollment_count'] > 0) {

    die(
        "This course cannot be deleted because students are enrolled in it."
        . "<br><br>"
        . "<a href='courses.php'>← Back to Courses</a>"
    );
}


// Delete course
$sql = "DELETE FROM Course
        WHERE Course_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $course_id);

if ($stmt->execute()) {

    header("Location: courses.php");
    exit();

} else {

    echo "Error deleting course.";

}

?>