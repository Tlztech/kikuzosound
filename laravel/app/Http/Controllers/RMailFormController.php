<?php

namespace App\Http\Controllers;

use Input;
use Illuminate\Http\Request;    // telemedica 20180810
use App\Lib\Member; // telemedica 20180810
use App\Account;    // telemedica 20180810
use App\Deletes;    // telemedica 20180810
use App\TrialMembers;   // telemedica 20190228
use App\Professions;   // telemedica 20190228
use App\TrialmembersProfessions;   // telemedica 20190228
use App\Groups;   // telemedica 20190228
use App\Services;   // telemedica 20190228
use App\TrialmembersServices;   // telemedica 20190228
use App\Identification;   // telemedica 20190228
use Session;    // telemedica 20180810
use DB;
use Validator;
use Mail;
use Log;
use App\Http\Requests\RMailFormRequest;
use Carbon\Carbon;
use Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;    // 例外補足
use App\Lib\IniFile;    /* telemedica 20180109 */
use Illuminate\Support\Facades\App;

define('M_CAREMANA_NO',"100"); // ケアマネの職種番号(固定)
define('M_IMAGE_PATH',"/img/trialmembers/");   // 画像を保存する相対パス

class RMailFormController extends Controller
{
  /* iniファイルを読み取り、設定 */
  private $adIni;
  // telemedica 20170615
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
    
    /* iniファイルを読み取り、設定 */
    $this->adIni = new IniFile();
  }

  // 試聴音登録フォーム
  public function rmailform(Request $request)
  {
    $edit = $request->edit;    // getパラメータ取得(あれば何でもOK)
    $suid = $request->suid;    // getパラメータ取得(DBのid暗号化)

    Session::forget('SAMPLE-USER-PASSWORD');     // 削除(パスワード)

    setcookie("imgaccess","true");  // .htaccessで許可

    // 新規or確認画面から(初期値)
    $mail = "";
    $name1 = "";
    $name2 = "";
    $kana1 = "";
    $kana2 = "";
    $yearSelect = "1969";
    $monthSelect = "1";
    $daySelect = "1";
    $gender = "";
    $kind = "1";
    $caremane = array();
    $group = "";
    $graduationYear = "2020";
    $services1 = "";
    $services2 = "";
    $services3 = "";
    $services4 = "";
    $services5 = "";
    $identification = "none";
    $tel = "";

    $editimage = 0;   // 0:画像はない 1:画像はある

    if(empty($suid)) {  // ない(getで送られていない)場合
        // $suidはDBのidを暗号化(暗号化はAjaxSampleLogin.php)
        $suid = Session::get('SAMPLE-3SP-USER');
    } else {    // ある(getで送られている)場合
        Session::put('SAMPLE-3SP-USER',$suid);  // suid設定
    }

    if(!empty($edit) && !empty($suid)) {    // Myページの編集の場合
      $id = $this->decryptID($suid); // DBのidを復号化

      /****************************************************************/
      /*
        DBのselectをfirstOrFailでまとめて行って、失敗時、例外をキャッチ
        https://readouble.com/laravel/5.1/ja/eloquent.html

        getでは例外は投げないが、これはとりあえずそのままにしておく
        恐らくfirstOrFailのどれかで例外が投げられるので

        因みに例外の補足にはuseが必要。[firstOrFail 例外]で検索。下記参照の事
        https://qiita.com/ma_me/items/959e1f734ff8054360cb
      */
      /****************************************************************/
      try {
        // DBから取得(会員情報)
        $TrialMembers = TrialMembers::where('id',$id)->where('deleted_at', NULL)->firstOrFail();
        // DBから取得(職種)
        $T_P = TrialmembersProfessions::where('trialmember_id',$id)->where('deleted_at', NULL)->get();
        // DBから取得(勤務先・学校)
        $Groups = Groups::where('trialmember_id',$id)->where('deleted_at', NULL)->firstOrFail();
        // DBから取得(診療科目)
        $T_S = TrialmembersServices::where('trialmember_id',$id)->where('deleted_at', NULL)->get(); // 会員-診療科目(中間テーブル)
        // DBから取得(本人確認)
        $Identification = Identification::where('trialmember_id',$id)->where('deleted_at', NULL)->firstOrFail();
      } catch (ModelNotFoundException $e) {
        $flush_message = "データが読み込めませんでした";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBから取得(会員情報)
      /****************************************************************/
      $mail = $TrialMembers->mail;
      $name1 = $TrialMembers->name1;
      $name2 = $TrialMembers->name2;
      $kana1 = $TrialMembers->kana1;
      $kana2 = $TrialMembers->kana2;
      $yearSelect = $TrialMembers->yearSelect;
      $monthSelect = $TrialMembers->monthSelect;
      $daySelect = $TrialMembers->daySelect;
      $gender = $TrialMembers->gender;

      /****************************************************************/
      // DBから取得(職種)
      /****************************************************************/
      foreach($T_P as $professions) { // 本職1つと(あれば)ケアマネの最大2つ
        $pid = $professions->profession_id; // [FK]職種
        $TrialProfessions = Professions::where('id',$pid)->where('deleted_at', NULL)->first();  // 職種[含むケアマネ](マスタ)

        if($TrialProfessions->profession_no == M_CAREMANA_NO) { //ケアマネの場合
          $caremane = array("1");   // ケアマネ設定
        } else {    // 職種はケアマネ以外は1つしかない前提
          $kind = $TrialProfessions->profession_no; // 本職設定
        }
      }

      /****************************************************************/
      // DBから取得(勤務先・学校)
      /****************************************************************/
      $group = $Groups->group_name;

      if(!empty($Groups->graduationYear)) { // リクエストに卒業予定年がある場合
        $graduationYear = $Groups->graduationYear; // 卒業予定年(学生の場合)
      }

      /****************************************************************/
      // DBから取得(診療科目)
      /****************************************************************/
      foreach($T_S as $ts) {
        $sid = $ts->service_id; // [FK]診療科目

        $Services = Services::where('id',$sid)->where('deleted_at', NULL)->first(); // 診療科目(マスタ)
        $services_no = $Services->services_no;  // 診療科目番号

        switch($ts->disp_order) {   // 順番で設定
          case 1:   // 必須
            $services1 = $services_no;
            break;
          case 2:   // 任意
            $services2 = $services_no;
            break;
          case 3:   // 任意
            $services3 = $services_no;
            break;
          case 4:   // 任意
            $services4 = $services_no;
            break;
          case 5:   // 任意
            $services5 = $services_no;
            break;
        }
      }

      /****************************************************************/
      // DBから取得(本人確認)
      /****************************************************************/
      $identification = $Identification->type;  // none tel image
      $tel = $Identification->tel;  // 電話番号
    }

    /* 
    編集で、画像がDBにある場合は、
    「前回の画像をそのまま使用する場合は、選択の必要はありません。」
    を表示する為だけに、以下のブロックの処理を行っている
    */
    if(!empty($suid)) { // 編集の場合
      $id = $this->decryptID($suid); // DBのidを復号化

      try {
        // DBから取得(本人確認)
        $Identification = Identification::where('trialmember_id',$id)->where('deleted_at', NULL)->firstOrFail();
      } catch (ModelNotFoundException $e) {
        $flush_message = "データが読み込めませんでした";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      if(!empty($Identification->image_name)) { // 画像がある場合
        $editimage = 1;   // 0:画像はない 1:画像はある
      }
    }

    /*
        診療科目1～5

        old(laravelの直前のリクエストのフラッシュデータ)がある場合はoldを優先
        ない場合は初期値(何もなし)またはDBからの値

        view側でBladeテンプレートのoldヘルパを使うのが楽だが、プログラム化の為
    */
    $services_old = $request->old('services1'); // 診療科目1のold
    if(!empty($services_old)) { // oldがある場合
      $services1 = $services_old;   // oldを使用
    }

    $services_old = $request->old('services2'); // 診療科目2のold
    if(!empty($services_old)) { // oldがある場合
      $services2 = $services_old;   // oldを使用
    }

    $services_old = $request->old('services3'); // 診療科目3のold
    if(!empty($services_old)) { // oldがある場合
      $services3 = $services_old;   // oldを使用
    }

    $services_old = $request->old('services4'); // 診療科目4のold
    if(!empty($services_old)) { // oldがある場合
      $services4 = $services_old;   // oldを使用
    }

    $services_old = $request->old('services5'); // 診療科目5のold
    if(!empty($services_old)) { // oldがある場合
      $services5 = $services_old;   // oldを使用
    }

    return view('r-mail-form',compact('mail','name1','name2','kana1','kana2','yearSelect','monthSelect','daySelect','gender','kind','caremane','group','graduationYear','services1','services2','services3','services4','services5','identification','tel','edit','editimage'));
  }

  /* getで送られたトークンの確認と本登録 */
  public function authtoken(Request $request)
  {
    DB::beginTransaction();

    $urltoken = $request->urltoken; // getパラメータ取得

    // DBでトークンを「仮登録者・仮登録から24H以内」で同一があるか検索
    $TrialMembers = TrialMembers::where('urltoken',$urltoken)->where('status_flag',0)->where('deleted_at', NULL)->whereRaw('urltoken_at > now() - interval 24 hour')->first();

    // トークンが空の場合とDBに指定のトークンがない場合
    if(empty($urltoken) || empty($TrialMembers)) {
      DB::rollback();

      $flush_message = $this->createTokenFail();  // メッセージ作成
      return redirect()->route('contact')->with('message',$flush_message);
    }

    // 以下、トークンが正しい場合の正常処理

    // 登録完了メール用にメアド・名前を取得
    $mail = $TrialMembers->mail;    // メアド
    $name1 = $TrialMembers->name1;  // 姓
    $name2 = $TrialMembers->name2;  // 名
    $name = $name1."　".$name2;     // 名前

    // 本登録
    $TrialMembers->status_flag = 1; // 0:仮登録 1:本登録
    $db_success = $TrialMembers->save();  // update

    if(!$db_success) {  // DBエラーの場合
      DB::rollback();

      $flush_message = "本登録できませんでした。";
      return redirect()->route('contact')->with('message',$flush_message);
    }

    // 登録完了メール送信関連
    $to = $mail; // 宛先
    $subject = '【3sp】聴診会員ライブラリ利用登録完了のお知らせ';  // タイトル
    $body = $this->createThanksText($name,$mail); // メール本文

    // メール送信
    $success = Mail::raw($body, function ($message) use ($to, $subject) {
      $message ->to($to)->subject($subject);
    });

    DB::commit();

    // 登録完了のページ
    return view('r-form',compact('urltoken'));
  }

  // メアド変更時の認証処理 meado
  /* getで送られたトークンの確認とメアド登録変更 */
  public function changemail(Request $request)
  {
    DB::beginTransaction();

    $urltoken = $request->urltoken; // getパラメータ取得

    // DBでトークンを「本登録・メアド変更から24H以内」で同一があるか検索
    $TrialMembers = TrialMembers::where('urltoken',$urltoken)->where('status_flag',1)->where('deleted_at', NULL)->whereRaw('urltoken_at > now() - interval 24 hour')->first();

    // トークンが空の場合とDBに指定のトークンがない場合
    if(empty($urltoken) || empty($TrialMembers)) {
      DB::rollback();

      $flush_message = $this->createTokenFail();  // メッセージ作成
      return redirect()->route('contact')->with('message',$flush_message);
    }

    // 以下、トークンが正しい場合の正常処理

    // 登録完了メール用にメアド・名前を取得
    $mail = $TrialMembers->mail;    // メアド
    $name1 = $TrialMembers->name1;  // 姓
    $name2 = $TrialMembers->name2;  // 名
    $name = $name1."　".$name2;     // 名前

    // メアドのバックアップを新しいメアドに変更
    $TrialMembers->mail_backup = $TrialMembers->mail; // メアドコピー
    $db_success = $TrialMembers->save();  // update

    if(!$db_success) {  // DBエラーの場合
      DB::rollback();

      $flush_message = "本登録できませんでした。";
      return redirect()->route('contact')->with('message',$flush_message);
    }

    // 登録完了メール送信関連
    $to = $mail; // 宛先
    $subject = '【3sp】聴診会員ライブラリ　メールアドレス変更完了のお知らせ';  // タイトル
    $body = $this->changeThanksText($name,$mail); // メール本文

    // メール送信
    $success = Mail::raw($body, function ($message) use ($to, $subject) {
      $message ->to($to)->subject($subject);
    });

    DB::commit();

    $flush_message = "メールアドレスの変更が完了しました。";
    return redirect()->route('contact')->with('message',$flush_message);
  }

  /**
   * 「試聴音利用ご登録」の確認画面の表示
   *
   */
  public function confirm(RMailFormRequest $request)
  {
    $name1    = $request->input('name1');
    $name2    = $request->input('name2');
    $kana1    = $request->input('kana1');
    $kana2    = $request->input('kana2');
    $mail     = $request->input('mail');
    $password    = $request->input('password');
    $yearSelect    = $request->input('yearSelect');
    $monthSelect    = $request->input('monthSelect');
    $daySelect    = $request->input('daySelect');
    $gender      = $request->input('gender');
    $tel      = $request->input('tel');
    $image_files      = $request->input('image_files');
    $kind     = $request->input('kind');
    $caremane     = $request->input('caremane');
    $services1     = $request->input('services1');
    $services2     = $request->input('services2');
    $services3     = $request->input('services3');
    $services4     = $request->input('services4');
    $services5     = $request->input('services5');
    $graduationYear     = $request->input('graduationYear');
    $group    = $request->input('group');
    $identification    = $request->input('identification');
    $edit    = $request->input('edit');

    $request->flash();

    // $suidはDBのidを暗号化(暗号化はAjaxSampleLogin.php)
    $suid = Session::get('SAMPLE-3SP-USER');

    // パスワードはhiddenだと見えてしまうのでセッションで
    Session::forget('SAMPLE-USER-PASSWORD');     // 削除(パスワード)
    Session::put('SAMPLE-USER-PASSWORD',$password);  // 登録(パスワード)

    // 職種によって必要ないパラメータを初期化
    // 因みにケアマネは職種によりJSで削除している
    $services1 = $this->noDataForServices($kind,$services1);  // 診療科目
    $services2 = $this->noDataForServices($kind,$services2);  // 診療科目
    $services3 = $this->noDataForServices($kind,$services3);  // 診療科目
    $services4 = $this->noDataForServices($kind,$services4);  // 診療科目
    $services5 = $this->noDataForServices($kind,$services5);  // 診療科目
    $graduationYear = $this->noDataForGraduationYear($kind,$graduationYear);   // 卒業予定年
    $identification = $this->noDataForID($kind,$identification);   // ご本人確認
    $image_files = $this->noDataForID($kind,$image_files); // 画像
    $tel = $this->noDataForServices($kind,$tel);  // 電話
    
    /*
    DBはmailをUNIQUEにしている(メアドを唯一にする必要がある為)
    なので、仮でも本でも同一メアドは不可とする(status_flagは見ない)

    物理削除は取得したメアドが消えるので避けたい
    * 但し、本登録で[登録削除]の場合は物理削除
    * 仮登録の場合はソフトデリート

    仮登録で終了し24時間後に再登録は許可する為、ソフトデリート時にメアドを変更
    メアドを変更しないと同じメアドで再登録しようとしてもDBのUNIQUEでできない
    これを回避する為にメアドの後ろに.telemedica.co.jpを付けて、ソフトデリート
    これなら同一になる事はない
    */
//    $TrialMembers = TrialMembers::where('mail',$mail)->where('status_flag',1)->where('deleted_at', NULL)->first();

    // 仮登録・本登録共に既に登録されているメアドかチェック
    $TrialMembers = TrialMembers::where('mail',$mail)->where('deleted_at', NULL)->first();

    $checkmail = 1; // 0;正常 1:異常

    if(empty($TrialMembers)) {  // 登録されているメアドがない場合
      $checkmail = 0; // 0;正常 1:異常

      // 拒絶メアドを取得
      $mailtext = $this->adIni->getConfig('mail','reject','false');
      $mailarray = explode(",",$mailtext);  // 一応、配列に

      if(in_array($mail,$mailarray,true)) { // 拒絶メアドの場合
        $checkmail = 2; // 0;正常 1:異常 2:拒絶
      }

      // 拒絶ドメインを取得
      $domaintext = $this->adIni->getConfig('domain','reject','false');
      $domainarray = explode(",",$domaintext);  // 一応、配列に

      if(!empty($domainarray)) {    // 拒絶ドメインがある場合
        $domainmailarray = explode("@",$mail);  // メアドの分割
        $domainmail = array_pop($domainmailarray);  // メアドのドメイン取得

        if(!empty($domainmail)) {   // メアドのドメインがある場合
          if(in_array($domainmail,$domainarray,true)) { // 拒絶ドメインの場合
            $checkmail = 2; // 0;正常 1:異常 2:拒絶
          }
        }
      }
    } else {    // メアドがある場合
      if(!empty($edit) && !empty($suid)) {    // Myページの編集の場合
        $id = $this->decryptID($suid); // DBのidを復号化

        // DBからメアドを取得
        $member = TrialMembers::where('id',$id)->where('deleted_at', NULL)->first();
        if($member->mail == $mail) { // メアドがDBと同じ(変更なしの)場合
          $checkmail = 0; // 0;正常 1:異常
        } else { // メアドがDBのと違う場合
          $checkmail = 1; // 0;正常 1:異常
        }
      } else {    // 編集ではない(新規の)場合
        $checkmail = 1; // 0;正常 1:異常
      }
    }

    // 職種(医師)の入力診療科目チェック(メアドとは逆でデフォルトが正常)
    $checkservices1 = 0;    // 0:正常 1:異常
    if($kind == 1 && empty($services1)) {  // 必須の診療科目1がない場合
      $checkservices1 = 1;    // 0:正常 1:異常
    }

    // ご本人確認時の入力チェック(メアドとは逆でデフォルトが正常)
    $checkinput = 0;    // 0:正常 1:tel異常 2:image異常 3:image移動失敗

    // ご本人確認は医師や薬剤師等に限定する必要あり
    // 限定しないと、職種を変えて、送信されるとチェックが走る
    $imagekind = $this->kindForImage($kind);

    // ご本人確認の方法によりチェック
    if($identification == "none") { // 今は選択しない
    } else if($identification == "image" && $imagekind == 1) { // 画像による確認
      /* イメージの保存 */
      if($request->hasFile('image_files')) {  // アップデータがある場合
        $enmail = $this->encryptMail($mail);  // メアド暗号化(ファイル名の一部)
        $image_path = M_IMAGE_PATH;
        $current = microtime(true);   // 今の時刻(下記で「保存名」が返る)
        $success = $this->initImages($image_path,$current,$enmail,$request);  // 保存

        if($success) {  // 成功した場合
          $checkinput = 0; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
        } else {    // 移動に失敗した場合
          $checkinput = 3; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
        }
      } else {    // アップデータがない場合
        if(!empty($edit) && !empty($suid)) {    // Myページの編集の場合
          $id = $this->decryptID($suid); // DBのidを復号化

          $Identification = Identification::where('trialmember_id',$id)->where('deleted_at', NULL)->first();

          if(empty($Identification->image_name)) { // 以前登録の画像がない場合
            $checkinput = 2; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
          } else {  // 以前登録の画像がある場合
            $image_path = M_IMAGE_PATH;
            $current = $Identification->image_name;

            $checkinput = 0; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
          }
        } else {
          $checkinput = 2; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
        }
      }
    } else if($identification == "tel" && $kind == 1) {   // 勤務先へのお電話による確認
      if(empty($tel)) {  // 電話番号がない場合
        $checkinput = 1; // 0;正常 1:tel異常 2:image異常 3:image移動失敗
      }
    }

    if($checkmail == 0 && $checkinput == 0 && $checkservices1 == 0) {   // 正常の場合
      return view('r-mail-form-confirm',compact('name1','name2','kana1','kana2','mail','password','yearSelect','monthSelect','daySelect','gender','tel','image_files','current','image_path','kind','caremane','services1','services2','services3','services4','services5','graduationYear','group','identification','edit'));
    } else if($checkmail == 1) {    // メアドが既にある場合
      return redirect()->back()->with('ls',trans('r-mail-form.same_email'));
    } else if($checkmail == 2) {    // 拒絶するメアド場合
      return redirect()->back()->with('ls','メールアドレスが不正です。');
    } else if($checkinput == 1) {    // 電話番号がない場合
      return redirect()->back()->with('notel','電話番号を入力して下さい');
    } else if($checkinput == 2) {    // 画像がない場合
      return redirect()->back()->with('noimage','画像ファイルを選択して下さい');
    } else if($checkinput == 3) {    // 画像の移動に失敗の場合
      return redirect()->back()->with('noimage','画像ファイルが保存できません');
    } else if($checkservices1 == 1) {   // 医師なのに診療科目1がない場合
      return redirect()->back()->with('noservices1','診療科目を選択して下さい');
    }
  }

  /**
   * 仮登録を行い、アドレス確認のメールを送信する
   * 本登録のアップデートを行い、メールを送信する
   *
   * @return \Illuminate\Http\Response
   */
  public function send_mail(RMailFormRequest $request)
  {
    DB::beginTransaction();

    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $current     = $request->input('current');
    $image_path     = $request->input('image_path');
    $kind     = $request->input('kind');
    $caremane     = $request->input('caremane');
    $services1     = $request->input('services1');
    $services2     = $request->input('services2');
    $services3     = $request->input('services3');
    $services4     = $request->input('services4');
    $services5     = $request->input('services5');
    $graduationYear     = $request->input('graduationYear');
    $group    = $request->input('group');
    $identification    = $request->input('identification');
    $edit    = $request->input('edit');
    $lang   = $request->input('language');
     // $suidはDBのidを暗号化(暗号化はAjaxSampleLogin.php)
    $suid = Session::get('SAMPLE-3SP-USER');

    if(!empty($edit) && !empty($suid)) {    // Myページの編集の場合
      $id = $this->decryptID($suid); // DBのidを復号化

      /****************************************************************/
      // DBから取得(会員情報)
      /****************************************************************/
      $TrialMembers = TrialMembers::where('id',$id)->where('deleted_at', NULL)->first();

      // メアド変更時の認証処理 meado
      $premail = $TrialMembers->mail; // 元のメアド
      $newmail = $request->input("mail");    // 新しく送られたメアド

      // DBに登録するデータの設定
      $TrialMembers = $this->assignRequestToMembers($TrialMembers,$request);

      // メアド変更時の認証処理 meado
      if($premail != $newmail) {  // メアドが違う場合
        // トークンの作成
        $urltoken = hash('sha256',uniqid(rand(),1));    // トークンの作成
        $url = env('APP_URL')."r-changemail?urltoken=".$urltoken; // トークン付きのURL

        // 独自にDBに保存する項目
        $TrialMembers->urltoken = $urltoken;    // トークン
        $TrialMembers->urltoken_at = Carbon::now(); // 登録日時
      }

      $db_success = $TrialMembers->save();  // update

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "会員情報が更新できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBから取得(職種)
      /****************************************************************/
      $TrialProfessions = Professions::where('profession_no',$kind)->where('deleted_at', NULL)->first();    // 職種[含むケアマネ](マスタ)
      $T_P = TrialmembersProfessions::where('trialmember_id',$id)->where('deleted_at', NULL)->get(); // 会員-職種(中間テーブル)

      $caremaneDB = 0;    // 0:DBにケアマネなし 1:DBにケアマネあり

      foreach($T_P as $professions) {   // 本職1つと(あれば)ケアマネの最大2つ 
        $pid = $professions->profession_id; // [FK]職種
        $Professions = Professions::where('id',$pid)->where('deleted_at', NULL)->first();   // 職種[含むケアマネ](マスタ)

        if($Professions->profession_no != M_CAREMANA_NO) { // ケアマネ以外の場合
          // DBに保存する項目(職種はケアマネ以外は1つしかない前提)
          $professions->profession_id = $TrialProfessions->id; // [FK]職種

          $db_success = $professions->save();  // update

          if(!$db_success) {  // DBエラーの場合
            DB::rollback();

            $flush_message = "職種が更新できませんでした。";
            return redirect()->route('contact')->with('message',$flush_message);
          }
        } else {    // ケアマネの場合
          $caremaneDB = 1;    // 0:DBにケアマネなし 1:DBにケアマネあり

          if(empty($caremane)) {   // ケアマネがない場合
            $db_success = $professions->delete();  // デリート

            if(!$db_success) {  // DBエラーの場合
              DB::rollback();

              $flush_message = "ケアマネージャーが削除できませんでした。";
              return redirect()->route('contact')->with('message',$flush_message);
            }
          }
        }
      }

      // リクエストにケアマネがあり、DBにない場合
      if(!empty($caremane) && $caremaneDB == 0) {
        $TrialProfessions = Professions::where('profession_no',M_CAREMANA_NO)->where('deleted_at', NULL)->first(); // 職種のidを取得

        $T_P = new TrialmembersProfessions(); // 会員-職種(中間テーブル)

        // DBに保存する項目
        $T_P->trialmember_id = $TrialMembers->id;   // [FK]会員
        $T_P->profession_id = $TrialProfessions->id;   // [FK]職種

        $db_success = $T_P->save();  // insert

        if(!$db_success) {  // DBエラーの場合
          DB::rollback();

          $flush_message = "ケアマネージャーが更新できませんでした。";
          return redirect()->route('contact')->with('message',$flush_message);
        }
      }

      /****************************************************************/
      // DBから取得(勤務先・学校)
      /****************************************************************/
      $Groups = Groups::where('trialmember_id',$id)->where('deleted_at', NULL)->first();
      $Groups->group_name = $group;   // 勤務先・学校名称

      if(!empty($graduationYear)) { // リクエストに卒業予定年がある場合
        $Groups->graduationYear = $graduationYear;   // 卒業予定年(学生の場合)
      }

      $db_success = $Groups->save();  // update

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "勤務先・学校が更新できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBから取得(診療科目)
      /****************************************************************/
      $service_array = array($services1,$services2,$services3,$services4,$services5);   // 配列に設定

      foreach($service_array as $no => $service) {  // ループ(必須の1はある前提)
        $do = $no+1;  // 順番。1～5

        // disp_orderで検索する
        $T_S = TrialmembersServices::where('trialmember_id',$id)->where('disp_order',$do)->where('deleted_at', NULL)->first(); // 会員-診療科目(中間テーブル)

        if(!empty($service)) {    // リクエストがある場合
          $Services = Services::where('services_no',$service)->where('deleted_at', NULL)->first(); // 診療科目のidを取得

          if(empty($T_S)) { // DBにない場合(新規作成)
            $T_S = new TrialmembersServices(); // 会員-診療科目(中間テーブル)
          }

          $this->assignToTrialmembersServices($T_S,$Services,$TrialMembers,$do); // 設定

          $db_success = $T_S->save();  // insert update

          if(!$db_success) {  // DBエラーの場合
            DB::rollback();

            $flush_message = "会員-診療科目が更新できませんでした。";
            return redirect()->route('contact')->with('message',$flush_message);
          }
        } else {  // リクエストがない場合
          if(!empty($T_S)) { // DBにある場合
            $db_success = $T_S->delete(); // デリート

            if(!$db_success) {  // DBエラーの場合
              DB::rollback();

              $flush_message = "会員-診療科目が削除できませんでした。";
              return redirect()->route('contact')->with('message',$flush_message);
            }
          }
        }
      }

      /****************************************************************/
      // DBから取得(本人確認)
      /****************************************************************/
      $Identification = Identification::where('trialmember_id',$id)->where('deleted_at', NULL)->first();

      $Identification->type = $identification;   // none tel image
      $Identification->tel = $tel;  // 電話番号(あってもなくても)
      $Identification->image_path = $image_path; // 画像パス(あってもなくても)
      $Identification->image_name = $current;  // 画像名(あってもなくても)

      $db_success = $Identification->save();  // update

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "ご本人確認が更新できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      $mailSuccess = $this->updateMailSuccess();    // 画面メッセージ

      // メアド変更時の認証処理 meado
      if($premail != $newmail) {  // メアドが違う場合
        // メール送信関連
        $to = $newmail; // 宛先
        $subject = '【3sp】聴診会員ライブラリ　メールアドレス変更のお知らせ';  // タイトル
        $body = $this->changeMailBodyText($url);    // メール本文
        $mailSuccess = $this->changeMailSuccess($subject);    // 画面メッセージ

        // メール送信
        $success = Mail::raw($body, function ($message) use ($to, $subject) {
          $message ->to($to)->subject($subject);
        });
      }

      $success = 1; // ここまで来ていれば、成功
    } else {    // 新規の場合
      // トークンの作成
      $urltoken = hash('sha256',uniqid(rand(),1));    // トークンの作成
      $url = env('APP_URL')."r-form?urltoken=".$urltoken; // トークン付きのURL

      /****************************************************************/
      // DBに「仮登録」(会員情報)
      /****************************************************************/
      $TrialMembers = new TrialMembers(); // 試聴音利用登録テーブル

      // DBに登録するデータの設定
      $TrialMembers = $this->assignRequestToMembers($TrialMembers,$request);

      // 独自にDBに保存する項目
      $TrialMembers->urltoken = $urltoken;    // トークン
      $TrialMembers->urltoken_at = Carbon::now(); // 登録日時

      // メアドバックアップ
      // UPDATE trialmembers SET mail_backup=mail;
      // 本番でも実行
      $TrialMembers->mail_backup = $TrialMembers->mail; // メアドのバックアップ

      $db_success = $TrialMembers->save();  // insert

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "会員情報が新規作成できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBに「仮登録」(職種)
      /****************************************************************/
      $TrialProfessions = Professions::where('profession_no',$kind)->where('deleted_at', NULL)->first(); // 職種のidを取得

      $T_P = new TrialmembersProfessions(); // 会員-職種(中間テーブル)

      // DBに保存する項目
      $T_P->trialmember_id = $TrialMembers->id;   // [FK]会員
      $T_P->profession_id = $TrialProfessions->id;   // [FK]職種

      $db_success = $T_P->save();  // insert

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "職種が新規作成できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBに「仮登録」(職種 ケアマネを持っている場合)
      /****************************************************************/
      if(!empty($caremane)) {   // リクエストにケアマネがある場合
        $TrialProfessions = Professions::where('profession_no',M_CAREMANA_NO)->where('deleted_at', NULL)->first(); // 職種のidを取得

        $T_P = new TrialmembersProfessions(); // 会員-職種(中間テーブル)

        // DBに保存する項目
        $T_P->trialmember_id = $TrialMembers->id;   // [FK]会員
        $T_P->profession_id = $TrialProfessions->id;   // [FK]職種

        $db_success = $T_P->save();  // insert

        if(!$db_success) {  // DBエラーの場合
          DB::rollback();

          $flush_message = "ケアマネージャーが新規作成できませんでした。";
          return redirect()->route('contact')->with('message',$flush_message);
        }
      }

      /****************************************************************/
      // DBに「仮登録」(勤務先・学校)
      /****************************************************************/
      $Groups = new Groups(); // 勤務先・学校

      // DBに保存する項目
      $Groups->trialmember_id = $TrialMembers->id;   // [FK]会員
      $Groups->group_name = $request->input('group'); // 勤務先・学校名称

      if(!empty($graduationYear)) { // 卒業予定年(学生の場合)がある場合
        $Groups->graduationYear = $graduationYear;   // 卒業予定年(学生の場合)
      }

      $db_success = $Groups->save();  // insert

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "勤務先・学校が新規作成できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // DBに「仮登録」(診療科目)
      /****************************************************************/
      $service_array = array($services1,$services2,$services3,$services4,$services5); // 配列に設定

      foreach($service_array as $no => $service) {  // ループ(必須の1はある前提)
        if(!empty($service)) {    // リクエストにある場合
          $Services = Services::where('services_no',$service)->where('deleted_at', NULL)->first(); // 診療科目のidを取得
          $T_S = new TrialmembersServices(); // 会員-診療科目(中間テーブル)
          $this->assignToTrialmembersServices($T_S,$Services,$TrialMembers,$no+1); // 設定

          $db_success = $T_S->save();  // insert

          if(!$db_success) {  // DBエラーの場合
            DB::rollback();

            $flush_message = "診療科目が新規作成できませんでした。";
            return redirect()->route('contact')->with('message',$flush_message);
          }
        }
      }

      /****************************************************************/
      // DBに「仮登録」(本人確認)
      /****************************************************************/
      $Identification = new Identification(); // ご本人確認

      // DBに保存する項目
      $Identification->trialmember_id = $TrialMembers->id;   // [FK]会員
      $Identification->type = $identification;  // none image tel
      $Identification->tel = $tel;  // 電話番号(あってもなくても)
      $Identification->image_path = $image_path; // 画像パス(あってもなくても)
      $Identification->image_name = $current;  // 画像名(あってもなくても)

      $db_success = $Identification->save();  // insert

      if(!$db_success) {  // DBエラーの場合
        DB::rollback();

        $flush_message = "ご本人確認が新規作成できませんでした。";
        return redirect()->route('contact')->with('message',$flush_message);
      }

      /****************************************************************/
      // メール送信関連
      /****************************************************************/
      $to = $mail; // 宛先
      $subject = trans('contact.subject');  // タイトル
      $body = $this->createMailBodyTextByLan($lang,$url);    // メール本文
      $mailSuccess = $this->createMailSuccess($subject);    // 画面メッセージ

      // メール送信
      $success = Mail::raw($body, function ($message) use ($to, $subject) {
        $message ->to($to)->subject($subject);
      });
    }

    DB::commit();

    // 画面に表示するメッセージ
    $flush_message = $success ? $mailSuccess : $this->createMailFail();

    // お問合わせ画面にリダイレクト
    return redirect()->route('contact')->with('message', $flush_message);
  }


  /* 会員-診療科目(中間テーブル)に保存するデータを設定 */
  private function assignToTrialmembersServices($T_S,$Services,$TrialMembers,$no) {
    // DBに保存する項目
    $T_S->trialmember_id = $TrialMembers->id;   // [FK]会員
    $T_S->service_id = $Services->id;   // [FK]診療科目
    $T_S->disp_order = $no; // 順番。1～5

    return($T_S);
  }

  /* リクエストからDBに保存するデータを設定 */
  private function assignRequestToMembers($TrialMembers,$request) {
    $TrialMembers->mail = $request->input("mail");    // メール

    // パスワードはhiddenだと見えてしまうのでセッションで
    $password = Session::get('SAMPLE-USER-PASSWORD');     // 取得(パスワード)
    $TrialMembers->password = bcrypt($password); // パスワード
    Session::forget('SAMPLE-USER-PASSWORD');     // 削除(パスワード)

//    $TrialMembers->password = bcrypt($request->input("password")); // パスワード
    $TrialMembers->name1 = $request->input("name1");    // 姓
    $TrialMembers->name2 = $request->input("name2");    // 名
    $TrialMembers->kana1 = $request->input("kana1");    // 姓(カナ)
    $TrialMembers->kana2 = $request->input("kana2");    // 名(カナ)
    $TrialMembers->yearSelect = $request->input("yearSelect");  // 生年月日(年)
    $TrialMembers->monthSelect = $request->input("monthSelect");// 生年月日(月)
    $TrialMembers->daySelect = $request->input("daySelect");    // 生年月日(日)
    $TrialMembers->gender = $request->input("gender");  // 男:male 女:female

    return($TrialMembers);
  }

  /* DBのidを復号化 */
  private function decryptID($suid) {
    $method = 'aes-128-ecb'; // 暗号化方式
    $salt = 'TMI3sp045Salt875xyz1924'; // ソルト
    $encrypted = str_replace(array('_','-','.'),array('+','=','/'),$suid); // URLSafeをbase64に

    $idaddsalt = openssl_decrypt($encrypted,$method,$salt); // 復号

    $idsalt = 19661030; // idに足しているソルト
    $id = $idaddsalt - $idsalt; // idのソルトを引く

    return($id);    // 復号化したDBのid
  }

  /* メアドの暗号化 */
  // 保存するファイル名に使用するだけなので特に復号化は行わない
  private function encryptMail($mail) {
    $salt = '2019Gnoshiowotasu'; // ソルト

    // 暗号化方式(ecbよりcbcの方が強固だがivが必要になる)
    // ecbとcbcの違い
    // http://yut.hatenablog.com/entry/20140228/1393543543
    $method = 'aes-128-ecb';

    $encrypted = openssl_encrypt($mail,$method,$salt); // 暗号化

    // base64をURLSafeに
    $urlsafe = str_replace(array('+','=','/'),array('_','-','.'),$encrypted);

    return($urlsafe);
  }

  /* ご本人確認(画像) */
  private function kindForImage($kind) {
    $status = 0;    // 0:画像は対象外 1:対象

    switch($kind) {
      case "1":
      case "2":
      case "4":
      case "13":
      case "14":
      case "16":
      case "18":
      case "21":
      case "22":
      case "24":
      case "5":
      case "6":
        $status = 1;    // 0:画像は対象外 1:対象
        break;
      default:
        $status = 0;    // 0:画像は対象外 1:対象
        break;
    }

    return($status);
  }

  /* 職種により「診療科目」を無効にする */
  /* 職種により「電話」を無効にする */
  private function noDataForServices($kind,$services) {
    if($kind != 1) {    // 医師以外
      $services = "";   // なし(""はemptyでtrueになる)
    }

    return($services);
  }

  /* 職種により「卒業予定年」を無効にする */
  private function noDataForGraduationYear($kind,$graduationYear) {
    if($kind != 5 && $kind != 6) {  // 医学生でなく、かつ、薬学生でない
      $graduationYear = "";   // なし(""はemptyでtrueになる)
    }

    return($graduationYear);
  }

  /* 職種により「ご本人確認」を無効にする */
  /* 職種により「ご本人確認(画像)」を無効にする */
  private function noDataForID($kind,$identification) {
    switch($kind) {
      case "3":
      case "7":
      case "8":
      case "9":
      case "10":
      case "11":
      case "12":
      case "15":
      case "17":
      case "23":
      case "25":
      case "26":
      case "19":
      case "20":
        // ご本人確認がない人もnoneになってしまうので嫌だが、ないと死ぬので
        // とりあえずnoneにしている
        $identification = "none";
        break;
      default:
        break;
    }

    return($identification);
  }

    /**
   * メールで送信する内容をテキストにして返す
   *
   * @param $url     トークン付きのURL 
   *@param $lan     トークン付きのLAN
   *
   * @return string メールで送信するテキスト
   */
  private function createMailBodyTextByLan($lang, $url)
  {
    $jaBody = <<<EOD
登録はまだ完了しておりません。現在は仮登録の状態です。

聴診会員ライブラリ利用のお申し込みありがとうございます。
24時間以内に下記のURLをクリックしていただきますと、本登録が完了致します。

【本登録】
{$url}
　※24時間有効です
EOD;

    $enBody = <<<EOD
Thank you for applying "kikuzosound.com".
You have not completed your registration yet.
Please click the below URL to complete your registration within 24hours.

【Registration URL】
{$url}
  *This expires after 24 hours.
EOD;

    $body = $lang == "en" ? $enBody : $jaBody;

    return ($body);
  }

  /**
   * メールで送信する内容をテキストにして返す
   *
   * @param $url     トークン付きのURL 
   *
   * @return string メールで送信するテキスト
   */
  private function createMailBodyText($url) 
  {
    $body = <<<EOD
登録はまだ完了しておりません。現在は仮登録の状態です。

聴診会員ライブラリ利用のお申し込みありがとうございます。
24時間以内に下記のURLをクリックしていただきますと、本登録が完了致します。

【本登録】
{$url}
　※24時間有効です
EOD;

    return($body);
  }

  /**
   * メールで送信する内容をテキストにして返す
   *
   * @param $url     トークン付きのURL 
   *
   * @return string メールで送信するテキスト
   */
  private function changeMailBodyText($url) 
  {
    $body = <<<EOD
登録の変更はまだ完了しておりません。

メールアドレス変更の為の確認となります。
24時間以内に下記のURLをクリックしていただきますと、登録の変更が完了致します。

なお、下記のURLをクリックせず登録が完了しませんとメールアドレスの変更は行われません。

※登録が完了しませんと、24時間後に元のメールアドレスに戻りますので、ご注意下さい

【メールアドレス登録の変更】
{$url}
　※24時間有効です
EOD;

    return($body);
  }

  /**
   * 登録変更完了時にメールで送信する内容をテキストにして返す
   *
   * @param $mail   メアド 
   *
   * @return string メールで送信するテキスト
   */
  private function changeThanksText($name,$mail) 
  {
    $body = <<<EOD
{$name}様

「聴診会員ライブラリ利用」へのメールアドレス変更が完了致しました。

メールアドレスとパスワードはログインの際に必要な重要な情報です。
印刷や画面キャプチャへの保存、メモ等をして、忘れないよう大切に保管して下さい。

━━━━━━━━━━━━
・あなたのメールアドレス： {$mail}
・あなたのパスワード：登録時にご入力頂いたものです。
（パスワードはセキュリティ確保のため表示しておりません。）
━━━━━━━━━━━━
EOD;

    return($body);
  }

  /**
   * 登録完了時にメールで送信する内容をテキストにして返す
   *
   * @param $mail   メアド 
   *
   * @return string メールで送信するテキスト
   */
  private function createThanksText($name,$mail) 
  {
    $body = <<<EOD
{$name}様

この度は「聴診会員ライブラリ利用」にご登録頂き、ありがとうございました。

「聴診会員ライブラリ利用」へのご登録が完了致しました。

今後、サービスの充実に努めて参りますのでぜひご活用下さい。

メールアドレスとパスワードはログインの際に必要な重要な情報です。
印刷や画面キャプチャへの保存、メモ等をして、忘れないよう大切に保管して下さい。

━━━━━━━━━━━━
・あなたのメールアドレス： {$mail}
・あなたのパスワード：登録時にご入力頂いたものです。
（パスワードはセキュリティ確保のため表示しておりません。）
━━━━━━━━━━━━
EOD;

    return($body);
  }

  /*
    送信成功メッセージ(登録変更)
  */
  private function updateMailSuccess() 
  {
    $body = <<<EOD
登録情報を変更致しました。
EOD;

    return($body);
  }

  /*
    送信成功メッセージ(仮登録)
  */
  private function createMailSuccess($subject) 
  {
    //get the body content from the translation
    $body1 = trans('contact.body1');
    $body2 = trans('contact.body2');

    $body = <<<EOD
{$body1}{$subject}{$body2}
EOD;

    return($body);
  }

  /*
    送信成功メッセージ(メアド変更)
  */
  private function changeMailSuccess($subject) 
  {
    $body = <<<EOD
ご変更頂きましたメールアドレスにタイトル {$subject} のメールを送信致しました。

24時間以内にメールに記載されたURLから変更を完了して下さい。

メールが届かない場合はメールアドレスを確認頂き、再度ご登録下さい。なお、「迷惑メール」等に入る場合もございますので、届かない場合はご確認下さい。
EOD;

    return($body);
  }

  /*
    送信失敗メッセージ
  */
  private function createMailFail() 
  {
    $body = <<<EOD
ご入力頂きましたメールアドレスへの送信に失敗しました。

メールアドレスを確認頂き、再度ご登録下さい。
EOD;

    return($body);
  }

  /*
    トークンエラーメッセージ
  */
  private function createTokenFail() 
  {
    $body = trans('r-mail-form.create_token_fail');

    return($body);
  }

  /*
    画像の保存処理
  */
  private function initImages($image_path,&$current,$enmail,$request) {
    $files  = $request->file('image_files');    // イメージ自体取得
    $file  = $files[0]; // (今は)1つのみの固定

    $base_url = $image_path;   // 保存する相対パス
    $base_dir = public_path() . $base_url;  // 保存する絶対パス

    $last_id = $enmail."-".$current;  // 保存ファイル名(暗号化メアド-今の時刻)
    $ext = $file->getClientOriginalExtension();    // 元の拡張子
    $filename = $last_id . '.' . $ext;  // 保存ファイル名.拡張子

    $current = $filename;   // 保存ファイル名.拡張子に変更

    $success = $file->move($base_dir,$filename);   // 一時 -> 保存

    // リサイズ
    $ip = $base_url.$filename;  // 相対パスとファイル名
    list($w,$t) = getimagesize(public_path($ip));   // 幅と高さ

    if($w > 640) {  // 幅が640より大きい場合
      // 「幅:640 高:自動」に縮小リサイズ
      $img = Image::make(public_path($ip))->resize(640,null,function ($constraint) {
        $constraint->aspectRatio();
      });

      // 上書き保存
      $img->save(public_path($ip));
    }

    return($success);
  }

  /*
    前後にある半角全角スペースを削除【未使用】
  */
  private function spaceTrim($str) {
    $str = preg_replace('/^[ 　]+/u','',$str);    // 行頭
    $str = preg_replace('/[ 　]+$/u','',$str);    // 末尾

    return($str);
  }
}
