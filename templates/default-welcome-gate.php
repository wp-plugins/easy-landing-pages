<!DOCTYPE html>
<?php
$kickofflabsWelcomeGate = new KickofflabsWelcomeGate();
$welcomeGateConfig = $kickofflabsWelcomeGate->getConfig();
?>
<html>
<head>
    <title><?php echo $welcomeGateConfig[ 'title' ]; ?></title>
    <meta charset="utf-8">
    <meta name="generator" content="KickoffLabs">
    <meta name="robots" content="index, follow">
    <style>
        *{margin:0;padding:0;}
        html{overflow:hidden;}
        #full_kickoffpage{position:absolute;width:100%;height:100%;}
        iframe{height:100%;width:100%;border:none;}
        p{position:absolute;float:right;text-align:right;right:0px;margin:10px 20px;}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">
        function setRepeatVisitor() {
            var exdate=new Date();//assigns the current date to the variable exDate
            exdate.setTime(exdate.getTime() + (3600000 * 24 * <?php echo $welcomeGateConfig[ 'repeat_visitors_cookie' ]; ?>) );//sets the expiry date
            document.cookie="KOL_repeat_visitor=skip_welcome;expires="+exdate.toGMTString();//sets the cookie
        }
    </script>
</head>
<body>
<div id="full_kickoffpage">
    <p>
        <a href="#" id="skip-welcome-gate"><?php echo $welcomeGateConfig[ 'skip_text' ]; ?></a>
    </p>
    <script type="text/javascript">
        var kol_location = document.location.protocol +'//embed.kickoffpages.com';
        var frameSource = kol_location + '/<?php echo $welcomeGateConfig[ 'page_id' ]; ?>/' + window.location.search
        var ifrm = '<iframe title="<?php echo $welcomeGateConfig[ 'title' ]; ?>" frameborder="0" id="kol_iframe" src="'+frameSource+'">';
        //ifrm += '<a href="http://wp-test-2.kickoffpages.com" title="wp-test">wp-test</a>';
        ifrm += '</iframe>';
        document.write(ifrm);
    </script>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        // Get our iframe
        kol_iframe = document.getElementById( 'kol_iframe' );

        // Make sure we can get a contentWindow
        if( typeof kol_iframe.contentWindow == 'undefined' ) {
            setRepeatVisitor();
            window.location.reload();
        }
        // Make sure we can
        if( typeof kol_iframe.contentWindow.postMessage == 'undefined' ) {
            setRepeatVisitor();
            window.location.reload();
        }

        $('#skip-welcome-gate').click(function(){
            setRepeatVisitor();
            window.location.reload();
        });

		function messageListener(e) {
			if( e.origin !== kol_location) {
				return;
			}
			if( e.data == 'kol_success' ) {
				setRepeatVisitor();
				<?php if( $welcomeGateConfig[ 'after_signup' ] == 'immediate_redirect' ): ?>
				window.location.reload();
				<?php elseif( $welcomeGateConfig[ 'after_signup' ] == 'delay_redirect' ): ?>
				window.setTimeout( function(){window.location.reload()}, 5000 );
				<?php endif; ?>
			}
		}

		if (window.addEventListener){
			addEventListener("message", messageListener, false)
		} else {
			attachEvent("onmessage", messageListener)
		}
    });
</script>
</body>
</html>