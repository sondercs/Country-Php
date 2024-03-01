<?php
#   Put UTF-8 
header('Content-Type: text/html; charset=UTF-8');

#   Take the informations from the file
$file = fopen("countrys.csv", "r");
if (!$file) {
    die("Failed to open the CSV file.");
}

#  Connect to DataBase
$DataBase = new mysqli("localhost", "root", "usbw", "test", 3307);
if ($DataBase->connect_error) {
    die("Connection to the database failed: " . $DataBase->connect_error);
}

#  Create Table
$command = "CREATE table if not exists country(id int auto_increment,\nname VARCHAR(40),\nPRIMARY KEY (`id`))";
$DataBase->query($command);

#  Ignore First Line
fgets($file);

# CLean the Table
$command = "TRUNCATE country";
$DataBase->query($command);

#  Prepare the SQL statement
$stmt = $DataBase->prepare("INSERT INTO country(name) VALUES (?)");

#  Loop
while (!feof($file)) {
    $line = fgets($file);
    $all_lines = explode(",", $line);

    // Bind the parameter
    $stmt->bind_param("s", $all_lines[1]);

    echo $all_lines[1]."<br>"; //Country Name

    // Set the parameter value
    $all_lines[1] = utf8_decode($all_lines[1]);

    // Execute the statement
    $stmt->execute();
}

// Close the statement
$stmt->close();

?>