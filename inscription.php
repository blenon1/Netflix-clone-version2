<?php 
	session_start();

	if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password-two'])) {

		$pseudo = $_POST['pseudo'];
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		$password_two = htmlspecialchars($_POST['password-two']);

		//verify password = password-two
		if($password != $password_two) {
			header('location: inscription.php?error=1&message=Mot de passe non identique');
			exit();
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			<?php 

				if(isset($_GET['error'])) {
					if(isset($_GET['message'])) {
						echo 'div>'.htmlspecialchars($_GET['mesage']).'</div>';
					}
				}
			
			?>

			<form method="post" action="inscription.php">
				<input type="text" name="pseudo" placeholder="Votre pseudo" required />
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>