<?php 
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
	session_start();
	
	// Initialiser les variables
	$email = $password = "";
	
	// Vérifier si le formulaire a été soumis
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Récupérer les données du formulaire et les nettoyer
		$email = htmlspecialchars($_POST["email"]);
		$password = htmlspecialchars($_POST["password"]);

		//IMPORT BASE DE DONNEE
		require('src/connection.php');
	
		try {
			// Vérifier les identifiants de connexion dans la base de données
			$req = $bdd->prepare("SELECT * FROM users WHERE email = :email");
			$req->bindParam(':email', $email);
			$req->execute();
			$user = $req->fetch(PDO::FETCH_ASSOC);
	
			if ($user && password_verify($password, $user['password'])) {
				$_SESSION['connect'] = 1;
				$_SESSION['pseudo'] = $user['pseudo'];

				header('Location: index.php/?success=1');
				exit();
				
			} else {
				header('Location: index.php/?error=1&message=Mot de passe ou Email incorrect.');
				exit();
			}
		} catch (PDOException $e) {
			echo "Erreur lors de la connexion : " . $e->getMessage();
		}
	}
	
	// Fonction pour nettoyer les données du formulaire
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
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

	<?php if(isset($_SESSION['connect'])) { ?>
		<h1>Bonjour !</h1>
		<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
	<?php } else { ?>
	
		<section>
			<div id="login-body">
				<h1>S'identifier</h1>
				<?php 
					if(isset($_GET['error'])) {
						if(isset($_GET['message'])) {
							echo '<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
						}
					} else if(isset($_GET['success'])) {
						echo '<div class="alert success">Vous etes bien connecté</div>';
					}
				?>

				<form method="post" action="index.php">
					<input type="email" name="email" placeholder="Votre adresse email" required />
					<input type="password" name="password" placeholder="Mot de passe" required />
					<input class="button" type="submit" value="S'identifier" >
					<label id="option"><input type="checkbox" name="cle" checked />Se souvenir de moi</label>
				</form>
			

				<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
			</div>
		</section>
	<?php } ?>

	<?php include('src/footer.php'); ?>
</body>
</html>