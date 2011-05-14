<link type="text/css" href="<URL>includes/codemirror/codemirror.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/codemirror.js"></script>
<link type="text/css" href="<URL>includes/codemirror/xml/xml.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/xml/xml.js"></script>
<link type="text/css" href="<URL>includes/codemirror/javascript/javascript.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/javascript/javascript.js"></script>
<link type="text/css" href="<URL>includes/codemirror/css/css.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/css/css.js"></script>
<script type="text/javascript" src="<URL>includes/codemirror/htmlmixed/htmlmixed.js"></script>
<style>.CodeMirror {background: #f8f8f8;}</style> 
<script>
$(document).ready(function() {
	%COMMENTHACK%var editor = CodeMirror.fromTextArea(document.getElementById("tplCode"), {mode: "htmlmixed"});
    $("#editTheTplNao").click(function() {
        $("#" + this.id).blur();
        $("#" + this.id).attr("disabled", "disabled");
        var code = $("#tplCode").val();
        $.post("<AJAX>?function=edittpl", { file: "footer", contents: editor.getValue(), __tht_csrf_magic: csrfMagicToken }, function(data){
            if(data == 'Trying to remove the copyright? No thanks.') {
                $.get("<AJAX>?function=notice", { status: "bad", message: data }, function(data2){
                    $("#belowDynamic").slideUp(500, function() {
                        $("#spaceForNotice").html(data2);
                        $("#editTheTplNao").removeAttr("disabled");
                        $("#belowDynamic").slideDown(500);
                    });
                });
            }
            else {
                $.get("<AJAX>?function=notice", { status: "good", message: data }, function(data2){
                    $("#belowDynamic").slideUp(500, function() {
                        $("#spaceForNotice").html(data2);
                        $("#editTheTplNao").removeAttr("disabled");
                        $("#belowDynamic").slideDown(500);
                    });
                });
            }
        });
    });
    $("#refreshButton").click(function() {
        window.location.reload();
    });
});
</script>
<strong>Editing your Footer Template</strong><br />
<p>Want to edit your style in an web based interface? Here it is!<br /><br />
<em>Variables:</em><br /> &lt;PAGEGEN&gt; tag shows the debug stuff.<br />
&lt;COPYRIGHT&gt; shows the THT Copyright. <strong>Don't even attempt to remove it.</strong><br />
<textarea cols="75" style="width:99%; height:300px;" id="tplCode" rows="25" wrap="no" name="edit"%READONLY%>%CSSCONTENT%</textarea>
<div id="belowDynamic">
    <div id="spaceForButton">
        <button id="editTheTplNao"%DISABLED%>Edit Footer</button><button id="refreshButton" style="float: right;">Refresh</button>
    </div>
    <div id="spaceForNotice" style="font-weight: bold; font-style: italic;">
        %NOTICE%
    </div>
</div>
