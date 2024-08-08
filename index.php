<!DOCTYPE html>
<html>
<head>
    <title>Feedback Survey Form</title>
</head>
<body>
    <h1>Feedback Survey Form</h1>
    <form action="create.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br><br>

        <label for="document">Documents:</label>
        <input type="file" id="document" name="document"><br><br>

        <label for="feedback_type">Type of Feedback:</label>
        <select id="feedback_type" name="feedback_type" required>
            <option value="Positive">Positive</option>
            <option value="Negative">Negative</option>
        </select><br><br>

        <label>Service Used:</label><br>
        <input type="checkbox" id="service1" name="service_used[]" value="Service 1">
        <label for="service1">Service 1</label><br>
        <input type="checkbox" id="service2" name="service_used[]" value="Service 2">
        <label for="service2">Service 2</label><br>
        <input type="checkbox" id="service3" name="service_used[]" value="Service 3">
        <label for="service3">Service 3</label><br><br>

        <label for="additional_comment">Additional Comment:</label><br>
        <textarea id="additional_comment" name="additional_comment"></textarea><br><br>

        <input type="submit" value="Submit Feedback">
    </form>

    <footer>
        <h2>Feedback Records</h2>
        <a href="index.php">Name</a> |
        <a href="index.php">Email</a> |
        <a href="index.php">Age</a> |
        <a href="index.php">Documents</a> |
        <a href="index.php">Type</a> |
        <a href="index.php">Service</a> |
        <a href="index.php">Comments</a> |
        <a href="index.php">Action</a>
    </footer>
</body>
</html>

<?php
$conn = new mysqli('localhost', 'root', '', 'feedback_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM feedback");
?>

<h1>Feedback Records</h1>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Age</th>
        <th>Document</th>
        <th>Feedback Type</th>
        <th>Service Used</th>
        <th>Comments</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['age']); ?></td>
        <td>
            <?php if ($row['document']): ?>
                <a href="data:application/octet-stream;base64,<?php echo base64_encode($row['document']); ?>" download="document">Download</a>
            <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($row['feedback_type']); ?></td>
        <td><?php echo htmlspecialchars($row['service_used']); ?></td>
        <td><?php echo htmlspecialchars($row['additional_comment']); ?></td>
        <td>
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
$conn->close();
?>