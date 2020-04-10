<?php
require_once 'app/init.php';
if(!isset($_GET['poll'])){
	header('Location:index.php');
}
else{
	$id=(int)$_GET['poll'];
	$user=(int)$_SESSION['user_id'];
	$pollQuery=$db->prepare("SELECT id, question
		From polls
		Where id =?
		and Date(NOW()) Between starts and ends");
	$pollQuery->bind_param("i",$id);
	$pollQuery->execute();
	$result=$pollQuery->get_result();
	$poll=$result->fetch_object();

	$answerQuery=$db->prepare("SELECT polls_choices.id AS choice_id,polls_choices.name AS choice_name
		FROM polls_answers
		JOIN polls_choices
		ON polls_answers.choice=polls_choices.id
		Where polls_answers.user=?
		AND polls_answers.poll=?
		");

	$answerQuery->bind_param("ii",$user,$id);
	$answerQuery->execute();
	$result3=$answerQuery->get_result();
	$answer=$result3->fetch_object();

	$votesQuery=$db->prepare("SELECT COUNT(id) AS totalcount FROM polls_answers");
	$votesQuery->execute();
	$result5=$votesQuery->get_result();
	$total=$result5->fetch_object();

	
	
	$completed=mysqli_num_rows($result3)?true:false;
	if($completed){
		$answersQuery=$db->prepare("
			SELECT polls_choices.name,
			COUNT(polls_answers.id) As count,
			COUNT(polls_answers.id)*100/(
				SELECT COUNT(*)
				FROM polls_answers
				WHERE polls_answers.poll=?) AS percentage
			FROM polls_choices
			LEFT JOIN polls_answers
			ON polls_choices.id=polls_answers.choice
			WHERE polls_choices.poll=?
			GROUP BY polls_choices.id
			");
		$answersQuery->bind_param("ii",$id,$id);
		$answersQuery->execute();
		$result4=$answersQuery->get_result();
		
		while($count=$result4->fetch_object()){
			$counts[]=$count;
		}

	}
	else{
		$choicesQuery=$db->prepare("
			SELECT polls.id, polls_choices.id, polls_choices.name 
			FROM polls
			JOIN polls_choices
			ON polls.id=polls_choices.poll
			WHERE polls.id=?
			And DATE(NOW()) BETWEEN polls.starts AND polls.ends");
		$choicesQuery->bind_param("i", $id);
		$choicesQuery->execute();
		$result2=$choicesQuery->get_result();
		$choices=[];
		while($row=$result2->fetch_object())
		{
			$choices[]=$row;
		}
	}
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Opinion-Poll</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<div class="container">
			<div class="poll">
				<?php if($completed): ?>
					<h1>JavaScript Libraries</h1>
					<h3>Opinion Poll-Results</h3>
					<div class="poll-question">
						<p><?php echo $poll->question; ?></p>
					</div>
					<p>You have completed the poll, thanks.</p>
					<p><b><?php echo $total->totalcount?></b> people have thus far taken part in this poll.
					<table border=1, cellpadding="10" cellspacing="8">
						<?php foreach($counts as $c): ?>
							<tr><td><?php echo $c->name; ?>  got: </td><td><b><?php echo $c->count ?> votes -> (<?php echo number_format($c->percentage,2); ?> %)</b></td></tr>
						<?php endforeach;?>
					</table>
				</p>
					
				<?php else: ?>
					<h1>JavaScript Libraries</h1>
					<h3>Opinion Poll</h3>
					<div class="poll-question">
						<p><?php echo $poll->question; ?></p>
					</div>
					<?php if(!empty($choices)): ?>
					<form action="vote.php" method="post">
						<div class="poll-options">
							<?php foreach($choices as $choice):?>
							<div class="poll-option">
								<input type="radio" name="choice" value="<?php echo $choice->id ?>" id="c<?php echo $index; ?>">
								<label for="c<?php echo $index; ?>"><?php echo $choice->name;?></label>
							</div>
							<?php endforeach; ?>	
						</div>
						<div class="btn">
						<input class="submit" type="submit" value="Submit answer">
						<input type="hidden" name="poll" value="1">
						</div>
					</form>
					<?php else:?>
						<p>There are no choices right now.</p>
					<?php endif;?>
				<?php endif?>
			</div>	
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	</body>
</html>