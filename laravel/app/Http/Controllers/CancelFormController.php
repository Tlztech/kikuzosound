<?php

namespace App\Http\Controllers;

use Input;
use Illuminate\Http\Request;    // telemedica 20180810
use App\Lib\Member; // telemedica 20180810
use App\Account;    // telemedica 20180810
use App\Deletes;    // telemedica 20180810
use Session;    // telemedica 20180810
use DB;
use Validator;
use Mail;
use Log;
use App\Http\Requests\CancelFormRequest;
use Illuminate\Support\Facades\App;

class CancelFormController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /* 解約ページ初期化 */
  public function index()
  {
    $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');    // アカウントid

    $params = CancelFormController::contractInfo($account_id);  // 取得

    return view('cancel_form',compact('params'));
  }

  /**
   * お問合わせ内容の確認画面を表示する
   *
   */
  public function confirm(CancelFormRequest $request)
  {
    $name     = $request->input('name');
    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $kind     = $request->input('kind');
    $group    = $request->input('group');
    $question = $request->input('question');

    $request->flash();
    
    return view('cancel_form_confirm', compact('name', 'mail', 'tel', 'kind', 'group', 'question'));
  }

  /**
   * お問合わせの内容をメールで送信する
   *
   * @return \Illuminate\Http\Response
   */
  public function send_mail(CancelFormRequest $request)
  {
    $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');    // アカウントid

    $this->upDateDelete($account_id);   // 予約削除を4ヶ月後にする

    // 宛先
    $to = array(env('CANCEL_FORM_MAIL_TO1'));
// 複数宛先の場合は、配列にして渡す
//    $to = array(env('CANCEL_FORM_MAIL_TO1'),env('CANCEL_FORM_MAIL_TO2'));

    $kind     = $request->input('kind');
    $question = $request->input('question');
    $body     = $this->createMailBodyText($kind,$question,$account_id);
    $subject  = '途中解約';

    // メール送信
    $success = Mail::raw($body, function ($message) use ($to, $subject) {
      $message ->to($to)->subject($subject);
    });

    // 画面に表示するメッセージ
    $flush_message = $success ? '解約手続きを受け付けました' : '解約手続きに失敗しました';
    // お問合わせ画面にリダイレクト
    return redirect()->route('contact')->with('message', $flush_message);
  }

  /**
   * メールで送信する内容をテキストにして返す
   *
   * @param $name     お名前 
   * @param $mail     メールアドレス
   * @param $tel      電話番号
   * @param $question お問合わせ内容
   * @return string メールで送信するテキスト
   */
  private function createMailBodyText($kind,$question,$account_id) 
  {
    // valueを日本語に変換
    $transfer = [0=>'なし',1=>'他に良い教材を見つけた',2=>'利用する必要がなくなった',3=>'その他'];

    $etc = "販売会社がスズケンの場合は担当者にメールする事";

    $params = $this->contractInfo($account_id);  // 取得

    $data = [
      '【ご施設名】'        => $params['c_com'],
      '【お名前】'          => $params['c_name'],
      '【メールアドレス】'  => $params['c_email'],
      '【電話番号】'        => $params['c_tel'],
      '【契約プラン】'      => $params['usage_disp'],
      '【解約理由】'        => $transfer[$kind],
      '【解約理由補足】'    => $question,
      '【販売会社】'        => $params['d_com'],
      '【販売会社担当名】'  => $params['d_name'],
      '【販売会社メール】'  => $params['d_email'],
      '【販売会社電話】'    => $params['d_tel'],
      '【備考】' => $etc
    ];
    $text = '';
    foreach($data as $key => $value) {
      // 改行は
      $text .= $key . "\n" . $value . "\n\n";
    }
    return $text;
  }

  /* 解約に必要なデータを取得 */
  private static function contractInfo($account_id) {
    $transfer = [0=>'購入',1=>'サイト利用プラン1年(月払)',2=>'サイト利用プラン1年(年払)',3=>'サイト利用プラン3年(月払)',4=>'サイト利用プラン3年(年払)',5=>'サイト利用プラン1年(月払)',6=>'サイト利用プラン1年(年払)',7=>'サイト利用プラン3年(月払)',8=>'サイト利用プラン3年(年払)',9=>'購入(請求書発行)',99=>''];

    $result = DB::selectOne("SELECT accounts.id,accounts.user,accounts.usage_type,d_com.company AS d_com,dealer.name AS d_name,dealer.email AS d_email,dealer.tel AS d_tel,c_com.company AS c_com,customer.name AS c_name,customer.email AS c_email,customer.tel AS c_tel FROM accounts INNER JOIN contracts ON accounts.contract_id=contracts.id INNER JOIN contacts AS dealer ON contracts.dealer_id=dealer.id INNER JOIN contacts AS customer ON contracts.customer_id=customer.id INNER JOIN companys AS d_com ON dealer.company_id=d_com.id INNER JOIN companys AS c_com ON customer.company_id=c_com.id WHERE accounts.id=?",[$account_id]);

    $params['result'] = $result;    // 結果全て

    $params['id'] = $result->id;    // アカウントid
    $params['user'] = $result->user;    // アカウント名
    $params['usage_type'] = $result->usage_type;    // サイト使用プラン
    $params['usage_disp'] = $transfer[$result->usage_type];    // 表示用
    $params['d_com'] = $result->d_com;  // 販売会社
    $params['d_name'] = $result->d_name;    // 販売会社担当名
    $params['d_email'] = $result->d_email;  // 販売会社メール
    $params['d_tel'] = $result->d_tel;  // 販売会社電話
    $params['c_com'] = $result->c_com;  // 顧客施設名
    $params['c_name'] = $result->c_name;    // 顧客担当者名
    $params['c_email'] = $result->c_email;  // 顧客メール
    $params['c_tel'] = $result->c_tel;  // 顧客電話

    return($params);
  }

  /* 予約削除を4ヶ月後にする */
  /* 申請した月の翌月から3ヶ月後に解約、なので4ヶ月後 */
  /* 解約申請日時の日時を更新(NULLではなくなる) */
  private static function upDateDelete($account_id) {
    $nm = date("Y-m-d H:i:s",strtotime("4 month")); // 今の4ヶ月後
    CancelFormController::date2text($nm,$Y,$M,$D,$h,$m,$s); // 日時分解
    $target = $Y."-".$M."-"."01"." "."00".":"."00".":"."00";    // 再設定日時

    // 予約削除の日時を取得
    $result = DB::selectOne("SELECT up_at FROM deletes WHERE account_id=?",[$account_id]);
    $up_at = $result->up_at;    // 削除日時

    // 予約削除の日時を保持
    DB::update("UPDATE deletes SET original_at=cast(? as datetime) WHERE account_id=?",[$up_at,$account_id]);

    // 予約削除の日時を更新
    DB::update("UPDATE deletes SET up_at=cast(? as datetime) WHERE account_id=?",[$target,$account_id]);

    // 解約申請日時の日時を更新(NULLではなくなる)
    DB::update("UPDATE deletes SET cancel_at=now() WHERE account_id=?",[$account_id]);
  }

  /* 日時分解 */
  private static function date2text($date,&$Y,&$M,&$D,&$h,&$m,&$s) {
    $tmp1 = explode(" ",$date);     // 2014-09-10 09:35:48
    $tmp2 = explode("-",$tmp1[0]);  // 2014-09-10
    $Y = $tmp2[0];
    $M = $tmp2[1];
    $D = $tmp2[2];
    $tmp2 = explode(":",$tmp1[1]);  // 09:35:48
    $h = $tmp2[0];
    $m = $tmp2[1];
    $s = $tmp2[2];
  }
}

