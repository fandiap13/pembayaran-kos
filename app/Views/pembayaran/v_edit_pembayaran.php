<?= $this->extend("template_admin"); ?>

<?= $this->section('header'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><?= $title; ?></h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= $title; ?></li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->
<?= $this->endSection('header'); ?>

<?= $this->section("main"); ?>
<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?= base_url(); ?>template/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>libs/autoNumeric.min.js"></script>
<script src="<?= base_url(); ?>libs/autoNumeric.js"></script>


<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/summernote/summernote-bs4.min.css">
<script src="<?= base_url(); ?>template/plugins/summernote/summernote-bs4.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">
                    <button class="btn btn-warning" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button>
                </h5>

                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <a href="<?= base_url('pembayaran'); ?>" class="btn btn-block btn-primary"><i class="fas fa-angle-double-left"></i> Kembali ke pembayaran</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>No.Pembayaran: </strong><?= $pembayaran['no_pembayaran']; ?></li>
                    <li class="list-group-item"><strong>Ditambahkan Oleh Admin: </strong><?= $pembayaran['admin']; ?></li>
                    <li class="list-group-item"><strong>Untuk Pembayaran: </strong><?= $pembayaran['keterangan']; ?></li>
                    <li class="list-group-item"><strong>Status Pembayaran: </strong>
                        <?php
                        if ($pembayaran['status'] == "lunas") { ?>
                            <span class="badge badge-success"><?= ucfirst($pembayaran['status']); ?></span>
                        <?php } else if ($pembayaran['status'] == "cicil") { ?>
                            <span class="badge badge-warning"><?= ucfirst($pembayaran['status']); ?> (Belum lunas)</span>
                        <?php } else { ?>
                            <span class="badge badge-danger"><?= ucfirst($pembayaran['status']); ?> (Belum lunas)</span>
                        <?php } ?>
                    </li>
                </ul>

                <br>

                <button class="btn btn-block btn-success" onclick="cetakKuitansi('<?= encryptID($pembayaran['id']); ?>')"><i class="fa fa-print"></i> Cetak Kuitansi</button>

                <button class="btn btn-block btn-danger" onclick="hapusTransaksiPembayaran('<?= encryptID($pembayaran['id']); ?>')"><i class="fa fa-trash-alt"></i> Hapus Transaksi Pembayaran</button>
            </div>
        </div><!-- /.card -->
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-user mr-2"></i> Informasi Penyewa</h5>
            </div>
            <div class="card-body">
                <ul class="list-group text-sm">
                    <li class="list-group-item"><strong>Nama Penyewa: </strong><a href="<?= base_url('anggota/edit/' . encryptID($pembayaran['id_anggota'])); ?>" target="_blank"><?= $pembayaran['nama_anggota']; ?></a></li>
                    <li class="list-group-item"><strong>No.Kamar: </strong><?= $pembayaran['kamar']; ?></li>
                    <li class="list-group-item"><strong>Biaya Sewa Kamar Perbulan: </strong>Rp <?= number_format($pembayaran['harga'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Tanggal Mulai Sewa: </strong><?= date("d F Y", strtotime($pembayaran['tanggal_mulai_sewa'])); ?></li>
                    <li class="list-group-item"><strong>Tanggal Pembayaran: </strong><?= date("d F Y H:i:s", strtotime($pembayaran['tanggal'])); ?></li>
                    <li class="list-group-item"><strong>Tipe Pembayaran: </strong><?= ucfirst($pembayaran['tipe_pembayaran']); ?></li>
                    <li class="list-group-item"><strong>Jatuh Tempo: </strong><?= date("d F Y", strtotime($pembayaran['jatuh_tempo'])); ?></li>
                    <li class="list-group-item"><strong>Potongan Harga: </strong>Rp <?= number_format($pembayaran['diskon'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Biaya Tambahan: </strong>Rp <?= number_format($pembayaran['total_biaya_tambahan'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Total Yang Harus Dibayar: </strong>Rp <?= number_format($pembayaran['total_bayar'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Dibayar: </strong>Rp <?= number_format($total_dibayar, 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Sisa Bayar: </strong>Rp <?= number_format(intval($pembayaran['total_bayar'] - $total_dibayar), 0, ",", "."); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-sticky-note mr-2"></i> Catatan/Keterangan Tagihan</h5>
                    </div>
                    <div class="card-body">
                        <form action="" id="simpan_keterangan">
                            <input type="hidden" name="id_pembayaran" value="<?= $pembayaran['id']; ?>">
                            <div class="form-group">
                                <label>Untuk pembayaran</label>
                                <input type="text" name="keterangan" class="form-control" value="<?= $pembayaran['keterangan']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Catatan/Keterangan Tagihan Kuitansi</label>
                                <textarea class="form-control summernote" name="keterangan_pembayaran" id="keterangan_pembayaran" rows="2"><?= $pembayaran['keterangan_pembayaran']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-block btn-primary btnSimpanKeterangan"><i class="fa fa-save"></i> Simpan Keterangan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-money-check mr-2"></i> Pelunasan Tagihan Kos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="" id="form-pelunasan">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="hidden" id="id_pembayaran" name="id_pembayaran" value="<?= $pembayaran['id']; ?>">
                                            <div class="form-group">
                                                <label for="bayar">Masukkan total pembayaran (Rp)</label>
                                                <input type="text" name="bayar" id="bayar" class="form-control" value="0" required min="1000">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="tipe_pembayaran">Tipe pembayaran</label>
                                                <select name="tipe_pembayaran" id="tipe_pembayaran" class="form-control" required>
                                                    <option value="tunai">Tunai</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group catatan_pembayaran">

                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary btnSimpan"><i class="fa fa-money-bill"></i> Simpan Pembayaran</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12">
                                <?php if ($detail_pembayaran) { ?>
                                    <table class="table table-sm text-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tanggal bayar</th>
                                                <th scope="col">Tipe Pembayaran</th>
                                                <th scope="col">Catatan</th>
                                                <th scope="col">Dibayar (Rp)</th>
                                                <th scope="col">Dicatat Oleh</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $sudah_dibayar = 0;
                                            foreach ($detail_pembayaran as $p) :
                                                $sudah_dibayar += intval($p['bayar']);
                                            ?>
                                                <tr>
                                                    <th scope="row"><?= $no++; ?></th>
                                                    <td><?= date("d F Y H:i:s", strtotime($p['tanggal'])); ?></td>
                                                    <td><?= ucfirst($p['tipe_pembayaran']); ?></td>
                                                    <td><?= $p['keterangan'] == "" ? "-" : ucfirst($p['keterangan']); ?></td>
                                                    <td><?= number_format($p['bayar'], 0, ",", "."); ?></td>
                                                    <td><?= $p['admin']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" onclick="hapus('<?= encryptID($p['id']); ?>')"><i class="fa fa-trash-alt"></i> Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6">Total Yang harus Dibayar</th>
                                                <td class="text-right">Rp <?= number_format($pembayaran['total_bayar'], 0, ",", "."); ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="6">Total Yang Sudah Dibayar</th>
                                                <td class="text-right">Rp <?= number_format(intval($sudah_dibayar), 0, ",", "."); ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="6">Sisa Bayar</th>
                                                <td class="text-right">Rp <?= number_format(intval($pembayaran['total_bayar'] - $sudah_dibayar), 0, ",", "."); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php } else { ?>
                                    <div class="alert alert-danger">Anda belum melakukan pembayaran!</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var biaya = new AutoNumeric('#bayar', {
        currencySymbol: 'Rp ',
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0
    });

    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // Summernote
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['strikethrough', 'superscript', 'subscript']],
                // ['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['misc', ['undo', 'redo']],
            ],
            height: 100,
        });
    });

    function hapusTransaksiPembayaran(id) {
        Swal.fire({
            title: "Hapus Transaksi Pembayaran",
            text: "Apakah anda ingin menghapus transaksi pembayaran saat ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "GET",
                    url: "<?= base_url("pembayaran/hapus_transaksi_pembayaran/"); ?>" + id,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                            }).then(function() {
                                window.location.href = "<?= base_url('pembayaran'); ?>";
                            });
                        }
                        if (response.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message,
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Terjadi Kesalahan pada sistem !",
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    }

    $("#simpan_keterangan").submit(function(e) {
        e.preventDefault();
        const keterangan = $("#keterangan_pembayaran").val();
        if (!keterangan || keterangan == "") {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Catatan/Keterangan tagihan tidak boleh kosong!",
            });
            return;
        }

        $.ajax({
            type: "post",
            url: "<?= base_url("pembayaran/simpan_keterangan"); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".btnSimpanKeterangan").attr("disabled", true);
                $(".btnSimpanKeterangan").html(`<i class="fa fa-spin fa-spinner"></i> Loading`);
            },
            success: function(response) {
                $(".btnSimpanKeterangan").attr("disabled", false);
                $(".btnSimpanKeterangan").html(`<i class="fa fa-save"></i> Simpan Keterangan`);
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                }
                if (response.error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                }
                if (response.info) {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                // console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi Kesalahan pada sistem !",
                }).then(function() {
                    window.location.reload();
                });
            }
        });
    });

    $("#form-pelunasan").submit(function(e) {
        e.preventDefault();
        const keterangan = $("#keterangan").val();
        const tipe_pembayaran = $("#tipe_pembayaran").val();

        const bayar = biaya.getNumber();
        const id_pembayaran = $("#id_pembayaran").val();
        const total_bayar = "<?= $pembayaran['total_bayar']; ?>";
        const total_dibayar = "<?= $total_dibayar; ?>";
        const sisa_bayar = parseInt(total_bayar) - parseInt(total_dibayar);
        if (!id_pembayaran) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Input pembayaran tidak valid!",
            }).then(function() {
                window.location.reload();
            });
            return;
        }

        if (!bayar || parseInt(bayar) <= 0) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Input pembayaran tidak boleh kosong!",
            });
            return;
        }

        if (parseInt(bayar) > parseInt(total_bayar)) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Uang anda masukkan terlalu banyak! Sisa pembayaran adalah " + sisa_bayar + " Rp !",
            });
            return;
        }

        $.ajax({
            type: "post",
            url: "<?= base_url("pembayaran/tambah_pelunasan"); ?>",
            data: {
                id_pembayaran: $('#id_pembayaran').val(),
                bayar,
                keterangan,
                tipe_pembayaran,
            },
            dataType: "json",
            beforeSend: function() {
                $(".btnSimpan").attr("disabled", true);
                $(".btnSimpan").html(`<i class="fa fa-spin fa-spinner"></i> Loading`);
            },
            success: function(response) {
                $(".btnSimpan").attr("disabled", false);
                $(".btnSimpan").html(`<i class="fa fa-money-bill"></i> Pembayaran`);
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                    }).then(function() {
                        // window.location.href = "<?= base_url($_SERVER['REQUEST_URI']); ?>";
                        window.location.reload();
                    });
                }
                if (response.error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                }
                if (response.info) {
                    Swal.fire({
                        icon: "info",
                        title: "Info",
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                // console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi Kesalahan pada sistem !",
                }).then(function() {
                    window.location.reload();
                });
            }
        });
    });

    function hapus(id) {
        Swal.fire({
            title: "Hapus Pembayaran",
            text: "Anda ingin pembayaran ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "GET",
                    url: "<?= base_url("pembayaran/hapus_detail_pembayaran/"); ?>" + id,
                    data: {
                        id_pembayaran: $("#id_pembayaran").val(),
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                        if (response.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message,
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Terjadi Kesalahan pada sistem !",
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    }

    function cetakKuitansi(id) {
        const tinggi = 750; // konversi dari cm ke piksel, dengan asumsi 1 cm = 29.7 piksel
        const lebar = 600; // konversi dari cm ke piksel, dengan asumsi 1 cm = 21 piksel

        const printURL = "<?= base_url('pembayaran/cetak_kuitansi/'); ?>" + id;
        const windowCetak = window.open(printURL, "", `width=${lebar},height=${tinggi},toolbar=no,menubar=no,scrollbars=yes,resizable=yes,titlebar=no`);
        windowCetak.focus();
    }

    // menampilkan catatan pembayaran untuk melakukan pembayaran
    $("#tipe_pembayaran").change(function(e) {
        e.preventDefault();
        const tipe_pembayaran = $(this).val();
        if (tipe_pembayaran == "transfer") {
            $(".catatan_pembayaran").html(
                `
                <label for="keterangan">Catatan pembayaran</label>
                <textarea rows='2' type="text" name="keterangan" id="keterangan" class="form-control" required></textarea>
                `
            );
        } else {
            $(".catatan_pembayaran").html("");
        }
    });
</script>
<?= $this->endSection("main"); ?>