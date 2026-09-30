<?php

require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $department_id = $_POST["department_id"];

    $sql = "INSERT INTO Student
            (First_Name, Last_Name, Gender, Phone, Email, Department_ID)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssi",
        $first_name,
        $last_name,
        $gender,
        $phone,
        $email,
        $department_id
    );

    if ($stmt->execute()) {
        $message = "Student added successfully!";
    } else {
        $message = "Error adding student.";
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

    <title>Add Student - MKU Database System</title>

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

    <p>Add Student</p>

</div>

<div class="container">

    <h2>Register New Student</h2>

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
            required
        >


        <label>Last Name</label>

        <input
            type="text"
            name="last_name"
            required
        >


        <label>Gender</label>

        <select name="gender" required>

            <option value="">Select Gender</option>

            <option value="Male">Male</option>

            <option value="Female">Female</option>

        </select>


        <label>Phone</label>

        <input
            type="text"
            name="phone"
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
        >


        <label>Department</label>

        <select name="department_id" required>

            <option value="">Select Department</option>

            <?php while ($department = $departments->fetch_assoc()): ?>

                <option value="<?php echo $department['Department_ID']; ?>">

                    <?php echo $department['Department_Name']; ?>

                </option>

            <?php endwhile; ?>

        </select>


        <button type="submit">
            Add Student
        </button>

    </form>


    <a class="back" href="students.php">
        ← Back to Students
    </a>

</div>

</body>

</html>