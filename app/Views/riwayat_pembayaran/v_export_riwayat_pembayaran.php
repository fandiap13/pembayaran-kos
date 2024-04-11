<?php

use App\Controllers\RiwayatPembayaran;
use App\Models\DetailPembayaranModel;

$riwayatC = new RiwayatPembayaran();
$detailPembayaranModel = new DetailPembayaranModel();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <style>
        table {
            border-collapse: collapse;
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
        }
    </style>
</head>

<body>
    <h1><?= $title; ?></h1>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Penyewa</th>
                <th colspan="12" style="text-align: center;">Bulan (<?= $tahun; ?>)</th>
                <th rowspan="2">Total (Rp)</th>
            </tr>
            <tr>
                <?php
                $bulan = array(
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'Mei',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Agu',
                    9 => 'Sept',
                    10 => 'Okt',
                    11 => 'Nov',
                    12 => 'Des'
                );
                foreach ($bulan as $index => $nama_bulan) { ?>
                    <th style="text-align: center;"><?= $nama_bulan; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $total = 0;
            foreach ($riwayat as $row) :
            ?>
                <tr>
                    <td style="text-align: center;"><?= $i++; ?></td>
                    <td>
                        <ul>
                            <li>
                                <strong>Nama Penyewa: </strong><?= $row->nama; ?>
                            </li>
                            <li><strong>No.Kamar: </strong><?= $row->kamar; ?></li>
                            <li><strong>Tanggal Mulai: </strong><?= date("d-m-Y", strtotime($row->tgl_kost)); ?></li>
                            <li><strong>Telp: </strong><?= $row->telp; ?></li>
                            <li><strong>Jenis Sewa: </strong><?= ucfirst($row->jenis_sewa); ?></li>
                            <li><strong>Status Penyewa: </strong><?= ($row->active == 0 ? 'Tidak aktif' : 'Aktif'); ?></li>
                        </ul>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-1'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-2'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-3'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-4'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-5'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-6'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-7'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-8'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-9'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-10'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-11'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: left;">
                        <?php
                        $bulan = date("Y-m", strtotime($tahun . '-12'));
                        echo $riwayatC->cekPembayaranPerBulanNoStyle($row, $bulan);
                        ?>
                    </td>
                    <td style="text-align: right;">
                        <?php
                        $total_bayar = $detailPembayaranModel->totalDibayarByIDAnggota($row->id_anggota, $tahun);
                        $total += $total_bayar;
                        echo "Rp. " . number_format($total_bayar, 0, ",", ".");
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="14" style="text-align:center">Total Keseluruhan (Rp)</th>
                <td style="text-align: right;">Rp. <?= number_format($total, 0, ",", "."); ?></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>

<?php

// Set header untuk konten Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $title . ".xls");

?>