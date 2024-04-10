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
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Pencarian tahun & Status Anggota</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group" id="searchMode">
                                <select name="tahun" id="tahun" class="form-control">
                                    <?php
                                    $tahun_sekaran = date("Y");
                                    for ($tahun = 2023; $tahun <= $tahun_sekaran; $tahun++) : ?>
                                        <option value="<?= $tahun; ?>" <?= $tahun == date("Y") ? 'selected' : ""; ?>><?= $tahun; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <select name="status" id="status" class="form-control">
                                <option value="all">Tampilkan Semua Anggota</option>
                                <option value="1">Anggota Aktif</option>
                                <option value="0">Anggota Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <button type="submit" id="tampil-pencarian" class="btn btn-block btn-primary"><i class="fa fa-list"></i> Tampilkan</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-block btn-success" onclick="exportRiwayatPembayaran();"><i class="fa fa-file-excel"></i> Export Excel</button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- <div class="table-responsive"> -->
                <table id="data-table" class="table table-sm table-bordered table-hover text-sm">
                    <thead>
                        <tr>
                            <th style="width: 10px;" rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <!-- <th rowspan="2">Status</th> -->
                            <!-- <th rowspan="2">Tanggal Masuk</th> -->
                            <!-- <th rowspan="2">Total Sewa (Rp)</th> -->
                            <th colspan="12" class="text-center">Bulan</th>
                            <th rowspan="2" class="text-center">Total (Rp)</th>
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
                                <th class="text-center"><?= $nama_bulan; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="14" style="text-align:center">Total Keseluruhan (Rp):</th>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <!-- </div> -->
            </div>
        </div><!-- /.card -->
    </div>
</div>

<script>
    var table;
    $(document).ready(function() {
        // console.log($('#tahun').val());
        table = $('#data-table').DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 50,
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('riwayat_pembayaran/riwayat_pembayaran_datatable/'); ?>' + $('#tahun').val(),
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
                    data: 'januari',
                    orderable: false,
                },
                {
                    data: 'februari',
                    orderable: false,
                },
                {
                    data: 'maret',
                    orderable: false,
                },
                {
                    data: 'april',
                    orderable: false,
                },
                {
                    data: 'mei',
                    orderable: false,
                },
                {
                    data: 'juni',
                    orderable: false,
                },
                {
                    data: 'juli',
                    orderable: false,
                },
                {
                    data: 'agustus',
                    orderable: false,
                },
                {
                    data: 'september',
                    orderable: false,
                },
                {
                    data: 'oktober',
                    orderable: false,
                },
                {
                    data: 'november',
                    orderable: false,
                },
                {
                    data: 'desember',
                    orderable: false,
                },
                {
                    data: 'total_bayar',
                },
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                let totalBayar = data.reduce((acc, curr) => acc + parseInt(curr.total), 0);
                let formattedTotalBayar = "Rp " + totalBayar.toLocaleString("id-ID");

                $(api.column(14).footer()).html(formattedTotalBayar);
            }
        });
    });

    function reloadData() {
        if ($("#tahun").val()) {
            table.ajax.url('<?= base_url('riwayat_pembayaran/riwayat_pembayaran_datatable/'); ?>' + $("#tahun").val()).load();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Pilih tahun terlebih dahulu !",
            });
        }
    }

    $('#tampil-pencarian').click(function(e) {
        e.preventDefault();
        reloadData();
    });

    function exportRiwayatPembayaran() {
        const tahun = $("#tahun").val();;
        const status = $("#status").val();

        let printURL = "<?= base_url("riwayat_pembayaran/export") ?>";
        printURL += "?tahun=" + tahun + "&status=" + status;

        window.location.href = printURL;
    }
</script>
<?= $this->endSection("main"); ?>