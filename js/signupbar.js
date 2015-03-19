(function(doc, el) {
    var s = doc.createElement(el);
    s.src = ('https:' == doc.location.protocol ? 'https:' : 'http:') + '//api.kickofflabs.com/bar/v1/signupbar.js';
    var scr = doc.getElementsByTagName(el)[0], par = scr.parentNode; par.insertBefore(s, scr);
})(document, 'script');