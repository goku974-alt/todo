<?php
/**
 * logout.php - Déconnexion utilisateur
 * 
 * Compatible Hostinger Mutualisé
 */

session_start();
session_destroy();

header('Location: index.php');
exit;
