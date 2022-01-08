const url = new URL(window.location.href);
var user = url.searchParams.get("user");
user = user.toLowerCase();

// Fetch match details using the api for one player
async function fetchMatchJson(player) {
  const response = await fetch(
    "https://api2.splinterlands.com/players/outstanding_match?username=" +
      player
  );

  if (response.status >= 200 && response.status <= 299) {
    const matchDetails = await response.json();
    return matchDetails;
  } else {
    console.log(response.status, response.statusText);
    let message = document.getElementById("message");
    message.innerHTML =
      "Splinterlands is having technical issues and we can not connect. Try again at a later time.";
  }
}

// Check if user is playing and get opponent
fetchUser(user);

function fetchUser(user) {
  fetchMatchJson(user).then(function (response) {
    let opponent = response.opponent_player;
    let message = document.getElementById("message");
    message.innerHTML = "Waiting for opponent to lock in his cards.";
    fetchMatch(opponent);
  });
}

// Check if team has been submitted by the opponent
function fetchMatch(player) {
  fetchMatchJson(player).then(function (response) {
    if (response.team == null && response.team_hash == null) {
      setTimeout(function () {
        fetchMatch(player);
      }, 5000);
    } else if (response.team == null && response.team_hash != null) {
      let message = document.getElementById("message");
      message.innerHTML =
        "Your opponent has locked in his team, but has prevented us from looking at his cards.";
    } else {
      let message = document.getElementById("message");
      message.innerHTML = "";

      // If team has been submitted get an array of the card IDs
      let team = JSON.parse(response.team);
      let summoner = team.summoner;
      let monsters = team.monsters;
      let summonerId = summoner.split("-")[1];
      let monstersId = monsters.map((o) => parseInt(o.split("-")[1]));
      monstersId.unshift(summonerId);
      fetchCardDetails(monstersId);
    }
  });
}

// Fetch details for all cards using API
async function fetchCardDetails(monstersId) {
  const link = "https://api2.splinterlands.com/cards/get_details";
  let response = await fetch(link);
  const cardDetails = await response.json();

  // Loop through each card to get the name and edition
  for (const monsterId of monstersId) {
    let cardName = cardDetails[monsterId - 1].name;
    let cardEdition = cardDetails[monsterId - 1].editions;

    const edition = {
      "0,1": "beta",
      1: "beta",
      2: "promo",
      3: "reward",
      4: "untamed",
      5: "dice",
    };
    let cename = edition[cardEdition];

    // Render the card image
    let img = document.createElement("img");
    img.setAttribute(
      "src",
      "https://d36mxiodymuqjm.cloudfront.net/cards_by_level/" +
        cename +
        "/" +
        cardName +
        "_lv1.png"
    );
    img.setAttribute("width", "120");
    img.setAttribute("alt", cardName);
    document.getElementById("team").appendChild(img);
  }
  let enter = document.createElement("br");
  document.getElementById("team").appendChild(enter);
}
