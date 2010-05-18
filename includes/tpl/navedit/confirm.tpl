<script type="text/javascript">
//Kevin doing things the JavaScript/jQuery way. ;)
$(document).ready(function() {
    var currentLocation = window.location.toString();
    $('#kthx').click(function() {
        var currentLocationArray = currentLocation.split('&confirm');
        window.location = currentLocationArray[0] + '&confirm=1';
    });
    $('#knunthx').click(function() {
        var kthxSplit = currentLocation.split('&action');
        window.location = kthxSplit[0];
    });
});
</script>
<p><strong>Are you sure you want to delete this NavBar link?</strong></p>
<div align="center"><button id="kthx">Yes</button>&nbsp;<button id="knunthx">No</button></div>