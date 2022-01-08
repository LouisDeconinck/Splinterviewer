<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname); // Create connection
if ($conn->connect_error) {     // Check connection
 die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$days = $_POST['days'];

$sql = "SELECT * FROM users WHERE name='" . $name . "'";
$result = @mysqli_query($conn, $sql);

$fetch = mysqli_fetch_assoc($result);
$odate = $fetch["untill"];
$odatetime = strtotime($odate);

if (mysqli_num_rows($result) >= 1 and $odatetime > time()) {

 $newdate = date('Y-m-d H:i:s', strtotime($odate . ' + ' . $days . ' days'));

 $sql = "UPDATE users SET untill = '" . $newdate . "' WHERE name = '" . $name . "'";
 $result = @mysqli_query($conn, $sql);
} elseif (mysqli_num_rows($result) >= 1 and $odatetime <= time()) {
 $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', time()) . '+ ' . $days . ' days'));
 $sql = "UPDATE users SET untill = '" . $date . "' WHERE name = '" . $name . "'";
 $result = @mysqli_query($conn, $sql);
} else {

 $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', time()) . '+ ' . $days . ' days'));

 $sql = "INSERT INTO users (name, untill) VALUES('" . $name . "', '" . $date . "')";
 $result = @mysqli_query($conn, $sql);
}
