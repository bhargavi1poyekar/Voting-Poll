<?php 
require_once 'app/init.php';
if(!isset($_POST['choice'])){
	echo "<script>alert('You did not vote!');
	document.location='http://localhost/php-server-side/poll.php?poll=1'</script>";
}
else{

	$poll=(int)$_POST['poll'];
	$choice=(int)$_POST['choice'];
	$user=(int)$_SESSION['user_id'];

	$voteQuery=$db->query("
		INSERT INTO polls_answers(user,poll,choice)
		values($user,$poll,$choice);
	");

	header('Location:poll.php?poll=' . $poll);	exit();
}
heaader('Location:index.php');
?>
