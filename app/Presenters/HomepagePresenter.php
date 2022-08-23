<?php

declare(strict_types=1);

namespace App\Presenters;


use Nette;



final class HomepagePresenter extends Nette\Application\UI\Presenter
{

  public function __construct()
  {
  }

  public function actionDefault()
  {
    $x=5;
    var_dump($_ENV['API_KEY']);
  }
}
