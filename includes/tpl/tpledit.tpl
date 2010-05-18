<script type="text/javascript">
$(document).ready(function() {
    $('#tplSelect').change(function() {
        var currentLocation = window.location.toString();
        var currentLocationArray = currentLocation.split('&');
        var newLocation = currentLocationArray[0] + '&sub=edit' + this.value;
        window.location = newLocation;
    });
});
</script>
<strong>Editing Templates</strong>
<p>This is an advanced feature for you to edit specific templates in your THT Installation. This is NOT recommended for beginners
unless you have enough HTML knowledge to do so. Its always recommended to have a backup of the templates before editing.</p>
<strong>Select a template to edit:</strong>
<select name="tpl" id="tplSelect">
    <option value="null" disabled="disabled" selected="selected">Choose an template to edit</option>
    <option value="header">header.tpl (Your Theme's Header Template)</option>
    <option value="footer">footer.tpl (Your Theme's Footer Template)</option>
</select>