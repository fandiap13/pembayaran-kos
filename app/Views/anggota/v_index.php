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

                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <a href="<?= base_url('anggota/tambah'); ?>" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Tambah <?= $title; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Pencarian Tanggal Mulai Kos</label>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <select name="lantai" id="lantai" class="form-control">
                                    <option value="">--pilih lantai--</option>
                                    <option value="atas">Atas</option>
                                    <option value="bawah">Bawah</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <button type="submit" id="tampil-pencarian" class="btn btn-block btn-primary"><i class="fa fa-list"></i> Tampilkan</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <a href="<?= base_url('anggota/export_excel'); ?>" type="button" class="btn btn-block btn-success"><i class="fa fa-file-excel"></i> Export Excel</a>
                            </div>
                        </div>
                    </div>
                </div>

                <table id="data-table" class="table table-sm table-bordered table-hover text-sm">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No</th>
                            <th>No.Kamar</th>
                            <th>Lantai</th>
                            <th>Nama</th>
                            <th>Telp</th>
                            <th>Harga Sewa/bulan (Rp)</th>
                            <th>Jenis Sewa</th>
                            <th>Tanggal Masuk</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var table;
    $(document).ready(function() {
        table = $('#data-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('anggota/anggota_datatable'); ?>',
                data: function(e) {
                    e.tanggal_awal = $('#tanggal_awal').val();
                    e.tanggal_selesai = $('#tanggal_selesai').val();
                    e.lantai = $('#lantai').val();
                }
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

    function edit(id) {
        window.location.href = '<?= base_url('anggota/edit/'); ?>' + id;
    }

    function hapus(id) {
        Swal.fire({
            title: "Nonaktifkan Anggota",
            text: "Anda ingin menonaktifkan anggota kos ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, lakukan!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url("anggota/hapus/"); ?>" + id,
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

    $('#tampil-pencarian').click(function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
</script>
<?= $this->endSection("main"); ?>