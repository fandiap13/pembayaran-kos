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
                            <a href="<?= base_url('anggota'); ?>" class="btn btn-block btn-primary"><i class="fas fa-angle-double-left"></i> Kembali</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <form action="" id="formsave">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" style="text-transform: uppercase;">
                        <div class="invalid-feedback error_nama">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telp">No. Telp/WA</label> <span class="text-sm ml-2 text-red font-italic">*contoh: 6285234777851</span>
                        <input type="number" name="telp" id="telp" class="form-control">
                        <div class="invalid-feedback error_telp">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telp_kerabat">No. Telp/WA (Orang Tua/Kerabat)</label> <span class="text-sm ml-2 text-red font-italic">*contoh: 6285234777851</span>
                        <input type="number" name="telp_kerabat" id="telp_kerabat" class="form-control">
                        <div class="invalid-feedback error_telp_kerabat">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-control"></textarea>
                        <div class="invalid-feedback error_alamat">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_kamar">Kamar Kost Yang Tersedia</label>
                        <select name="id_kamar" id="id_kamar" class="form-control select2bs4">
                            <option value="">--pilih kamar--</option>
                            <?php foreach ($kamar as $k) : ?>
                                <option value="<?= $k['id']; ?>"><?= $k['nama']; ?> - (Rp <?= number_format($k['harga'], 0, ",", "."); ?> / bulan)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback error_id_kamar">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tgl_kost">Tanggal Mulai Kost</label>
                        <input type="date" name="tgl_kost" id="tgl_kost" class="form-control">
                        <div class="invalid-feedback error_tgl_kost">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jenis_sewa">Jenis Sewa</label>
                        <select name="jenis_sewa" id="jenis_sewa" class="form-control">
                            <option value="bulanan">Per bulan</option>
                            <option value="3 bulan">Per 3 bulan</option>
                            <option value="1 tahun">Per 1 tahun</option>
                        </select>
                        <div class="invalid-feedback jenis_sewa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="biaya_tambahan">Biaya Tambahan</label>
                        <input type="number" name="biaya_tambahan" id="biaya_tambahan" class="form-control" value="0">
                        <div class="invalid-feedback error_biaya_tambahan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="2" class="form-control">-</textarea>
                        <div class="invalid-feedback error_keterangan">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="reset" class="btn btn-block btn-danger clearError"><i class="fas fa-sync-alt"></i> Kosongkan Form</button>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div><!-- /.card -->
    </div>
</div>

<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    $(".clearError").click(function(e) {
        $("#nama").removeClass("is-invalid");
        $(".error_nama").html("");
        $("#tgl_lahir").removeClass("is-invalid");
        $(".error_tgl_lahir").html("");
        $("#telp").removeClass("is-invalid");
        $(".error_telp").html("");
        $("#telp_kerabat").removeClass("is-invalid");
        $(".error_telp_kerabat").html("");
        $("#alamat").removeClass("is-invalid");
        $(".error_alamat").html("");
        $("#tgl_kost").removeClass("is-invalid");
        $(".error_tgl_kost").html("");
        $("#biaya_tambahan").removeClass("is-invalid");
        $(".error_biaya_tambahan").html("");
        $("#keterangan").removeClass("is-invalid");
        $(".error_keterangan").html("");
        $("#id_kamar").removeClass("is-invalid");
        $(".error_id_kamar").html("");
        $("#jenis_sewa").removeClass("is-invalid");
        $(".error_jenis_sewa").html("");
    });

    $("#formsave").submit(function(e) {
        e.preventDefault();
        const url = "<?= base_url("anggota/create") ?>";
        $.ajax({
            type: "post",
            url: url,
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".btnSimpan").attr("disabled", true);
                $(".btnSimpan").html(`<i class="fa fa-spin fa-spinner"></i> Loading`);
            },
            success: function(response) {
                $(".btnSimpan").attr("disabled", false);
                $(".btnSimpan").html(`<i class="fa fa-save"></i> Simpan`);
                if (response.errors) {
                    if (response.errors.nama) {
                        $("#nama").addClass("is-invalid");
                        $(".error_nama").html(response.errors.nama);
                    } else {
                        $("#nama").removeClass("is-invalid");
                        $(".error_nama").html("");
                    }
                    if (response.errors.tgl_lahir) {
                        $("#tgl_lahir").addClass("is-invalid");
                        $(".error_tgl_lahir").html(response.errors.tgl_lahir);
                    } else {
                        $("#tgl_lahir").removeClass("is-invalid");
                        $(".error_tgl_lahir").html("");
                    }
                    if (response.errors.telp) {
                        $("#telp").addClass("is-invalid");
                        $(".error_telp").html(response.errors.telp);
                    } else {
                        $("#telp").removeClass("is-invalid");
                        $(".error_telp").html("");
                    }
                    if (response.errors.telp_kerabat) {
                        $("#telp_kerabat").addClass("is-invalid");
                        $(".error_telp_kerabat").html(response.errors.telp_kerabat);
                    } else {
                        $("#telp_kerabat").removeClass("is-invalid");
                        $(".error_telp_kerabat").html("");
                    }
                    if (response.errors.alamat) {
                        $("#alamat").addClass("is-invalid");
                        $(".error_alamat").html(response.errors.alamat);
                    } else {
                        $("#alamat").removeClass("is-invalid");
                        $(".error_alamat").html("");
                    }
                    if (response.errors.tgl_kost) {
                        $("#tgl_kost").addClass("is-invalid");
                        $(".error_tgl_kost").html(response.errors.tgl_kost);
                    } else {
                        $("#tgl_kost").removeClass("is-invalid");
                        $(".error_tgl_kost").html("");
                    }
                    if (response.errors.biaya_tambahan) {
                        $("#biaya_tambahan").addClass("is-invalid");
                        $(".error_biaya_tambahan").html(response.errors.biaya_tambahan);
                    } else {
                        $("#biaya_tambahan").removeClass("is-invalid");
                        $(".error_biaya_tambahan").html("");
                    }
                    if (response.errors.keterangan) {
                        $("#keterangan").addClass("is-invalid");
                        $(".error_keterangan").html(response.errors.keterangan);
                    } else {
                        $("#keterangan").removeClass("is-invalid");
                        $(".error_keterangan").html("");
                    }
                    if (response.errors.id_kamar) {
                        $("#id_kamar").addClass("is-invalid");
                        $(".error_id_kamar").html(response.errors.id_kamar);
                    } else {
                        $("#id_kamar").removeClass("is-invalid");
                        $(".error_id_kamar").html("");
                    }
                    if (response.errors.jenis_sewa) {
                        $("#jenis_sewa").addClass("is-invalid");
                        $(".error_jenis_sewa").html(response.errors.jenis_sewa);
                    } else {
                        $("#jenis_sewa").removeClass("is-invalid");
                        $(".error_jenis_sewa").html("");
                    }
                    return;
                }
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                    }).then(function() {
                        window.location.href = "<?= base_url("anggota"); ?>";
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
    });
</script>
<?= $this->endSection("main"); ?>