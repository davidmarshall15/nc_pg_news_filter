<?php
// Database connection details
// No security around this - strongly recommend security is added such as restricted to LAN addresses.

$host = 'database-hostname';
$dbname = 'your_database';
$user = 'your_username';
$password = 'your_password';
$dsn = "pgsql:host=$host;dbname=$dbname";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Insert data
if (isset($_POST['insert'])) {
    $stmt = $pdo->prepare("INSERT INTO oc_news_filter (sub_domain, txt_filter, inc_filter) VALUES (:sub_domain, :txt_filter, :inc_filter)");
    $stmt->execute(['sub_domain' => $_POST['sub_domain'], 'txt_filter' => $_POST['txt_filter'], 'inc_filter' => isset($_POST['inc_filter']) ? "true" : "false"]);
}

// Update data
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE oc_news_filter SET sub_domain = :sub_domain, txt_filter = :txt_filter, inc_filter = :inc_filter WHERE id = :id");
    $stmt->execute(['id' => $_POST['id'], 'sub_domain' => $_POST['sub_domain'], 'txt_filter' => $_POST['txt_filter'], 'inc_filter' => isset($_POST['inc_filter']) ? "true" : "false"]);
}

// Delete data
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM oc_news_filter WHERE id = :id");
    $stmt->execute(['id' => $_POST['id']]);
}

// Retrieve data
$records = $pdo->query("SELECT * FROM oc_news_filter")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nextcloud News Filter</title>
</head>
<body>
    <h2>Manage Records</h2>
    
    <form method="POST">
        <input type="text" name="sub_domain" placeholder="Sub Domain" required>
        <input type="text" name="txt_filter" placeholder="Text Filter" required>
        <label>
            Include Filter <input type="checkbox" name="inc_filter">
        </label>
        <button type="submit" name="insert">Insert</button>
    </form>
    
    <h3>Records List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Subscription Domain</th>
            <th>Text Filter</th>
            <th>Include Filter</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($records as $record): ?>
        <tr>
            <td><?= $record['id'] ?></td>
            <td><?= $record['sub_domain'] ?></td>
            <td><?= $record['txt_filter'] ?></td>
            <td><?= $record['inc_filter'] ? 'Yes' : 'No' ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $record['id'] ?>">
                    <input type="text" name="sub_domain" value="<?= $record['sub_domain'] ?>" required>
                    <input type="text" name="txt_filter" value="<?= $record['txt_filter'] ?>" required>
                    <label>
                        Include Filter <input type="checkbox" name="inc_filter" <?= $record['inc_filter'] ? 'checked' : '' ?>>
                    </label>
                    <button type="submit" name="update">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $record['id'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p>
    Include Filter checked (Yes): Include all articles that match the Text Filter and Subscription Domain, everything else is excluded.<br>
    Include Filter unchecked (No): When no Include Filters are checked for the Subscription Domain, exclude articles that match the Text Filter and Subscription Domain.
    </p>
</body>
</html>