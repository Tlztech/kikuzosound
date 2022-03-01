<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
   * 一時ファイルとセッションを削除する
   *
   * @param 削除するパス
   */
  protected function removeTmpFile($filepath)
  {
    if ( !empty($filepath) ) {
      if ( \File::exists($filepath) ) {
        \File::delete($filepath);
      }
    }
  }

  /**
   * ファイルを移動し、相対URLを返す
   *
   * @param Request $request
   * @param string $base_url
   * @param string $last_id
   * @param string $file_input_name
   * @param string $path_input_name
   * @return string ファイルへの相対URLパス（失敗時はnull）
   */
  protected function moveFile($request, $base_url, $last_id, $file_input_name, $path_input_name)
  {
    $base_dir = public_path() . $base_url;

    $filename = '';
    $success = false;
    // アップロードファイルがある場合、そのファイルを優先する。
    if ( $request->hasFile($file_input_name) ) {
      $file = $request->file($file_input_name);
      $ext  = $file->getClientOriginalExtension();
      $filename = $last_id . '.' . $ext;
      $success = $file->move($base_dir, $filename);
    }
    // パスだけがある場合
    else if ( $request->has($path_input_name) && \File::exists(public_path() . $request->input($path_input_name)) ) {
      $src_filepath = public_path() . $request->input($path_input_name);
      $ext = \File::extension($src_filepath);
      $filename = $last_id . '.' . $ext;
      $success = \File::move($src_filepath, $base_dir . $filename);
    }
    if ( $success ) {
      return $base_url . $filename;
    }
    else {
      return null;
    }
  }

   /**
   * moveMultipleFiles
   * @param Request $request
   * @param string $base_url
   * @param string $last_id
   * @param string $file_input_name
   * @param string $path_input_name
   * @return array Filepath
   */
  protected function moveMultipleFiles($request, $base_url, $video_base_url, $last_id, $file_input_name, $path_input_name)
  {
    $base_dir = public_path() . $base_url;
    $multi_filename = [];
    $filename = '';
    $success = false;
    if ( $request->hasFile($file_input_name) ) {
        $files = $request->file($file_input_name);
        $tmp_url=[];
        foreach ($files as $index => $f) {
          $ext  = $f->getClientOriginalExtension();
          if($ext=="mp4" || $ext=="mpeg-4"){
            $base_dir = public_path() .  $video_base_url;
            $base_url = $video_base_url;
          }
          $filename = $last_id.'_'.$index. '.' . $ext;
          $success =  $f->move($base_dir, $filename);
          if($success){
            array_push($tmp_url,$base_url . $filename);
          }else{
            $success = false;
            break;
          }
        }
        $multi_filename = $tmp_url;
    }
    else if ( $request->has($path_input_name)) {
        $path_tmp_url=[];
        $files = $request->input($path_input_name);
        foreach ($files as $index => $f) {
          if(!\File::exists(public_path() . $f)){
            $success = false;
            break;
          }
          $src_filepath = public_path() . $f;
          $ext = \File::extension($src_filepath);
          if($ext=="mp4" || $ext=="mpeg-4"){
            $base_dir = public_path() .  $video_base_url;
            $base_url = $video_base_url;
          }
          $filename = $last_id.'_'.$index. '.' . $ext;
          $success =   \File::move($src_filepath, $base_dir . $filename);
          if($success){
            array_push($path_tmp_url,$base_url . $filename);
          }else{
            $success = false;
            break;
          }
        }
        $multi_filename = $path_tmp_url;
        

    }
    if ( $success ) {
      return $multi_filename;
    }
    else {
      return null;
    }
  }

    /**
   * 単体ファイルを移動し、セッションを保存します。
   *
   * @param string $file_input_name ファイルのフォームのインプット名 
   * @param string $tmp_dir         ファイルの一時保存先
   * @param string $session_key     一時保存先のパスを保存するセッションキー
   * @return string $url            相対URL（失敗時はnull）
   */
  protected function setSessionAndMoveSingleFile($request, $file_input_name, $tmp_dir, $session_key,$is_multiple_file=false)
  {
    // 聴診音ファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
    if ( $request->hasFile($file_input_name) ) {
      // ファイルを公開エリアに移動する
      $file = $request->file($file_input_name);
      if($is_multiple_file){
        $tmp_url=[];
        foreach ($file as $index => $f) {
          $url = $this->moveTempFile($f, $tmp_dir);
          array_push($tmp_url,$url);
        }
        \Session::set($session_key, $tmp_url);
      }else{
        $url = $this->moveTempFile($file, $tmp_dir);
        // 一時的にセッションにパスに格納しておき、Bladeで表示するようにした。
        // old関数を利用できるようにしたかったが、そのためのAPIがLaravelにないため。
        \Session::set($session_key, $url);
      }
    }
    return true;
  }

  /**
   * ファイルを移動し、相対URLを返します。
   *
   * @param UploadedFile $file
   * @param string $baseurl
   * @return string 相対URL
   */
  protected function moveTempFile($file, $baseurl)
  {
    $ext = $file->getClientOriginalExtension();
    $filename = uniqid() . '.' . $ext;
    $tmpdir = public_path() . $baseurl;
    $file->move( $tmpdir, $filename );

    return $baseurl . $filename;
  }

}
