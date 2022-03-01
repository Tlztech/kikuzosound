<?php
namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Carbon\Carbon;

class Model extends EloquentModel
{
  /**
  * Return a timestamp as a localized DateTime object.
  *
  * @param  mixed  $value
  * @return \Carbon\Carbon
  */
  protected function asDateTime($value)
  {
    $carbon = parent::asDateTime($value);

    $tz = 'Asia/Tokyo';
    // FIXME: 将来的にユーザ毎のタイムゾーンで表示内容を変えたい。 
    if(\Auth::check() && \Auth::user()->timezone) {
      $tz = \Auth::user()->timezone;
    }
    $timezone = new \DateTimeZone($tz);
    $carbon->setTimezone($timezone);
    return $carbon;
  }

  /**
  * Convert a localized DateTime to a normalized storable string.
  *
  * @param  \DateTime|int  $value
  * @return string
  */
  public function fromDateTime($value)
  {
    $save = parent::fromDateTime($value);

    $format = $this->getDateFormat();
    $timezone = 'Asia/Tokyo';
    // FIXME: 将来的にユーザ毎のタイムゾーンで表示内容を変えたい。 
    if(\Auth::check() && \Auth::user()->timezone) {
      $timezone = new \DateTimeZone(\Auth::user()->timezone);
    } 
    $carbon = Carbon::createFromFormat($format, $value, $timezone);
    $carbon->setTimezone(\Config::get('app.timezone'));
    $save = $carbon->format($format);
    return $save;
  }
}
