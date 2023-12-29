<?php
    session_start(); //initialisation
    session_unset(); //desativation de la section
    session_destroy(); //detruire la section

    header('location: index.php');
?>