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

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Backup Database</h5>
    </div>
    <div class="card-body">
        <a href="<?= base_url('utils/backup_database'); ?>" class="btn btn-block btn-primary"><i class="fa fa-database"></i> Klik untuk backup database</a>
        <button onclick="backupAndClearDatabase();" class="btn btn-block btn-danger mt-2"><i class="fa fa-database"></i> Klik untuk backup dan reset database</button>
    </div>
</div>

<script>
    function backupAndClearDatabase() {
        Swal.fire({
            title: 'Backup dan Reset Database',
            text: "Hati - hati, jika anda menjalankan printah ini maka database anda akan terhapus, pertimbangkan dengan baik!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, lakukan!',
            cancelButtonText: 'batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('utils/backup_reset_database'); ?>";
            }
        });
    }
</script>

<?= $this->endSection("main"); ?>