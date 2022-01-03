<?php

/**
 * Contrôleur de l'administrateur
 */
class ControllerAdmin
{
    /**
     *
     */
    public function __construct()
    {
        global $rep,$vues;
        session_start();

        try
        {
            if (!isset($_REQUEST['action']))
                $action = NULL;
            else
                $action = $_REQUEST['action'];
            switch ($action)
            {
                case 'connect' :
                    $this->connect();
                    break;
                case 'deconnect' :
                    $this->deconnect();
                    break;
                case 'add-news' :
                    $this->addNews();
                    break;
                case 'del-news' :
                    $this->delNews();
                    break;
                default:
                    $dVueEreur [] = "Erreur d'appel php";
                    require($rep.$vues['erreur']);
                    break;
            }
        }
        catch (PDOException $e)
        {
            global $rep,$vues;
            $dVueEreur [] = "Erreur exception";
            require($rep.$vues['erreur']);
        }
    }

    public function connect()
    {
        $login = $_POST['user_login'];
        $mdp = $_POST['user_mdp'];
        $mdl = new ModeleAdmin();

        $login = Nettoyage::nettoyerChaine($login);
        $mdp = Nettoyage::nettoyerChaine($mdp);
        $mdl->connection($login, $mdp);

        $_REQUEST['action'] = NULL;
        new ControllerUtil();
    }

    public function deconnect()
    {
        $mdl = new ModeleAdmin();

        $mdl->deconnection();

        $_REQUEST['action'] = NULL;
        new ControllerUtil();
    }

    public function addNews()
    {
       $title = $_POST['news_title'];
       $date =  $_POST['news_date'];
       $content =  $_POST['news_content'];

       $title = Nettoyage::nettoyerChaine($title);
       $date = Nettoyage::nettoyerChaine($date);

       $mdl = new Modele();
       $mdl->insertNews($date, $title, $_SESSION['login'], $content);

        $_REQUEST['action'] = NULL;
        new ControllerUtil();
    }

    public function delNews()
    {
        $id = $_GET['id'];
        $mdl = new Modele();

        $news = $mdl->findNewsById($id);
        $mdl->deleteNews($news);

        $_REQUEST['action'] = NULL;
        new ControllerUtil();
    }
}