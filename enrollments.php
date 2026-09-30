<?php

require_once "config/db.php";

$sql = "SELECT
            Enrollment.Enrollment_ID,
            Student.First_Name,
            Student.Last_Name,
            Course.Course_Code,
            Course.Course_Name,
            Enrollment.Semester,
            Enrollment.Academic_Year,
            Enrollment.Grade

        FROM Enrollment

        LEFT JOIN Student
        ON Enrollment.Student_ID = Student.Student_ID

        LEFT JOIN Course
        ON Enrollment.Course_ID = Course.Course_ID

        ORDER BY Enrollment.Enrollment_ID";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Enrollments - MKU Database System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;

            background-image:
                linear-gradient(
                    rgba(0, 0, 0, 0.20),
                    rgba(0, 0, 0, 0.20)
                ),
                url("images/mku-background.jpg");

            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* HEADER */

        .header {
            background: #800000;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin-bottom: 0;
        }

        /* NAVIGATION */

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

        /* MAIN CONTAINER */

        .container {
            width: 95%;
            max-width: 1250px;

            margin: 30px auto;

            background: rgba(255, 255, 255, 0.95);

            padding: 25px;

            border-radius: 10px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.2);
        }

        /* TOP SECTION */

        .top {
            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 15px;
        }

        .top h2 {
            margin: 0;
        }

        /* BUTTONS */

        .add {
            text-decoration: none;

            background: #800000;

            color: white;

            padding: 10px 15px;

            border-radius: 5px;

            display: inline-block;
        }

        .add:hover {
            background: #600000;
        }

        .back {
            text-decoration: none;

            color: white;

            background: #555;

            padding: 10px 15px;

            border-radius: 5px;

            margin-left: 10px;

            display: inline-block;
        }

        .back:hover {
            background: #333;
        }

        /* TABLE WRAPPER */

        .table-wrapper {
            overflow-x: auto;
        }

        /* TABLE */

        table {
            width: 100%;

            min-width: 1000px;

            border-collapse: collapse;

            background: white;

            margin-top: 25px;
        }

        th,
        td {
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

        /* ACTION LINKS */

        td a {
            color: #800000;

            font-weight: bold;

            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        /* FOOTER */

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

        /* MOBILE */

        @media (max-width: 700px) {

            .container {
                width: 95%;

                padding: 15px;
            }

            .top {
                flex-direction: column;

                align-items: flex-start;
            }

        }

    </style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <h1>Mount Kenya University</h1>

    <p>Enrollment Management</p>

</div>


<!-- NAVIGATION -->

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


<!-- MAIN CONTENT -->

<div class="container">


    <div class="top">

        <h2>Enrollments</h2>

        <div>

            <a class="add" href="add_enrollment.php">
                + Add Enrollment
            </a>

            <a class="back" href="index.php">
                ← Dashboard
            </a>

        </div>

    </div>


    <!-- ENROLLMENT TABLE -->

    <div class="table-wrapper">

        <table>

            <tr>

                <th>ID</th>

                <th>Student</th>

                <th>Course Code</th>

                <th>Course Name</th>

                <th>Semester</th>

                <th>Academic Year</th>

                <th>Grade</th>

                <th>Actions</th>

            </tr>


            <?php

            if ($result && $result->num_rows > 0) {

                while ($enrollment = $result->fetch_assoc()) {

                    echo "<tr>";

                    echo "<td>"
                        . $enrollment['Enrollment_ID']
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['First_Name']
                        )
                        . " "
                        . htmlspecialchars(
                            $enrollment['Last_Name']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['Course_Code']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['Course_Name']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['Semester']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['Academic_Year']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $enrollment['Grade']
                        )
                        . "</td>";

                    echo "<td>

                        <a href='edit_enrollment.php?id="
                        . $enrollment['Enrollment_ID']
                        . "'>
                            Edit
                        </a>

                        |

                        <a href='delete_enrollment.php?id="
                        . $enrollment['Enrollment_ID']
                        . "'
                           onclick=\"return confirm(
                           'Are you sure you want to delete this enrollment?'
                           );\">

                            Delete

                        </a>

                    </td>";

                    echo "</tr>";

                }

            } else {

                echo "<tr>";

                echo "<td colspan='8'>
                        No enrollments found.
                      </td>";

                echo "</tr>";

            }

            ?>

        </table>

    </div>


</div>


<!-- FOOTER -->

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