<?php

namespace App\Http\Paginators;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginatorContract;
use Illuminate\Pagination\UrlWindow as BaseUrlWindow;

class UrlWindow extends BaseUrlWindow
{
  /**
   * Get the starting URLs of a pagination slider.
   *
   * @return array
   */
  public function getStart()
  {
    return $this->paginator->getUrlRange(1, 1);
  }

  /**
   * Get the ending URLs of a pagination slider.
   *
   * @return array
   */
  public function getFinish()
  {
    return $this->paginator->getUrlRange(
      $this->lastPage() ,
      $this->lastPage()
      );
  }
}
