<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Ikhsan" />
	<!-- Stylesheet -->
	<?php echo $_head; ?>
	<style type="text/css">
	.noscript{
		position: fixed;
		top: 0;
		width: 100%;
		text-align: center;
		z-index: 999;
		background: red;
	}
	</style>
</head>

<body>

<noscript>
		<div class="noscript">Mohon untuk menghidupkan javascript di browser anda, sistem tidak dapat bekerja tanpa javascript aktif</div>
</noscript>

<div id="wrapper">
	<div id="header">
		<!-- Header -->
  		<?php echo $_header; ?>
 	</div>
  
	<div id="container">
		<!-- Content -->
		<?php echo $_content; ?>
	</div>
 	<div id="footer">
 		<!-- Footer -->
 		<?php echo $_footer; ?>
 	</div>
</div>

<div class="overlay">
	<div class="overlay-container">
		<div></div>
		<button class="btn btn-cancel"></button>
	</div>
</div>

<?php echo isset($_slider) ? $_slider : ''; ?>

</body>

<!-- Script -->
<script type="text/javascript">
	function base_url(url){
		if(typeof url == 'undefined')
			url = '';
		return ('<?= base_url();?>'+url);
	}
</script>
<?php echo $_script; ?>
</html>