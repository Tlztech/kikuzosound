<?php
namespace App\Http\Paginators;

use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use App\Http\Paginators\UrlWindow;

class PagingPaginator extends BootstrapThreePresenter 
{

  // 現在のページの両隣のみ表示させるため、BootstrapThreePresenterのコンストラクタをオーバライド
  public function __construct(PaginatorContract $paginator, UrlWindow $window = null)
  {
    $this->paginator = $paginator;
    $this->window = is_null($window) ? UrlWindow::make($paginator,1) : $window->get();
  }

  /**
   * Convert the URL window into Bootstrap HTML.
   *
   * @return string
   */
  public function render()
  {
    if ($this->hasPages()) {
      return sprintf(
        '<ul class="pager_list">%s %s %s</ul>',
        $this->getPreviousButton(),
        $this->getLinks(),
        $this->getNextButton()
      );
    }
    return '';
  }

  /**
   * Get HTML wrapper for an available page link.
   *
   * @param  string  $url
   * @param  int  $page
   * @param  string|null  $rel
   * @return string
   */
  protected function getAvailablePageWrapper($url, $page, $rel = null)
  {
    $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

    // if ($page == '<' || $page == '>') {
    //   return '<li class="number"><a href="'.htmlentities($url).'"'.$rel.'>'.$page.'</a></li>';
    // }

    return '<li class="number"><a href="'.htmlentities($url).'"'.$rel.'>'.$page.'</a></li>';
  }

  /**
   * Get HTML wrapper for disabled text.
   *
   * @param  string  $text
   * @return string
   */
  protected function getDisabledTextWrapper($text)
  {
    return '<li class=""><span>'.$text.'</span></li>';
  }

  /**
   * Get HTML wrapper for active text.
   *
   * @param  string  $text
   * @return string
   */
  protected function getActivePageWrapper($text)
  {
    return '<li class="active"><span>'.$text.'</span></li>';
  }
}