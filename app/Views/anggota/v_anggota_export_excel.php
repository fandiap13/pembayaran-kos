<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Anggota Kos</title>
    <style>
        table {
            border-collapse: collapse;
            width: auto;
            /* Atur lebar tabel sesuai kebutuhan */
        }

        table,
        th,
        td {
            /* font-size: 10pt; */
            border: 1px solid black;
            padding: 0;
            margin: 0;
        }

        th,
        td {
            padding: 2px;
            text-align: start;
        }
    </style>
</head>

<body>
    <h1>Daftar Anggota Aktif Kos Home Green</h1>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>No.Kamar</th>
                <th>Lantai</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Harga Sewa/bulan (Rp)</th>
                <th>Jenis Sewa</th>
                <th>Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $a) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $a['kamar']; ?></td>
                    <td><?= $a['lantai']; ?></td>
                    <td><?= $a['nama']; ?></td>
                    <td>"<?= $a['telp']; ?>"</td>
                    <td><?= $a['harga']; ?></td>
                    <td><?= $a['jenis_sewa']; ?></td>
                    <td><?= $a['tgl_kost']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>

<?php

// Set header untuk konten Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Daftar Anggota Aktif Kos Home Green.xls");

?>