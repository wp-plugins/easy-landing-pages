<!DOCTYPE html>
<?php
$kickofflabsLandingPages = new KickofflabsLandingPages();
$foundLandingPage = $kickofflabsLandingPages->findByWordpressPageId( $post->ID );
?>
<html>
<head>
    <title><?php echo $foundLandingPage[ 'title' ]; ?></title>
    <meta charset="utf-8">
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
        var ifrm = '<iframe title="<?php echo $foundLandingPage[ 'page_title' ]; ?>" frameborder="0" src="'+frameSource+'">';
        ifrm += '</iframe>';
        document.write(ifrm);
    </script>
</div>
</body>
</html>