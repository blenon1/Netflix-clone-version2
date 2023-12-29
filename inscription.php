<?php  
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	session_start();
	if(isset($_SESSION['connect'])) {
		header('location: index.php');
		exit();
	}
	
	//initialisation des variable 
	$pseudo = $email = $password = $password_two = "";

	// verifacation de la soumission du formulaire
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		$password_two = htmlspecialchars($_POST['password_two']);

		//IMPORT BASE DE DONNEE
		require('src/connection.php');

		// Vérifier si les mots de passe correspondent
		if ($password != $password_two) {
			header('location: inscription.php?error=1&message=Les mots de passes ne sont pas identiques');
			exit();
		}else {
			//verifcation de la validité de lemail
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				header('location: inscription.php?error=1&message=Votre adresse mail est invalide');
				exit();
			}else {
				//verifcation de l'unité du mail dans la base de donnée
				$req = $bdd->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
				$req->bindParam(':email', $email);
				$req->execute();
				$count = $req->fetchColumn();

				if ($count > 0 ) {
					header('location: inscription.php?error=1&message=Cet adresse mail est deja utilisée');
					exit();
				} else {
					$password_hashing = password_hash($password, PASSWORD_DEFAULT);
				}
	 $cle = sha1($email).rand();
	 $cle = sha1($cle).rand();

				try {
					//Prepation et execution de la requete d'envoi et insertion des données
					$req = $bdd->prepare("INSERT INTO users(pseudo, email, password, cle) VALUE (:pseudo, :email, :password, :cle)");
					$req->bindParam(':pseudo', $pseudo);
					$req->bindParam('email', $email);
					$req->bindParam('password', $password_hashing);
					$req->bindParam('cle', $cle);
					$req->execute();

					header('location: inscription.php?success=1');
					exit();

				} catch (PDOException $e) {
					echo "Erreur lors de l'inscription" . $e->getMessage();
				}
			}
		}

	}
	// Fermer la connexion à la base de données
	$bdd = null;
	

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
						echo '<div class="alert error">'.htmlspecialchars($_GET['message']). '</div>';
					}
				}else if(isset($_GET['success'])) {
					echo '<div class="alert success">Votre inscription a bien été pris en compte. <a href="index.php">Connectez-vous.</a> </div>';
				}
			
			?>
			<form method="post" action="inscription.php">
				<input type="text" name="pseudo" placeholder="Votre pseudo" required >
				<input type="email" name="email" placeholder="Votre adresse email" required >
				<input type="password" name="password" placeholder="Mot de passe" required >
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required >
				<input class="button" type="submit" name="button" value="S'inscrire">
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>