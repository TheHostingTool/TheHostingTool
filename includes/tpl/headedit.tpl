<link type="text/css" href="<URL>includes/codemirror/lib/codemirror.css" rel="stylesheet"/>
<script src="<URL>includes/codemirror/lib/codemirror.js"></script>
<script src="<URL>includes/codemirror/mode/javascript/javascript.js"></script>
<script src="<URL>includes/codemirror/mode/xml/xml.js"></script>
<script src="<URL>includes/codemirror/mode/css/css.js"></script>
<script src="<URL>includes/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<style>.CodeMirror {background: #f8f8f8;}</style> 
<script>
$(document).ready(function() {
	%COMMENTHACK%var editor = CodeMirror.fromTextArea(document.getElementById("tplCode"), {mode: "htmlmixed"});
    $("#editTheTplNao").click(function() {
        $("#" + this.id).blur();
        $("#" + this.id).attr("disabled", "disabled");
        var code = $("#tplCode").val();
        var post = { file: "header", contents: editor.getValue() };
        post[csrfMagicName] = csrfMagicToken;
		$.post("<AJAX>?function=edittpl", post, function(data){
            $.get("<AJAX>?function=notice", { status: "good", message: data }, function(data2){
                $("#belowDynamic").slideUp(500, function() {
                    $("#spaceForNotice").html(data2);
                    $("#editTheTplNao").removeAttr("disabled");
                    $("#belowDynamic").slideDown(500);
                });
            });
        });
    });
    $("#refreshButton").click(function() {
        window.location.reload();
    });
});
</script>
<strong>Editing your Header Template</strong><br />
<p>Want to edit your style in an web based interface? Here it is!<br /><br />
<em>Variables:</em><br /> &lt;THT TITLE&gt; tag shows the Title Content.<br />
&lt;JAVASCRIPT&gt; links to all Javascript Scripts in this page<br />
&lt;CSS&gt; links to the CSS Content along with the style tag<br />
&lt;IMG&gt; links to the /images folder of your Styles Directory<br />
&lt;ICONDIR&gt; links to your /themes/icons directory.</p><br /><br />
<textarea cols="75" style="width:99%; height:300px;" id="tplCode" rows="25" wrap="no" name="edit"%READONLY%>%CSSCONTENT%</textarea>
<div id="belowDynamic">
    <div id="spaceForButton">
        <button id="editTheTplNao"%DISABLED%>Edit Header</button><button id="refreshButton" style="float: right;">Refresh</button>
    </div>
    <div id="spaceForNotice" style="font-weight: bold; font-style: italic;">
        %NOTICE%
    </div>
</div>
