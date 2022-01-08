<!DOCTYPE html>
<html>

<head>
 <title>Splinterviewer Card Art</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
 <link rel="stylesheet" href="stylesheet.css">

 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="shortcut icon" type="image/jpg" href="iconmonstr-eye-6.svg" />

 <style>
  .cardart {
   height: 120px;
  }
 </style>
</head>

<body>

 <a href="https://splinterviewer.com">
  <h1>Splinterviewer</h1>
 </a>
 <img src="venari.png" alt="Venari Wavesmith" />
 <h2>Splinterlands Card Art</h2>
 <p>This page contains a list off all the art used on the Splinterlands cards. In order to use the art in full size, do a right mouse click and use "copy", "save as" or "open image in new tab"</p>
 <p>Help keep this information up to date and consider donating if it has helped you. You can send DEC, SPS, SPT, Hive,
  HBD,
  or cards to @solaito. You can also use the buttons at the bottom of the page to securely donate
  through <a href="https://hive-keychain.com/" target="_blank">Hive Keychain</a>.</p>

 <?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

 $contents0 = file_get_contents("https://api.splinterlands.io/cards/get_details");
 $array0 = json_decode($contents0, true);

 foreach ($array0 as $card) {
  $cardname = $card['name'];
  echo '<img src="https://d36mxiodymuqjm.cloudfront.net/card_art/' . $cardname . '.png" alt="' . $cardname . '" class="cardart" />';
 }
 ?>

 <h2 id="donate">Support</h2>
 <p>Help me keep this information up to date and consider donating if it has helped you. You can send DEC, SPS, SPT
  or cards to @solaito in game or use the buttons on the bottom to securely donate through Hive Keychain.</p><br /><br /><br /><br />

 <div id="bottom">Donate to @solaito or use Hive Keychain:<br /><button id="donate50">50 DEC</button><button id="donate100">100
   DEC</button><button id="donate500">500 DEC</button></div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <script src="keychainpurchase.js"></script>

</body>

</html>
