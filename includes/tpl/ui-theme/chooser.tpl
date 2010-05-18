<script type="text/javascript">
$(document).ready(function() {
    $("#changeThemeBtn").click(function() {
        var val = $("#ui-theme").val();
        $("#submitNotice").slideUp(500);
        $.get("<AJAX>?function=uiThemeChange", { theme: val }, function() {
        $("#submitNotice").slideDown(500);
        });
    });
});
</script>
As of THT 1.2 we've been switching to the jQuery User Interface to provide some
of the more AJAXy features. The jQuery UI is themeable and you can select your
own theme to use within your THT install.
<br />
%THEME%
<button id="changeThemeBtn">Change Theme</button><br />
<div id="submitNotice" style="display: none;">%NOTICE%</div>