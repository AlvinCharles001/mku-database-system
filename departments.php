<?php

require_once "config/db.php";

$sql = "SELECT
            Department_ID,
            Department_Name
        FROM Department
        ORDER BY Department_ID";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Departments - MKU Database System</title>

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
            max-width: 1200px;

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

        /* TABLE */

        table {
            width: 100%;

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

            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 10px;
            }

        }

    </style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <h1>Mount Kenya University</h1>

    <p>Department Management</p>

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

        <h2>Departments</h2>

        <div>

            <a class="add" href="add_department.php">
                + Add Department
            </a>

            <a class="back" href="index.php">
                ← Dashboard
            </a>

        </div>

    </div>


    <!-- DEPARTMENT TABLE -->

    <table>

        <tr>

            <th>ID</th>

            <th>Department Name</th>

            <th>Actions</th>

        </tr>


        <?php

        if ($result && $result->num_rows > 0) {

            while ($department = $result->fetch_assoc()) {

                echo "<tr>";

                echo "<td>"
                    . $department['Department_ID']
                    . "</td>";

                echo "<td>"
                    . htmlspecialchars(
                        $department['Department_Name']
                    )
                    . "</td>";

                echo "<td>

                    <a href='edit_department.php?id="
                    . $department['Department_ID']
                    . "'>
                        Edit
                    </a>

                    |

                    <a href='delete_department.php?id="
                    . $department['Department_ID']
                    . "'
                       onclick=\"return confirm(
                       'Are you sure you want to delete this department?'
                       );\">

                        Delete

                    </a>

                </td>";

                echo "</tr>";

            }

        } else {

            echo "<tr>";

            echo "<td colspan='3'>
                    No departments found.
                  </td>";

            echo "</tr>";

        }

        ?>

    </table>


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