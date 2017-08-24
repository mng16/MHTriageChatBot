<?php
// Includes
include '../includes/connect.php';
include '../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<?php include '../includes/bootstrapHead.php' ?>
	<title>Users At Risk of Harm | MhtBot</title>

</head>
<body>

<?php include '../includes/nav.php'; ?>

<header>
<h1>Users at Risk of Harm</h1>
</header>

<main>

<section>
	<p>Users considered at risk of harm (suicide, self-harm) were identified as follows:</p>
	<ul>
	<li>Users who have answered at least 'More than half the days' to the PHQ-9 question 'How many days have you had thoughts that you would be better off dead or of hurting yourself in some way?'</li>
	<li>User responses that contain suicide/self harm ideation words in the text</li>
	</ul>
</section>

<h2>Identified from PHQ9</h2>

<p>List of users who have answered at least 'More than half the days' to the PHQ-9 question 'How many days have you had thoughts that you would be better off dead or of hurting yourself in some way?'</p>

<?php
//Note: this is the question ID of this question as stored in the database
$questionID = 16;
$userIDs = getHarmRiskUsers($conn, $questionID);
foreach($userIDs as $userID){
	$username = getUsernameFromID($conn, $userID);
?>

	<form action = "singleUserDetails.php" method="post">
	<input type="hidden" name="userID" value="<?php echo $userID;?>">
	<button type="submit"><?php echo $username?></button>
	</form>
<?php
}
?>

<h2>Identified by Keyword/Key Phrase Analysis</h2>

<p>The keywords and phrases searched for within user responses is based on <a href="http://dl.acm.org/citation.cfm?id=2791023">this</a> paper.

<p>The keywords and phrases used to identify users at risk of suicide/self-harm are as follows:</p>

<ul>
<?php 

$suicideIdeationPhrases = array("want to commit suicide", "thinking about killing", "cutting", "suicide", "want to be dead", "wanting to die", "want to die", "wanted to die", "end it", "ending it all", "don't want to live", "can't cope anymore", "don't want to be alive", "can't take it anymore", "can't go on", "trigger warning", "eating disorder", "death", "selfharm", "self harm", "pain", "hate myself", "kill myself", "kill");

for($i = 0; $i<count($suicideIdeationPhrases); $i++){
?>
<li><?php echo $suicideIdeationPhrases[$i]; ?></li>
<?php
}
?>
</ul>

<p>User's who gave responses containing one or more of the above words are as follows:</p>

<?php

$tsql = "SELECT UserResponse, InteractionID
			FROM UserResponses";
$getResults = sqlsrv_Query($conn, $tsql);
if($getResults == FALSE){
	if(($errors = sqlsrv_errors())!=null){
		formatErrors($errors);
	}
	die("Failure in executing query to retrieve userResponses and corresponding interactionIDs");
}

while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
	$interactionID = $row['InteractionID'];
	$userResponse = $row['UserResponse'];

	$harmIdeation = checkForHarmIdeation($userResponse, $suicideIdeationPhrases);
	if($harmIdeation == true){
		$userID = getUserIDFromInteractionID($conn, $interactionID);
		$username = getUsernameFromID($conn, $userID);
?>

	<form action = "singleUserDetails.php" method="post">
	<input type="hidden" name="userID" value="<?php echo $userID;?>">
	<button type="submit"><?php echo $username?></button>
	</form>

<?php
	}
}
?>

</main>
</body>

<?php include '../includes/bootstrapFoot.php'; ?>

</html>
