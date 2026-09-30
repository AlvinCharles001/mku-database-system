<?php

require_once "config/db.php";

$message = "";

/* =========================
   DELETE FEE
   ========================= */
if (isset($_GET["delete"])) {

    $fee_id = (int) $_GET["delete"];

    $stmt = $conn->prepare("DELETE FROM fee WHERE Fee_ID = ?");
    $stmt->bind_param("i", $fee_id);

    if ($stmt->execute()) {
        $message = "Fee record deleted successfully.";
    } else {
        $message = "Error deleting fee record.";
    }

    $stmt->close();
}


/* =========================
   ADD / UPDATE FEE
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = (int) $_POST["student_id"];
    $amount_required = $_POST["amount_required"];
    $semester = $_POST["semester"];
    $academic_year = $_POST["academic_year"];

    /* UPDATE */
    if (!empty($_POST["fee_id"])) {

        $fee_id = (int) $_POST["fee_id"];

        $stmt = $conn->prepare(
            "UPDATE fee
             SET Student_ID = ?, Amount_Required = ?, Semester = ?, Academic_Year = ?
             WHERE Fee_ID = ?"
        );

        $stmt->bind_param(
            "idssi",
            $student_id,
            $amount_required,
            $semester,
            $academic_year,
            $fee_id
        );

        if ($stmt->execute()) {
            $message = "Fee record updated successfully.";
        } else {
            $message = "Error updating fee record.";
        }

        $stmt->close();

    }

    /* ADD */
    else {

        $stmt = $conn->prepare(
            "INSERT INTO fee
             (Student_ID, Amount_Required, Semester, Academic_Year)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "idss",
            $student_id,
            $amount_required,
            $semester,
            $academic_year
        );

        if ($stmt->execute()) {
            $message = "Fee record added successfully.";
        } else {
            $message = "Error adding fee record.";
        }

        $stmt->close();
    }
}


/* =========================
   GET RECORD FOR EDITING
   ========================= */
$edit_fee = null;

if (isset($_GET["edit"])) {

    $fee_id = (int) $_GET["edit"];

    $stmt = $conn->prepare(
        "SELECT * FROM fee WHERE Fee_ID = ?"
    );

    $stmt->bind_param("i", $fee_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $edit_fee = $result->fetch_assoc();

    $stmt->close();
}


/* =========================
   GET STUDENTS
   ========================= */
$students = $conn->query(
    "SELECT Student_ID, First_Name, Last_Name
     FROM Student
     ORDER BY First_Name"
);


/* =========================
   GET FEES
   ========================= */
$fees = $conn->query(
    "SELECT 
        f.Fee_ID,
        f.Student_ID,
        f.Amount_Required,
        f.Semester,
        f.Academic_Year,
        s.First_Name,
        s.Last_Name
     FROM fee f
     LEFT JOIN Student s
        ON f.Student_ID = s.Student_ID
     ORDER BY f.Fee_ID DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Fees - MKU Database System</title>

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

        /* MESSAGE */

        .message {
            padding: 12px;

            margin-bottom: 20px;

            background: #e8f5e9;

            color: #2e7d32;

            border-radius: 5px;

            font-weight: bold;
        }

        /* FORM */

        form {
            display: grid;

            gap: 10px;

            margin-bottom: 35px;
        }

        label {
            font-weight: bold;

            margin-top: 8px;
        }

        input,
        select {
            padding: 11px;

            width: 100%;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;

            border-color: #800000;

            box-shadow:
                0 0 4px rgba(128, 0, 0, 0.3);
        }

        button {
            width: fit-content;

            padding: 11px 20px;

            background: #800000;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            margin-top: 10px;

            font-weight: bold;
        }

        button:hover {
            background: #600000;
        }

        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;

            min-width: 850px;

            border-collapse: collapse;

            background: white;

            margin-top: 20px;
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

        /* ACTIONS */

        .edit {
            color: #1565c0;

            text-decoration: none;

            font-weight: bold;

            margin-right: 10px;
        }

        .delete {
            color: #c62828;

            text-decoration: none;

            font-weight: bold;
        }

        .edit:hover,
        .delete:hover {
            text-decoration: underline;
        }

        /* BACK BUTTON */

        .back {
            display: inline-block;

            margin-top: 25px;

            text-decoration: none;

            color: white;

            background: #555;

            padding: 10px 15px;

            border-radius: 5px;
        }

        .back:hover {
            background: #333;
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

    <p>Fees Management</p>

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

    <h2>
        <?php echo $edit_fee ? "Edit Fee Record" : "Add Fee Record"; ?>
    </h2>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- FEE FORM -->

    <form method="POST">

        <?php if ($edit_fee): ?>

            <input
                type="hidden"
                name="fee_id"
                value="<?php echo $edit_fee["Fee_ID"]; ?>"
            >

        <?php endif; ?>


        <label>Student</label>

        <select name="student_id" required>

            <option value="">Select Student</option>

            <?php while ($student = $students->fetch_assoc()): ?>

                <option
                    value="<?php echo $student["Student_ID"]; ?>"
                    <?php
                    if (
                        $edit_fee &&
                        $edit_fee["Student_ID"] == $student["Student_ID"]
                    ) {
                        echo "selected";
                    }
                    ?>
                >

                    <?php
                    echo htmlspecialchars(
                        $student["First_Name"] .
                        " " .
                        $student["Last_Name"]
                    );
                    ?>

                </option>

            <?php endwhile; ?>

        </select>


        <label>Amount Required</label>

        <input
            type="number"
            name="amount_required"
            step="0.01"
            min="0"
            required
            value="<?php
                echo $edit_fee
                    ? htmlspecialchars($edit_fee["Amount_Required"])
                    : "";
            ?>"
        >


        <label>Semester</label>

        <input
            type="text"
            name="semester"
            placeholder="e.g. Semester 1"
            required
            value="<?php
                echo $edit_fee
                    ? htmlspecialchars($edit_fee["Semester"])
                    : "";
            ?>"
        >


        <label>Academic Year</label>

        <input
            type="text"
            name="academic_year"
            placeholder="e.g. 2026/2027"
            required
            value="<?php
                echo $edit_fee
                    ? htmlspecialchars($edit_fee["Academic_Year"])
                    : "";
            ?>"
        >


        <button type="submit">

            <?php echo $edit_fee ? "Update Fee" : "Add Fee"; ?>

        </button>

    </form>


    <h2>Fee Records</h2>


    <!-- FEE TABLE -->

    <div class="table-wrapper">

        <table>

            <tr>

                <th>Fee ID</th>

                <th>Student</th>

                <th>Amount Required</th>

                <th>Semester</th>

                <th>Academic Year</th>

                <th>Actions</th>

            </tr>


            <?php while ($fee = $fees->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $fee["Fee_ID"]; ?>
                    </td>

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $fee["First_Name"] .
                            " " .
                            $fee["Last_Name"]
                        );

                        ?>

                    </td>

                    <td>

                        <?php echo number_format(
                            $fee["Amount_Required"],
                            2
                        ); ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars(
                            $fee["Semester"]
                        ); ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars(
                            $fee["Academic_Year"]
                        ); ?>

                    </td>

                    <td>

                        <a
                            class="edit"
                            href="fees.php?edit=<?php echo $fee["Fee_ID"]; ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete"
                            href="fees.php?delete=<?php echo $fee["Fee_ID"]; ?>"
                            onclick="return confirm(
                                'Are you sure you want to delete this fee record?'
                            );"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    </div>


    <a class="back" href="index.php">
        ← Back to Dashboard
    </a>


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