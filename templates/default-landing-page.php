<!DOCTYPE html>
<?php
$kickofflabsLandingPages = new KickofflabsLandingPages();
$foundLandingPage = $kickofflabsLandingPages->findByWordpressPageId( $post->ID );
?>
<html>
<head>
	<title><?php echo $foundLandingPage[ 'page_title' ]; ?></title>
	<?php echo $foundLandingPage[ 'favicon_link' ]; ?>
	<meta content="<?php echo $foundLandingPage[ 'open_graph_title' ]; ?>" property="og:title">
	<meta content="<?php echo $foundLandingPage[ 'open_graph_description' ]; ?>" property="og:description">
	<meta content='<?php echo $foundLandingPage[ 'open_graph_image' ]; ?>' property='og:image'>
	<meta content="<?php echo $foundLandingPage[ 'meta_description' ]; ?>" name="description">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="generator" content="KickoffLabs">
	<meta name="robots" content="index, follow">
	<style>
		*{margin:0;padding:0;}
		html{overflow:hidden;}
		#full_kickoffpage{position:absolute;width:100%;height:100%;}
		iframe{height:100%;width:100%;border:none;}
	</style>
</head>
<body>
<div id="full_kickoffpage">
	<script type="text/javascript">
		var frameSource = document.location.protocol +'//embed.kickoffpages.com/<?php echo $foundLandingPage[ 'page_id' ]; ?>/' + window.location.search
		var ifrm = '<iframe title="<?php echo $foundLandingPage[ 'title' ]; ?>" frameborder="0" src="'+frameSource+'">';
		ifrm += '</iframe>';
		document.write(ifrm);
	</script>
</div>
</body>
</html>
