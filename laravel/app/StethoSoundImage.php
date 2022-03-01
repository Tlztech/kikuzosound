<?php

namespace App;

use App\Model;
use \File;
use \Log;

class StethoSoundImage extends Model
{
  protected $dates = ['created_at', 'updated_at'];
  protected $fillable = ['stetho_sound_id', 'title', 'image_path', 'disp_order', 'lang'];

  /**
   * 削除する際に画像ファイルも削除します。 
   */
  public function delete() {
    if($this->attributes['image_path']){
      $path = $this->attributes['image_path'];
      $full_path = public_path($path);

      if(File::isFile($full_path) && File::delete($full_path)){
        Log::info("聴診音画像を削除しました　path=".$full_path);
      } else {
        Log::error("聴診音画像の削除に失敗しました　path=".$full_path);
      }
    }
    parent::delete();
  }
}
