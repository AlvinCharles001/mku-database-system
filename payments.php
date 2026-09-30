<?php

require_once "config/db.php";

$message = "";

/* =========================
   DELETE PAYMENT
   ========================= */
if (isset($_GET["delete"])) {

    $payment_id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM payment WHERE Payment_ID = ?"
    );

    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        $message = "Payment deleted successfully.";
    } else {
        $message = "Error deleting payment.";
    }

    $stmt->close();
}


/* =========================
   ADD / UPDATE PAYMENT
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = (int) $_POST["student_id"];
    $amount = $_POST["amount"];
    $payment_date = $_POST["payment_date"];
    $payment_method = $_POST["payment_method"];
    $receipt_number = $_POST["receipt_number"];


    /* UPDATE PAYMENT */
    if (!empty($_POST["payment_id"])) {

        $payment_id = (int) $_POST["payment_id"];

        $stmt = $conn->prepare(
            "UPDATE payment
             SET Student_ID = ?,
                 Amount = ?,
                 Payment_Date = ?,
                 Payment_Method = ?,
                 Receipt_Number = ?
             WHERE Payment_ID = ?"
        );

        $stmt->bind_param(
            "idsssi",
            $student_id,
            $amount,
            $payment_date,
            $payment_method,
            $receipt_number,
            $payment_id
        );

        if ($stmt->execute()) {
            $message = "Payment updated successfully.";
        } else {
            $message = "Error updating payment.";
        }

        $stmt->close();

    }

    /* ADD PAYMENT */
    else {

        $stmt = $conn->prepare(
            "INSERT INTO payment
             (Student_ID, Amount, Payment_Date, Payment_Method, Receipt_Number)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "idsss",
            $student_id,
            $amount,
            $payment_date,
            $payment_method,
            $receipt_number
        );

        if ($stmt->execute()) {
            $message = "Payment added successfully.";
        } else {
            $message = "Error adding payment.";
        }

        $stmt->close();
    }
}


/* =========================
   GET PAYMENT FOR EDITING
   ========================= */

$edit_payment = null;

if (isset($_GET["edit"])) {

    $payment_id = (int) $_GET["edit"];

    $stmt = $conn->prepare(
        "SELECT *
         FROM payment
         WHERE Payment_ID = ?"
    );

    $stmt->bind_param("i", $payment_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $edit_payment = $result->fetch_assoc();

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
   GET PAYMENTS
   ========================= */

$payments = $conn->query(
    "SELECT
        p.Payment_ID,
        p.Student_ID,
        p.Amount,
        p.Payment_Date,
        p.Payment_Method,
        p.Receipt_Number,
        s.First_Name,
        s.Last_Name
     FROM payment p
     LEFT JOIN Student s
        ON p.Student_ID = s.Student_ID
     ORDER BY p.Payment_ID DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Payments - MKU Database System</title>

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

            min-width: 1000px;

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

    <p>Payment Management</p>

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

        <?php

        echo $edit_payment
            ? "Edit Payment"
            : "Add Payment";

        ?>

    </h2>


    <?php if ($message != ""): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- PAYMENT FORM -->

    <form method="POST">


        <?php if ($edit_payment): ?>

            <input
                type="hidden"
                name="payment_id"
                value="<?php echo $edit_payment["Payment_ID"]; ?>"
            >

        <?php endif; ?>


        <label>Student</label>

        <select name="student_id" required>

            <option value="">
                Select Student
            </option>


            <?php while ($student = $students->fetch_assoc()): ?>

                <option
                    value="<?php echo $student["Student_ID"]; ?>"

                    <?php

                    if (
                        $edit_payment &&
                        $edit_payment["Student_ID"]
                        == $student["Student_ID"]
                    ) {

                        echo "selected";

                    }

                    ?>
                >

                    <?php

                    echo htmlspecialchars(
                        $student["First_Name"]
                        . " "
                        . $student["Last_Name"]
                    );

                    ?>

                </option>

            <?php endwhile; ?>

        </select>


        <label>Amount</label>

        <input
            type="number"
            name="amount"
            step="0.01"
            min="0"
            required

            value="<?php

                echo $edit_payment
                    ? htmlspecialchars(
                        $edit_payment["Amount"]
                    )
                    : "";

            ?>"
        >


        <label>Payment Date</label>

        <input
            type="date"
            name="payment_date"
            required

            value="<?php

                echo $edit_payment
                    ? htmlspecialchars(
                        $edit_payment["Payment_Date"]
                    )
                    : "";

            ?>"
        >


        <label>Payment Method</label>

        <select
            name="payment_method"
            required
        >

            <option value="">
                Select Payment Method
            </option>

            <option value="M-Pesa">
                M-Pesa
            </option>

            <option value="Bank">
                Bank
            </option>

            <option value="Cash">
                Cash
            </option>

            <option value="Card">
                Card
            </option>

        </select>


        <label>Receipt Number</label>

        <input
            type="text"
            name="receipt_number"
            required

            value="<?php

                echo $edit_payment
                    ? htmlspecialchars(
                        $edit_payment["Receipt_Number"]
                    )
                    : "";

            ?>"
        >


        <button type="submit">

            <?php

            echo $edit_payment
                ? "Update Payment"
                : "Add Payment";

            ?>

        </button>


    </form>


    <h2>Payment Records</h2>


    <!-- PAYMENT TABLE -->

    <div class="table-wrapper">

        <table>

            <tr>

                <th>Payment ID</th>

                <th>Student</th>

                <th>Amount</th>

                <th>Payment Date</th>

                <th>Payment Method</th>

                <th>Receipt Number</th>

                <th>Actions</th>

            </tr>


            <?php while ($payment = $payments->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $payment["Payment_ID"]; ?>
                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $payment["First_Name"]
                            . " "
                            . $payment["Last_Name"]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo number_format(
                            $payment["Amount"],
                            2
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $payment["Payment_Date"]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $payment["Payment_Method"]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $payment["Receipt_Number"]
                        );

                        ?>

                    </td>


                    <td>

                        <a
                            class="edit"
                            href="payments.php?edit=<?php echo $payment["Payment_ID"]; ?>"
                        >
                            Edit
                        </a>


                        <a
                            class="delete"
                            href="payments.php?delete=<?php echo $payment["Payment_ID"]; ?>"

                            onclick="return confirm(
                                'Are you sure you want to delete this payment?'
                            );"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    </div>


    <a
        class="back"
        href="index.php"
    >
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