<?php

    /**
     * Vérifie si l'email envoyé par l'utilisateur existe dèjà ou non.
     *
     * @param string $email
     * @param PDO $db
     * 
     * @return boolean retourne true si l'email existe et false dans le cas contraire.
     */
    function already_exists(string $email, PDO $db): bool
    {
        // Vérifier si l'email correspond à celui d'un utilisateur existant dans la base de données.
        $request = $db->prepare("SELECT id FROM user WHERE email=:email");
        $request->bindValue(":email", $email);
        $request->execute();

        // Si c'est le cas,
        if ( $request->rowCount() == 1 ) 
        {
            // la fonction retourne true
            return true;
        }
        
        // Dans le cas contraire,
        // la fonction retourne false
        return false;
    }


    /**
     * Récupère l'utilisateur connecté
     *
     * @param PDO $db
     * @return array|null
     */
    function getUser(PDO $db): array|null
    {
        // Vérifions si s'il existe un utilisateur dont les informations ont été sauvegardées en session
        // Si ce n'est pas le cas,
        if ( !isset($_SESSION['auth']) || empty($_SESSION['auth']) ) 
        {
            // Alors, la fonction retourne null pour indiquer que l'utilisateur n'a pas été trouvé.
            return null;
        }

        // Dans le cas contraire,
        // Vérifions tout de même, si l'identifiant de l'utilisateur présent dans la session, existe bel et bien en base de données.
        $request = $db->prepare("SELECT * FROM user WHERE id=:id");
        $request->bindValue(":id", $_SESSION['auth']['id']);
        $request->execute();


        // S'il n'existe pas
        if ($request->rowCount() != 1) 
        {
            // Alors, la fonction retourne null pour indiquer que l'utilisateur n'a pas été trouvé.
            return null;
        }
        
        // Dans le cas contraire,
        // Retourner les informations de ce dernier.
        return $request->fetch();
    }