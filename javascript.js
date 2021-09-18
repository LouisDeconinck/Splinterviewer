var opponent;
var team;

// Get user from a URL parameter ?user=
const url = new URL(window.location.href);
var user = url.searchParams.get("user");

// Only certain users are allowed to use the application
if (user == "solaito") {

 // Fetch match details using the api for one player
 async function fetchMatchJson(player) {
  let link = 'https://api.splinterlands.io/players/outstanding_match?username=' + player;
  const response = await fetch(link);
  const matchDetails = await response.json();
  return matchDetails;
 }

 // Check if team has been submitted by the opponent
 function fetchMatch(player) {
  fetchMatchJson(player).then(function (response) {
   console.log("fetchMatchJson");
   if (response.team == null) {
    setTimeout(function () { fetchMatch(player) }, 5000); // Recheck every 5 seconds
   } else {
    let message = document.getElementById("message");
    message.innerHTML = '';

    // If team has been submitted get an array of the card IDs
    let team = JSON.parse(response.team);
    let summoner = team.summoner;
    let monsters = team.monsters;
    let summonerId = summoner.split('-')[1];
    let monstersId = monsters.map(o => parseInt(o.split('-')[1]));
    monstersId.unshift(summonerId);
    fetchCardDetails(monstersId);
   }
  })
 }

 // Fetch details for all cards using API
 async function fetchCardDetails(monstersId) {
  const link = 'https://api.splinterlands.io/cards/get_details';
  let response = await fetch(link);
  const cardDetails = await response.json();

  // Loop through each card to get the name and edition
  for (const monsterId of monstersId) {
   let cardName = cardDetails[monsterId - 1].name;
   let cardEdition = cardDetails[monsterId - 1].editions;

   const edition = {
    "0,1": "beta",
    "1": "beta",
    "2": "promo",
    "3": "reward",
    "4": "untamed",
    "5": "dice"
   }
   let cename = edition[cardEdition];

   // Render the card image
   let img = document.createElement("img");
   img.setAttribute("src", "https://d36mxiodymuqjm.cloudfront.net/cards_by_level/" + cename + "/" + cardName + "_lv1.png");
   img.setAttribute("height", "200");
   img.setAttribute("alt", cardName);
   document.getElementById("team").appendChild(img)
  }
 }

 // Check if user is playing and get opponent
 fetchMatchJson(user).then(matchDetails => {
  if (matchDetails == null) {
   let message = document.getElementById("message");
   message.innerHTML = 'No match is being played.';
  } else {
   let message = document.getElementById("message");
   message.innerHTML = 'Opponent has not yet locked in his team.';
   var opponent = matchDetails.opponent_player;

   fetchMatch(opponent)
  }
 });

} else {
 let message = document.getElementById("message");
 message.innerHTML = 'Account does not have access.';
}