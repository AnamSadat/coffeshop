<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET["id"] ?? header('Location: index.php');

    $check_query = "SELECT * FROM kopi WHERE id = $id";
    $result_check = $conn->query($check_query);

    if ($result_check->num_rows > 0) {
        $delete_query = "DELETE FROM kopi WHERE id = $id";

        if ($conn->query($delete_query) === TRUE) {
            echo "Data kopi berhasil dihapus.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Data dengan id $id tidak ditemukan.";
    }
}

$conn->close();
?>
