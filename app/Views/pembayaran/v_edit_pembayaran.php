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
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.5/autoNumeric.min.js" integrity="sha512-EGJ6YGRXzV3b1ouNsqiw4bI8wxwd+/ZBN+cjxbm6q1vh3i3H19AJtHVaICXry109EVn4pLBGAwaVJLQhcazS2w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.5/autoNumeric.js" integrity="sha512-XrQSAJkenc7fNUusjIG2X0/BQvde3lbKScw81XDgLlFRYGG9swBhtu7aiD+9V9VRWKGaPvn9sD5PegKcbogV8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                    <li class="list-group-item"><strong>Ditambahkan Oleh Admin: </strong><?= $pembayaran['admin']; ?></li>
                    <li class="list-group-item"><strong>Status Pembayaran: </strong>
                        <?php
                        if ($pembayaran['status'] == "lunas") { ?>
                            <span class="badge badge-success"><?= ucfirst($pembayaran['status']); ?></span>
                        <?php } else if ($pembayaran['status'] == "cicil") { ?>
                            <span class="badge badge-warning"><?= ucfirst($pembayaran['status']); ?></span>
                        <?php } else { ?>
                            <span class="badge badge-danger"><?= ucfirst($pembayaran['status']); ?></span>
                        <?php } ?>
                    </li>
                    <li class="list-group-item"><button class="btn btn-block btn-danger" onclick="hapusTransaksiPembayaran('<?= encryptID($pembayaran['id']); ?>')"><i class="fa fa-trash-alt"></i> Hapus Transaksi Pembayaran</button></li>
                </ul>
            </div>
        </div><!-- /.card -->
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-user mr-2"></i> Informasi Penyewa</h5>
            </div>
            <div class="card-body">
                <ul class="list-group text-sm">
                    <li class="list-group-item"><strong>Nama Penyewa: </strong><a href="<?= base_url('anggota/edit/' . encryptID($pembayaran['id_anggota'])); ?>" target="_blank"><?= $pembayaran['nama_anggota']; ?></a></li>
                    <li class="list-group-item"><strong>No.Kamar: </strong><?= $pembayaran['kamar']; ?></li>
                    <li class="list-group-item"><strong>Tanggal Mulai Sewa: </strong><?= date("d F Y", strtotime($pembayaran['tanggal_mulai_sewa'])); ?></li>
                    <li class="list-group-item"><strong>Tanggal Pembayaran: </strong><?= date("d F Y H:i:s", strtotime($pembayaran['tanggal'])); ?></li>
                    <li class="list-group-item"><strong>Tipe Pembayaran: </strong><?= ucfirst($pembayaran['tipe_pembayaran']); ?></li>
                    <li class="list-group-item"><strong>Jatuh Tempo: </strong><?= date("d F Y", strtotime($pembayaran['jatuh_tempo'])); ?></li>
                    <li class="list-group-item"><strong>Total Yang Harus Dibayar: </strong>Rp <?= number_format($pembayaran['total_bayar'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Biaya Tambahan: </strong>Rp <?= number_format($pembayaran['total_biaya_tambahan'], 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Dibayar: </strong>Rp <?= number_format($total_dibayar, 0, ",", "."); ?></li>
                    <li class="list-group-item"><strong>Sisa Bayar: </strong>Rp <?= number_format(intval($pembayaran['total_bayar'] - $total_dibayar), 0, ",", "."); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-money-check mr-2"></i> Pelunasan Tagihan Kos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="" id="form-pelunasan">
                            <div class="form-group">
                                <label for="">Masukkan total pembayaran (Rp)</label>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <input type="hidden" id="id_pembayaran" name="id_pembayaran" value="<?= $pembayaran['id']; ?>">
                                            <input type="text" name="bayar" id="bayar" class="form-control" value="0" required min="1000">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-success btnSimpan"><i class="fa fa-money-bill"></i> Pembayaran</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-12">
                        <?php if ($detail_pembayaran) { ?>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tanggal bayar</th>
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
                                        <th colspan="4">Total Yang harus Dibayar</th>
                                        <td class="text-right">Rp <?= number_format($pembayaran['total_bayar'], 0, ",", "."); ?></td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Total Yang Sudah Dibayar</th>
                                        <td class="text-right">Rp <?= number_format(intval($sudah_dibayar), 0, ",", "."); ?></td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Sisa Bayar</th>
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
        })
    });

    function hapusTransaksiPembayaran(id) {
        Swal.fire({
            title: "Hapus Transaksi Pembayaran",
            text: "Apakah anda ingin menhapus transaksi pembayaran saat ini?",
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

    $("#form-pelunasan").submit(function(e) {
        e.preventDefault();
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
                        window.location.href = "<?= base_url($_SERVER['REQUEST_URI']); ?>";
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
</script>
<?= $this->endSection("main"); ?>