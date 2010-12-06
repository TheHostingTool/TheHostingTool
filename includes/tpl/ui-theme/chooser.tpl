<script type="text/javascript">
$(document).ready(function() {
    $("#changeThemeBtn").click(function() {
        var val = $("#ui-theme").val();
        $("#submitNotice").slideUp(500);
		$("#submitNoticeError").slideUp(500);
        $.post("<AJAX>", { "function": "uiThemeChange", "<CSRF_NAME>": csrfMagicToken, theme: val }, function(data) {
			if(data == "true") {
				$("#submitNotice").slideDown(500);
			}
			else {
				$("#submitNoticeError").slideDown(500);
			}
        });
    });
});
</script>
As of THT 1.2 we've been switching to the jQuery User Interface to provide some
of the more Ajaxy features. The jQuery UI is themeable and you can select your
own theme to use within your THT install.
<br />
%THEME%
<button id="changeThemeBtn">Change Theme</button><br />
<div id="submitNotice" style="display: none;">%NOTICE%</div>
<div id="submitNoticeError" style="display: none; color: red;"><strong><em>An unexpected error has occured.</em></strong></div>