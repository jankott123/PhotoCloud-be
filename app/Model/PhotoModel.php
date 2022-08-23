<?php


namespace App\Model;


use App\Model\Authorization;
use Nette;
use Nettrine\ORM\EntityManagerDecorator;
use Exception;
use App\Model\StorageManager;

class PhotoModel
{


    private $authorization;
    private $manager;
    private $storage;

    public function __construct(Authorization $authorization, EntityManager $manager, StorageManager $storage)
    {

        $this->authorization = $authorization;
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function addPhoto()
    {
        $auth_id = $this->authorization->authorize();

        if ($auth_id) {
            if (!empty($_FILES["fileUpload"]["name"])) {
                if ($_POST['selection'] == "empty") {
                    return "SelectAlbum";
                }

                for ($i = 0; $i < count($_FILES['fileUpload']['name']); $i++) {

                    $filename = time() . rand(1000000, 9999999);
                    $filename = $filename . ".jpg";
                    $this->storage->upload_object($_ENV['BUCKET_NAME'], $filename, $_FILES['fileUpload']['tmp_name'][$i]);


                    if ($_POST['selection'] == "null") {
                        $_POST['selection'] = null;
                    }

                    $this->manager->insert($filename, $auth_id, $_POST['selection']);
                }
                $filename = $this->manager->render($auth_id);
                return $filename;
            }
        }
    }

    // overit podle id uzivatele
    public function viewPhoto($image)
    {


        $auth_id = $this->authorization->authorize();

        if ($auth_id) {

            $result = $this->manager->checkPhoto($image);
            if ($result == $auth_id) {
                $image = $this->storage->download_object($_ENV['BUCKET_NAME'], $image);
                $imageData = (file_get_contents($image));
                echo $imageData;
            }
        }


        // authorizace ke sdilenym album a fotkam
        $result = $this->manager->getShareAlbum($auth_id);
        $valid = false;
        for ($i = 0; $i < count($result); $i++) {


            if ($image == $result[$i]->Filename) {
                $valid = true;

                $i = count($result);
            }
        }
        // vykresleni fotky ze sdileneho alba
        if ($valid) {
            $this->authorization->refreshToken();
            $image = $this->storage->download_object($_ENV['BUCKET_NAME'], $image);
            $imageData = (file_get_contents($image));

            echo $imageData;
        } else {
        }
    }

    public function downloadPhoto($image)
    {
        $auth_id = $this->authorization->authorize();
        $result = $this->manager->checkDelete($image, $auth_id);

        if ($auth_id && $result) {
           
            $image = $this->storage->download_object($_ENV['BUCKET_NAME'], $image);
            readfile($image);
        }
    }

    public function deletePhoto($image)
    {

        $auth_id = $this->authorization->authorize();
        if ($auth_id) {
            $result = $this->manager->checkDelete($image, $auth_id);
            if ($result) {
                $this->manager->delete($image, $auth_id);
                $this->storage->delete_object($_ENV['BUCKET_NAME'], $image);
                $filename = $this->manager->render($auth_id);
                return $filename;
            }
        }
    }

    public function getAllPhotos()
    {

        $auth_id = $this->authorization->authorize();


        if ($auth_id) {
            $filename = $this->manager->render($auth_id);
            $albumname = $this->manager->getAlbum($auth_id);
            $pole = array(
                "filename" => $filename,
                "albumname" => $albumname
            );
        }

        return $pole;
    }
}
