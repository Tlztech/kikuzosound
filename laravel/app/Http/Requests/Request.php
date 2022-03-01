<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

abstract class Request extends FormRequest
{
  protected $dates = [];

  public function authorize()
  {
    return true;
  }

  // 検証後のコールバック（after）を呼び出すため、オーバーライドする。
  protected function getValidatorInstance()
  {   
    return parent::getValidatorInstance()->after(function($validator) {
      $this->after($validator);
    });
  }

  /**
   * 検証（validate）後に呼ばれる
   * @param validator $validator validator
   */
  public function after($validator)
  {
    // FIXME: フェーズ１では'Asia/Tokyo'固定。ユーザのタイムゾーンを考慮してい変換するべき。
    $tz = 'Asia/Tokyo';

    $merge = [];
    foreach ($this->dates as $key => $value) {
      $d = Carbon::parse(\Input::get($value),$tz)->toW3cString();
      $merge[$value] = $d;
    }
    $this->merge($merge);
  }

  /**
   * 単体ファイルを移動し、セッションを保存します。
   *
   * @param string $file_input_name ファイルのフォームのインプット名 
   * @param string $tmp_dir         ファイルの一時保存先
   * @param string $session_key     一時保存先のパスを保存するセッションキー
   * @return string $url            相対URL（失敗時はnull）
   */
  protected function setSessionAndMoveSingleFile($file_input_name, $tmp_dir, $session_key)
  {
    // 聴診音ファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
    if ( $this->hasFile($file_input_name) ) {
      // ファイルを公開エリアに移動する
      $file = $this->file($file_input_name);
      $url = $this->moveFile($file, $tmp_dir);
      // 一時的にセッションにパスに格納しておき、Bladeで表示するようにした。
      // old関数を利用できるようにしたかったが、そのためのAPIがLaravelにないため。
      \Session::set($session_key, $url);

      return $url;
    }
    return null;
  }

  /**
   * ファイルを移動し、相対URLを返します。
   *
   * @param UploadedFile $file
   * @param string $baseurl
   * @return string 相対URL
   */
  protected function moveFile($file, $baseurl)
  {
    $ext = $file->getClientOriginalExtension();
    $filename = uniqid() . '.' . $ext;
    $tmpdir = public_path() . $baseurl;
    $file->move( $tmpdir, $filename );

    return $baseurl . $filename;
  }
}
