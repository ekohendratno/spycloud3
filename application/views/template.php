<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title><?php echo $title?></title>


    <script src="<?php echo base_url('js/jquery.min.js') ?>"></script>	
    <script src="<?php echo base_url('js/jquery-ui.js') ?>"></script>	
    <script src="<?php echo base_url('js/bootstrap-tagsinput.min.js') ?>"></script>	
    <script src="<?php echo base_url('js/jquery-ui-timepicker-addon.min.js') ?>"></script>	
	<link href="<?php echo base_url('css/AdminLTE.css') ?>" rel="stylesheet">
	<script src="<?php echo base_url(); ?>js/adminlte.js"></script>
    <script defer src="<?php echo base_url(); ?>js/fontawesome/js/all.js"></script>
    <script src="<?php echo base_url(); ?>js/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo base_url('js/bootstrap.min.js') ?>"></script>

    <link rel="icon" type="image/ico" href="<?php echo base_url('img/logo.png') ?>"><link rel='dns-prefetch' href='<?php echo base_url();?>' />
	
	<link href="<?php echo base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/jquery-ui.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/bootstrap-tagsinput.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/jquery-ui-timepicker-addon.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/timeline.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('css/quotes.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/custom.css') ?>" rel="stylesheet">
	<style type="text/css">
		/*body{
			background: #ddd url("<?php echo base_url('img/bg3.png') ?>") right center repeat;
		}*/
      	.navbar-inverse{background-color:  #3c4b59;}
		
		.inset {
		  width: 32px;
		  height: 32px;
		  border-radius: 50%;
		  margin-top: 3px;
            margin-left: 0px;
            margin-right: 0px;
		  background-color: transparent !important;
		  z-index: 999;
		}

		.inset img {
		  border-radius: inherit;
		  width: inherit;
		  height: inherit;
		  display: block;
		  position: relative;
		  z-index: 998;
		}


        .control-sidebar {
            top: 0;
            right: -300px;
            width: 300px;
        }

    </style>
	<style id="jsbin-css">
		.navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus, .navbar-inverse .navbar-nav>.open>a:hover{
			background: transparent;
			    color: #fff;
			
		}
		@media (min-width:768px) { 

		  .nav-bg {
				height: 0px;
				width: 100%;
				position: absolute;
				top: 50px;
				background: #fff;
			  	-webkit-box-shadow: 0px 3px 3px 0px rgba(0,0,0,0.09);
			  -moz-box-shadow: 0px 3px 3px 0px rgba(0,0,0,0.09);
			  box-shadow: 0px 3px 3px 0px rgba(0,0,0,0.09);
			}

			.menu-open .nav-bg { height: 50px } /* change to your height of the child menu */

		}

		.navbar-nav.nav > li { position: static }

		.navbar-nav.nav .dropdown-menu {
			left: 0 !important;
			right: 0 !important;
			box-shadow: none;
			border: none;
			margin: 0 auto;
			max-width: 1170px;
			background: transparent;
			padding: 0;
		}

		.navbar-nav.nav .dropdown-menu > li { float: left }

		.navbar-nav.nav .dropdown-menu > li > a {
			width: auto !important;
			background: transparent;
			line-height: 49px;
			padding-top: 0;
			padding-bottom: 0;
			margin: 0;
		}
	}



	</style>

	<script type="text/javascript">var base_url = "<?php echo base_url(); ?>";</script>
</head>
<body id="body">
	<div id="loading_ajax"><center style="padding:20px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div>
	
	<nav class="navbar <?php if($this->uri->segment(3) == 'mulai'){?>navbar-ujian<?php }else{?>navbar-inverse<?php }?> navbar-fixed-top" role="navigation">
		<div class="container <?php if($this->session->userdata('level') == 'siswa'){?>container-small<?php }else{?>container-medium<?php }?>">

			<?php if($this->uri->segment(2) == 'dashboard'){?>
                <a class="navbar-brand" href="<?php echo base_url() . $this->session->userdata('level') ;?>/dashboard" title="Mata Mata">
                    Tracking
                </a>
            <?php }?>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php if($this->uri->segment(2) != 'dashboard'){?>
					<li><a href="<?php echo base_url() . $this->session->userdata('level') ;?>/dashboard"><span class="glyphicon glyphicon-arrow-left"></span></a></li>
					<?php }?>
					<?php if($this->session->userdata('level') == 'admin'){?>
                    <li><a href="<?php echo base_url();?>admin/phone"title="Phone"><span class="glyphicon glyphicon-phone"></span></a></li>
                    <li><a href="<?php echo base_url();?>admin/commander"title="Data Master"><span class="glyphicon glyphicon-save"></span></a></li>
                        <li><a href="<?php echo base_url();?>admin/files"title="Files"><span class="glyphicon glyphicon-file"></span></a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Aplikasi"><span class="fas fa-rocket"></span>
                            <span class="caret" style="display: none"></span></a>
                        <ul class="container container-medium dropdown-menu">
                            <li><a href="<?php echo base_url();?>admin/camera" title="Camera">Camera</a></li>
                            <li><a href="<?php echo base_url();?>admin/gps" title="GPS">GPS</a></li>
                            <li><a href="<?php echo base_url();?>admin/contact" title="Contact">Contact</a></li>
                            <li><a href="<?php echo base_url();?>admin/sms" title="SMS">SMS</a></li>
                            <li><a href="<?php echo base_url();?>admin/call" title="Call">Call</a></li>
                            <li><a href="<?php echo base_url();?>admin/calendar" title="Calendar">Calendar</a></li>
                            <li><a href="<?php echo base_url();?>admin/history" title="history">History</a></li>
                        </ul>
                    </li>

					<?php }?>
				</ul>
				<ul class="nav navbar-nav navbar-right">

					<?php if($this->session->userdata('level') == 'admin'){?>
					<li><a href="<?php echo base_url().'admin/pesan'; ?>" title="Pesan"><span class="fas fa-comment-dots"<?php if($this->uri->segment(2) == 'pesan'){?> active<?php }?>></span></a></li>
					<li><a href="<?php echo base_url().'admin/pengaturan'; ?>" title="Pengaturan"><span class="fas fa-cog<?php if($this->uri->segment(2) == 'pengaturan'){?> active<?php }?>"></span></a></li>
					<?php }elseif($this->session->userdata('level') == 'guru'){?>
					<li><a href="<?php echo base_url().'guru/pesan'; ?>" title="Pesan"><span class="fas fa-comment-dots<?php if($this->uri->segment(2) == 'pesan'){?> active<?php }?>"></span></a></li>
					<li><a href="<?php echo base_url().'guru/pengaturan'; ?>" title="Pengaturan"><span class="fas fa-cogs<?php if($this->uri->segment(2) == 'pengaturan'){?> active<?php }?>"></span></a></li>
					<?php }elseif($this->session->userdata('level') == 'siswa'){?>
                    <li><a href="<?php echo base_url().'siswa/pengaturan'; ?>" title="Pengaturan"><span class="fas fa-cogs<?php if($this->uri->segment(2) == 'pengaturan'){?> active<?php }?>"></span></a></li>
                    <?php }?>
                    <li><a href="javascript:void(0);" title="Logout"><span class="fas fa-power-off" onclick="aksiLogout()"></span></a></li>

                    <li class="namaimg">
                        <a href="<?php echo base_url().'auth/profile';?>" title="Hallo, <?php echo $this->session->userdata('username');?>">
                            <div class="inset">
                                <img src="<?php echo $this->session->userdata('foto');?>">
                            </div>
                        </a>
                    </li>
                </ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

		<!-- /
		<aside class="main-sidebar control-sidebar control-sidebar-light control-sidebar-open">
							<ul class="nav nav-pills nav-stacked">			
							<li><a href="<?php echo base_url();?>admin/dashboard"><span class="fas fa-home"></span></a></li>
							<li><a href="<?php echo base_url();?>admin/soal"><span class="fas fa-blackboard"></span></a></li>
							<li><a href="<?php echo base_url();?>admin/ujian"><span class="fas fa-bullhorn"></span></a></li>
							</ul>
		</aside> -->
<?php
echo $contents 
?>
	
	<footer>
        <div class="text-center">
          &copy; 2019. Dikembangkan oleh Eko Hendratno, S.Kom
        </div>
    </footer>
    <script type="text/javascript">


        <?php if($this->uri->segment(2) != 'dashboard'){?>
        $('.navbar-right').attr("style", "display:none;");
        $('.panel-title-button').attr("style", "display:block; margin-top:10px; margin-right:15px;");
        $('.panel-title-button').detach().prependTo( $('#bs-example-navbar-collapse-1') );
        //$('.panel-heading').remove();
        <?php }?>


        $('#silit').click(function() {
            var side = $(this).attr('class');
            if(side=="left-btn"){
                $('.toggler .glyphicon').removeClass('.glyphicon glyphicon-chevron-left');
                $('.toggler .glyphicon').addClass('.glyphicon glyphicon-chevron-right');
                $(this).removeClass('left-btn');
                $(this).addClass('right-btn');
                $('.toggler').css('right','300px');
            }else{
                $('.toggler .glyphicon').removeClass('.glyphicon glyphicon-chevron-right');
                $('.toggler .glyphicon').addClass('.glyphicon glyphicon-chevron-left');
                $(this).removeClass('right-btn');
                $(this).addClass('left-btn');
                $('.toggler').css('right','0px');
            }
        });




        $( '.navbar' ).append( '<span class="nav-bg"></span>' );

        $('.dropdown-toggle').click(function () {

          if (!$(this).parent().hasClass('open')) {

             $('html').addClass('menu-open');

          } else {

             $('html').removeClass('menu-open');


          }

        });


        $(document).on('click touchstart', function (a) {
                if ($(a.target).parents().index($('.navbar-nav')) == -1) {
                        $('html').removeClass('menu-open');
                }
        });

        function aksiLogout() {
            swal({
                title: "Keluar?",
                text: "Kamu yakin mau keluar?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.assign("<?php echo base_url();?>auth/logout");
                    }

                });
        }

    </script>
</body>
</html>
