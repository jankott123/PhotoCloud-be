<?php

namespace App\Presenters;

use App\Model\AlbumModel;
use Nette;

class AlbumPresenter extends Nette\Application\UI\Presenter
{
    private $albummodel;

    public function __construct(AlbumModel $Albummodel)
    {
        $this->albummodel = $Albummodel;
    }

    public function actionAlbum($album)
    {

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: http://localhost:3000");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            $this->sendJson("xxx");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $res = $this->albummodel->addAlbum();
            $this->sendJson($res);
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $res = $this->albummodel->getAlbum($album);
            $this->sendJson($res);
        }

        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            $res = $this->albummodel->deleteAlbum($album);
            $this->sendJson($res);
        }

       
    }

    public function actionshare(){
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: http://localhost:3000");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
           $res=$this->albummodel->shareAlbum();
            $this->sendJson($res);
        }
    }
}
