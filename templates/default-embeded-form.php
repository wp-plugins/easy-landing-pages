<!-- BEGIN KICKOFFLABS EMBED CODE -->
<div id='kol_embed' data-page-id='<?=$attributes[ 'id' ];?>' data-height='<?=$attributes[ 'height' ];?>px'></div>
<script type="text/javascript">
	(function(doc, el) {
		var script_is_loaded = false;
		var s = doc.createElement(el);
		s.src = ('https:' == doc.location.protocol ? 'https://' : 'http://') + 'embed.kickoffpages.com/javascripts/kol_embed.js';
		s.onload = s.onreadystatechange = function() {
			var rs = this.readyState; if (script_is_loaded) return; if (rs) if (rs != 'complete') if (rs != 'loaded')  return;
			script_is_loaded = true;
			try { KOL_Embed_Page.makeFrame(); } catch (e) {}};
		var scr = doc.getElementsByTagName(el)[0], par = scr.parentNode; par.insertBefore(s, scr);
	})(document, 'script');</script>
<!-- END KICKOFFLABS EMBED CODE -->