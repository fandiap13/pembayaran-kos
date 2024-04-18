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
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<script src="<?= base_url(); ?>template/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title"><button class="btn btn-warning" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button></h5>
            </div>
            <div class="card-body">
                <table id="data-table" class="table table-sm table-bordered table-hover text-sm">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No</th>
                            <th>No.Kamar</th>
                            <th>Lantai</th>
                            <th>Nama</th>
                            <th>Telp</th>
                            <th>Harga Sewa/bulan (Rp)</th>
                            <th>Biaya Tambahan (Rp)</th>
                            <th>Jenis Sewa</th>
                            <th>Tanggal Masuk</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div><!-- /.card -->
    </div>
</div>

<script>
    var table;
    $(document).ready(function() {
        table = $('#data-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('anggota_tidak_aktif/anggota_tidak_aktif_datatable'); ?>',
            },
            order: [],
            columns: [{
                    data: 'no',
                    orderable: false,
                },
                {
                    data: 'kamar',
                },
                {
                    data: 'lantai'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'telp',
                    orderable: false
                },
                {
                    data: 'harga',
                },
                {
                    data: 'biaya_tambahan',
                },
                {
                    data: 'jenis_sewa',
                },
                {
                    data: 'tgl_kost',
                },
                {
                    data: 'action',
                    orderable: false
                },
            ],
        });
    });

    function restoreData(id) {
        Swal.fire({
            title: "Mengaktifkan Anggota",
            text: "Anda ingin mengaktifkan Anggota ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, aktifkan!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url("anggota/aktifkan_anggota/"); ?>" + id,
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

    function hapusPermanen(id) {
        Swal.fire({
            title: "Hapus Permanen Anggota",
            text: "Hapus permanen anggota dapat menyebabkan riwayat anggota yang dihapus akan terhapus semua !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus permanen!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Peringatan, Hapus Permanen Anggota !",
                    text: "Apakah anda benar - benar yakin !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, yakin!",
                    cancelButtonText: "batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?= base_url("anggota/hapus_permanen/"); ?>" + id,
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
        });
    }
</script>
<?= $this->endSection("main"); ?>