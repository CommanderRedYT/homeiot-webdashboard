# Web-UI

## General
  
This project is a web-dashboard that interfaces with the API of [Thinger.io](https://thinger.io/).
  It works on any apache instance (PHP module required). Or you could just use `php -S IP:80`.
  ***Required PHP Version > 7.3***

----------
 ## Usage (Server-Side)

- Steps to create your own UI:
    1. Duplicate the file `dashboard_template.php` and rename it to `dashboard_$USERNAME`, but replace `$USERNAME` with your username!
    2. Open the login-page and execute `createHash({username}, {password})` and copy the hash returned!
    3. Then, create a file called `.credentials`. In this file, put the copied hash into the first line and your username into the second!
    4. Customize the UI with the functions listed below!
<br><br>

- OUTPUT
  
    ***`$device` refers to the device name you declared <a href="https://console.thinger.io/" target="_blank">here</a>.***

    ***`$thing` refers to the "thing" created in the device code! (for example from an NodeMCU or ESP8266!)***
    <br><br>
    - `status(string $device)` Use this to display current status (offline/online + *optional stats*)
    <br><br>
    - `temperature(string $device, string $thing, int $maxVal, int $width, bool $unit)` Use this to display temperature! 
       - `$maxVal` is the maximum value the gauge will display
       -  `$width` defines the width and size of the canvas
       -  `$unit` defines which unit. °C (*`true`*) or °F (*`false`*)
    <br><br>
    - `humidity(string $device, string $thing, int $width)` Use this to display humidity! (0-100%)
      -  `$width` defines the width and size of the canvas
    <br><br>
    - `textValue(string $device, string $thing)` Use this to display plain messages!
    <br><br>
    - `led(string $device, string $thing, int $size = 32)` A little LED/Light bulb to display a boolean value.
      -  `$size` (Optional) Defaults to `32`. Value will be used in the css-unit `p (pixels)` for height and width for the "led"
      - The state **`true`** will be triggered, if API-Response is `true`*(boolean)*, `"true"`*(string)* or `1`*(number)*.
      - The state **`false`** will be triggered from every other value that doesn't trigger **`true`**!
    <br><br>

- INPUT

    - `button($device, $thing, $value = '{"in":true}', $text = "", $style = "primary")` A normal push button to trigger an action!
      - `$value` (Optional) Defaults to `'{"in":true}'`. Can be set to any value, for example `'{"in":true}'`
      - `$text` (Optional) Defaults to `$thing`. Can be set to any string to for custom Text on Button!
      - `$style` (Optional) Defaults to `"primary"`. Can be set to any bootstrap-color-value (for example *danger*, *success*, ...)
    <br><br>

- LAYOUT

    - `layout($type, $isfluid = false)` Use this for layout! It behaves like html tags, so you have to call `layout($type)` again to "close the layout".
      - `$type` Type of layout. ('grid')
      - `$isfluid` (Optional) Defaults to `false`. If it is set to *`true`*, the container will be a fluid container.
      <br><br>

----------
## Usage (User-Side)

- Gauges will have `$thing` displayed in brackets after the value