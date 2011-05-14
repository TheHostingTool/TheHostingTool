<link type="text/css" href="<URL>includes/codemirror/codemirror.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/codemirror.js"></script>
<link type="text/css" href="<URL>includes/codemirror/css/css.css" rel="stylesheet"/>
<script type="text/javascript" src="<URL>includes/codemirror/css/css.js"></script>
<style>.CodeMirror {background: #f8f8f8;}</style> 
<script>
$(document).ready(function() {
	%COMMENTHACK%var editor = CodeMirror.fromTextArea(document.getElementById("cssArea"), {mode: "css"});
	$("#editTheCssNao").click(function() {
		$("#" + this.id).blur();
		$("#" + this.id).attr("disabled", "disabled");
		$.post("<AJAX>?function=editcss", { css: editor.getValue(), __tht_csrf_magic: csrfMagicToken }, function(data){
			$.get("<AJAX>?function=notice", { status: "good", message: data }, function(data2){
				$("#belowDynamic").slideUp(500, function() {
					$("#spaceForNotice").html(data2);
					$("#editTheCssNao").removeAttr("disabled");
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
<strong>Editing your Cascading Style Sheet</strong><br />
<p>Want to edit your style in an web based interface? Here it is!</p>
<p>Variables:<br /> &lt;IMG&gt; tag links to /themes/your_style/images/ directory.</p><br /><br />
    <textarea name="css" id="cssArea" cols="45" rows="5" style="width:99%; height:300px;"%READONLY%>%CSSCONTENT%</textarea>
<div id="belowDynamic">
    <div id="spaceForButton">
        <button id="editTheCssNao"%DISABLED%>Edit CSS</button><button id="refreshButton" style="float: right;">Refresh</button>
    </div>
    <div id="spaceForNotice" style="font-weight: bold; font-style: italic;">
        %NOTICE%
    </div>
</div>
