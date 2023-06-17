<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
    <title>TicketFactory - Editor de Correos</title>
    <meta content="Best Free Open Source Responsive Newsletter Builder" name="description">
    <link rel="stylesheet" href="css/grapes.min.css"> 
    <link rel="stylesheet" href="css/material.css">
    <link rel="stylesheet" href="css/tooltip.css">
	<link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/toastr.min.css">
    <link rel="stylesheet" href="css/grapesjs-preset-newsletter.css?v=0.2.15">
    <link rel="stylesheet" href="css/tickets.css?v2">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

	<!-- favicons
    ================================================== -->
    <link rel="shortcut icon" href="https://www.ticketfactory.cl/new/favico.png" type="image/x-icon">
    <link rel="icon" href="https://www.ticketfactory.cl/new/favico.png" type="image/x-icon">
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/aviary.js?rnd=<?php echo rand();?>"></script>
	<script src="js/loading.js?rnd=<?php echo rand();?>"></script>
    <script src="js/grapes.min.js?rnd=<?php echo rand();?>"></script>
    <script src="https://grapesjs.com/js/ckeditor/ckeditor.js?rnd=<?php echo rand();?>"></script>
    <script src="js/grapesjs-plugin-ckeditor.min.js?rnd=<?php echo rand();?>"></script>
    <script src="js/grapesjs-preset-newsletter.min.js?rnd=<?php echo rand();?>"></script>
    <script src="js/grapesjs-aviary.min.js?rnd=<?php echo rand();?>"></script>
    <script src="js/toastr.min.js?rnd=<?php echo rand();?>"></script>
    <script src="js/ajaxable.min.js?rnd=<?php echo rand();?>"></script>
	<script src="js/tickets.js?rnd=<?php echo rand();?>"></script>
	<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	
  </head>

  <body>


    <div id="gjs" style="height:0px; overflow:hidden">
    </div>
	<script>
		// Evento que asociado a los correos
		eventoID = <?php echo $_GET["idevent"];?>;
	</script> 
	<?php include 'sections/logo.php';?>
	<?php include 'sections/tags.php';?>
    <?php include 'sections/mails.php';?>
	<?php include 'sections/tpl.php';?>
	<?php include 'sections/save.php';?>
  </body>
</html>
