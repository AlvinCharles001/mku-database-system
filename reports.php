<?php

require_once "config/db.php";


/* =========================
   REPORT 1: STUDENTS BY DEPARTMENT
   ========================= */

$department_report = $conn->query(
    "SELECT
        d.Department_Name,
        COUNT(s.Student_ID) AS Total_Students
     FROM Department d
     LEFT JOIN Student s
        ON d.Department_ID = s.Department_ID
     GROUP BY d.Department_ID, d.Department_Name
     ORDER BY d.Department_Name"
);


/* =========================
   REPORT 2: ENROLLMENT REPORT
   ========================= */

$enrollment_report = $conn->query(
    "SELECT
        s.Student_ID,
        CONCAT(s.First_Name, ' ', s.Last_Name) AS Student_Name,
        c.Course_Code,
        c.Course_Name,
        e.Semester,
        e.Academic_Year,
        e.Grade
     FROM Enrollment e
     JOIN Student s
        ON e.Student_ID = s.Student_ID
     JOIN Course c
        ON e.Course_ID = c.Course_ID
     ORDER BY s.Student_ID"
);


/* =========================
   REPORT 3: FEES AND PAYMENTS
   ========================= */

$finance_report = $conn->query(
    "SELECT
        s.Student_ID,
        CONCAT(s.First_Name, ' ', s.Last_Name) AS Student_Name,
        COALESCE(f.Total_Fees, 0) AS Total_Fees,
        COALESCE(p.Total_Paid, 0) AS Total_Paid,
        COALESCE(f.Total_Fees, 0)
        - COALESCE(p.Total_Paid, 0) AS Balance

     FROM Student s

     LEFT JOIN
     (
        SELECT
            Student_ID,
            SUM(Amount_Required) AS Total_Fees
        FROM fee
        GROUP BY Student_ID
     ) f
        ON s.Student_ID = f.Student_ID

     LEFT JOIN
     (
        SELECT
            Student_ID,
            SUM(Amount) AS Total_Paid
        FROM payment
        GROUP BY Student_ID
     ) p
        ON s.Student_ID = p.Student_ID

     ORDER BY s.Student_ID"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reports - MKU Database System</title>


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


        /* =========================
           HEADER
           ========================= */

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


        /* =========================
           NAVIGATION
           ========================= */

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


        /* =========================
           MAIN CONTAINER
           ========================= */

        .container {

            width: 95%;

            max-width: 1250px;

            margin: 30px auto;

        }


        /* =========================
           REPORT CARD
           ========================= */

        .report {

            background: rgba(255, 255, 255, 0.95);

            padding: 25px;

            margin-bottom: 30px;

            border-radius: 10px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.2);

        }


        .report h2 {

            margin-top: 0;

            color: #800000;

            border-left: 5px solid #800000;

            padding-left: 10px;

        }


        /* =========================
           TABLE WRAPPER
           ========================= */

        .table-wrapper {

            overflow-x: auto;

        }


        /* =========================
           TABLE
           ========================= */

        table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            margin-top: 15px;

        }


        th,
        td {

            padding: 13px;

            border: 1px solid #ddd;

            text-align: left;

        }


        th {

            background: #800000;

            color: white;

        }


        tr:hover {

            background: #f5f5f5;

        }


        /* =========================
           BACK BUTTON
           ========================= */

        .back {

            display: inline-block;

            margin-bottom: 20px;

            text-decoration: none;

            color: white;

            background: #555;

            padding: 10px 15px;

            border-radius: 5px;

        }


        .back:hover {

            background: #333;

        }


        /* =========================
           FOOTER
           ========================= */

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


        /* =========================
           MOBILE
           ========================= */

        @media (max-width: 700px) {

            .report {

                padding: 15px;

            }


            table {

                min-width: 700px;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     HEADER
     ========================= -->

<div class="header">

    <h1>Mount Kenya University</h1>

    <p>Database Reports</p>

</div>


<!-- =========================
     NAVIGATION
     ========================= -->

<div class="navbar">

    <a href="index.php">
        Dashboard
    </a>

    <a href="students.php">
        Students
    </a>

    <a href="departments.php">
        Departments
    </a>

    <a href="courses.php">
        Courses
    </a>

    <a href="lecturers.php">
        Lecturers
    </a>

    <a href="enrollment.php">
        Enrollment
    </a>

    <a href="fees.php">
        Fees
    </a>

    <a href="payments.php">
        Payments
    </a>

    <a href="reports.php">
        Reports
    </a>

</div>


<!-- =========================
     REPORTS
     ========================= -->

<div class="container">


    <a class="back" href="index.php">

        ← Back to Dashboard

    </a>


    <!-- =========================
         REPORT 1
         ========================= -->

    <div class="report">

        <h2>
            1. Students by Department
        </h2>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>
                        Department
                    </th>

                    <th>
                        Total Students
                    </th>

                </tr>


                <?php while ($row = $department_report->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Department_Name"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $row["Total_Students"];

                            ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>


    <!-- =========================
         REPORT 2
         ========================= -->

    <div class="report">

        <h2>
            2. Enrollment Report
        </h2>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>
                        Student ID
                    </th>

                    <th>
                        Student
                    </th>

                    <th>
                        Course Code
                    </th>

                    <th>
                        Course
                    </th>

                    <th>
                        Semester
                    </th>

                    <th>
                        Academic Year
                    </th>

                    <th>
                        Grade
                    </th>

                </tr>


                <?php while ($row = $enrollment_report->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <?php

                            echo $row["Student_ID"];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Student_Name"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Course_Code"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Course_Name"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Semester"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Academic_Year"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Grade"]
                            );

                            ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>


    <!-- =========================
         REPORT 3
         ========================= -->

    <div class="report">

        <h2>
            3. Fees and Payments Report
        </h2>


        <div class="table-wrapper">

            <table>

                <tr>

                    <th>
                        Student ID
                    </th>

                    <th>
                        Student
                    </th>

                    <th>
                        Total Fees
                    </th>

                    <th>
                        Total Paid
                    </th>

                    <th>
                        Balance
                    </th>

                </tr>


                <?php while ($row = $finance_report->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <?php

                            echo $row["Student_ID"];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["Student_Name"]
                            );

                            ?>

                        </td>


                        <td>

                            KSh
                            <?php

                            echo number_format(
                                $row["Total_Fees"],
                                2
                            );

                            ?>

                        </td>


                        <td>

                            KSh
                            <?php

                            echo number_format(
                                $row["Total_Paid"],
                                2
                            );

                            ?>

                        </td>


                        <td>

                            KSh
                            <?php

                            echo number_format(
                                $row["Balance"],
                                2
                            );

                            ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </div>


</div>


<!-- =========================
     FOOTER
     ========================= -->

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