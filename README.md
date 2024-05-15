# Afin de déployer et tester l'application sur vos machines, voici la marche à suivre :
- Avec la console/terminal placez vous dans votre dossier de projets
- Faites ensuite git clone https://github.com/ComN-Stay/blog.git
- Ouvrez le projet dans VSCode
- Dans la console de VSCode faites composer update
- Renseignez les paramètres de votre base de données dans le .env
- IMPORTANT : un des bundle a un petit bug qui fait planter la génération des fixtures, pour y remédier, remplacez le fichier "lorem.php" situé dans vendor/fakerphp/faker/provider par celui que j'ai mis (lorem.php aussi) à la racine du projet et vous pouvez ensuite supprimer le fichier lorem.php de la racine
- Dans la console de VSCode saisissez les commandes suivantes :
  - php bin/console doctrine:database:create
  - php bin/console doctrine:migration:migrate
  - php bin/console doctrine:fixture:load

Le blog est prêt à être utilisé !
Pour tester, voici les login / mot de passe pour un utilisateur et un administrateur :
- Utilisateur :
  - login : user1@blog.local
  - mot de passe : Password@2024
- Administrateur :
  - login : admin@blog.local
  - mot de passe : Password@2024
