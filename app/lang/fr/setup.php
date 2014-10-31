<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Setup language lines
	|--------------------------------------------------------------------------
	|
	| This file defines language lines used by the setup pages
	|
	*/

	"installer"         => "Installer Sticky Notes",
	"welcome"           => "Merci d'avoir choisi Sticky Notes. Le programme d'installation vous guidera tout au long ".
	                       "du processus d'installation. Suivez les étapes simples pour une installation sans tracas!",
	"develop_warn"      => "<b>Important:</b> Vous essayez de mettre en place une pré-version. Il est ".
	                       "fortement recommandé de ne pas installer les versions de développement en serveur de ".
	                       "production. Pour télécharger la dernière version stable à la place, <a href='%s'>cliquer ici</a>.",
	"i_stage1_title"    => "Étape 1: Configuration de la base de données ",
	"i_stage1_exp"      => "Ouvrez votre fichier de configuration de base de données situé à <code>app/config/database.php</code> ".
	                       "et remplir vos détails de BDD. Sticky Notes utilise une connexion active uniquement, alors assurez-vous ".
	                       "que vous modifiez le pilote de base de données «par défaut» en conséquence. voir <a href='http://codebright".
	                       ".daylerees.com/database'>le guide</a> pour plus d'informations sur la configuration BDD.",
	"i_stage2_title"    => "Étape 2: Prêt à installer",
	"i_stage2_exp"      => "Vos paramètres de base de données semblent bonnes. Lorsque vous êtes prêt, cliquez sur le <code>Démarrer ".
	                       "l'installation</code> bouton pour commencer le processus automatisé d'installation.",
	"i_stage3_title"    => "Étape 3: Installation en cours",
	"i_stage3_exp"      => "Sticky Notes est installé sur votre serveur. Cela peut prendre plusieurs minutes...",
	"i_stage4_title"    => "Étape 4: Installation complète",
	"i_stage4_exp"      => "Sticky Notes a été installation avec succès! Se il vous plaît prendre note les ".
	                       "informations d'identification pour se connecter au panneau d'administration.",
	"u_stage1_title"    => "Stage 1: Sélectionnez la version de mise à jour",
	"u_stage1_exp"      => "L'utilitaire de mise à niveau de vos tables de base de données avec les données les plus récentes. Vous devez être ".
	                       "exécutant l'un des versions suivantes de Post-it billets déjà pour pouvoir utiliser cette ".
	                       "outil. S'il vous plaît faire en sorte que vous sélectionnez la bonne version ci-dessous.",
	"u_stage2_title"    => "Étape 2: Mise à jour en cours",
	"u_stage2_exp"      => "Sticky Notes se met mis à jour. Cela peut prendre plusieurs minutes...",
	"u_stage3_title"    => "Étape 3: Mise à jour complète",
	"u_stage3_exp"      => "Sticky Notes a été mis à jour avec succès! Vous pouvez maintenant commencer à utiliser votre nouvelle version.",
	"proceed_login"     => "Procéder à la connexion &rarr;",
	"click_check"       => "Une fois que vous avez rempli les informations correctes BDD, cliquez sur le bouton de ".
	                       "<code>Test de connexion</code>",
	"update_config"     => "Si vous souhaitez garder la configuration de votre ancien site (paramètres) et migré vers la nouvelle Sticky Notes, ".
	                       "s'il vous plaît placer votre ancien <code>config.php</code> à l'intérieur du dossier <code>app/config</code>.",
	"test_connection"   => "Teste de connexion",
	"test_fail"         => "Connexion de base de données a échoué avec l'erreur suivante: %s",
	"install_warn"      => "<b>Important</b>: Le programme d'installation va supprimer toutes les tables Sticky Notes. Si vous mettez à niveau à partir de ".
	                       "une version antérieure, utilisez le %s à la place.",
	"update_util"       => "utilitaire de mise à jour",
	"start_install"     => "Démarrer l'installation",
	"initializing"      => "Initialitation...",
	"create_table"      => "Création tableau: %s",
	"create_index"      => "Création des indexes...",
	"almost_done"       => "Presque terminé...",
	"install_complete"  => "Installation complète.",
	"update_complete"   => "Update complète.",
	"complete"          => "complète",
	"error_occurred"    => "Une erreur est survenue",
	"error_title"       => "Programme d'installation à échoué",
	"error_exp"         => "Une erreur est survenue et l'installation a été abandonnée. Le message d'erreur a été affichée ci-dessous:",
	"process_version"   => "Installation de nouveaux changements après la version %s...",
	"update_from"       => "Mise à jour de",
	"start_update"      => "Démarrer mise à jour",
	"return_sn"         => "Revenir à Sticky Notes &rarr;",
	"ldap_update_warn"  => "Il semble que vous utilisiez l'authentification LDAP. Les nouvelles versions de Sticky Notes ".
	                       "les connexions de support pour les utilisateurs et les administrateurs à la différence de connexions ".
	                       "d'administration uniquement dans les versions antérieures. ".
	                       "Compte tenu de cela, un nouveau paramètre <code>admin filters</code> a été ajouté à votre LDAP ".
	                       "options de configuration dont vous aurez besoin de mettre dans le panneau d'administration. Sans cette option, ".
	                       "tout les utilisateurs qui se connectent obtiendront des privilèges d'administrateur.",
	"update_notifs"     => "Notifications",
	"update_notifs_exp" => "Ce sont les messages de notification générés par mises à jours individuels que vous avez effectués dans le cadre ".
	                       "du processus de mise à jour. Ces messages peuvent contenir des avertissement de sécurité importante, il est donc fortement ".
	                       "recommandé de les lires et de prendre les mesures proposées.",
	"notify_version"    => "Version %s mise à jour",

);
