<?php

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("Department ID is missing.");
}

$department_id = $_GET['id'];

$message = "";

// Update department
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $department_name = $_POST["department_name"];

    $sql = "UPDATE Department
            SET Department_Name = ?
            WHERE Department_ID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "si",
        $department_name,
        $department_id
    );

    if ($stmt->execute()) {

        $message = "Department updated successfully!";

    } else {

        $message = "Error updating department.";

    }
}

// Get department information
$sql = "SELECT *
        FROM Department
        WHERE Department_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $department_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Department not found.");
}

$department = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Department - MKU Database System</title>

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

        input {
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

    <p>Edit Department</p>

</div>


<div class="container">

    <h2>Update Department</h2>


    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Department Name</label>

        <input
            type="text"
            name="department_name"
            value="<?php echo htmlspecialchars($department['Department_Name']); ?>"
            required
        >

        <button type="submit">
            Update Department
        </button>

    </form>


    <a class="back" href="departments.php">
        ← Back to Departments
    </a>

</div>

</body>

</html>