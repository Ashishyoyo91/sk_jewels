<?php
$db = new mysqli('localhost', 'root', '', 'jewelry_invoice');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$id=$_GET['id'];
$sql="UPDATE invoices SET is_delete = 1 WHERE id = $id";
if ($db->query($sql)) {
    header("Location: index.php");
    exit();
}
print_r($id);
?>