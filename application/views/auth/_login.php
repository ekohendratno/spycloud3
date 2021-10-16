<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>USBK - Ujian Sekolah Berbasis Komputer</title>
    <script src="<?php echo base_url('js/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('js/jquery-ui.js') ?>"></script>
    <script src="<?php echo base_url(); ?>js/sweetalert/sweetalert.min.js"></script>
    <link rel="icon" type="image/ico" href="<?php echo base_url('img/logo.ico') ?>">
    <link rel='dns-prefetch' href='<?php echo base_url();?>' />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('css/jquery-ui.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/teamof-elegant-modal-form.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/login2.css') ?>" rel="stylesheet">
    <script src="<?php echo base_url('js/bootstrap.min.js') ?>"></script>
    <style type="text/css">

    </style>
    <script type="text/javascript">

        function showPassword() {
            $(".toggle-password").toggleClass("glyphicon-eye-close");
            var input = $($(".toggle-password").attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        };

        $(document).on('keypress', 'input', function(e) {

            if(e.keyCode == 13 && e.target.type !== 'submit') {
                e.preventDefault();
                signin();
            }

        });

        function signin() {

            $('.status').empty();
            var username = $('#username').val();
            var password = $('#password').val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>auth/signin',
                data:'username='+username+'&password='+password,
                dataType:'json',
                beforeSend: function () {
                    $("input").attr('disabled','disabled');
                    $("button").attr('disabled','disabled');

                    $('.status').html('<div class="alert alert-warning" role="alert">Loading ...</div>');
                    $('#loading_ajax').show();
                },
                success: function (hasil) {
                    console.log(hasil);

                    $('#loading_ajax').fadeOut("slow");
                    $('.status').html(hasil.pesan);
                    if(hasil.pesan == ''){
                        swal({
                            title: "Berhasil!",
                            text: "Sedang di dialihkan, Tunggu beberapa saat!",
                            icon: "success",
                            button: false,
                        });

                        window.location.assign("<?php echo base_url();?>"+hasil.redirect);
                    }else{
                        $("input").removeAttr('disabled');
                        $("button").removeAttr('disabled');
                    }
                    $('#loading_ajax').fadeOut("slow");
                }
            });
        }
    </script>
</head>

<body>

<div class="container-full">
    <header>
        <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
            <img src="<?php echo base_url();?>img/logo-white.png" width="70px" style="float: left;"/>
            <h2 class="text-light text-left">USBK</h2>
            <h4 class="pt-2 text-left">Ujian Sekolah Berbasis Komputer</h4>
        </div>
    </header>
    <main class="content">

        <div id="loginbox" class="mainbox col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
            <div class="panel panel-default" >
                <div class="panel-body">

                    <div class="of-elegant-modal show">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1 px-0">
                                    <p>
                                    <h3 class="pt-2">Selamat Datang,</h3>
                                    Silahkan login dengan username dan password yang telah anda dapat.
                                    </p>

                                    <div class="status"></div>

                                    <div class="form-group">
                                        <div class="of-input-container">
                                            <div class="of-input-icon"><img src="<?php echo base_url();?>img/mail.svg"></div>
                                            <input type="input" class="form-control" id="username" placeholder="Username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="of-input-container">
                                            <div class="of-input-icon"><img src="<?php echo base_url();?>img/lock.svg"></div>
                                            <input type="password" class="form-control circle" id="password" placeholder="Password">
                                            <span toggle="#password" class="glyphicon glyphicon-eye-open field-icon toggle-password" onclick="showPassword()"></span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button onclick="signin()" type="button" id="btn-tambah" class="btn btn-lg btn-block btn-primary">MASUK</button>
                                    </div>

                                    <br class="clear"/>
                                    <!--<a class="of-signup-link of-toggle-link pb-2" href="#">Don't have an account yet? Sign Up!</a>-->
                                </div> <!--End .col-lg-6 -->
                            </div> <!--End .row -->
                        </div> <!--End .container-fluid -->
                    </div>

                </div>
                <div class="panel-footer">&nbsp;
                </div>

            </div>
        </div>

    </main>
    <footer>
        <div class="inner">
            <p class="text-dark">&copy; 2019. Dikembangkan oleh Eko Hendratno, S.Kom</p>
        </div>
    </footer>
</div>



</body>
</html>
