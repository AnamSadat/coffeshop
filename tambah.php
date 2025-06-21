<?php include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nama = $_POST["nama_baru"];
    $harga_update = $_POST["harga_update"];
    $stok_update = $_POST["stok_update"];

    $check_query = "SELECT * FROM kopi WHERE id = $id";
    $result_check = $conn->query($check_query);

    if ($result_check->num_rows > 0) {
        $update_query = "UPDATE kopi SET";

        if (!empty($nama)) {
            $update_query .= " nama='$nama',";
        }

        if (!empty($harga_update)) {
            $insert_harga_query = "INSERT INTO harga (nominal) VALUES ('$harga_update')";
            $conn->query($insert_harga_query);

            $harga_id = $conn->insert_id;

            $update_query .= " harga_id='$harga_id',";
        }

        if (!empty($stok_update)) {
            $insert_stok_query = "INSERT INTO stok (jumlah) VALUES ('$stok_update')";
            $conn->query($insert_stok_query);

            $stok_id = $conn->insert_id;

            $update_query .= " stok_id='$stok_id',";
        }

        $update_query  = rtrim($update_query, ",");
        $update_query .= " WHERE id=$id";

        if ($conn->query($update_query) === TRUE) {
            header("Location: index.php?alert=Data berhasil diupdate !");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Data dengan ID $id tidak ditemukan.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET["id"] ?? header('Location: index.php');

    $query = "SELECT kopi.id, kopi.nama, harga.nominal AS harga, stok.jumlah AS stok 
              FROM kopi
              INNER JOIN harga ON kopi.harga_id = harga.id
              INNER JOIN stok ON kopi.stok_id = stok.id
              WHERE kopi.id = $id";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama = $row['nama'];
        $harga = $row['harga'];
        $stok = $row['stok'];
    } else {
        echo "Data dengan ID $id tidak ditemukan.";
        exit();
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Form Update Kopi</title>
</head>
<body>
    <h2>Form Update Kopi</h2>
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Nama Kopi Baru: <input type="text" name="nama_baru" value="<?php echo $nama; ?>"><br>
        Update Harga: <input type="text" name="harga_update" value="<?php echo $harga; ?>"><br>
        Update Stok: <input type="text" name="stok_update" value="<?php echo $stok; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>