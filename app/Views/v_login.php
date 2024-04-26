<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN REKAP KOS HOME GREEN</title>
    <!-- remove default favicon -->
    <link rel="icon" href="data:,">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>template/dist/css/adminlte.min.css">
    <style>
        * {
            text-transform: uppercase !important;
        }

        input,
        textarea,
        select {
            text-transform: uppercase;
        }

        .btn {
            text-transform: uppercase !important;
        }

        input.password {
            text-transform: none;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="" class="h4"><b>LOGIN REKAP KOS </b>HOME GREEN</a>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('msg')) {
                    $alert = explode("#", session()->getFlashdata('msg') ?? "danger#Cek jaringan anda!");
                ?>
                    <div class="alert alert-<?= $alert[0]; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php if ($alert[0] == "danger") { ?>
                            <h5><i class="icon fas fa-ban"></i> Terdapat kesalahan!</h5>
                        <?php } else { ?>
                            <h5><i class="icon fas fa-info-circle"></i>Pesan</h5>
                        <?php } ?>
                        <!-- <strong>
                        </strong> -->
                        <?= $alert[1]; ?>
                    </div>
                <?php } ?>


                <?php if (validation_errors()) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Terdapat kesalahan!</h5>
                        <strong><?= validation_list_errors(); ?></strong>
                    </div>
                <?php } ?>

                <form action="" method="post">
                    <?= csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" value="<?= old('username'); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control password" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>template/dist/js/adminlte.min.js"></script>
</body>

</html>