<?php
require '../dbConnection.php';
include 'config.php'; 
?>

<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>License Manager</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>
<h2>License Manager</h2>
<a href="add.php">Add New License</a><br><br>
<table>
    <tr>
        <th>ID</th><th>License Key</th><th>Domain</th><th>Product</th><th>Status</th><th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM licenses ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['license_key']}</td>
                <td>{$row['domain']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='edit.php?id={$row['id']}'>Edit</a> |
                    <a href='delete.php?id={$row['id']}' onclick=\"return confirm('Are you sure?');\">Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>
</body>
</html>

<!-- add.php -->
<?php include 'config.php'; ?>
<form method="post">
    License Key: <input type="text" name="license_key" required><br>
    Domain: <input type="text" name="domain" required><br>
    Product: <input type="text" name="product_name" required><br>
    Status: 
    <select name="status">
        <option value="active">Active</option>
        <option value="blocked">Blocked</option>
    </select><br>
    <input type="submit" name="save" value="Save">
</form>
<?php
if (isset($_POST['save'])) {
    $stmt = $conn->prepare("INSERT INTO licenses (license_key, domain, product_name, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['license_key'], $_POST['domain'], $_POST['product_name'], $_POST['status']);
    $stmt->execute();
    header("Location: index.php");
}
?>

<!-- edit.php -->
<?php include 'config.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM licenses WHERE id=$id");
$row = $result->fetch_assoc();
?>
<form method="post">
    License Key: <input type="text" name="license_key" value="<?php echo $row['license_key']; ?>" required><br>
    Domain: <input type="text" name="domain" value="<?php echo $row['domain']; ?>" required><br>
    Product: <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>" required><br>
    Status: 
    <select name="status">
        <option value="active" <?php if($row['status']=='active') echo 'selected'; ?>>Active</option>
        <option value="blocked" <?php if($row['status']=='blocked') echo 'selected'; ?>>Blocked</option>
    </select><br>
    <input type="submit" name="update" value="Update">
</form>
<?php
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE licenses SET license_key=?, domain=?, product_name=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $_POST['license_key'], $_POST['domain'], $_POST['product_name'], $_POST['status'], $id);
    $stmt->execute();
    header("Location: index.php");
}
?>

<!-- delete.php -->
<?php include 'config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM licenses WHERE id=$id");
header("Location: index.php");
?>
