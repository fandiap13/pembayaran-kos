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
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h4 class="font-weight-bold">Rp.<?= number_format($total_pemasukan, 0, ",", "."); ?></h4>

                        <p>Total Pemasukan Kos Tahun <?= $tahun; ?></p>
                    </div>
                    <div class="icon">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <a href="<?= base_url('riwayat_pembayaran'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h4 class="font-weight-bold">Rp.<?= number_format($total_pemasukan_tunai, 0, ",", "."); ?></h4>

                        <p>Total Pemasukan (TUNAI) Kos Tahun <?= $tahun; ?></p>
                    </div>
                    <div class="icon">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <a href="<?= base_url('riwayat_pembayaran'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h4 class="font-weight-bold">Rp.<?= number_format($total_pemasukan_transfer, 0, ",", "."); ?></h4>

                        <p>Total Pemasukan (TRANSFER) Kos Tahun <?= $tahun; ?></p>
                    </div>
                    <div class="icon">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <a href="<?= base_url('riwayat_pembayaran'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h4 class="font-weight-bold"><?= $total_admin_kos; ?> Orang</h4>

                        <p>Total Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-tie"></i>
                    </div>
                    <a href="<?= base_url('admin'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h4 class="font-weight-bold"><?= $total_anggota_kos; ?> Orang</h4>

                        <p>Total Anggota Kos Aktif</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?= base_url('anggota'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h4 class="font-weight-bold"><?= $total_kamar_tersedia; ?> Kamar</h4>

                        <p>Total Kamar Tersedia</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-house-user"></i>
                    </div>
                    <a href="<?= base_url('kamar'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-user-tie mr-2"></i> Admin Login</h5>
            </div>
            <div class="card-body">
                <ul>
                    <li><strong>Username: </strong><?= $currUser['username']; ?></li>
                    <li><strong>Nama: </strong><?= $currUser['nama']; ?></li>
                    <li><strong>Waktu Login: </strong><?= date("d F Y H:i:s", strtotime(session("LoggedUserData")['waktu_login'])); ?></li>
                </ul>
            </div>
        </div>
    </div> -->
</div>
<?= $this->endSection("main"); ?>