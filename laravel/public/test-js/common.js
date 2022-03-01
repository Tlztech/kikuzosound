
function getUrlVars()
{
    var vars = [], hash; 
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&'); 
    for(var i = 0; i < hashes.length; i++) { 
        hash = hashes[i].split('='); 
        vars.push(hash[0]); 
        vars[hash[0]] = hash[1]; 
    } 
    return vars;
} 


var contents_type = DEFAULT_CONTENTS;

var url_vars = getUrlVars();
if (url_vars['type']=='lung') {
    contents_type = 'lung';
}

$("link[rel='manifest']").attr('href', 'manifest-' + contents_type + '.json');

console.log('contents_type: ' + contents_type);