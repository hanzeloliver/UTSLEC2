<?php
// Memulai sesi
session_start();
require 'koneksi.php'; 

$message = '';

// Proses form untuk menambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update'])) {
    // Menangkap data dari form
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $nim = htmlspecialchars($_POST['nim']);
    $gender = htmlspecialchars($_POST['gender']);
    $birth_place = htmlspecialchars($_POST['birth_place']);
    $birth_date = htmlspecialchars($_POST['birth_date']);
    $hobby = htmlspecialchars($_POST['hobby']);
    $program_study = htmlspecialchars($_POST['program_study']);
    $faculty = htmlspecialchars($_POST['faculty']);

    // Query untuk memasukkan data ke dalam database
    $query = "INSERT INTO biodata_users (fullname, email, nim, gender, birth_place, birth_date, hobby, program_study, faculty) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssss", $fullname, $email, $nim, $gender, $birth_place, $birth_date, $hobby, $program_study, $faculty);
        
        if ($stmt->execute()) {
            $message = "Data berhasil disimpan.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Edit data
$row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM biodata_users WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }
}

// Update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $nim = htmlspecialchars($_POST['nim']);
    $gender = htmlspecialchars($_POST['gender']);
    $birth_place = htmlspecialchars($_POST['birth_place']);
    $birth_date = htmlspecialchars($_POST['birth_date']);
    $hobby = htmlspecialchars($_POST['hobby']);
    $program_study = htmlspecialchars($_POST['program_study']);
    $faculty = htmlspecialchars($_POST['faculty']);

    $query = "UPDATE biodata_users SET fullname=?, email=?, nim=?, gender=?, birth_place=?, birth_date=?, hobby=?, program_study=?, faculty=? WHERE id=?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssssi", $fullname, $email, $nim, $gender, $birth_place, $birth_date, $hobby, $program_study, $faculty, $id);
        
        if ($stmt->execute()) {
            $message = "Data berhasil diupdate.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Hapus data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM biodata_users WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Data berhasil dihapus.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Query untuk mengambil data dari tabel
$query_sql = "SELECT * FROM biodata_users";
$result = $conn->query($query_sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <style>
        /* CSS Styles */
        body { margin: 0; padding: 0; font-family: "Roboto", sans-serif; background-color: #f4f4f4; }
        header { position: fixed; background: #22242A; padding: 20px; width: 100%; height: 60px; color: #fff; }
        .left_area h3 { margin: 0; text-transform: uppercase; font-size: 22px; font-weight: 900; }
        .right_area img { float: right; margin-top: -40px; margin-right: 20px; }
        .sidebar { background: #2f323a; margin-top: 100px; padding: 30px 0; position: fixed; left: 0; width: 200px; height: 100%; color: #fff; overflow-y: auto; }
        .sidebar a { color: #fff; display: flex; align-items: center; text-decoration: none; padding: 15px 20px; transition: background 0.5s; }
        .sidebar a:hover { background: #9f2ebb; }
        .container { margin-left: 250px; padding: 80px 20px; display: flex; justify-content: center; }
        .form-wrapper { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 1000px; }
        h1 { text-align: center; color: #333; }
        label { display: block; margin: 15px 0 5px; color: #333; }
        input[type="text"], input[type="email"], input[type="date"], select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #5cb85c; color: #fff; border: none; padding: 12px; cursor: pointer; font-size: 16px; transition: background 0.3s; border-radius: 4px; width: 100%; }
        input[type="submit"]:hover { background: #4cae4c; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; color: #333; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #e0e0e0; }
        .footer { text-align: center; color: #777; position: fixed; left: 0; bottom: 0; width: 100%; background-color: #22242A; color: #e8e8e8; padding: 10px 0; }
    </style>
</head>
<body>
    <header>
        <div class="left_area">
            <h3>Pemrograman <span>Web</span></h3>
        </div>
        <div class="right_area">
            <img src="Logo-UMN-e1634700898276.png" width="50" height="50" alt="Logo UMN">
        </div>
    </header>

    <div class="sidebar">
        <a href="Formbiodataku.html"><i class="fas fa-desktop"></i>Biodataku</a>
    </div>

    <div class="container">
        <div class="form-wrapper">
            <h1>Data Biodata</h1>

            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <table>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>NIM</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Hobi</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                    <th>Aksi</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['fullname']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['nim']) . "</td>
                                <td>" . htmlspecialchars($row['gender']) . "</td>
                                <td>" . htmlspecialchars($row['birth_place']) . "</td>
                                <td>" . htmlspecialchars($row['birth_date']) . "</td>
                                <td>" . htmlspecialchars($row['hobby']) . "</td>
                                <td>" . htmlspecialchars($row['program_study']) . "</td>
                                <td>" . htmlspecialchars($row['faculty']) . "</td>
                                <td>
                                    <a href='edit.php?id=" . $row['id'] . "'>Edit</a> 
                                    <a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Tidak ada data ditemukan.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Biodata Application
    </div>
</body>
</html>
