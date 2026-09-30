<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Enrollment ID is missing.");
}

$enrollment_id = $_GET['id'];

$message = "";


// Update enrollment
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST["student_id"];
    $course_id = $_POST["course_id"];
    $semester = $_POST["semester"];
    $academic_year = $_POST["academic_year"];
    $grade = $_POST["grade"];

    $sql = "UPDATE Enrollment
            SET Student_ID = ?,
                Course_ID = ?,
                Semester = ?,
                Academic_Year = ?,
                Grade = ?
            WHERE Enrollment_ID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iisssi",
        $student_id,
        $course_id,
        $semester,
        $academic_year,
        $grade,
        $enrollment_id
    );

    if ($stmt->execute()) {

        $message = "Enrollment updated successfully!";

    } else {

        $message = "Error updating enrollment.";

    }
}


// Get enrollment
$sql = "SELECT *
        FROM Enrollment
        WHERE Enrollment_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $enrollment_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Enrollment not found.");
}

$enrollment = $result->fetch_assoc();


// Get students
$students = $conn->query(
    "SELECT Student_ID, First_Name, Last_Name
     FROM Student
     ORDER BY First_Name, Last_Name"
);


// Get courses
$courses = $conn->query(
    "SELECT Course_ID, Course_Code, Course_Name
     FROM Course
     ORDER BY Course_Code"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Enrollment - MKU Database System</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .header {
            background: #800000;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #800000;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            background: #e8f5e9;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>Mount Kenya University</h1>

    <p>Edit Enrollment</p>

</div>


<div class="container">

    <h2>Update Enrollment</h2>


    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>


    <form method="POST">


        <label>Student</label>

        <select name="student_id" required>

            <option value="">
                Select Student
            </option>

            <?php while ($student = $students->fetch_assoc()): ?>

                <option
                    value="<?php echo $student['Student_ID']; ?>"
                    <?php
                    if ($student['Student_ID'] == $enrollment['Student_ID']) {
                        echo "selected";
                    }
                    ?>
                >

                    <?php
                    echo htmlspecialchars(
                        $student['First_Name']
                        . " "
                        . $student['Last_Name']
                    );
                    ?>

                </option>

            <?php endwhile; ?>

        </select>


        <label>Course</label>

        <select name="course_id" required>

            <option value="">
                Select Course
            </option>

            <?php while ($course = $courses->fetch_assoc()): ?>

                <option
                    value="<?php echo $course['Course_ID']; ?>"
                    <?php
                    if ($course['Course_ID'] == $enrollment['Course_ID']) {
                        echo "selected";
                    }
                    ?>
                >

                    <?php
                    echo htmlspecialchars(
                        $course['Course_Code']
                        . " - "
                        . $course['Course_Name']
                    );
                    ?>

                </option>

            <?php endwhile; ?>

        </select>


        <label>Semester</label>

        <select name="semester" required>

            <option value="">Select Semester</option>

            <option value="Semester 1"
                <?php
                if ($enrollment['Semester'] == "Semester 1") {
                    echo "selected";
                }
                ?>>
                Semester 1
            </option>

            <option value="Semester 2"
                <?php
                if ($enrollment['Semester'] == "Semester 2") {
                    echo "selected";
                }
                ?>>
                Semester 2
            </option>

            <option value="Semester 3"
                <?php
                if ($enrollment['Semester'] == "Semester 3") {
                    echo "selected";
                }
                ?>>
                Semester 3
            </option>

        </select>


        <label>Academic Year</label>

        <input
            type="text"
            name="academic_year"
            value="<?php echo htmlspecialchars($enrollment['Academic_Year']); ?>"
            required
        >


        <label>Grade</label>

        <select name="grade">

            <option value="">Select Grade</option>

            <?php

            $grades = ["A", "B", "C", "D", "E", "F"];

            foreach ($grades as $grade) {

                $selected =
                    ($enrollment['Grade'] == $grade)
                    ? "selected"
                    : "";

                echo "<option value='$grade' $selected>$grade</option>";
            }

            ?>

        </select>


        <button type="submit">
            Update Enrollment
        </button>

    </form>


    <a class="back" href="enrollments.php">
        ← Back to Enrollments
    </a>

</div>

</body>

</html>