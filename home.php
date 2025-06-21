<?php include "koneksi.php";

$getId = (isset($_GET["id"]) ? "WHERE kopi.id = {$_GET["id"]}" : '');
$result = $conn->query("SELECT kopi.id, kopi.nama, harga.nominal AS harga, stok.jumlah AS stok 
                        FROM kopi
                        INNER JOIN harga ON kopi.harga_id = harga.id
                        INNER JOIN stok ON kopi.stok_id = stok.id {$getId}");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./kopi.png">
    <title>Kedai Kopi</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/style_table.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php' ?>
        
        <div class="right">
            <div class="menu-toggle">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="text-container">
                <?php if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'tambah':
                            ?> <h1>Tambah Kopi</h1> <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $nama = $_POST["nama"];
                            
                                $harga = $_POST["harga"];
                                $stok = $_POST["stok"];
                            
                                $query = "INSERT INTO kopi (nama) VALUES ('{$nama}')";
                                if ($conn->query($query) === TRUE) {
                                    $id = $conn->insert_id;
                            
                                    if (!empty($harga) && !empty($stok)) {
                                        $insertHargaQuery = "INSERT INTO harga (nominal) VALUES ('{$harga}')";
                                        $conn->query($insertHargaQuery);
                                        $hargaId = $conn->insert_id;
                            
                                        $insertStokQuery = "INSERT INTO stok (jumlah) VALUES ('{$stok}')";
                                        $conn->query($insertStokQuery);
                                        $stok_id = $conn->insert_id;
                            
                                        $updateKopiQuery = "UPDATE kopi 
                                                            SET harga_id='{$hargaId}', stok_id = '$stok_id' 
                                                            WHERE id = {$id}";
                                        $conn->query($updateKopiQuery);
                                    }
                            
                                    ?>
                                    <script>
                                        alert('Data berhasil ditambahkan !');
                                        window.location = 'home.php';
                                    </script>
                                    <?php
                                } else {
                                    ?>
                                    <script>
                                        alert('<?= "Error: " . $insert_query . "<br>" . $conn->error ?>');
                                    </script>
                                    <?php
                                }
                            }
                            ?>
                            <table style="width: 600px;">
                                <tbody>
                                    <form action="" method="post">
                                        <tr>
                                            <td>Nama Kopi</td>
                                            <td><input type="text" name="nama" placeholder="Moccacino" required></td>
                                        </tr>
                                        <tr>
                                            <td>Harga</td>
                                            <td><input type="number" name="harga" placeholder="10000" required></td>
                                        </tr>
                                        <tr>
                                            <td>Stok</td>
                                            <td><input type="number" name="stok" placeholder="10" required></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><button type="submit">Tambah</button></td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                            <?php
                            die();
                            break;
                        case 'edit':
                            ?> <h1>Edit Kopi</h1> <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $id = $_POST['id'];
                                $nama = $_POST['nama'];
                                $harga = $_POST['harga'];
                                $stok = $_POST['stok'];

                                $query = "SELECT * FROM kopi WHERE id = {$id}";
                                if ($conn->query($query)->num_rows > 0) {
                                    if (!empty($nama)) {
                                        $updateQuery = "UPDATE kopi SET nama = '{$nama}' WHERE id = {$id}";
                                        if (!$conn->query($updateQuery)) {
                                            echo "Error updating record: " . $conn->error;
                                            exit();
                                        }
                                    }

                                    if (!empty($harga)) {
                                        $updateQuery = "UPDATE harga 
                                                         INNER JOIN kopi ON harga.id = kopi.harga_id 
                                                         SET harga.nominal = '{$harga}' 
                                                         WHERE kopi.id = {$id}";
                                        if (!$conn->query($updateQuery)) {
                                            echo "Error updating record: " . $conn->error;
                                            exit();
                                        }
                                    }

                                    if (!empty($stok)) {
                                        $updateQuery = "UPDATE stok 
                                                         INNER JOIN kopi ON stok.id = kopi.stok_id 
                                                         SET stok.jumlah = '{$stok}' 
                                                         WHERE kopi.id = {$id}";
                                        if ($conn->query($updateQuery) !== TRUE) {
                                            echo "Error updating record: " . $conn->error;
                                            exit();
                                        }
                                    }

                                    ?>
                                    <script>
                                        alert('Data berhasil diedit !');
                                        window.location = 'home.php';
                                    </script>
                                    <?php
                                } else {
                                    ?> <script>alert('Data gagal diedit !')</script> <?php
                                }
                            }
                            if ($_SERVER["REQUEST_METHOD"] == "GET") { ?>
                                <table style="width: 600px;">
                                    <tbody>
                                        <form action="" method="post">
                                            <?php while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td>Nama Kopi</td>
                                                    <td>
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <input type="text" name="nama" value="<?= $row['nama'] ?>" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Harga</td>
                                                    <td><input type="number" name="harga" value="<?= $row['harga'] ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Stok</td>
                                                    <td><input type="number" name="stok" value="<?= $row['stok'] ?>" required></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td></td>
                                                <td><button type="submit">Edit</button></td>
                                            </tr>
                                        </form>
                                    </tbody>
                                </table>
                            <?php }
                            die();
                            break;
                        case 'hapus':
                            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
                                $id = $_GET["id"];

                                if (isset($_GET['action']) && $_GET['action'] === 'hapus') {
                                    $query = "DELETE FROM kopi WHERE id = $id";
                            
                                    if ($conn->query($query)) {
                                        ?><script>alert('Data kopi berhasil dihapus.'); 
                                        window.location.href = 'home.php';</script> <?php
                                    } else {
                                        echo "Error deleting record: " . $conn->error;
                                    }
                                    exit();
                                }
                                ?> 
                                <script>
                                    var result = confirm('Apakah Anda yakin ingin menghapus data ini?');
                                    if (result) {
                                        window.location.href = "?action=hapus&id=<?= $id ?>";
                                    } else {
                                        window.history.back();
                                    }
                                </script>
                                <?php
                            }
                            die();
                            break;
                        default:
                            header('Location: index.php');
                            break;
                    }
                } ?>
                <h1>Daftar Kopi</h1>
                <a class="aksi tambah" href="?action=tambah">Tambah</a>
                <hr style="border: white;"><hr style="border: white;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kopi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="5"><i>Tidak ada data yang tersedia.</i></td>
                            </tr>
                        <?php endif;
                        ?>
                        <?php 
                        $i = 1;
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['harga'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td>
                                    <a class="aksi edit" href="?action=edit&id=<?= $row['id'] ?>">Edit</a>
                                    <a class="aksi hapus" href="?action=hapus&id=<?= $row['id'] ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php 
                        $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="./script.js"></script>
</body>

</html>