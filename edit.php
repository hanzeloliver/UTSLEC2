<?php
// Memulai sesi
session_start();
require 'koneksi.php'; 

$message = '';

// Mengambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil data dari database
    $query = "SELECT * FROM biodata_users WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } else {
        $message = "Error mengambil data: " . $conn->error;
    }
} else {
    header("Location: index.php"); // Redirect ke halaman utama jika tidak ada ID yang diberikan
    exit();
}

// Pembaruan data
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
            header("Location: biodataku.php"); // Redirect kembali ke halaman utama setelah pembaruan
            exit();
        } else {
            $message = "Error saat mengupdate data: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $message = "Error saat menyiapkan pernyataan: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <style>
        /* Gaya CSS */
        body { margin: 0; padding: 0; font-family: "Roboto", sans-serif; background-color: #f4f4f4; }
        .container { display: flex; justify-content: center; padding: 80px 20px; }
        .form-wrapper { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 600px; }
        h1 { text-align: center; color: #333; }
        label { display: block; margin: 15px 0 5px; color: #333; }
        input[type="text"], input[type="email"], input[type="date"], select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #5cb85c; color: #fff; border: none; padding: 12px; cursor: pointer; font-size: 16px; transition: background 0.3s; border-radius: 4px; width: 100%; }
        input[type="submit"]:hover { background: #4cae4c; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>Edit Biodata</h1>

            <?php if (!empty($message)): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <label for="fullname">Nama Lengkap</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>

                <label for="email">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

                <label for="nim">NIM</label>
                <input type="text" name="nim" value="<?php echo htmlspecialchars($row['nim']); ?>" required>

                <label for="gender">Jenis Kelamin</label>
                <select name="gender" required>
                    <option value="Laki-laki" <?php echo ($row['gender'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($row['gender'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>

                <label for="birth_place">Tempat Lahir</label>
                <input type="text" name="birth_place" value="<?php echo htmlspecialchars($row['birth_place']); ?>" required>

                <label for="birth_date">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="<?php echo htmlspecialchars($row['birth_date']); ?>" required>

                <label for="hobby">Hobi</label>
                <input type="text" name="hobby" value="<?php echo htmlspecialchars($row['hobby']); ?>" required>

                <label for="program_study">Program Studi</label>
                <input type="text" name="program_study" value="<?php echo htmlspecialchars($row['program_study']); ?>" required>

                <label for="faculty">Fakultas</label>
                <input type="text" name="faculty" value="<?php echo htmlspecialchars($row['faculty']); ?>" required>

                <input type="submit" name="update" value="Update">
            </form>
        </div>
    </div>
</body>
</html>
