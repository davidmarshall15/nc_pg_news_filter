<?php
// Database connection details
// No security around this - strongly recommend security is added such as restricted to LAN addresses.
require_once('config.php');
$host = Hostname;
$dbname = Database;
$user = Username;
$password = Password;

$dsn = "pgsql:host=$host;dbname=$dbname";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Secure Input Data
function secureInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Insert data
if (isset($_POST['insert'])) {
    $sub_domain = secureInput($_POST['sub_domain']);
    $txt_filter = secureInput($_POST['txt_filter']);
    $inc_filter = isset($_POST['inc_filter']) ? "true" : "false";

    $stmt = $pdo->prepare("INSERT INTO oc_news_filter (sub_domain, txt_filter, inc_filter) VALUES (:sub_domain, :txt_filter, :inc_filter)");
    $stmt->execute(['sub_domain' => $sub_domain, 'txt_filter' => $txt_filter, 'inc_filter' => $inc_filter]);
}

// Update data
if (isset($_POST['update'])) {
    $id = secureInput($_POST['id']);
    $sub_domain = secureInput($_POST['sub_domain']);
    $txt_filter = secureInput($_POST['txt_filter']);
    $inc_filter = isset($_POST['inc_filter']) ? "true" : "false";

    $stmt = $pdo->prepare("UPDATE oc_news_filter SET sub_domain = :sub_domain, txt_filter = :txt_filter, inc_filter = :inc_filter WHERE id = :id");
    $stmt->execute(['id' => $id, 'sub_domain' => $sub_domain, 'txt_filter' => $txt_filter, 'inc_filter' => $inc_filter]);
}

// Delete data
if (isset($_POST['delete'])) {
    $id = secureInput($_POST['id']);
    $stmt = $pdo->prepare("DELETE FROM oc_news_filter WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Retrieve data
$records = $pdo->query("SELECT * FROM oc_news_filter ORDER BY sub_domain, txt_filter")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nextcloud News Filter</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            color: #ddd;
        }
        form, table {
            width: 90%;
            margin: auto;
        }
        input[type="text"], input[type="checkbox"], button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #3e3e3e;
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #3e3e3e;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #1e1e1e;
        }
        button {
            background-color: #333;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #444;
        }
    </style>
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
    <table>
        <tr>
            <th>Subscription Domain</th>
            <th>Text Filter</th>
            <th>Include Filter</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record['sub_domain']) ?></td>
            <td><?= htmlspecialchars($record['txt_filter']) ?></td>
            <td><?= $record['inc_filter'] ? 'Yes' : 'No' ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
                    <input type="text" name="txt_filter" value="<?= htmlspecialchars($record['txt_filter']) ?>" required>
                    <label>
                        Include Filter <input type="checkbox" name="inc_filter" <?= $record['inc_filter'] ? 'checked' : '' ?>>
                    </label>
                    <button type="submit" name="update">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
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