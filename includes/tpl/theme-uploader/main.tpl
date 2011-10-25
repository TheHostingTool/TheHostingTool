You can use this tool to upload a theme of your choice. Please make sure that the name of your zip file is the
alphanumeric name of your theme. Eg: <em>Super & Cool Theme!!</em> should be named <em>SuperCoolTheme.zip</em> The
contents of that zip will be extracted in a new directory of the same name in the themes directory.

<br />So a basic theme zip file would have a hierarchy similar to this:
<pre>
-SuperCoolTheme.zip
---header.tpl
---footer.tpl
---style.css
---images/
-----blah.png
</pre>

So now that you have a good idea of how this works go ahead and upload your theme.<br />
<strong>The max upload size is %MAXSIZE% as determined by your PHP configuration.</strong>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="uploadedTheme" accept="application/zip">
    <input type="submit" value="Upload" />
</form>