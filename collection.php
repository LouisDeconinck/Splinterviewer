<!DOCTYPE html>
<html>

<head>
 <title>Splinterviewer</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
 <link rel="stylesheet" href="stylesheet.css">

 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="shortcut icon" type="image/jpg" href="iconmonstr-eye-6.svg" />
</head>

<body>
 <a href="https://splinterviewer.com">
  <h1>Splinterviewer</h1>
 </a>

 <?php

 // Check for errors
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

 $user = strtolower($_GET["user"]);

 $contents1 = file_get_contents("https://api2.splinterlands.com/cards/get_details");
 $array1 = json_decode($contents1, true);

 $contents2 = file_get_contents('https://api2.splinterlands.com/cards/collection/' . $user);
 $array2 = json_decode($contents2, true);

 $contents3 = file_get_contents('https://api2.splinterlands.com/players/quests?username=' . $user);
 $array3 = json_decode($contents3, true);

 $contents4 = file_get_contents('https://api2.splinterlands.com/players/details?name=' . $user);
 $array4 = json_decode($contents4, true);

 $battles = $array4['battles'];
 $wins = $array4['wins'];
 $currentstreak = $array4['current_streak'];
 $longeststreak = $array4['longest_streak'];
 $rating = $array4['rating'];
 $maxrating = $array4['max_rating'];
 $capturerate = $array4['capture_rate'];
 if (isset($array4['guild']['name'])) {
  $guild = $array4['guild']['name'];
 }
 $cp = $array4['collection_power'];
 $league = $array4['league'];

 $quest = $array3[0]['name'];
 $questcompleted = $array3[0]['completed_items'];
 if ($quest == "Stir the Volcano") {
  $questname = "Fire";
 } elseif ($quest == "Pirate Attacks") {
  $questname = "Water";
 } elseif ($quest == "Lyanna's Call") {
  $questname = "Earth";
 } elseif ($quest == "Defend the Borders") {
  $questname = "Life";
 } elseif ($quest == "Rising Dead") {
  $questname = "Death";
 } elseif ($quest == "Gloridax Revenge") {
  $questname = "Dragon";
 } elseif ($quest == "Stubborn Mercenaries") {
  $questname = "No neutral";
 } elseif ($quest == "Stealth Mission") {
  $questname = "Sneak";
 } elseif ($quest == "High Priority Targets") {
  $questname = "Snipe";
 }

 echo '<h2>Player info</h2>Name: ' . $user;

 if (isset($guild)) {
  echo ' | Guild: ' . $guild;
 }

 echo ' | Rating: ' . $rating . ' | Max rating: ' . $maxrating . '<br />Total battles: ' . $battles . ' | Total wins: ' . $wins . ' | Win rate: ' . round(($wins / $battles) * 100) . '% | Win streak: ' . $currentstreak . ' | Longest win streak: ' . $longeststreak . '<br/>Collection Power: ' . $cp . ' | League: ' . $league . ' | Capture rate: ' . round($capturerate / 100) . '%<br/>' . $questname . ' quest: ' . $questcompleted . '/5<h2>Cards owned</h2>';

 $cards = $array2['cards'];
 foreach ($array1 as $allcard) {
  if (($allcard['editions'] == "0,1" or $allcard['editions'] == "4") and ($allcard['rarity'] == 1 or $allcard['rarity'] == 2)) {
   array_push($cards, ['card_detail_id' => $allcard['id'], 'gold' => null, 'level' => 1, 'edition' => $allcard['editions']]);
  }
 }

 $ids = array_unique(array_column($cards, 'card_detail_id'));
 foreach ($ids as $id) {
  $level = max(array_column($selection = array_filter($cards, function ($card) use ($id) {
   return $card['card_detail_id'] == $id;
  }), 'level'));

  foreach ($selection as $card) if ($card['level'] == $level) {
   $colcardid = $card['card_detail_id'];
   $colcardid--;
   $colcardname = $array1[$colcardid]['name'];
   $colcardlvl = $card['level'];
   $colcardeditionnr = $card['edition'];
   if ($colcardeditionnr == "0") {
    $colcardeditionname = "alpha";
   } elseif ($colcardeditionnr == "0,1") {
    $colcardeditionname = "beta";
   } elseif ($colcardeditionnr == "1") {
    $colcardeditionname = "beta";
   } elseif ($colcardeditionnr == "2") {
    $colcardeditionname = "promo";
   } elseif ($colcardeditionnr == "3") {
    $colcardeditionname = "reward";
   } elseif ($colcardeditionnr == "4") {
    $colcardeditionname = "untamed";
   } elseif ($colcardeditionnr == "5") {
    $colcardeditionname = "dice";
   } elseif ($colcardeditionnr == "6") {
    $colcardeditionname = "gladius";
   }
   $colcardgold = $card['gold'];
   if ($colcardgold == true) {
    $colcardgoldstring = "_gold";
   } else {
    $colcardgoldstring = "";
   }
   echo '<img class="cardcollection" src="https://d36mxiodymuqjm.cloudfront.net/cards_by_level/' . $colcardeditionname . '/' . $colcardname . '_lv' .  $colcardlvl . $colcardgoldstring . '.png" height="150" alt="' . $colcardname . '" />';
   break;
  }
 }
 ?>
</body>

</html>
