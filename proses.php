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
if ($pesan != ""): ?>
    <div class="pesan-info"><?= htmlspecialchars($pesan) ?></div>
<?php endif;  ?>

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