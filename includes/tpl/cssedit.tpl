<link type="text/css" href="<URL>includes/codepress/languages/codepress-css.css" rel="stylesheet" id="cp-lang-style" />
	<script type="text/javascript" src="<URL>includes/codepress/codepress.js"></script>
	<script type="text/javascript">
		CodePress.language = 'css';
	</script>
<script>
$(document).ready(function() {
    $("#editTheCssNao").click(function() {
        $("#" + this.id).blur();
        $("#" + this.id).attr("disabled", "disabled");
        var code = cssArea.getCode();
        $.post("<AJAX>?function=editcss", { css: code }, function(data){
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
    <textarea name="css" class="%CODEPRESS%css" id="cssArea" cols="45" rows="5" style="width:99%; height:300px;"%READONLY%>
    %CSSCONTENT%
    </textarea>
<div id="belowDynamic">
    <div id="spaceForButton">
        <button id="editTheCssNao"%DISABLED%>Edit CSS</button><button id="refreshButton" style="float: right;">Refresh</button>
    </div>
    <div id="spaceForNotice" style="font-weight: bold; font-style: italic;">
        %NOTICE%
    </div>
</div>