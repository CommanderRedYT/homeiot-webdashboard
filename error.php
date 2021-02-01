<?
require_once('session.php');
?>
<style>
    .parentcontainer {
    font-size: 125%;
    }
</style>
<h2>An error has occurred!</h2>
This could be caused by:
<br>
<ol>
    <li>A wrong username in the file <code>.credentials</code>.</li>
    <li>The file <code>"dashboard_<b><i>USERNAME</i></b>.php"</code> either doesn't exist or the <code><b><i>USERNAME</i></b></code>-Part in the filename has the wrong Username inside.
</ol>