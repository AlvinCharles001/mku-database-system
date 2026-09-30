<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Lecturer ID is missing.");
}

$lecturer_id = $_GET['id'];

$message = "";


// Update lecturer
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $department_id = $_POST["department_id"];

    $sql = "UPDATE Lecturer
            SET First_Name = ?,
                Last_Name = ?,
                Email = ?,
                Phone = ?,
                Department_ID = ?
            WHERE Lecturer_ID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssii",
        $first_name,
        $last_name,
        $email,
        $phone,
        $department_id,
        $lecturer_id
    );

    if ($stmt->execute()) {

        $message = "Lecturer updated successfully!";

    } else {

        $message = "Error updating lecturer.";

    }
}


// Get lecturer information
$sql = "SELECT *
        FROM Lecturer
        WHERE Lecturer_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $lecturer_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Lecturer not found.");
}

$lecturer = $result->fetch_assoc();


// Get departments
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

    <title>Edit Lecturer - MKU Database System</title>

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

    <p>Edit Lecturer</p>

</div>


<div class="container">

    <h2>Update Lecturer</h2>


    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>First Name</label>

        <input
            type="text"
            name="first_name"
            value="<?php echo htmlspecialchars($lecturer['First_Name']); ?>"
            required
        >


        <label>Last Name</label>

        <input
            type="text"
            name="last_name"
            value="<?php echo htmlspecialchars($lecturer['Last_Name']); ?>"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($lecturer['Email']); ?>"
            required
        >


        <label>Phone</label>

        <input
            type="text"
            name="phone"
            value="<?php echo htmlspecialchars($lecturer['Phone']); ?>"
            required
        >


        <label>Department</label>

        <select name="department_id" required>

            <option value="">
                Select Department
            </option>

            <?php while ($department = $departments->fetch_assoc()): ?>

                <option
                    value="<?php echo $department['Department_ID']; ?>"
                    <?php
                    if ($department['Department_ID'] == $lecturer['Department_ID']) {
                        echo 'selected';
                    }
                    ?>
                >

                    <?php echo htmlspecialchars($department['Department_Name']); ?>

                </option>

            <?php endwhile; ?>

        </select>


        <button type="submit">
            Update Lecturer
        </button>

    </form>


    <a class="back" href="lecturers.php">
        ← Back to Lecturers
    </a>

</div>

</body>

</html>