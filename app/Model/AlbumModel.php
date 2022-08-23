<?php

namespace App\Model;


use App\Model\Authorization;
use App\Model\EntityManager;
use Nette;

class AlbumModel
{

    private $authorization;
    private $manager;

    public function __construct(Authorization $authorization, EntityManager $manager)
    {

        $this->authorization = $authorization;
        $this->manager = $manager;
    }

    public function addAlbum()
    {
        $auth_id = $this->authorization->authorize();
        $albums = array(
            "name" => [],
            "id" => [],
        );

        if (isset($_POST['albumName']) && $_POST['albumName'] == "") {
            return "empty";
        }

        if (isset($_POST['albumName']) && $auth_id && !empty($_POST['albumName'])) {
            if (!preg_match('/^[a-zA-Z0-9]{1}+[ \.a-zA-Z0-9_-]{0,28}+$/', $_POST['albumName'])) {
                return "BadFormat";
            }
            $name = $_POST['albumName'];
            $id = random_int(99, 999999);
            $this->manager->insertAlbum($name, $id, $auth_id);
            $this->manager->insertUserAlb($id, $auth_id);
            $albums['name'] = $name;
            $albums['id'] = $id;
            return $albums;
        }
    }

    public function getAlbum($album)
    {
        $auth_id = $this->authorization->authorize();

        if ($auth_id) {
            $response = $this->manager->getAlbumPhotos($auth_id, $album);
            return $response;
        }
    }

    public function deleteAlbum($album)
    {
        $auth_id = $this->authorization->authorize();

        // mazat muze pouze ten kdo album vytvoril
        if ($auth_id) {

            $this->manager->deleteAlbum($auth_id, $album);
            $albumname = $this->manager->getAlbum($auth_id);
            $pole = array(
                "albumname" => $albumname
            );
            return $pole;
        }
    }

    public function shareAlbum()
    {
        $auth_id = $this->authorization->authorize();
        if ($auth_id) {
            $result = $this->manager->authorizeAlbum($auth_id, $_POST['sharedAlbum']);
            $userID = $this->manager->getIduser($_POST['userName']);

            if (!$userID) {
                return "UserNotExist";
            }
            if ($result && $userID) {
                $this->manager->shareAlbum($userID, $_POST['sharedAlbum']);
                return "Shared!";
            }
        }
    }
}
