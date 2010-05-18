<script type="text/javascript">
    var divId;
    var dun;
    $(document).ready(function(){
        $("#doIt").click(function(){
            divId = "#" + this.id;
            dun = "#dun";
            this.disabled = "true";
            $(divId).blur();
            $.get("<AJAX>?function=genkey&do=it", function(data){
                $(dun).slideUp(500, function(){
                $(dun).html(data);
                $(dun).slideDown(500);
                });
            });
        });
    });
</script>

Here you can generate a new API Key. Simply push the Generate button.
<br /><br />
<span style="color: red;"><strong>WARNING:</strong> Generating a new key will cause all existing keys to
become invalid. All applications that use the old key will also no longer
function.</span>
<br /><br />
<div id="dun" style="text-align:center;"><button id="doIt">Generate</button></div>