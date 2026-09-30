<?php

require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $course_code = $_POST["course_code"];
    $course_name = $_POST["course_name"];
    $department_id = $_POST["department_id"];

    $sql = "INSERT INTO Course
            (Course_Code, Course_Name, Department_ID)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssi",
        $course_code,
        $course_name,
        $department_id
    );

    if ($stmt->execute()) {

        $message = "Course added successfully!";

    } else {

        $message = "Error adding course.";

    }
}

$departments = $conn->query(
    "SELECT Department_ID, Department_Name
     FROM Department
     ORDER BY Department_Name"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Course - MKU Database System</title>

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

    <p>Course Management</p>

</div>


<div class="container">

    <h2>Add New Course</h2>


    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Course Code</label>

        <input
            type="text"
            name="course_code"
            placeholder="e.g. CSC301"
            required
        >


        <label>Course Name</label>

        <input
            type="text"
            name="course_name"
            placeholder="e.g. Operating Systems"
            required
        >


        <label>Department</label>

        <select name="department_id" required>

            <option value="">Select Department</option>

            <?php while ($department = $departments->fetch_assoc()): ?>

                <option value="<?php echo $department['Department_ID']; ?>">

                    <?php echo htmlspecialchars($department['Department_Name']); ?>

                </option>

            <?php endwhile; ?>

        </select>


        <button type="submit">
            Add Course
        </button>

    </form>


    <a class="back" href="courses.php">
        ← Back to Courses
    </a>

</div>

</body>

</html>