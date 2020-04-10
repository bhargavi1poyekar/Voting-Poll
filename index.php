<?php
require_once 'app/init.php';
$pollsQuery=$db->query("
	Select id,question
 	From polls
	Where DATE(NOW()) BETWEEN starts and ends
	");

while($row=$pollsQuery->fetch_object()){
	$polls[]=$row;
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
		<style>
			body{
				text-align:center;
			}
			h1{
				text-align: center;
				margin:10px;
				font-variant: small-caps;
			}
			li{
				list-style-type: none;
				
			}
			ul{
				margin: 0;
				padding: 0;
			}
			.Questions{
				background-color: white;
				position:absolute;
				top:50%;
				left:50%;
				transform:translate(-50%,-50%);
				margin-right:20px;
				padding:20px;
				border:1px #ccc;
			 	border-style:outset;
			 	border-radius:10px;
			 	box-shadow: 0 0 5px 2px #333333
						}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="Questions">
				<h1> Opinion poll</h1>
				<?php if(!empty($polls)):?>
					<ul>
						<?php foreach($polls as $poll):?>
							<li><a href="poll.php?poll=<?php echo $poll->id;?>"><?php echo $poll->question; ?></a></li>
						<?php endforeach;?>
					</ul>
				<?php else:?>
					<p>Sorry,no polls available right now.</p>
				<?php endif;?>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	</body>
</html>