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
                <h5 class="card-title"><button class="btn btn-warning" onclick="reloadData();"><i class="fas fa-sync-alt"></i> Refresh</button></h5>

                <!-- <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                        </li>
                    </ul>
                </div> -->
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group" id="searchMode">
                                <label for="bulan">Pencarian Pembayaran Kos</label>
                                <input type="month" class="form-control" id="bulan" name="bulan" value="<?= date("Y-m"); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Pencarian Status Pembayaran Kos</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Tampilkan Semua</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="cicil">Dicicil</option>
                                    <option value="proses">Proses belum selesai</option>
                                    <option value="belum bayar">Belum bayar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Tipe Pembayaran Kos</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <select name="jenis_sewa" id="jenis_sewa" class="form-control">
                                    <option value="bulanan">Perbulan</option>
                                    <option value="3 bulan">3 bulan</option>
                                    <option value="1 tahun">1 tahun</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <button type="submit" id="tampil-pencarian" class="btn btn-block btn-primary"><i class="fa fa-list"></i> Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <table id="data-table" class="table table-sm table-bordered table-hover text-sm">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No</th>
                            <th>Nama</th>
                            <th>Telp</th>
                            <th>No.Kamar</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Bayar</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                url: '<?= base_url('pembayaran/pembayaran_datatable/'); ?>' + $('#bulan').val() +
                    '/' + $('#jenis_sewa').val(),
                data: function(e) {
                    e.status = $('#status').val();
                }
            },
            order: [],
            columns: [{
                    data: 'no',
                    orderable: false,
                },
                {
                    data: 'nama'
                },
                {
                    data: 'telp',
                    orderable: false
                },
                {
                    data: 'kamar',
                },
                {
                    data: 'tgl_kost',
                },
                {
                    data: 'tgl_pembayaran',
                    orderable: false
                },
                {
                    data: 'jatuh_tempo',
                    orderable: false
                },
                {
                    data: 'status',
                    orderable: false
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
            title: "Hapus Kamar",
            text: "Anda ingin menghapus kamar ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
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

    function reloadData() {
        if ($("#bulan").val()) {
            table.ajax.url('<?= base_url('pembayaran/pembayaran_datatable/'); ?>' + $("#bulan").val() + '/' + $("#jenis_sewa").val()).load();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Pilih bulan terlebih dahulu !",
            });
        }
    }

    $('#tampil-pencarian').click(function(e) {
        e.preventDefault();
        // console.log($('#bulan').val());
        reloadData();
    });

    $("#jenis_sewa").change(function(e) {
        e.preventDefault();
        // console.log($('#bulan').val());
        // $("#searchMode").html(`
        //     <input type="month" class="form-control" id="bulan" name="bulan" value="<?= date("Y-m"); ?>">
        // `);
        // const jenis_sewa = $(this).val();
        // if (jenis_sewa == "1 tahun") {
        //     flatpickr("#bulan", {
        //         dateFormat: "Y", // Format tanggal hanya tahun
        //         minDate: "today", // Batasi tahun terendah ke tahun saat ini
        //         maxDate: new Date().getFullYear() + 10 // Batasi tahun tertinggi ke 10 tahun dari sekarang
        //     });
        //     $("#bulan").attr("type", 'year');
        // } else {
        //     $("#bulan").attr("type", 'month');
        // }
        reloadData();
    });
</script>
<?= $this->endSection("main"); ?>