<?php

namespace App\Presenters;

use Nette;
use App\Model\PhotoModel;
class PhotoPresenter extends Nette\Application\UI\Presenter {

    private $photomodel;
    private $filemodel;

    public function __construct(PhotoModel $photomodel)
    {
        $this->photomodel=$photomodel;
        
    }


    public function actionPhoto($image){
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: http://localhost:3000");
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            $this->sendJson("xxx");
          }

        if($_SERVER["REQUEST_METHOD"]=="POST") {

            $res=$this->photomodel->addPhoto();
            $this->sendJson($res);
        }

        if($_SERVER["REQUEST_METHOD"]=="GET" && $image!=1) {
            $file="file";
            header("Content-Description: File Transfer"); 
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"". $file ."\""); 
            $httpResponse = $this->getHttpResponse();
            $this->photomodel->downloadPhoto($image);
         
        }

        if($_SERVER["REQUEST_METHOD"]=="DELETE") { 

            $res=$this->photomodel->deletePhoto($image);
            $this->sendJson($res);

        }

        if($_SERVER["REQUEST_METHOD"]=="GET" && $image==1) { 

            $res=$this->photomodel->getAllPhotos();
            $this->sendJson($res);
        }
    }

    public function actionView($image){

       $this->photomodel->viewPhoto($image);
        


    }


}