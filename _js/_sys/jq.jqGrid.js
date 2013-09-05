// we make it simple as possible
function jqGridInclude()
{
    var pathtojsfiles = "_js/"; // need to be ajusted
    // if you do not want some module to be included
    // set include to false.
    // by default all modules are included.
    var minver = false;
    var modules = [
        { include: true, incfile:'jq.jqGrid.base.js',minfile: 'jq.jqGrid.base,min.js'}, // jqGrid base
        { include: true, incfile:'jq.jqGrid.formedit.js',minfile: 'jq.jqGrid.formedit.min.js' }, // jqGrid Form editing
        { include: true, incfile:'jq.jqGrid.inlinedit.js',minfile: 'jq.jqGridgrid.inlinedit.min.js' }, // jqGrid inline editing
        { include: true, incfile:'jq.jqGrid.subgrid.js',minfile: 'jq.jqGrid.subgrid.min.js'}, //jqGrid subgrid
        { include: true, incfile:'jq.jqGrid.custom.js',minfile: 'jq.jqGrid.custom.min.js'}, //jqGrid custom 
        { include: true, incfile:'jq.jqGrid.postext.js',minfile: 'jq.jqGrid.postext.min.js'} //jqGrid postext
    ];
    for(var i=0;i<modules.length; i++)
    {
        if(modules[i].include === true) {
        	if (minver !== true) IncludeJavaScript(pathtojsfiles+modules[i].incfile,CallMe);
        	else IncludeJavaScript(pathtojsfiles+modules[i].minfile,CallMe);
        }
    }
    function CallMe() {
        return true;
    }
    function IncludeJavaScript(jsFile,oCallback)
    {
        var oHead = document.getElementsByTagName('head')[0];
        var oScript = document.createElement('script');
        oScript.type = 'text/javascript';
        oScript.src = jsFile;
        oHead.appendChild(oScript);
        // most browsers
        oScript.onload = oCallback;
        // IE
        oScript.onreadystatechange = function() {
            if (this.readyState == 'loaded' || this.readyState == 'complete') {
                oCallback();
            }
        };
        return false;
    }
}

jqGridInclude();
