<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Database Access for Mental Health Triage Chatbot</title>
	</head>

<header>
<h1>Database Access for Mental Health Triage Chatbot</h1>
</header>

<body>
<p>
This page provides access to the database supporting the mental health triage chatbot.
</p>

<?php
//https://docs.microsoft.com/en-us/azure/sql-database/sql-database-connect-query-php

$serverName = "mhtbotdb.database.windows.net";
$connectionOptions = array(
	"Database" => "mhtBotDB",
	"Uid" => "mng17@mhtbotdb",
	"PWD" => "1PlaneFifth"
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Read Query
$tsql = "SELECT * FROM UserResponsesNew;";
$getResults = sqlsrv_query($conn, $tsql);
echo("Reading data from table" . PHP_EOL);

if($getResults == FALSE)
	die(FormatErrors(sqlsrv_errors()));
	//echo (sqlsrv_errors());
while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
	echo($row['UserResponse'] . PHP_EOL);
}
sqlsrv_free_stmt($getResults);

function FormatErrors( $errors )
{
    /* Display errors. */
    echo "Error information: ";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."";
        echo "Code: ".$error['code']."";
        echo "Message: ".$error['message']."";
    }
}
?>

<?php




?>


</body>

</html>