$("#send_1").click(function () {
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":95,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the purchase of a 1 day Splinterviewer license for 95 DEC.",
    function (response) {
      $.post("purchase.php", { name: response.data.username, days: 1 });
    }
  );
});

$("#send_7").click(function () {
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":495,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the purchase of a 7 days Splinterviewer license for 495 DEC.",
    function (response) {
      $.post("purchase.php", { name: response.data.username, days: 7 });
    }
  );
});

$("#send_30").click(function () {
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":995,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the purchase of a 30 days Splinterviewer license for 995 DEC.",
    function (response) {
      $.post("purchase.php", { name: response.data.username, days: 30 });
    }
  );
});

$("#donate50").click(function () {
  console.log($("#donate50").value);
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":50,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the donation of 50 DEC."
  );
});

$("#donate100").click(function () {
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":100,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the donation of 100 DEC."
  );
});

$("#donate500").click(function () {
  hive_keychain.requestCustomJson(
    null,
    "sm_token_transfer",
    "active",
    '{"to":"solaito","qty":500,"token":"DEC","type":"withdraw","app":"splinterviewer"}',
    "Confirm the donation of 500 DEC."
  );
});
