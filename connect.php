
<?php

  echo "Trying to connect ... \n ";
  
  // create the connection variables
  $config = require __DIR__ . '/config.php'; // see config.example.php
  $servername = $config['host'];
  $username   = $config['user'];
  $password   = $config['pass'];

  // Create a connection
  $conn = new mysqli($servername, $username, $password);

  echo "Something has been done, but ...\n ";

  // Check the connection
  if ($conn->connect_error) {
    echo "Something went wrong!\n";
    die("Connection failed: " . $conn->connect_error);
  } else {
    echo "Connected successfully";
  }

?>


