<?php

$url = current_url(true);
$currentUrl = strtolower($url->getSegment(1));

$db = db_connect();
$currUser = $db->table('tbl_admin')->where('id', decryptID(session("LoggedUserData")['id_admin']))
    ->get()->getRowArray();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REKAP KOS HOME GREEN</title>

    <!-- remove default favicon -->
    <link rel="icon" href="data:,">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/summernote/summernote-bs4.min.css">
    <!-- jQuery -->
    <script src="<?= base_url(); ?>template/plugins/jquery/jquery.min.js"></script>

    <style>
        * {
            text-transform: uppercase !important;
        }

        input,
        textarea,
        select {
            text-transform: uppercase;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini text-sm">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="<?= base_url(); ?>template/#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-danger logout" href="#">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
                <span class="brand-text font-weight-light text-lg">REKAP KOST HOME GREEN</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fa fa-user-circle fa-2x mr-2 text-white"></i>
                    </div>
                    <div class="info">
                        <a href="" class="d-block"><?= $currUser['nama']; ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url("dashboard"); ?>" class="nav-link <?= $currentUrl == "" || $currentUrl == "dashboard" ? 'active' : ""; ?>">
                                <i class=" nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin'); ?>" class="nav-link  <?= $currentUrl == "admin" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>
                                    Admin Kos
                                </p>
                            </a>
                        </li>

                        <li class="nav-item <?= $currentUrl == "kamar" || $currentUrl == "kategori" ? 'menu-open' : ""; ?>">
                            <a href="<?= base_url(); ?>" class="nav-link <?= $currentUrl == "kamar" || $currentUrl == "kategori" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-house-user"></i>
                                <p>
                                    Data Kamar
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('kategori'); ?>" class="nav-link <?= $currentUrl == "kategori" ? 'active' : ""; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kategori Kamar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('kamar'); ?>" class="nav-link <?= $currentUrl == "kamar" ? 'active' : ""; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kamar Kos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item <?= $currentUrl == "anggota" || $currentUrl == "anggota_tidak_aktif" ? 'menu-open' : ""; ?>">
                            <a href="<?= base_url(); ?>" class="nav-link <?= $currentUrl == "anggota" || $currentUrl == "anggota_tidak_aktif" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Data Anggota Kos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('anggota'); ?>" class="nav-link  <?= $currentUrl == "anggota" ? 'active' : ""; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Anggota Kos Aktif
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('anggota_tidak_aktif'); ?>" class="nav-link  <?= $currentUrl == "anggota_tidak_aktif" ? 'active' : ""; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Anggota Kos Tidak Aktif
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('pembayaran'); ?>" class="nav-link  <?= $currentUrl == "pembayaran" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-money-bill"></i>
                                <p>
                                    Transaksi Pembayaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('riwayat_pembayaran'); ?>" class="nav-link  <?= $currentUrl == "riwayat_pembayaran" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Riwayat Pembayaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">
                            <hr style="border-bottom: 1px solid #a9a9a9; opacity: .3;" />
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('utils'); ?>" class="nav-link  <?= $currentUrl == "utils" ? 'active' : ""; ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Utils
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link logout">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <?= $this->renderSection('header'); ?>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?= $this->renderSection("main"); ?>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                <a href="https://fandiap13.github.io/portofolio_new" class="font-weight-bold" target="_blank">
                    Developer
                </a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2024-<?= date("Y"); ?> <a href="<?= base_url(); ?>">KOST HOME GREEN</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>template/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= base_url(); ?>template/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Summernote -->
    <script src="<?= base_url(); ?>template/plugins/summernote/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function() {
            // showing alert
            <?php $alert = session()->getFlashData("msg") ?>
            <?php if (!empty($alert)) : ?>
                <?php $alert = explode("#", $alert) ?>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                setTimeout(function() {
                    Toast.fire({
                        icon: "<?php echo $alert[0] ?>",
                        title: "<?php echo $alert[1] ?>"
                    });
                }, 500);
            <?php endif ?>
        });

        $('.myModal').on('hidden.bs.modal', function() {
            window.location.reload();
        });

        // logout
        $(".logout").click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Log Out",
                text: "Apakah anda ingin logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, lakukan!",
                cancelButtonText: "batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('logout'); ?>";
                }
            });
        });
    </script>
</body>

</html>