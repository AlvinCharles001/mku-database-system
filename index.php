<?php

require_once "config/db.php";

/* =========================
   DASHBOARD STATISTICS
   ========================= */

$students = $conn->query(
    "SELECT COUNT(*) AS total FROM Student"
)->fetch_assoc()['total'];

$departments = $conn->query(
    "SELECT COUNT(*) AS total FROM Department"
)->fetch_assoc()['total'];

$courses = $conn->query(
    "SELECT COUNT(*) AS total FROM Course"
)->fetch_assoc()['total'];

$lecturers = $conn->query(
    "SELECT COUNT(*) AS total FROM Lecturer"
)->fetch_assoc()['total'];

$fees = $conn->query(
    "SELECT COALESCE(SUM(Amount_Required), 0) AS total
     FROM fee"
)->fetch_assoc()['total'];

$payments = $conn->query(
    "SELECT COALESCE(SUM(Amount), 0) AS total
     FROM payment"
)->fetch_assoc()['total'];

$balance = $fees - $payments;

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>MKU Database System</title>


<style>

/* =========================
   GENERAL
   ========================= */

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family: Arial, sans-serif;

    background-image:
    linear-gradient(
        rgba(0, 0, 0, 0.30),
        rgba(0, 0, 0, 0.30)
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

    background: rgba(128, 0, 0, 0.96);

    color: white;

    padding: 25px;

    text-align: center;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.25);

}

.header h1 {

    margin: 0;

    font-size: 32px;

}

.header p {

    margin: 8px 0 0;

    font-size: 16px;

}


/* =========================
   NAVIGATION
   ========================= */

.navbar {

    background: rgba(0, 0, 0, 0.82);

    display: flex;

    flex-wrap: wrap;

    justify-content: center;

    position: sticky;

    top: 0;

    z-index: 1000;

    backdrop-filter: blur(6px);

}

.navbar a {

    color: white;

    text-decoration: none;

    padding: 15px 18px;

    font-weight: bold;

    transition: 0.3s;

}

.navbar a:hover {

    background: #800000;

}


/* ACTIVE PAGE */

.navbar .active {

    background: #800000;

}


/* =========================
   MAIN CONTAINER
   ========================= */

.container {

    width: 92%;

    max-width: 1250px;

    margin: 35px auto;

}


/* =========================
   DASHBOARD TITLE
   ========================= */

.dashboard-title {

    background: rgba(255,255,255,0.95);

    padding: 20px 25px;

    border-radius: 10px;

    margin-bottom: 25px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.15);

}

.dashboard-title h2 {

    margin: 0;

    color: #800000;

}

.dashboard-title p {

    margin-bottom: 0;

    color: #555;

}


/* =========================
   STATISTICS
   ========================= */

.cards {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

}


.card {

    background:
        rgba(255,255,255,0.96);

    padding: 25px;

    text-align: center;

    border-radius: 12px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.18);

    transition:
        transform 0.25s,
        box-shadow 0.25s;

}


.card:hover {

    transform: translateY(-5px);

    box-shadow:
        0 7px 18px rgba(0,0,0,0.25);

}


.card-icon {

    font-size: 35px;

    margin-bottom: 10px;

}


.card h2 {

    margin: 0;

    color: #800000;

    font-size: 32px;

}


.card p {

    margin: 8px 0 0;

    color: #555;

    font-weight: bold;

}


.card a {

    display: inline-block;

    margin-top: 12px;

    color: #800000;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

}


/* =========================
   FINANCIAL SECTION
   ========================= */

.section-title {

    color: white;

    margin-top: 35px;

    margin-bottom: 15px;

    text-shadow:
        0 2px 4px rgba(0,0,0,0.6);

}


.finance {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;

}


.finance-card {

    background:
        rgba(255,255,255,0.96);

    padding: 25px;

    border-radius: 12px;

    text-align: center;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.18);

}


.finance-card h2 {

    margin: 0;

    color: #800000;

    font-size: 26px;

}


.finance-card p {

    color: #555;

    font-weight: bold;

}


/* =========================
   QUICK ACTIONS
   ========================= */

.quick-actions {

    margin-top: 30px;

    background:
        rgba(255,255,255,0.96);

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.18);

}


.quick-actions h2 {

    margin-top: 0;

    color: #800000;

}


.action-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: 12px;

}


.action-buttons a {

    background: #800000;

    color: white;

    text-decoration: none;

    padding: 12px 18px;

    border-radius: 6px;

    font-weight: bold;

    transition: 0.3s;

}


.action-buttons a:hover {

    background: #5c0000;

    transform: translateY(-2px);

}


/* =========================
   WELCOME
   ========================= */

.welcome {

    background:
        rgba(255,255,255,0.96);

    margin-top: 30px;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.18);

}


.welcome h2 {

    margin-top: 0;

    color: #800000;

}


.welcome p {

    color: #444;

    line-height: 1.6;

}


/* =========================
   FOOTER
   ========================= */

.footer {

    margin-top: 45px;

    background:
        rgba(128, 0, 0, 0.96);

    color: white;

    text-align: center;

    padding: 22px;

}


.footer p {

    margin: 5px 0;

    font-size: 14px;

}


/* =========================
   TABLET
   ========================= */

@media (max-width: 900px) {

    .cards {

        grid-template-columns:
            repeat(2, 1fr);

    }

    .finance {

        grid-template-columns: 1fr;

    }

}


/* =========================
   MOBILE
   ========================= */

@media (max-width: 550px) {

    .header h1 {

        font-size: 25px;

    }

    .navbar {

        position: relative;

    }

    .navbar a {

        width: 50%;

        text-align: center;

    }

    .cards {

        grid-template-columns: 1fr;

    }

    .container {

        width: 94%;

    }

    .action-buttons {

        flex-direction: column;

    }

    .action-buttons a {

        text-align: center;

    }

}

</style>

</head>


<body>


<!-- =========================
     HEADER
     ========================= -->

<div class="header">

    <h1>
        Mount Kenya University
    </h1>

    <p>
        University Database Management System
    </p>

</div>


<!-- =========================
     NAVIGATION
     ========================= -->

<div class="navbar">

    <a href="index.php" class="active">
        🏠 Dashboard
    </a>

    <a href="students.php">
        👨‍🎓 Students
    </a>

    <a href="departments.php">
        🏢 Departments
    </a>

    <a href="courses.php">
        📚 Courses
    </a>

    <a href="lecturers.php">
        👨‍🏫 Lecturers
    </a>

    <a href="enrollment.php">
        📝 Enrollment
    </a>

    <a href="fees.php">
        💰 Fees
    </a>

    <a href="payments.php">
        💳 Payments
    </a>

    <a href="reports.php">
        📊 Reports
    </a>

</div>


<!-- =========================
     MAIN
     ========================= -->

<div class="container">


    <!-- DASHBOARD TITLE -->

    <div class="dashboard-title">

        <h2>
            Dashboard
        </h2>

        <p>
            Welcome to the Mount Kenya University
            Database Management System.
        </p>

    </div>


    <!-- =========================
         UNIVERSITY STATISTICS
         ========================= -->

    <div class="cards">


        <div class="card">

            <div class="card-icon">
                👨‍🎓
            </div>

            <h2>
                <?php echo $students; ?>
            </h2>

            <p>
                Students
            </p>

            <a href="students.php">
                Manage Students →
            </a>

        </div>


        <div class="card">

            <div class="card-icon">
                🏢
            </div>

            <h2>
                <?php echo $departments; ?>
            </h2>

            <p>
                Departments
            </p>

            <a href="departments.php">
                Manage Departments →
            </a>

        </div>


        <div class="card">

            <div class="card-icon">
                📚
            </div>

            <h2>
                <?php echo $courses; ?>
            </h2>

            <p>
                Courses
            </p>

            <a href="courses.php">
                Manage Courses →
            </a>

        </div>


        <div class="card">

            <div class="card-icon">
                👨‍🏫
            </div>

            <h2>
                <?php echo $lecturers; ?>
            </h2>

            <p>
                Lecturers
            </p>

            <a href="lecturers.php">
                Manage Lecturers →
            </a>

        </div>


    </div>


    <!-- =========================
         FINANCE
         ========================= -->

    <h2 class="section-title">
        Financial Overview
    </h2>


    <div class="finance">


        <div class="finance-card">

            <h2>
                KSh
                <?php
                echo number_format($fees, 2);
                ?>
            </h2>

            <p>
                Total Fees Required
            </p>

        </div>


        <div class="finance-card">

            <h2>
                KSh
                <?php
                echo number_format($payments, 2);
                ?>
            </h2>

            <p>
                Total Payments
            </p>

        </div>


        <div class="finance-card">

            <h2>
                KSh
                <?php
                echo number_format($balance, 2);
                ?>
            </h2>

            <p>
                Outstanding Balance
            </p>

        </div>


    </div>


    <!-- =========================
         QUICK ACTIONS
         ========================= -->

    <div class="quick-actions">

        <h2>
            Quick Actions
        </h2>


        <div class="action-buttons">

            <a href="students.php">
                👨‍🎓 Manage Students
            </a>

            <a href="enrollment.php">
                📝 Manage Enrollment
            </a>

            <a href="fees.php">
                💰 Manage Fees
            </a>

            <a href="payments.php">
                💳 Manage Payments
            </a>

            <a href="reports.php">
                📊 View Reports
            </a>

        </div>

    </div>


    <!-- =========================
         WELCOME
         ========================= -->

    <div class="welcome">

        <h2>
            Welcome to the MKU Database System
        </h2>


        <p>

            This system provides a centralized platform
            for managing university information.

        </p>


        <p>

            The system currently supports student,
            department, course, lecturer, enrollment,
            fee and payment management, together with
            database reports.

        </p>


        <p>

            Use the navigation menu or the quick actions
            above to access the different sections.

        </p>

    </div>


</div>


<!-- =========================
     FOOTER
     ========================= -->

<footer class="footer">

    <p>
        <strong>
            Mount Kenya University
        </strong>
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