<?php
require_once('session.php');
if (file_exists(".credentials") == false) {
    //File .credentials does not exist!!!
    require("error.php");
    die;
}
if (file_exists(".api") == false) {
    header("Location: /settings.php");
    die;
}
$file = file_get_contents('.credentials');
$strings = explode("\n", $file);
$username = $strings[1];
$filename = "dashboard_$username.php";
if (file_exists($filename) == false) {
    //Wrong .credentials file!!!
    require("error.php");
    die();
}
echo '
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
';
require_once('api.php');
require_once("$filename");
echo '<script src="js/api.js"></script>';
?>

<?php
function led(string $device, string $thing, int $size = 32)
{
    if ($GLOBALS['IN_GRID'] == 1) {
        $GLOBALS['GRID_NUM']++;
        echo '<div class="col-sm" style="display:flex;align-items:center;">';
        $margin_top = "0px";
    } else {
        $margin_top = "8px";
    }
    echo ("
    <div class=\"led\" style=\"display:flex;align-items:center;\">
<p id=\"$thing\" data-device=\"$device\" class=\"text-center led text api\">help
</p>
<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"$size\" height=\"$size\" fill=\"white\" class=\"bi bi-lightbulb led true active\" viewBox=\"0 0 16 16\" style=\"margin-left: 12px; margin-top: $margin_top\">
  <path d=\"\"/>
</svg>
</div>
");
    if ($GLOBALS['IN_GRID'] == 1) echo '</div>';
}

function button(string $device, string $thing, $value = '{"in":true}', string $text = "", string $style = "primary")
{
    if ($value == '') $value = '{"in":true}';
    if ($GLOBALS['IN_GRID'] == 1) {
        echo '<div class="col-sm">';
    } else {
        echo '<div class="container-fluid w-100">';
    }
    if ($text == "")
        $text = strtoupper($thing);
    $onclick = 'sendThingerValue("' . $device . '","' . $thing . '",\'' . $value . '\')';
    echo "<button type=\"button\" style=\"height: 100%;box-shadow: 0px 0px 0px 1px white; border-radius: 2px;
    \" class=\"w-100 btn btn-{$style}\" onclick=$onclick>$text</button></div>";
}

function layout(string $type, bool $isfluid = false)
{
    if (isset($GLOBALS['IN_GRID'])) {
        $isstart = $GLOBALS['IN_GRID'];
    } else $isstart = false;
    $class = "";
    if ($isfluid) $class = "-fluid";
    switch ($type) {
        case 'grid':
            if ($isstart == 0) {
                //Start
                echo "<div style=\"margin-top: .3em;\" class=\"container$class\">
                <div class=\"row\">";
                $GLOBALS['IN_GRID'] = true;
                $GLOBALS['GRID_NUM'] = 0;
            } else {
                //End
                echo '</div></div>';
                $GLOBALS['IN_GRID'] = false;
                $GLOBALS['GRID_NUM'] = 0;
            }
            break;

        default:
            break;
    }
}

function status(string $device)
{
    if ($GLOBALS['IN_GRID'] == 1) {
        echo '<div class="col-sm">';
        $GLOBALS['GRID_NUM']++;
    }
    echo ("
        <div class=\"alert alert-secondary status\" id=\"$device\" role=\"alert\">
            <div class=\"row\">
                <div class=\"col\" style=\"display:flex;align-items:center;\">
                    <p class=\"centered\">
                        Status from \"$device\" is loading...
                    </p>
                </div>
                <div class=\"col-7 justify-content-end d-flex\">
                    <div class=\"spinner-border\" role=\"status\">
                        <span class=\"visually-hidden\">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    ");
    if ($GLOBALS['IN_GRID'] == 1) echo '</div>';
}
function temperature(string $device, string $thing, int $maxVal, int $width, bool $unit)
{
    if ($GLOBALS['IN_GRID'] == 1) {
        echo '<div class="col-sm">';
        $GLOBALS['GRID_NUM']++;
    }
    $opts = "opts_";
    $opts .= $thing;
    $textrenderer = "textRenderer_";
    $textrenderer .= $thing;
    $varname = "text_";
    $varname .= $thing;
    $width .= "px";
    switch ($unit) {
        case true:
            $setunit = "°C";
            break;
        case false:
            $setunit = "°F";
            break;
    }
    echo ("
            <div style=\"display:flex;align-items:center; width: $width\" class=\"$thing\">
            <canvas style=\"width: $width\" id=\"$thing\" data-device=\"$device\" class=\"gauge api\">
            </canvas>
            <div class=\"gauge-preview\" id=\"preview-$thing\"></div>
            <script>
                var $opts = {
                    angle: -0.05, // The span of the gauge arc
                    lineWidth: 0.25, // The line thickness
                    radiusScale: 1, // Relative radius
                    pointer: {
                      length: 0.5, // // Relative to gauge radius
                      strokeWidth: 0.035, // The thickness
                      color: '#000000' // Fill color
                    },
                    limitMax: true,     // If false, max value increases automatically if value > maxValue
                    limitMin: true,     // If true, the min value of the gauge will be fixed
                    colorStart: '#6FADCF',   // Colors
                    colorStop: '#8FC0DA',    // just experiment with them
                    strokeColor: '#E0E0E0',  // to see which ones work best for you
                    generateGradient: true,
                    highDpiSupport: true,     // High resolution support
                };
                target = document.getElementById('$thing');
                var $thing = new Gauge(target).setOptions($opts);
                $thing.maxValue = $maxVal;
                $thing.setMinValue(-10);
                $thing.animationSpeed = 10;
                $thing.set(0);

                //Text-Renderer
                var $textrenderer = new TextRenderer(document.getElementById(\"preview-$thing\"));
                $textrenderer.render = function($thing){
                    $varname = $thing.displayedValue
                    this.el.innerHTML = $varname.toFixed(1) + \"$setunit ($thing)\"
                };
                $thing.setTextField($textrenderer);
            </script>
            </div>
    ");
    if ($GLOBALS['IN_GRID'] == 1) echo '</div>';
}

function humidity(string $device, string $thing, int $width)
{
    if ($GLOBALS['IN_GRID'] == 1) {
        echo '<div class="col-sm">';
        $GLOBALS['GRID_NUM']++;
    }
    $maxVal = 100;
    $opts = "opts_";
    $opts .= $thing;
    $textrenderer = "textRenderer_";
    $textrenderer .= $thing;
    $varname = "text_";
    $varname .= $thing;
    $width .= "px";
    echo ("
    <div style=\"display:flex;align-items:center; width: $width\" class=\"$thing\">
            <canvas style=\"width: $width\" id=\"$thing\" data-device=\"$device\" class=\"gauge api\">
            </canvas>
            <div class=\"gauge-preview\" id=\"preview-$thing\"></div>
            <script>
                var $opts = {
                    angle: -0.05, // The span of the gauge arc
                    lineWidth: 0.25, // The line thickness
                    radiusScale: 1, // Relative radius
                    pointer: {
                      length: 0.5, // // Relative to gauge radius
                      strokeWidth: 0.035, // The thickness
                      color: '#000000' // Fill color
                    },
                    limitMax: true,     // If false, max value increases automatically if value > maxValue
                    limitMin: true,     // If true, the min value of the gauge will be fixed
                    colorStart: '#6FADCF',   // Colors
                    colorStop: '#8FC0DA',    // just experiment with them
                    strokeColor: '#E0E0E0',  // to see which ones work best for you
                    generateGradient: true,
                    highDpiSupport: true,     // High resolution support
                };
                target = document.getElementById('$thing');
                var $thing = new Gauge(target).setOptions($opts);
                $thing.maxValue = $maxVal;
                $thing.setMinValue(0);
                $thing.animationSpeed = 10;
                $thing.set(0);

                //Text-Renderer
                var $textrenderer = new TextRenderer(document.getElementById(\"preview-$thing\"));
                $textrenderer.render = function($thing){
                    $varname = $thing.displayedValue
                    this.el.innerHTML = $varname.toFixed(0) + \"% ($thing)\"
                };
                $thing.setTextField($textrenderer);
            </script>
            </div>
    ");
    if ($GLOBALS['IN_GRID'] == 1) echo '</div>';
}
function textValue(string $device, string $thing)
{
    if ($GLOBALS['IN_GRID'] == 1) {
        echo '<div class="col-sm">';
        $GLOBALS['GRID_NUM']++;
    }
    echo ("
<p id=\"$thing\" data-device=\"$device\" class=\"text-center text api\">
Requesting thing[$thing] from API...
</p>
");
    if ($GLOBALS['IN_GRID'] == 1) echo '</div>';
}
?>