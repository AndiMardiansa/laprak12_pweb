<?php
$namaFile = "data_mahasiswa.txt";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama    = trim($_POST["nama"] ?? "");
    $nim     = trim($_POST["nim"] ?? "");
    $prodi   = trim($_POST["prodi"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $alamat  = trim($_POST["alamat"] ?? "");

    if ($nama == "" || $nim == "" || $email == "") {
        $pesan = "Nama, NIM, dan Email wajib diisi!";
    } else {
        $baris = implode("|", [
            date("Y-m-d H:i:s"),
            str_replace("|", " ", $nama),
            str_replace("|", " ", $nim),
            str_replace("|", " ", $prodi),
            str_replace("|", " ", $email),
            str_replace(["|", "\r\n", "\n"], [" ", " ", " "], $alamat)
        ]);

        $file = fopen($namaFile, "a");
        fwrite($file, $baris . "\n");
        fclose($file);

        $pesan = "Data mahasiswa berhasil disimpan!";
    }
}

$daftarMahasiswa = [];
if (file_exists($namaFile)) {
    $file = fopen($namaFile, "r");
    while (!feof($file)) {
        $baris = fgets($file);
        if (trim($baris) != "") {
            $daftarMahasiswa[] = explode("|", trim($baris));
        }
    }
    fclose($file);
    $daftarMahasiswa = array_reverse($daftarMahasiswa);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpan Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #12c2e9, #c471ed, #f64f59);
            margin: 0;
            padding: 0;
            padding-bottom: 60px;
        }

        .judul-wrapper {
            text-align: center;
            padding: 20px;
        }

        .judul-teks {
            font-size: 24px;
            font-family: "Courier New", Courier, monospace;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 58%;
        }

        td {
            padding: 10px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        input[type="submit"],
        input[type="reset"] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="reset"] {
            background-color: #f44336;
        }

        .pesan-info {
            width: 58%;
            margin: 0 auto 20px auto;
            background: #e9f7ef;
            border: 1px solid #b7e1c1;
            color: #1e5631;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        .hasil-wrapper {
            width: 58%;
            margin: 30px auto 0 auto;
        }

        .hasil-judul {
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .kartu-mahasiswa {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.15);
            text-align: left;
        }

        .kartu-mahasiswa .nama {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .kartu-mahasiswa .waktu {
            font-size: 12px;
            color: #888;
            float: right;
        }

        .kartu-mahasiswa .baris-info {
            margin-top: 8px;
            font-size: 14px;
            color: #555;
        }

        .kartu-mahasiswa .label {
            font-weight: bold;
            color: #333;
        }

        .kartu-mahasiswa .deskripsi {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #444;
        }

        .kosong {
            color: #fff;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="judul-wrapper">
        <span class="judul-teks">FORM DATA MAHASISWA</span>
    </div>

    <?php if ($pesan != ""): ?>
        <div class="pesan-info"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <form name="form1" method="post" action="index.php">
        <table border="0">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td><input type="text" name="nama" id="nama"></td>
            </tr>

            <tr>
                <td>NIM</td>
                <td><input type="text" name="nim" id="nim"></td>
            </tr>

            <tr>
                <td>Prodi</td>
                <td><input type="text" name="prodi" id="prodi"></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><input type="text" name="email" id="email"></td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td><textarea name="alamat" id="alamat" rows="3"></textarea></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" name="Submit" value="Kirim">
                    <input type="reset" name="Submit2" value="Batal">
                </td>
            </tr>
        </table>
    </form>

    <div class="hasil-wrapper">
        <div class="hasil-judul">Data Mahasiswa Tersimpan (<?= count($daftarMahasiswa) ?>)</div>

        <?php if (count($daftarMahasiswa) == 0): ?>
            <p class="kosong">Belum ada data yang disimpan.</p>
        <?php else: ?>
            <?php foreach ($daftarMahasiswa as $m): ?>
                <?php
                $waktu  = $m[0] ?? "";
                $nama   = $m[1] ?? "";
                $nim    = $m[2] ?? "";
                $prodi  = $m[3] ?? "";
                $email  = $m[4] ?? "";
                $alamat = $m[5] ?? "";
                ?>
                <div class="kartu-mahasiswa">
                    <span class="waktu"><?= htmlspecialchars($waktu) ?></span>
                    <div class="nama"><?= htmlspecialchars($nama) ?></div>
                    <div class="baris-info">
                        <span class="label">NIM:</span> <?= htmlspecialchars($nim) ?> &nbsp;|&nbsp;
                        <span class="label">Prodi:</span> <?= htmlspecialchars($prodi) ?>
                    </div>
                    <div class="baris-info">
                        <span class="label">Email:</span> <?= htmlspecialchars($email) ?>
                    </div>
                    <?php if ($alamat != ""): ?>
                        <div class="deskripsi">
                            <span class="label">Alamat:</span> <?= htmlspecialchars($alamat) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>+