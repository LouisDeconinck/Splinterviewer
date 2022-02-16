<?php
checkErrors();

// FUNCTIONS
// Check for errors
function checkErrors()
{
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

// Connect to database
function connectDatabase()
{
  $servername = "";
  $username = "";
  $password = "";
  $dbname = "";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Find all users who have access
function usersAccess()
{
  $sql = "SELECT name FROM users WHERE untill > now()";
  $result = mysqli_query(connectDatabase(), $sql);

  $json = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $usersAllowed = array();

  foreach ($json as $row) {
    array_push($usersAllowed, $row["name"]);
  }
  return $usersAllowed;
}

// Time left to use the tool
function timeLeft()
{
  $user = strtolower($_GET["user"]);
  $sql1 = "SELECT * FROM users WHERE name='" . $user . "'";
  $result1 = mysqli_query(connectDatabase(), $sql1);

  $fetch = mysqli_fetch_assoc($result1);
  $odate = $fetch["untill"];
  $odatetime = strtotime($odate);

  $timeleft = $odatetime - time();
  $days = floor($timeleft / (60 * 60 * 24));
  $hours = floor(($timeleft - ($days * 60 * 60 * 24)) / (60 * 60));

  if ($timeleft > 0) {
    $timestring = 'Account time left: ';

    if ($days == 1) {
      $timestring .= '1 day & ';
    } elseif ($days != 0) {
      $timestring .= $days . ' days & ';
    }
    if ($hours == 1) {
      $timestring .= '1 hour';
    } elseif ($hours == 0 && $days == 0) {
      $timestring .= 'Less than 1 hour.';
    } else {
      $timestring .= $hours . ' hours';
    }
    return $timestring;
  }
  return null;
}

// Editions
function getEdition($editionnumber)
{
  if ($editionnumber == "0") {
    $editionname = "alpha";
  } elseif ($editionnumber == "0,1" || $editionnumber == "1") {
    $editionname = "beta";
  } elseif ($editionnumber == "2") {
    $editionname = "promo";
  } elseif ($editionnumber == "3") {
    $editionname = "reward";
  } elseif ($editionnumber == "4") {
    $editionname = "untamed";
  } elseif ($editionnumber == "5") {
    $editionname = "dice";
  } elseif ($editionnumber == "6") {
    $editionname = "gladius";
  }
  return $editionname;
}

// Render card image
function cardImage($edition, $cardDetailID, $level, $gold, $height, $array)
{
  $name = $array[$cardDetailID]['name'];
  if ($gold == true) {
    $goldstring = "_gold";
  } else {
    $goldstring = "";
  }
  $cardImage = '<img class="card" src="https://d36mxiodymuqjm.cloudfront.net/cards_by_level/' . getEdition($edition) . '/' . $name . '_lv' .  $level . $goldstring . '.png" height="' . $height . '" alt="' . $name . '" />';

  return $cardImage;
}

// Render active elements
function activeElements($inactive)
{
  if (str_contains($inactive, 'Red')) {
    $red = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_fire_inactive.svg";
  } else {
    $red = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_fire.svg";
  }
  $elements = '<img src=' . $red . ' width="25" />';
  if (str_contains($inactive, 'Blue')) {
    $blue = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_water_inactive.svg";
  } else {
    $blue = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_water.svg";
  }
  $elements .= '<img src=' . $blue . ' width="25" />';
  if (str_contains($inactive, 'Green')) {
    $green = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_earth_inactive.svg";
  } else {
    $green = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_earth.svg";
  }
  $elements .= '<img src=' . $green . ' width="25" /><br />';
  if (str_contains($inactive, 'White')) {
    $white = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_life_inactive.svg";
  } else {
    $white = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_life.svg";
  }
  $elements .= '<img src=' . $white . ' width="25" />';
  if (str_contains($inactive, 'Black')) {
    $black = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_death_inactive.svg";
  } else {
    $black = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_death.svg";
  }
  $elements .= '<img src=' . $black . ' width="25" />';
  if (str_contains($inactive, 'Gold')) {
    $gold = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_dragon_inactive.svg";
  } else {
    $gold = "https://d36mxiodymuqjm.cloudfront.net/website/ui_elements/icon_splinter_dragon.svg";
  }
  $elements .= '<img src=' . $gold . ' width="25" />';
  return $elements;
}

// Render quest name 
function questName($quest)
{
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
  return $questname;
}

// Get latest battles
function getBattles($details, $playernr, $array)
{
  if (!empty($details['team' . $playernr]['summoner']['card_detail_id'])) {

    // Summoner
    $sID = $details['team' . $playernr]['summoner']['card_detail_id'];
    $sID--;
    $editionID = $details['team' . $playernr]['summoner']['edition'];
    $sL = $details['team' . $playernr]['summoner']['level']; // TODO: Sometimes a level is not set, (happens when the other player did not sumbit team)
    $sG = $details['team' . $playernr]['summoner']['gold'];

    echo cardImage($editionID, $sID, $sL, $sG, 100, $array);

    // Monsters
    foreach ($details['team' . $playernr]['monsters'] as $monster) {
      $monsterID = $monster['card_detail_id'];
      $monsterID--;
      $editionID = $monster['edition'];
      $sL = $monster['level'];
      $sG = $monster['gold'];

      echo cardImage($editionID, $monsterID, $sL, $sG, 100, $array);
    }
    echo '<br/>';
  }
}

?>

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

  $user = strtolower($_GET["user"]);

  if ($user == null) {
    echo 'Go back to the <a href="https://splinterlands.com">homepage</a>.';
    // If user is allowed access do this
  } elseif (in_array($user, usersAccess())) {
    echo timeLeft();

    $contents0 = file_get_contents('https://api2.splinterlands.com/players/outstanding_match?username=' . $user);
    $array0 = json_decode($contents0, true);

    if (!isset($array0['player']) or $array0['opponent_player'] == null) {
      echo '<p>No battle found.</p>';
    } else {
      // Get match data
      $player = $array0['opponent_player'];
      $ruleset = $array0['ruleset'];
      $manacap = $array0['mana_cap'];
      $matchtype = $array0['match_type'];
      $inactive = $array0['inactive'];

      $rulesets = explode("|", $ruleset);
      $ruleset1 = strtolower(str_replace(' ', '-', $rulesets[0]));
      $ruleset1 = str_replace('-&', '', $ruleset1);

      $array = json_decode(file_get_contents("https://api2.splinterlands.com/cards/get_details"), true);
      $array3 = json_decode(file_get_contents('https://api2.splinterlands.com/cards/collection/' . $player), true);
      $array4 = json_decode(file_get_contents('https://api2.splinterlands.com/players/quests?username=' . $player), true);
      $array5 = json_decode(file_get_contents('https://api2.splinterlands.com/players/details?name=' . $player), true);
      $array6 = json_decode(file_get_contents('https://api2.splinterlands.com/battle/history?player=' . $player), true);

      $battles = $array5['battles'];
      $wins = $array5['wins'];
      $rating = $array5['rating'];
      $currentstreak = $array5['current_streak'];
      $longeststreak = $array5['longest_streak'];
      $maxrating = $array5['max_rating'];
      $capturerate = $array5['capture_rate'];
      if (isset($array5['guild']['name'])) {
        $guild = $array5['guild']['name'];
      }
      $cp = $array5['collection_power'];
      $league = $array5['league'];
      $quest = $array4[0]['name'];
      $questcompleted = $array4[0]['completed_items'];

      echo '<h2>Match info</h2>Opponent: ' . $player;

      if (isset($guild)) {
        echo ' | <wbr>Guild: ' . $guild;
      }

      echo ' | <wbr>Rating: ' . $rating . ' | <wbr>Max rating: ' . $maxrating . ' | <wbr>Match type: ' . $matchtype . ' | <wbr>Total battles: ' . $battles . ' | <wbr>Total wins: ' . $wins . ' | <wbr>Win rate: ' . round(($wins / $battles) * 100) . '% | <wbr>Win streak: ' . $currentstreak . ' | <wbr>Longest win streak: ' . $longeststreak . ' | <wbr>Collection Power: ' . $cp . ' | League: ' . $league . ' | <wbr>Capture rate: ' . round($capturerate / 100) . '% | <wbr>' . questName($quest) . ' quest: ' . $questcompleted . '/5<table id="matchinfo"><colgroup><col width="50px"><col width="100px"><col width="75px"></colgroup><tr><td><span class="mana">' . $manacap . '</span></td><td class="ruleset">';

      if (isset($rulesets[1])) {
        $ruleset2 = strtolower(str_replace(' ', '-', $rulesets[1]));
        $ruleset2 = str_replace('-&', '', $ruleset2);
        echo '<img src="https://d36mxiodymuqjm.cloudfront.net/website/icons/rulesets/new/img_combat-rule_' . $ruleset2 . '_150.png" width="50" alt="' . $rulesets[1] . '" />';
      }

      echo '<img src="https://d36mxiodymuqjm.cloudfront.net/website/icons/rulesets/new/img_combat-rule_' . $ruleset1 . '_150.png" width="50" alt="' . $rulesets[0] . '" /></td><td>' . activeElements($inactive) . '</td></tr></table>';
  ?>

      <script src="cardfetch.js"></script>

  <?php
      echo '<h2>Cards picked</h2><div id="message"></div><div id="team"></div><h2>Playable cards</h2><details><summary>View playable cards</summary>';

      if (count($array3['cards']) < 10000) {
        $cards = array();

        foreach ($array3['cards'] as $collectioncard) {
          $cardcolor = $array[$collectioncard['card_detail_id'] - 1]['color'];
          if (strpos($inactive, $cardcolor) !== true) {
            array_push($cards, ['card_detail_id' => $collectioncard['card_detail_id'], 'gold' => $collectioncard['gold'], 'level' => $collectioncard['level'], 'edition' => $collectioncard['edition']]);
          }
        }

        foreach ($array as $allcard) {
          if (($allcard['editions'] == "0,1" or $allcard['editions'] == "4") and ($allcard['rarity'] == 1 or $allcard['rarity'] == 2)) {
            if (strpos($inactive, $allcard['color']) !== true) {
              array_push($cards, ['card_detail_id' => $allcard['id'], 'gold' => null, 'level' => 1, 'edition' => $allcard['editions']]);
            }
          }
        }

        $ids = array_unique(array_column($cards, 'card_detail_id'));
        foreach ($ids as $id) {
          $level = max(array_column($selection = array_filter($cards, function ($card) use ($id) {
            return $card['card_detail_id'] == $id;
          }), 'level'));

          // Go through all cards in collection
          foreach ($selection as $card) if ($card['level'] == $level) {
            $colcardid = $card['card_detail_id'];
            $colcardid--;
            $colcardlvl = $card['level'];
            $colcardeditionnr = $card['edition'];
            $colcardgold = $card['gold'];
            echo cardImage($colcardeditionnr, $colcardid, $colcardlvl, $colcardgold, 100, $array);
            break;
          }
        }
      } else {
        echo 'The collection of this player exeeds our 10,000 cards cap.';
      }

      echo '</details><h2>Recent decks used</h2><table><colgroup><col width="65px"><col width="130px"><col width="110px"><col></colgroup>';

      // Go through every battle
      foreach ($array6['battles'] as $battle) {
        $details = json_decode($battle['details'], true);
        if (isset($details['team1']) and isset($details['team2'])) {
          $manacap = $battle['mana_cap'];
          $ruleset = $battle['ruleset'];
          $inactive = $battle['inactive'];
          $matchtype = $battle['match_type'];

          $rulesets = explode("|", $ruleset);
          $ruleset1 = strtolower(str_replace(' ', '-', $rulesets[0]));
          $ruleset1 = str_replace(
            '-&',
            '',
            $ruleset1
          );

          $matchtype = $battle['match_type'];

          $winner = $battle['winner'];
          if ($winner == $player) {
            $rowclass = 'winner';
          } else {
            $rowclass = 'loser';
          }

          echo '<tr class="' . $rowclass . '"><td class="ruleset"><span class="mana">' . $manacap . '</span></td><td><img src="https://d36mxiodymuqjm.cloudfront.net/website/icons/rulesets/new/img_combat-rule_' . $ruleset1 . '_150.png" width="50" alt="' . $rulesets[0] . '" />';

          if (isset($rulesets[1])) {
            $ruleset2 = strtolower(str_replace(' ', '-', $rulesets[1]));
            $ruleset2 = str_replace('-&', '', $ruleset2);
            echo '<img src="https://d36mxiodymuqjm.cloudfront.net/website/icons/rulesets/new/img_combat-rule_' . $ruleset2 . '_150.png" width="50" alt="' . $rulesets[1] . '" />';
          }

          echo '</td><td>' . activeElements($inactive) . '</td><td class="cardshistory" rowspan="2">';

          $player1 = $battle['player_1'];
          if ($player1 == $player) {
            $matchID = $battle['battle_queue_id_1'];
            getBattles($details, 1, $array);
          } else {
            $matchID = $battle['battle_queue_id_2'];
            getBattles($details, 2, $array);
          }

          echo '</td></tr><tr class="' . $rowclass . ' border"><td colspan="3">' . $matchtype . ' | <a href="https://splinterlands.com?p=battle&id=' . $matchID . '&ref=solaito" target="_blank">Replay</a></td></tr>';
        }
      }
      echo '</table>';
    }
  } else {
    echo 'This player does not have access. Buy a license <a href="https://splinterviewer.com/#pricing">here</a> to start using this tool.';
  }
  ?>
  <input id="bottombutton" type="button" value="Find new battle" onclick="window.location.reload()" />
</body>

</html>