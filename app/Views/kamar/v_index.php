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
                <h5 class="card-title m-0"><button class="btn btn-warning" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button></h5>

                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <button onclick="add();" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Tambah <?= $title; ?></button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No</th>
                            <th>No.Kamar</th>
                            <th>Lantai</th>
                            <th>Spesifikasi</th>
                            <th>Kategori</th>
                            <th style="width: 15%;">Harga/Bulan (Rp)</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = db_connect();
                        $no = 1;
                        foreach ($data as $key => $value) :
                            $cekForeign = $db->table('tbl_anggota')->where('id_kamar', $value['id'])->where('active', 1)->get()->getRowArray();
                            if ($cekForeign) {
                                $status = "<span class='badge badge-danger'>Tidak tersedia</span>";
                            } else {
                                $status = "<span class='badge badge-success'>Tersedia</span>";
                            }
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="text-center"><?= $value['nama']; ?></td>
                                <td class="text-center"><?= ucfirst($value['lantai']); ?></td>
                                <td><?= $value['spesifikasi']; ?></td>
                                <td><?= $value['kategori']; ?></td>
                                <td class="text-right"><?= number_format($value['harga'], 0, ",", "."); ?></td>
                                <td class="text-center">
                                    <?= $status; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" onclick="edit('<?= $value['id']; ?>')" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                        <?php
                                        if (!$cekForeign) :
                                        ?>
                                            <button type="button" onclick="hapus('<?= $value['id']; ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Hapus</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div><!-- /.card -->
    </div>
</div>

<div class="modal fade myModal" id="modal-default" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open("", [
                'id' => 'formsave',
            ]); ?>
            <div class="modal-body">
                <input type="hidden" name="id_kamar" id="id_kamar">
                <div class="form-group">
                    <label for="nama">No.Kamar</label>
                    <input type="text" name="nama" id="nama" class="form-control">
                    <div class="invalid-feedback error_nama">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lantai">Lantai</label>
                    <select name="lantai" id="lantai" class="form-control">
                        <option value="">--pilih lantai--</option>
                        <option value="atas">Atas</option>
                        <option value="bawah">Bawah</option>
                    </select>
                    <div class="invalid-feedback error_lantai">
                    </div>
                </div>
                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi</label>
                    <textarea name="spesifikasi" id="spesifikasi" rows="3" class="form-control"></textarea>
                    <div class="invalid-feedback error_spesifikasi">
                    </div>
                </div>
                <div class="form-group">
                    <label for="harga">Harga Sewa (Rp) / bulan</label>
                    <input type="number" name="harga" id="harga" class="form-control">
                    <div class="invalid-feedback error_harga">
                    </div>
                </div>
                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-control">
                        <option value="">--pilih kategori--</option>
                        <?php foreach ($kategori as $k) : ?>
                            <option value="<?= $k['id']; ?>"><?= $k['kategori']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback error_id_kategori">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function() {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

    $('#modal-default').on('shown.bs.modal', function() {
        $('#nama').trigger('focus');
    });

    function add() {
        $("#modal-default").modal('show');
        $(".modal-title").html("Tambah <?= $title; ?>");
    }

    function edit(id) {
        $.ajax({
            url: "<?= base_url("kamar/detail/") ?>" + id,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#modal-default").modal('show');
                    $(".modal-title").html("Edit <?= $title; ?>");
                    $("#id_kamar").val(id);
                    $("#nama").val(response.data.nama);
                    $("#lantai").val(response.data.lantai);
                    $("#id_kategori").val(response.data.id_kategori);
                    $("#spesifikasi").val(response.data.spesifikasi);
                    $("#harga").val(response.data.harga);
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
                    url: "<?= base_url("kamar/hapus/"); ?>" + id,
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

    $("#formsave").submit(function(e) {
        e.preventDefault();
        let url;
        if ($("#id_kamar").val()) {
            url = "<?= base_url("kamar/update/") ?>" + $("#id_kamar").val();
        } else {
            url = "<?= base_url("kamar/create") ?>";
        }
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
                    if (response.errors.id_kategori) {
                        $("#id_kategori").addClass("is-invalid");
                        $(".error_id_kategori").html(response.errors.id_kategori);
                    } else {
                        $("#id_kategori").removeClass("is-invalid");
                        $(".error_id_kategori").html("");
                    }
                    if (response.errors.lantai) {
                        $("#lantai").addClass("is-invalid");
                        $(".error_lantai").html(response.errors.lantai);
                    } else {
                        $("#lantai").removeClass("is-invalid");
                        $(".error_lantai").html("");
                    }
                    if (response.errors.nama) {
                        $("#nama").addClass("is-invalid");
                        $(".error_nama").html(response.errors.nama);
                    } else {
                        $("#nama").removeClass("is-invalid");
                        $(".error_nama").html("");
                    }
                    if (response.errors.spesifikasi) {
                        $("#spesifikasi").addClass("is-invalid");
                        $(".error_spesifikasi").html(response.errors.spesifikasi);
                    } else {
                        $("#spesifikasi").removeClass("is-invalid");
                        $(".error_spesifikasi").html("");
                    }
                    if (response.errors.harga) {
                        $("#harga").addClass("is-invalid");
                        $(".error_harga").html(response.errors.harga);
                    } else {
                        $("#harga").removeClass("is-invalid");
                        $(".error_harga").html("");
                    }
                    return;
                }
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
    });
</script>
<?= $this->endSection("main"); ?>