<?php
define('SITE', 'info');
define('TITLE', 'Info');
require_once('includes/header.php');
require_once('includes/parsedown-1.7.4/Parsedown.php');
?>
<style>
    .parentcontainer {
        font-size: 1.2rem;
        color: white;
    }

    .parentcontainer a {
        text-decoration: none !important;
    }

    body {
        background-color: #1e262e;
    }

    article {
        border-radius: 5px;
        padding: .2em .4em;
        background-color: rgba(255, 235, 235, 0.1);
    }
    h1 {
        text-align: unset !important;
    }
</style>

<?php
$Parsedown = new Parsedown();
$file = file_get_contents('README.md');
echo '<article>';
echo $Parsedown->text($file);
echo '</article>';
?>

<?php
require_once('includes/footer.php');
?>