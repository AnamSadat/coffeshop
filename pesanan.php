<?php include "koneksi.php";

$getId = (isset($_GET["id"]) ? "WHERE kopi.id = {$_GET["id"]}" : '');
$result = $conn->query("SELECT pesanan.id_pesanan, pelanggan.nama_pelanggan, kopi.nama 
                        AS nama_kopi, pesanan.jumlah_pesanan, pesanan.waktu_pesan 
                        FROM pesanan
                        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
                        JOIN kopi ON pesanan.id_kopi = kopi.id");

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
                            ?> <h1>Tambah Pelanggan</h1> <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $nama_pelanggan = $_POST["nama_pelanggan"];
                                $id_kopi = $_POST["id_kopi"];
                                $jumlah_pesanan = $_POST["jumlah_pesanan"];
                            
                                $sql_check_pelanggan = "SELECT id_pelanggan FROM pelanggan WHERE nama_pelanggan = '$nama_pelanggan'";
                                $result_check_pelanggan = $conn->query($sql_check_pelanggan);
                                if ($result_check_pelanggan->num_rows > 0) {
                                    $row_pelanggan = $result_check_pelanggan->fetch_assoc();
                                    $id_pelanggan = $row_pelanggan["id_pelanggan"];
                                } else {
                                    $sql_insert_pelanggan = "INSERT INTO pelanggan (nama_pelanggan) VALUES ('$nama_pelanggan')";
                                    if ($conn->query($sql_insert_pelanggan) === TRUE) {
                                        $id_pelanggan = $conn->insert_id;
                                    } else {
                                        echo "Error: " . $sql_insert_pelanggan . "<br>" . $conn->error;
                                    }
                                }
                            
                                $sql_get_stok_id = "SELECT stok_id FROM kopi WHERE id = $id_kopi";
                                $result_get_stok_id = $conn->query($sql_get_stok_id);
                                if ($result_get_stok_id->num_rows > 0) {
                                    $row_stok_id = $result_get_stok_id->fetch_assoc();
                                    $stok_id = $row_stok_id["stok_id"];
                            
                                    $sql_get_stok = "SELECT jumlah FROM stok WHERE id = $stok_id";
                                    $result_get_stok = $conn->query($sql_get_stok);
                                    if ($result_get_stok->num_rows > 0) {
                                        $row_stok = $result_get_stok->fetch_assoc();
                                        $stok_kopi = $row_stok["jumlah"];
                            
                                        if ($stok_kopi >= $jumlah_pesanan) {
                                            $sql_tambah_pesanan = "INSERT INTO pesanan (id_pelanggan, id_kopi, jumlah_pesanan) VALUES ($id_pelanggan, $id_kopi, $jumlah_pesanan)";
                                            if ($conn->query($sql_tambah_pesanan) === TRUE) {
                                                $sisa_stok = $stok_kopi - $jumlah_pesanan;
                                                $sql_update_stok = "UPDATE stok SET jumlah = $sisa_stok WHERE id = $stok_id";
                                                if ($conn->query($sql_update_stok) === TRUE) {
                                                    echo "<script>alert('Pesanan berhasil ditambahkan dan stok kopi diperbarui.');window.location = 'pesanan.php';</script>";
                                                } else {
                                                    echo "<script>alert('Gagal memperbarui stok kopi.')</script>";
                                                }
                                            } else {
                                                echo "<script>alert('Gagal menambahkan pesanan.')</script>";
                                            }
                                        } else {
                                            echo "<script>alert('Stok kopi tidak mencukupi untuk pesanan ini.')</script>";
                                        }
                                    } else {
                                        echo "<script>alert('Stok tidak ditemukan.')</script>";
                                    }
                                } else {
                                    echo "<script>alert('Kopi tidak ditemukan.')</script>";
                                }
                            }
                            
                            ?>
                            <table style="width: 600px;">
                                <tbody>
                                    <form action="" method="post">
                                        <tr>
                                            <td>Nama Pelanggan</td>
                                            <td>
                                            <input type="text" name="nama_pelanggan" class="form-control" style="width: 100%;" placeholder="Masukan nama pelanggan" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Kopi</td>
                                            <td>
                                                <select name="id_kopi" class="form-control" style="width: 100%;" required>
                                                    <option selected>Pilih</option>
                                                <?php
                                                $sql_kopi = "SELECT id, nama FROM kopi";
                                                $result_kopi = $conn->query($sql_kopi);
                                                while ($row_kopi = $result_kopi->fetch_assoc()) {
                                                    echo "<option value='" . $row_kopi['id'] . "'>" . $row_kopi['nama'] . "</option>";
                                                }
                                                ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Pesan</td>
                                            <td>
                                                <input type="number" name="jumlah_pesanan" required placeholder="1">
                                            </td>
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
                            ?> <h1>Edit Pesanan</h1> <?php
                            if (($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"]) && isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $getKopi = "SELECT * FROM kopi";
                                $getPesanan ="SELECT pesanan.id_pesanan, pelanggan.id_pelanggan, pelanggan.nama_pelanggan, kopi.nama AS nama_kopi, pesanan.jumlah_pesanan, pesanan.waktu_pesan, pesanan.id_kopi FROM pesanan
                                JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
                                JOIN kopi ON pesanan.id_kopi = kopi.id
                                WHERE pesanan.id_pesanan = $id";

                                $resultPesanan = $conn->query($getPesanan)->fetch_assoc();
                                $resultKopi = $conn->query($getKopi);
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    $nama_pelanggan = $_POST['nama_pelanggan'];
                                    $id_kopi = $_POST['nama_kopi'];
                                    $jumlah_pesanan = $_POST['jumlah_pesanan'];
                            
                                    $updateQueryPesanan  = "UPDATE pesanan SET id_kopi={$id_kopi}, jumlah_pesanan={$jumlah_pesanan} WHERE id_pesanan={$id}";
                                    $updateQueryPelanggan = "UPDATE pelanggan SET nama_pelanggan='$nama_pelanggan' WHERE id_pelanggan={$resultPesanan['id_pelanggan']}";

                                    if ($conn->query($updateQueryPesanan) && $conn->query($updateQueryPelanggan)) {
                                        echo "<script>alert('Data berhasil diedit !');window.location = 'pesanan.php';</script>";
                                    } else {
                                        echo "<script>alert('Data gagal diedit !')</script>";
                                    }
                                }
                                ?>
                                <table style="width: 600px;">
                                    <tbody>
                                        <form action="pesanan.php?action=edit&id=<?= $id ?>" method="post">
                                            <tr>
                                                <td>Nama Pelanggan</td>
                                                <td>
                                                    <input type="hidden" name="id" value="<?= $resultPesanan['id_pesanan'] ?>">
                                                    <input type="text" name="nama_pelanggan" value="<?= $resultPesanan['nama_pelanggan'] ?>" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nama Kopi</td>
                                                <td>
                                                    <select name="nama_kopi" id="nama_kopi">
                                                        <?php while($row = $resultKopi->fetch_assoc()):
                                                            ?>
                                                            <option value="<?= $row['id'] ?>" <?= $row['id'] == $resultPesanan['id_kopi'] ? "selected" : '' ?>>
                                                                <?= $row['nama'] ?>
                                                            </option> 
                                                            <?php
                                                        endwhile ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah</td>
                                                <td><input type="number" name="jumlah_pesanan" value="<?= $resultPesanan['jumlah_pesanan'] ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal</td>
                                                <td><input type="text" value="<?= $resultPesanan['waktu_pesan'] ?>" required disabled></td>
                                            </tr>
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
                                    $query = "DELETE FROM pesanan WHERE id_pesanan = $id";
                            
                                    if ($conn->query($query)) {
                                        ?><script>alert('Data pesanan berhasil dihapus.'); 
                                        window.location.href = 'pesanan.php';</script> <?php
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
                <h1>Daftar Pesanan</h1>
                <a class="aksi tambah" href="?action=tambah">Tambah</a>
                <hr style="border: white;"><hr style="border: white;">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Kopi</th>
                            <th>Jumlah Pesanan</th>
                            <th>Waktu Pesanan</th>
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
                                <td><?= $row['nama_pelanggan'] ?></td>
                                <td><?= $row['nama_kopi'] ?></td>
                                <td><?= $row['jumlah_pesanan'] ?></td>
                                <td><?= $row['waktu_pesan'] ?></td>
                                <td>
                                    <a class="aksi edit" href="?action=edit&id=<?= $row['id_pesanan'] ?>">Edit</a>
                                    <a class="aksi hapus" href="?action=hapus&id=<?= $row['id_pesanan'] ?>">Hapus</a>
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