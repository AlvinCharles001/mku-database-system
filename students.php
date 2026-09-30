<?php

require_once "config/db.php";

$sql = "SELECT 
            Student.Student_ID,
            Student.First_Name,
            Student.Last_Name,
            Student.Gender,
            Student.Phone,
            Student.Email,
            Department.Department_Name
        FROM Student
        LEFT JOIN Department
        ON Student.Department_ID = Department.Department_ID
        ORDER BY Student.Student_ID";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - MKU Database System</title>

    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;

    background-image:
        linear-gradient(rgba(0, 0, 0, 0.20), rgba(0, 0, 0, 0.20)),
        url("images/mku-background.jpg");

    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}

        .header {
            background: #800000;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
    width: 95%;
    max-width: 1200px;
    margin: 30px auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
}
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #800000;
            color: white;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .back {
            text-decoration: none;
            color: white;
            background: #555;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .navbar {
    background: rgba(0, 0, 0, 0.75);
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    backdrop-filter: blur(6px);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 14px 18px;
    font-weight: bold;
    transition: 0.3s;
}

.navbar a:hover {
    background: #800000;
}
.footer {
    margin-top: 40px;
    background: rgba(128, 0, 0, 0.95);
    color: white;
    text-align: center;
    padding: 20px;
}

.footer p {
    margin: 5px 0;
    font-size: 14px;
}
    </style>
</head>

<body>

<div class="header">
    <h1>Mount Kenya University</h1>
    <p>Student Management</p>
</div>

<div class="container">
    <div class="navbar">

    <a href="index.php">Dashboard</a>
    <a href="students.php">Students</a>
    <a href="departments.php">Departments</a>
    <a href="courses.php">Courses</a>
    <a href="lecturers.php">Lecturers</a>
    <a href="enrollment.php">Enrollment</a>
    <a href="fees.php">Fees</a>
    <a href="payments.php">Payments</a>
    <a href="reports.php">Reports</a>

</div>

    <div class="top">
        <h2>Students</h2>

        <a class="back" href="index.php">← Dashboard</a>
    </div>

    <table>

        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>

        <?php

        if ($result && $result->num_rows > 0) {

            while ($student = $result->fetch_assoc()) {

                echo "<tr>";

                echo "<td>" . $student['Student_ID'] . "</td>";
                echo "<td>" . $student['First_Name'] . "</td>";
                echo "<td>" . $student['Last_Name'] . "</td>";
                echo "<td>" . $student['Gender'] . "</td>";
                echo "<td>" . $student['Phone'] . "</td>";
                echo "<td>" . $student['Email'] . "</td>";
                echo "<td>" . $student['Department_Name'] . "</td>";

echo "<td>
        <a href='edit_student.php?id=" . $student['Student_ID'] . "'>Edit</a> |
        <a href='delete_student.php?id=" . $student['Student_ID'] . "' 
           onclick=\"return confirm('Are you sure you want to delete this student?');\">
           Delete
        </a>
      </td>";

echo "</tr>";
            }

        } else {

            echo "<tr>";
            echo "<td colspan='8'>No students found.</td>";
            echo "</tr>";
        }

        ?>

    </table>

</div>
<footer class="footer">

    <p>
        <strong>Mount Kenya University</strong>
    </p>

    <p>
        University Database Management System
    </p>

    <p>
        &copy; 2026 MKU Database System
    </p>

</footer>

</body>
</html>