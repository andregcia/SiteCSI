<?php

include "Billets.php";
include "Categorie.php";
include "Users.php";
include "Base.php";

class Vue{ 
	
	function AffichePage($content, $menuleft, $menuright, $barre) {
		
		echo "
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
		\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
		<head>
		<title>Blog de Gael</title>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
		<style type=\"text/css\">
		* { margin:0; padding:0; word-wrap:break-word; }
		td{padding:10px}
		BODY { background-color:#fff;
		font-size:16px;
		}
		
		.formulaire{
			display: block;
			width: 100px;
			float: left;
			margin-left: 400px;				
		}
		.description{
			
			margin-left: 300px;				
		}
								
		.tableauB{
			width: 500px;
			height: 250px;		
		}		
		
		.tableauC{
			width: 500px;
			height: 150px;		
		}		
		.tableau{
			margin: auto;
		}		
				
		.centrer{
			text-align : center;
		}	
				
		.right{
			text-align: right;
		}			
		
		.left{
			text-align: left;
		}
				
		.underline{
			text-decoration: underline;
		}	
				
		.titre{
			font-size: 25px;
		}		
						
						
		#entete { background-color:#ccc;
		padding:8px; }
		
		#barre { background-color:#F5F5F5;
		padding:8px; }
		
		#gauche { float:left;
		width:200px;
		padding:8px;
		background-color:#bbb; }
		
		#droite { float:right;
		width:200px;
		padding:8px;
		background-color:#aaa }
		
		#centre { margin-right:216px;
		margin-left:215px;
		padding:8px;
		background-color:#fff;
		border: 1px solid #eee;}
		
		#basdepage { clear:both;
		background-color:#888;
		padding:8px; }
		</style>
		</head>
		
		<body>
		<div class=\"centrer\" id=\"entete\"><h1>Blog de Ga�l</h1>";
		
		if(empty($_SESSION)){
			echo "<p class= \"right\"><a href=\"Admin.php?action=login\">Connexion</a></p>
		<p class= \"right\"><a href=\"Admin.php?action=register\">Inscription</a></p></div>";
		}
		else{
			echo "<p class= \"right\">Connect� en tant que  <a href= \"Blog.php?action=profil&amp;id=".$_SESSION['id']."\">".$_SESSION['pseudo']."</a></p>
		<p class= \"right\"><a href=\"Admin.php?action=logout\">D�connexion</a></p></div>";
				
			
		}
		
		
		
		echo "<div id=\"barre\"><div class=\"centrer\">$barre
		</div></div>
		
		
		<div><div id=\"gauche\"><div class=\"centrer\"><b>Cat�gories</b></div><br/>
		$menuleft</div>
		
		<div id=\"droite\"><div class=\"centrer\"><b>Billets</b></div><br/>
		$menuright</div>
		
		<div id=\"centre\"><br/>
		$content</div></div>
		
		<div id=\"basdepage\">"; 
				if(!empty($_SESSION)){
						echo("<p class= \"left\"><a href=\"Blog.php?action=listU\">Liste des utilisateurs</a></p>");
				}
				echo"</div>
		</body>
		</html>";
		
	}
	
	
	
	
	function afficheBillet($billet){
		
		$html = "<span class = \"underline\">Billet n�".$billet->getAttr('id')."</span><div class=\"centrer\"><h3><b><span class = \"underline\">".$billet->getAttr('titre')."</span></b></h3></div><br/>";
		$titreC = Categorie::findById($billet->getAttr('cat_id'));
		$html .= "<p class = \"right\"><span class = \"underline\">Cat�gorie :</span> ".$titreC->getAttr('titre')."    </p><br/><br/>";
		$html .= $billet->getAttr('body')."<br/><br/>";
		$u = Users::findById($billet->getAttr('iduser'));
		$html .= "<p class = \"right\">Post� par : <a href=\"Blog.php?action=profil&amp;id=".$u->getAttr('id')."\">".$u->getAttr('pseudo')."</a><i> le ".$billet->getAttr('date')."</i></p><br/><hr/>";
		if((!empty($_SESSION)) && (($_SESSION['id'] == $billet->getAttr('iduser')) || ($_SESSION['statut'] == "admin"))){ 
		
		$html .= "<p style=\"float: left;\"><a href=\"Admin.php?action=editB&amp;id=".$billet->getAttr('id')."\">Editer ce billet</a></p>
			<p style=\"float: right;\"><a href=\"Admin.php?action=deleteB&amp;id=".$billet->getAttr('id')."\">Supprimer ce billet</a></p>";
		}
		
		return($html);
	}
	
	
	function afficheBilletL($billet){
		
		$html = "<span class = \"underline\">Billet n�".$billet->getAttr('id')."</span><div class=\"centrer\"><h3><b><span class = \"underline\">".$billet->getAttr('titre')."</span></b></h3></div><br/>";
		$titreC = Categorie::findById($billet->getAttr('cat_id'));
		$html .= "<p class = \"right\"><span class = \"underline\">Cat�gorie :</span> ".$titreC->getAttr('titre')."    </p><br/><br/>";
		$chaine = $billet->getAttr('body');
		$fin = substr($chaine, 40);
		$final = str_replace($fin, "...  <a href = Blog.php?action=detail&amp;id=".$billet->getAttr('id').">[Lire la suite]</a>", $chaine);
		$html .= $final."<br/><br/>";
		$u = Users::findById($billet->getAttr('iduser'));
		$html .= "<p class = \"right\">Post� par : <a href=\"Blog.php?action=profil&amp;id=".$u->getAttr('id')."\">".$u->getAttr('pseudo')."</a><i> le ".$billet->getAttr('date')."</i></p><br/><hr/>";
		if((!empty($_SESSION)) && (($_SESSION['id'] == $billet->getAttr('iduser')) || (($_SESSION['statut'] == "admin") ||($_SESSION['statut'] == "superAdmin")))){ 
		
		$html .= "<p style=\"float: left;\"><a href=\"Admin.php?action=editB&amp;id=".$billet->getAttr('id')."\">Editer ce billet</a></p>
			<p style=\"float: right;\"><a href=\"Admin.php?action=deleteB&amp;id=".$billet->getAttr('id')."\">Supprimer ce billet</a></p>";
		}
		
		return($html);
	}
	
	
	
	function afficheCat($categorie){
		
		$html = "<span class = \"underline\">Cat�gorie n�".$categorie->getAttr('id')."</span><div class=\"centrer\"><h3><b><span class = \"underline\">".$categorie->getAttr('titre')."</span></b></h3></div><br/>";
		$html .= $categorie->getAttr('description')."<br/><br/><hr/>";
		if($categorie->getAttr('id') != 1){
			if((!empty($_SESSION)) && (($_SESSION['statut'] == "admin") || ($_SESSION['statut'] == "superAdmin"))){
				$html .= "<p style=\"float: left;\"><a href=\"Admin.php?action=editC&amp;id=".$categorie->getAttr('id')."\">Editer cette cat�gorie</a></p>
					<p style=\"float: right;\"><a href=\"Admin.php?action=deleteC&amp;id=".$categorie->getAttr('id')."\">Supprimer cette cat�gorie</a></p>";
				
			}
		}
		return($html);
	}
	
	function afficheListeBillet($billets){
		
		$html = "<div><table class = \"tableau\" cellspacing=\"20\" border=\"5\"><caption><span class = \"titre\"><b> Liste des billets</b></span></caption> ";
		
		foreach($billets as $billet){
			$html .= "<tr><td class = \"tableauB\">".$this->afficheBilletL($billet)."</td></tr>";
		}
		$html .= "</table></div>";
		
		return($html);
	}
	
	function afficheListeCat($categories){
		
		$html = "<div><table class = \"tableau\" cellspacing=\"20\" border=\"5\"><caption> <span class = \"titre\"><b>Liste des cat�gories</b></span></caption>";
		
		foreach($categories as $categorie){
			$html .= "<tr><td class = \"tableauC\">".$this->afficheCat($categorie)."</td></tr>";
		}
		$html .= "</table></div>";
		
		return($html);
	}
	
	function menuDroit(){
		
		$billets = Billets::findAll();
		
		$html ="<div class=\"centrer\"><a href=\"Blog.php?action=list\">Tous les billets</a></div><br/> ";
		foreach($billets as $billet){
			$html .= "<div class=\"centrer\"><a href=\"Blog.php?action=detail&amp;id=".$billet->getAttr('id')."\">".$billet->getAttr('titre')."</a></div>";
		}
		if(!empty($_SESSION)){
		$html .= "<br/><div class=\"centrer\"><a href=\"Admin.php?action=addM\">Ajouter un billet</a></div>";
		}
		return($html);
	}
	
	function menuGauche(){
		
		$categories = Categorie::findAll();
		$html = "<div class=\"centrer\"><a href=\"Blog.php?action=listC\">Toutes les cat�gories</a></div><br/> ";
		foreach($categories as $categorie){
			$html .= "<div class=\"centrer\"><a href=\"Blog.php?action=cat&amp;id=".$categorie->getAttr('id')."\">".$categorie->getAttr('titre')."</a></div>";
		}
		if((!empty($_SESSION)) && (($_SESSION['statut'] == "admin") || ($_SESSION['statut'] == "superAdmin"))){
		$html .= "<br/><div class=\"centrer\"><a href=\"Admin.php?action=addC\">Ajouter une cat�gorie</a></div>";
		}
		return($html);
	}
	
	function ajoutCategorie(){
		$html = "<form action=\"Admin.php?action=addC\" method=\"post\">
			<p class = \"centrer\">
			<input type=\"text\" name=\"titre\" value=\"titre de votre cat�gorie\" /><br/><br/>
			<textarea name=\"description\" rows=\"15\" cols=\"60\">
			Votre description ici </textarea><br/><br/>
			<input type=\"submit\" name=\"valider\" value=\"Valider\" />
			</p>
			</form>";
		
		return($html);
	}
	
	function ajoutBillet(){
		$html = "<form action=\"Admin.php?action=addM\" method=\"post\">
			<p class = \"centrer\"> <input type=\"text\" name=\"titre\" value= \"titre de votre billet\"/><br/><br/>
			<textarea name=\"body\" rows=\"15\" cols=\"60\">
			Votre message ici
			</textarea><br/><br/>
			<select name=\"categorie\">";
		
		$lc = Categorie::findAll();
		foreach($lc as $cat){
			
			$html .= "<option value=\"".$cat->getAttr('id')."\">".$cat->getAttr('titre')."</option>";
		}
		
		$html .= "</select><br/><br/>
			<input type=\"submit\" value=\"Valider\" />
			</p>
			</form>";
		
		return($html);
	}
	
	
	function editBillet($billet){ 
		
		$idB = $billet->getAttr('id');
		$titreB = $billet->getAttr('titre');
		$bodyB = $billet->getAttr('body');
		
		$html = "<form action=\"Admin.php?action=editB&amp;id=$idB\" method=\"post\">
			<p class=\"centrer\"> <input type=\"text\" name=\"titre\" value= \"$titreB\"/><br/><br/>
			<textarea name=\"body\" rows=\"15\" cols=\"60\">
			$bodyB
			</textarea><br/><br/>
			
			<select name=\"categorie\">";
		
		$lc = Categorie::findAll();
		foreach($lc as $cat){
			if($cat->getAttr('id') == $billet->getAttr('cat_id')){
				$html .= "<option value=\"".$cat->getAttr('id')."\" selected = \"selected\">".$cat->getAttr('titre')."</option>";
			}
			else{
				$html .= "<option value=\"".$cat->getAttr('id')."\">".$cat->getAttr('titre')."</option>";
			}
		}
		
		$html .= "</select><br/><br/>
			<input type=\"submit\" value=\"Valider\" />
			</p>
			</form>";
		
		return($html);
	}
	
	
	function editCat($categorie){ 
		
		$idC = $categorie->getAttr('id');
		$titreC = $categorie->getAttr('titre');
		$descriptionC = $categorie->getAttr('description');
		
		$html = "<form action=\"Admin.php?action=editC&amp;id=$idC\" method=\"post\">
			<p class=\"centrer\"> <input type=\"text\" name=\"titre\" value= \"$titreC\"/><br/><br/>
			<textarea name=\"description\" rows=\"15\" cols=\"60\">
			$descriptionC
			</textarea><br/><br/>
			
			
			<input type=\"submit\" value=\"Valider\" />
			</p>
			</form>";
		
		return($html);
	}
	
	function deleteBillet($billet){
		$idB = $billet->getAttr('id');
		
		$html = "<div class=\"centrer\"><h2>�tes vous s�r de vouloir supprimer ce billet ?</h2><br/>
			<form action=\"Admin.php?action=deleteB&amp;id=$idB\" method=\"post\">
			<p>
			<input type=\"submit\" name= \"Oui\" value=\"Oui\" />
			<input type=\"submit\" name= \"Non\" value=\"Non\" /></p></form></div>";
		
		return($html);
	}
	
	function deleteCat($categorie){
		$idC = $categorie->getAttr('id');
		
		$html = "<div class=\"centrer\"><h2>�tes vous s�r de vouloir supprimer cette cat�gorie ?</h2><br/>
			<form action=\"Admin.php?action=deleteC&amp;id=$idC\" method=\"post\">
			<p>
			<input type=\"submit\" name= \"Oui\" value=\"Oui\" />
			<input type=\"submit\" name= \"Non\" value=\"Non\" /></p></form></div>";
		
		return($html);
	}
	
	
	function inscription(){
		$html = "<form action=\"Admin.php?action=register\" method=\"post\">
			<p>
			<span class = \"formulaire\">Pseudo : </span><input type=\"text\" name=\"pseudo\" value=\"Votre pseudo\" /><br/>
			<span class = \"formulaire\">Mot de passe : </span><input type=\"password\" name=\"password\" value=\"mdp\" /><br/>
			<span class = \"formulaire\">E-Mail : </span><input type=\"text\" name=\"mail\" value=\"Votre mail\" /><br/>
			<span class = \"formulaire\">Sexe : </span><select name=\"sexe\">
   			<option value=\"Homme\">Homme</option>
    		<option value=\"Homme\">Femme</option>
			</select><br/>
			<span class = \"formulaire\">Pays : </span><input type=\"text\" name=\"pays\" value=\"Votre pays\" /><br/>	
			<span class = \"formulaire\">Ville : </span><input type=\"text\" name=\"ville\" value=\"Votre ville\" /><br/>
			<span class = \"description\">&nbsp;</span><textarea name=\"description\" rows=\"15\" cols=\"60\">
			Votre description
			</textarea><br/>				
			<span class = \"formulaire\">&nbsp; </span><input type=\"submit\" value=\"Valider\"  />
			</p>
			</form>";
		
		return($html);
	}
	
	function connexion(){
		
		$html = "<form action=\"Admin.php?action=login\" method=\"post\">
			<p class=\"centrer\">
			Pseudo : <input type=\"text\" name=\"pseudo\" value=\"Votre pseudo\" /><br/>
			Mot de passe :<input type=\"password\" name=\"password\" value=\"mdp\" /><br/>
			<input type=\"submit\" value=\"Valider\"  />
			</p>
			</form>";
		
		
		return($html);
		
	}
	
	function profil($user){
		$html = "<div class=\"centrer\"><span class = \"underline\">".$user->getAttr('pseudo')."</span>
				<br/><br/>".$user->getAttr('mail')."
				<br/><br/>".$user->getAttr('sexe')."
				<br/><br/>".$user->getAttr('pays')."
				<br/><br/>".$user->getAttr('ville')."
				<br/><br/>".$user->getAttr('description')."</div>";
				if($_SESSION['id'] == $user->getAttr('id')){
				$html .= "<p class = \"right\"><a href=\"Admin.php?action=editP&amp;id=".$user->getAttr('id')."\">Editer le profil</a></p>"; 		
				}
				return($html);
	}
	
	function deleteUser($user){
		$idU = $user->getAttr('id');
		
		$html = "<div class=\"centrer\"><h2>�tes vous s�r de vouloir supprimer cet utilisateur ?</h2><br/>
			<form action=\"Admin.php?action=deleteU&amp;id=$idU\" method=\"post\"><p>
			<input type=\"submit\" name= \"Oui\" value=\"Oui\" />
			<input type=\"submit\" name= \"Non\" value=\"Non\" /></p></form></div>";
		
		return($html);
	}
	
	
	function addAdmin($user){
		$idU = $user->getAttr('id');
		
		$html = "<div class=\"centrer\"><h2>�tes vous s�r de vouloir donner les droits d'administrateur � cet utilisateur ?</h2><br/>
			<form action=\"Admin.php?action=addAdmin&amp;id=$idU\" method=\"post\"><p>
			<input type=\"submit\" name= \"Oui\" value=\"Oui\" />
			<input type=\"submit\" name= \"Non\" value=\"Non\" /></p></form></div>";
		
		return($html);
	}
	
	function deleteAdmin($user){
		$idU = $user->getAttr('id');
		
		$html = "<div class=\"centrer\"><h2>�tes vous s�r de vouloir �ter les droits d'administrateur � cet utilisateur ?</h2><br/>
			<form action=\"Admin.php?action=deleteAdmin&amp;id=$idU\" method=\"post\"><p>
			<input type=\"submit\" name= \"Oui\" value=\"Oui\" />
			<input type=\"submit\" name= \"Non\" value=\"Non\" /></p></form></div>";
		
		return($html);
	}
	
	function afficheListeUsers($users){
		
		$html = "<div><table class = \"tableau\" border=\"5\" cellspacing=\"10\" cellpadding=\"30\"><caption><span class = \"titre\"><b> Liste des utilisateurs</b></span> </caption>";
		
		foreach($users as $user){
			$idU = $user->getAttr('id');
			$html .= "<tr><td >".$user->getAttr('statut')."</td>";
			$html .= "<td ><a href=\"Blog.php?action=profil&amp;id=$idU\">".$user->getAttr('pseudo')."</a></td>";
			$html .= "<td >".$user->getAttr('mail')."</td>";
			if(($_SESSION['statut'] == "superAdmin") && ($idU != 1) && ($_SESSION['id'] != $idU)){
				$html .= "<td><a href=\"Admin.php?action=deleteU&amp;id=$idU\">Supprimer cet utilisateur</a></td>";
				if($user->getAttr('statut') != "admin"){
					$html .= "<td><a href=\"Admin.php?action=addAdmin&amp;id=$idU\">Ajouter un administrateur</a></td></tr>";
				}
				else{
					if($_SESSION['statut'] == "superAdmin"){
						$html .= "<td><a href=\"Admin.php?action=deleteAdmin&amp;id=$idU\">Supprimer un administrateur</a></td></tr>";
					}
				}
			}
			else{
				$html .= "</tr>";
			}
			
		}
		$html .= "</table></div>";
		
		return($html);
	}
	
	function editProfil($user){
		
		$html = "<form action=\"Admin.php?action=editP&amp;id=".$user->getAttr('id')."\" method=\"post\">
			<p class=\"centrer\">
			Pseudo : <input type=\"text\" name=\"pseudo\" value=\"".$user->getAttr('pseudo')."\" /><br/>
			Mot de passe : <input type=\"password\" name=\"password\" value=\"".$user->getAttr('password')."\" /><br/>
			E-Mail : <input type=\"text\" name=\"mail\" value=\"".$user->getAttr('mail')."\" /><br/>
			Sexe : <select name=\"sexe\">
   			<option value=\"Homme\">Homme</option>
    		<option value=\"Homme\">Femme</option>
			</select><br/>
			Pays : <input type=\"text\" name=\"pays\" value=\"".$user->getAttr('pays')."\" /><br/>
			Ville : <input type=\"text\" name=\"ville\" value=\"".$user->getAttr('ville')."\" /><br/>		
			<textarea name=\"description\" rows=\"15\" cols=\"60\">
			".$user->getAttr('description')."
			</textarea><div class=\"centrer\"><br/>				
			<input type=\"submit\" value=\"Valider\"  />
			</p>
			</form>";
		
		return($html);
		
		
	}
	
	
	function firstRegister(){
		$html = "<div class = \"centrer\">Veuillez entrez le mot de passe administrateur ! </div>
				<div class = \"centrer\">Login = admin</div>
				<form action=\"Blog.php?action=firstR\" method=\"post\">
			<p class = \"centrer\">
			Mot de passe : <input type=\"password\" name=\"password\" value=\"mdp\" /><br/>				
			<input type=\"submit\" value=\"Valider\"  />
			</p>
			</form>";
		
		return($html);
	}
	
}

?>