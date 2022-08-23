<?php

namespace App\Model;

use App\Model\Entity\User;
use App\Model\Entity\Album;
use App\Model\Entity\Fotka;
use App\Model\Entity\UserAlb;
use App\Model\Service;
use App\Model\Service as ModelService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Nette;
use Nettrine\ORM\EntityManagerDecorator;
use Doctrine\ORM\Query\Parameter;
use Exception;

class EntityManager
{

    private $decorator;

    public function __construct(EntityManagerDecorator $entity)
    {
        $this->decorator = $entity;
    }

    public function deleteAll()
    {
        $query = $this->decorator->createQuery('SELECT u FROM App\Model\Entity\User u ORDER BY u.id DESC');


        return $query->getResult();
    }
    public function checkUser($username)
    {
        return $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["username" => $username]);
    }

    public function checkEmail($email)
    {
        return $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["email" => $email]);
    }

    public function registerUser(string $username, string $password, string $email, string $uniqid)
    {

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setActivation_code($uniqid);

        $this->decorator->persist($user);

        $this->decorator->flush();
    }

    public function activation(string $email, string $activation_code)
    {
        return $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["email" => $email, "activation_code" => $activation_code]);
    }

    public function activationFinal(string $email, string $activation_code, string $activated)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["email" => $email, "activation_code" => $activation_code]);

        $user->setActivation_code("activated");

        $this->decorator->flush();
    }

    public function loginCheck(string $username)
    {

        $res = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["username" => $username]);
        $pass = $res->getPassword();
        $act = $res->getActivation_code();
        $id = $res->getId();
        $username = $res->getUsername();
        $array = array('password' => $pass, 'activation_code' => $act, 'id' => $id, 'username' => $username);
        return $array;
    }

    public function insert(string $filename, int $id_user, $id_album)
    {

        $user_id = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id_user]);
        $album_id = $this->decorator->getRepository("App\Model\Entity\Album")->findOneBy(["id" => $id_album]);


        $photo = new Fotka();
        $photo->setFilename($filename);
        $photo->setId_user($user_id);
        $photo->setId_album($album_id);

        $this->decorator->persist($photo);

        $this->decorator->flush();
    }

    public function checkId(int $id)
    {
        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id]);
        $id = $user->getId();
        $username = $user->getUsername();

        return $array = array('id' => $id, 'username' => $username);
    }

    public function render(int $id_user)
    {
        $id = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id_user]);

        $qb = $this->decorator->createQueryBuilder();
        $qb->select('u')
            ->from('App\Model\Entity\Fotka', 'u')
            ->innerJoin('App\Model\Entity\User', 'g', 'WITH', 'u.id_user = g.id')
            ->where('g.id = :idee')
            ->setParameter('idee', $id);

        $query = $qb->getQuery();

        $res = $query->getResult();

        $arr = array();

        for ($i = 0; $i < count($res); $i++) {
            $arr[$i] = new Service();
            $arr[$i]->Filename = $res[$i]->getFilename();
            $arr[$i]->id = $res[$i]->getId();
        }


        return $arr;
    }

    public function checkPhoto($photoname)
    {

        $query = $this->decorator->createQuery('SELECT IDENTITY(u.id_user) FROM App\Model\Entity\Fotka u WHERE u.filename = :name');
        $query->setParameter('name', $photoname);
        $user = $query->getResult();

        return $user[0][1];
    }

    public function getAlbum($id)
    {

        $id = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id]);

        $qb = $this->decorator->createQueryBuilder();
        $qb->select('u')
            ->from('App\Model\Entity\Album', 'u')
            ->innerJoin('App\Model\Entity\UserAlb', 'g', 'WITH', 'u.id = g.id_album')
            ->where('g.id_user = :idee')
            ->setParameter('idee', $id);

        $query = $qb->getQuery();

        $query = $query->getResult();

        $arr = array();


        for ($i = 0; $i < count($query); $i++) {
            $arr[$i] = new Service();
            $arr[$i]->name = $query[$i]->getName();
            $arr[$i]->id = $query[$i]->getId();
        }

        return $arr;
    }

    public function checkDelete($image, $auth_id)
    {

        $query = $this->decorator->createQuery('SELECT u FROM App\Model\Entity\Fotka u WHERE u.filename = :name AND u.id_user = :id');
        $query->setParameters(array(
            'name' => $image,
            'id' => $auth_id
        ));

        $user = $query->getResult();

        return $user;
    }

    public function delete($image, $auth_id)
    {

        $query = $this->decorator->createQuery('DELETE App\Model\Entity\Fotka u WHERE u.id_user = :id AND u.filename= :name');
        $query->setParameters(array(
            'name' => $image,
            'id' => $auth_id
        ));

        return $query->getResult();
    }



    public function insertAlbum($name, $id, $auth_id)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $auth_id]);
        $my_date = new DateTime('now');

        $album = new Album();
        $album->setName($name);
        $album->setId($id);
        $album->setDate($my_date);
        $album->setUserID($user);

        $this->decorator->persist($album);

        $this->decorator->flush();
    }

    public function insertUserAlb($id_album, $id_user)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id_user]);
        $album = $this->decorator->getRepository("App\Model\Entity\Album")->findOneBy(["id" => $id_album]);


        $useralb = new UserAlb;
        $useralb->setId_album($album);
        $useralb->setId_user($user);

        $this->decorator->persist($useralb);

        $this->decorator->flush();
    }

    public function getAlbumPhotos($auth_id, $album)
    {
        $qb = $this->decorator->createQueryBuilder();

        $qb->select('u')
            ->from('App\Model\Entity\Fotka', 'u')
            ->innerJoin('App\Model\Entity\Album', 'g', 'WITH', 'u.id_album = g.id')
            ->innerJoin('App\Model\Entity\UserAlb', 'a', 'WITH', 'a.id_album=g.id')
            ->where('a.id_user = :id_user')
            ->andWhere('a.id_album = :id_album')
            ->setParameters(new ArrayCollection([
                new Parameter('id_user', $auth_id),
                new Parameter('id_album', $album)
            ]));

        $query = $qb->getQuery();

        $query = $query->execute();
        $arr = array();

        for ($i = 0; $i < count($query); $i++) {
            $arr[$i] = new Service();
            $arr[$i]->Filename = $query[$i]->getFilename();
            $arr[$i]->id = $query[$i]->getId();
        }

        return $arr;
    }

    public function deleteAlbum(int $auth_id, int $id_album)
    {

        $query = $this->decorator->createQuery('DELETE App\Model\Entity\Album u WHERE u.id = :id AND u.userID= :user_Id');
        $query->setParameters(array(
            'id' => $id_album,
            'user_Id' => $auth_id
        ));

        $query->getResult();
    }

    public function authorizeAlbum($auth_id, $album_id)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\Album")->findOneBy(["id" => $album_id, 'userID' => $auth_id]);

        return $user;
    }

    public function getIdUser($user_name)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["username" => $user_name]);

        return $user->getId();
    }

    public function shareAlbum(int $id_user, $id_album)
    {

        $user = $this->decorator->getRepository("App\Model\Entity\User")->findOneBy(["id" => $id_user]);
        $album = $this->decorator->getRepository("App\Model\Entity\Album")->findOneBy(["id" => $id_album]);

        $useralb = new UserAlb;
        $useralb->setId_album($album);
        $useralb->setId_user($user);

        $this->decorator->persist($useralb);

        $this->decorator->flush();
    }

    public function getShareAlbum(int $auth_id)
    {



        $qb = $this->decorator->createQueryBuilder();
        $qb->select(array('u'))
            ->from('App\Model\Entity\Fotka', 'u')
            ->innerJoin('App\Model\Entity\Album', 'g', 'WITH', 'u.id_album = g.id')
            ->innerJoin('App\Model\Entity\UserAlb', 'a', 'WITH', 'a.id_album=g.id')
            ->where('a.id_user = :id_user')
            ->setParameters(new ArrayCollection([
                new Parameter('id_user', $auth_id),

            ]));

        $query = $qb->getQuery();

        $query = $query->getResult();


        $arr = array();

        for ($i = 0; $i < count($query); $i++) {
            $arr[$i] = new Service();
            $arr[$i]->Filename = $query[$i]->getFilename();
        }

        return $arr;
    }
}
