<?php
function terbilang($angka)
{
    $angka = abs($angka);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($angka < 12) {
        $temp = " " . $huruf[$angka];
    } elseif ($angka < 20) {
        $temp = terbilang($angka - 10) . " belas";
    } elseif ($angka < 100) {
        $temp = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $temp = " seratus" . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $temp = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $temp = " seribu" . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $temp = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $temp = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        $temp = terbilang($angka / 1000000000) . " milyar" . terbilang(fmod($angka, 1000000000));
    } elseif ($angka < 1000000000000000) {
        $temp = terbilang($angka / 1000000000000) . " trilyun" . terbilang(fmod($angka, 1000000000000));
    }
    return $temp;
}

function rupiah($angka)
{
    $angka = terbilang($angka);
    return ucwords($angka) . " Rupiah";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }
    </style>

    <style>
        @media print {

            /* Menghilangkan elemen yang tidak diinginkan di bagian atas halaman */
            body {
                margin-top: 0;
                /* Hapus margin atas */
            }

            /* Menghilangkan elemen yang tidak diinginkan di bagian bawah halaman */
            body::after {
                content: "";
                /* Hapus konten setelah body */
                display: block;
                height: 0;
                page-break-after: always;
                /* Gunakan page break setelah body */
            }
        }

        #content {
            margin: 20mm 15mm;
            width: 10cm;
            height: 11cm;
        }

        header,
        footer,
        aside,
        nav,
        form,
        iframe,
        .menu,
        .hero,
        .adslot {
            display: none;
        }

        /* Media query untuk mencetak */
        @media print {
            .noPrint {
                display: none;
            }
        }

        * {
            font-size: 9pt;
            font-family: Arial, Helvetica, sans-serif
        }

        table {
            border-collapse: collapse;
            width: auto;
            /* Atur lebar tabel sesuai kebutuhan */
        }

        table,
        th,
        td {
            /* font-size: 10pt; */
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

<body id="content" onload="print();">
    <table style="width: 100%;">
        <tr>
            <th style="text-align: left; font-size: 12pt; position: relative; width: 20%;">
                <div style="position: absolute; top: 0; left: 0;">
                    KOS PUTRA HOME GREEN
                </div>
            </th>
            <th style="text-align: right;">Jl. Matoa VII No.8, RT.001/RW.007, Karangasem, Kec. Laweyan, Kota Surakarta <br>Jawa Tengah <br> 0817251949</th>
        </tr>
    </table>
    <br>
    <br>
    <div style="text-align: center; border: 1px solid black; font-size: 12pt; font-weight: bold; padding: 10px; background-color: rgba(128, 128, 128, 0.3);">KUITANSI</div>
    <br>
    <br>
    <div style="padding: 10px; border: 1px solid black;">
        <table style="border: none;">
            <tr>
                <th>No.Pembayaran</th>
                <th>:</th>
                <th><?= $pembayaran['no_pembayaran']; ?></th>
            </tr>
            <tr>
                <th>Telah Terima Dari</th>
                <th>:</th>
                <th><?= $pembayaran['nama']; ?></th>
            </tr>
            <tr>
                <th>Uang Sebesar</th>
                <th>:</th>
                <th><?= rupiah($pembayaran['total_bayar']); ?></th>
            </tr>
            <tr>
                <th>Untuk Pembayaran</th>
                <th>:</th>
                <th><?= $pembayaran['keterangan']; ?></th>
            </tr>
            <tr>
                <th>Periode</th>
                <th>:</th>
                <th><?= date("d M Y", strtotime($pembayaran['jatuh_tempo'])); ?> S/D <?= date("d M Y", strtotime("+1 month", strtotime($pembayaran['jatuh_tempo']))); ?></th>
            </tr>
        </table>
        <br>
        <br>
        <div style="display: flex; justify-content: space-between;">
            <table style="width: 100%;">
                <tr>
                    <th rowspan="5" style="width: 60%; text-align: start;">
                        <div style="display: inline-block; border-top: 1px solid black; border-bottom: 1px solid black; padding: 10px;">
                            <div style="display: flex; align-items: center; justify-content: start; gap: 10px; ">
                                <div style="font-size: 12pt;">Rp.</div>
                                <div style="font-size: 20pt;"><?= number_format($pembayaran['total_bayar'], 0, ',', "."); ?></div>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center;">Surakarta, <?= date("d F Y"); ?>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center;">Penerima,</th>
                </tr>
                <tr>
                    <th><br><br><br><br></th>
                </tr>
                <tr>
                    <th style="text-align: center;"><?= $pembayaran['admin']; ?></th>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>