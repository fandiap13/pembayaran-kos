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
                            <a href="<?= base_url('pembayaran'); ?>" class="btn btn-block btn-primary"><i class="fas fa-angle-double-left"></i> Kembali</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <form action="" id="formsave">
                    <input type="hidden" name="id_anggota" value="<?= $anggota['id']; ?>">
                    <input type="hidden" name="id_kamar" id="id_kamar" class="form-control" value="<?= $anggota['id_kamar']; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Nama Penyewa</label>
                                        <input type="text" name="nama" id="nama" class="form-control" style="text-transform: uppercase;" value="<?= $anggota['nama']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kamar">No.Kamar</label>
                                        <input type="text" name="kamar" id="kamar" class="form-control" value="<?= $anggota['kamar']; ?>" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe_pembayaran">Tipe Pembayaran</label>
                                        <input type="text" name="tipe_pembayaran" id="tipe_pembayaran" class="form-control" value="<?= $anggota['jenis_sewa']; ?>" required readonly>
                                        <div class="invalid-feedback error_tipe_pembayaran">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_mulai_sewa">Tanggal Mulai Sewa</label>
                                        <input type="date" name="tanggal_mulai_sewa" id="tanggal_mulai_sewa" class="form-control" value="<?= $anggota['tgl_kost']; ?>" required readonly>
                                        <div class="invalid-feedback error_tanggal_mulai_sewa">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal Pembayaran</label>
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required readonly>
                                        <div class="invalid-feedback error_tanggal">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jatuh_tempo">Jatuh Tempo</label>
                                        <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control" value="<?= $jatuh_tempo; ?>" required readonly>
                                        <div class="invalid-feedback error_jatuh_tempo">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Untuk Pembayaran</label>
                                        <textarea name="keterangan" id="keterangan" rows="2" class="form-control" required>-</textarea>
                                        <div class="invalid-feedback error_keterangan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <!-- <div class="card-header">
                                    <h5 class="card-title m-0">
                                        <button class="btn btn-warning" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button>
                                    </h5>
                                </div> -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="harga">Harga Sewa/Bulan (Rp)</label>
                                        <input type="text" name="harga" id="harga" class="form-control" value="<?= $anggota['harga']; ?>" readonly>
                                        <div class="invalid-feedback error_harga">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="biaya_tambahan">Biaya Tambahan (Rp)</label>
                                        <input type="number" name="biaya_tambahan" id="biaya_tambahan" class="form-control" value="<?= $anggota['biaya_tambahan']; ?>" readonly>
                                        <div class="invalid-feedback error_biaya_tambahan">
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="total_sewa">Total Biaya Sewa (<?= ucfirst($anggota['jenis_sewa']); ?>)</label>
                                        <input type="text" name="total_sewa" id="total_sewa" class="form-control" value="<?= $totalBayar; ?>" readonly>
                                        <div class="invalid-feedback error_total_sewa">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_biaya_tambahan">Biaya Tambahan Jika Ada (<?= ucfirst($anggota['jenis_sewa']); ?>)</label>
                                        <input type="text" name="total_biaya_tambahan" id="total_biaya_tambahan" class="form-control" value="<?= $totalBiayaTambahan; ?>" min="0" required>
                                        <div class="invalid-feedback error_total_biaya_tambahan">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="diskon">Potongan Harga (<?= ucfirst($anggota['jenis_sewa']); ?>)</label>
                                        <input type="text" name="diskon" id="diskon" class="form-control" value="0" min="0" required>
                                        <div class="invalid-feedback diskon">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_bayar">Total Yang Harus Dibayar (Rp)</label>
                                        <input type="text" name="total_bayar" id="total_bayar" class="form-control" value="<?= intval($totalBayar +  $totalBiayaTambahan); ?>" readonly required>
                                        <div class="invalid-feedback error_total_bayar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary btnSimpan"><i class="fa fa-save"></i> Tambahkan Pembayaran</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.card -->
    </div>
</div>

<script>
    var harga = new AutoNumeric('#harga', {
        currencySymbol: 'Rp ',
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0
    });
    var total_sewa = new AutoNumeric('#total_sewa', {
        currencySymbol: 'Rp ',
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0
    });
    var total_biaya_tambahan = new AutoNumeric('#total_biaya_tambahan', {
        currencySymbol: 'Rp ',
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0
    });
    var diskon = new AutoNumeric('#diskon', {
        currencySymbol: 'Rp ',
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0
    });
    var total_bayar = new AutoNumeric('#total_bayar', {
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

    $("#diskon").on("input", function(e) {
        e.preventDefault();
        if (diskon.getNumber() > 0) {
            // ubah total yang harus dibayar
            const total = parseInt(total_biaya_tambahan.getNumber()) + parseInt(total_sewa.getNumber()) - parseInt(diskon.getNumber());
            total_bayar.set(total);
        } else {
            diskon.set(0);
            total_bayar.set(total_sewa.getNumber());
        }
    });

    $("#total_biaya_tambahan").on("input", function(e) {
        e.preventDefault();
        // const diskon = $("#diskon").val();
        // const total_sewa = $("#total_sewa").val();
        // const total_biaya_tambahan = $(this).val();
        if (total_biaya_tambahan.getNumber() > 0) {
            // ubah total yang harus dibayar
            const total = parseInt(total_biaya_tambahan.getNumber()) + parseInt(total_sewa.getNumber()) - parseInt(diskon.getNumber());
            total_bayar.set(total);
        } else {
            total_biaya_tambahan.set(0);
            total_bayar.set(total_sewa.getNumber());
        }
    });

    $("#formsave").submit(function(e) {
        e.preventDefault();
        // Mengambil data dari form menggunakan serialize()
        var formData = $(this).serialize();
        // Membuat objek kosong untuk menyimpan data
        var jsonData = {};
        // Memecah data menjadi array pasangan nama-nilai
        var dataArray = formData.split('&');
        // Iterasi melalui array pasangan nama-nilai
        for (var i = 0; i < dataArray.length; i++) {
            // Memecah setiap pasangan nama-nilai menjadi nama dan nilai
            var pair = dataArray[i].split('=');
            // Menambahkan nama dan nilai ke dalam objek JSON
            jsonData[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }
        jsonData.harga = harga.getNumber();
        jsonData.total_sewa = total_sewa.getNumber();
        jsonData.total_biaya_tambahan = total_biaya_tambahan.getNumber();
        jsonData.diskon = diskon.getNumber();
        jsonData.total_bayar = total_bayar.getNumber();

        // Objek JSON sekarang berisi data dalam format yang diinginkan
        // console.log(jsonData);
        Swal.fire({
            title: "Tambah Pembayaran",
            text: "Apakah anda yakin ingin mencatat pembayaran ini?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, lakukan!",
            cancelButtonText: "batal",
        }).then((result) => {
            if (result.isConfirmed) {
                const url = "<?= base_url("pembayaran/tambah_pembayaran") ?>";
                $.ajax({
                    type: "post",
                    url: url,
                    data: jsonData,
                    dataType: "json",
                    beforeSend: function() {
                        $(".btnSimpan").attr("disabled", true);
                        $(".btnSimpan").html(`<i class="fa fa-spin fa-spinner"></i> Loading`);
                    },
                    success: function(response) {
                        $(".btnSimpan").attr("disabled", false);
                        $(".btnSimpan").html(`<i class="fa fa-save"></i> Tambahkan Pembayaran`);
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
    });
</script>

<?= $this->endSection("main"); ?>