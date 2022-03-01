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
use App\Http\Requests\ContactFormRequest;
use Illuminate\Support\Facades\App;

class ContactFormController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /* 解約ボタン表示・非表示の制御 */
  public function index()
  {
    $auth = Member::member_auth(); // 認証確認 0:未ログイン 1:ログイン中
    $usage_type = 0; // 0:購入 1:1年月払 2:1年年払 3:3年月払 4:3年年払 99:デモ
    $delete_at = NULL;  // 予約削除日
    $cancel = 0;    // 0:非表示 1:解約ボタン表示 2:予約削除日を表示

    if($auth) { // ログイン中
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');    // アカウントid
        $account = Account::where('id', $account_id)->where('deleted_at', NULL)->first();   // DBから取得
        $delete = Deletes::where('account_id', $account_id)->where('deleted_at', NULL)->first();   // DBから取得

        if($account) {  // アカウントがある場合
            $usage_type = $account->usage_type;

            // ログイン中でサイト利用プランの場合は表示
            if($auth == 1 && ($usage_type == 1 || $usage_type == 2 || $usage_type == 3 || $usage_type == 4 || $usage_type == 5 || $usage_type == 6 || $usage_type == 7 || $usage_type == 8)) {
                $cancel = 1; // 1:解約ボタン表示

                if($delete) {   // 予約削除がある場合
                    if(!is_null($delete->cancel_at)) { // 解約申請日時がある
                        // 予約削除日を取得
                        $delete_at = date("Y年m月d日",strtotime($delete->up_at));

                        $cancel = 2;// 2:予約削除日を表示
                    } else {
                        $cancel = 1;// 1:解約ボタン表示
                    }
                }
            }
        }
    }

    $sample_status = 0; // 0:未ログイン 1:ログイン(3sp) 2:ログイン(試聴音)

    $sample_user = Session::get('SAMPLE-3SP-USER'); // 試聴音ユーザ

    if($sample_user != '') {   // セッションがある場合(ログイン中)
        $sample_type = Session::get('SAMPLE-USER-TYPE'); // 0:試聴音 1:3sp

        if($sample_type == 0) { // 試聴音登録者の場合
            $sample_status = 2; // 2:ログイン(試聴音)
        } else if($sample_type == 1) {  // 3spアカウントの場合
            $sample_status = 1; // 1:ログイン(3sp)
        }
    }

    $params['auth'] = $auth;
    $params['usage_type'] = $usage_type;
    $params['delete_at'] = $delete_at;
    $params['cancel'] = $cancel;
    $params['sample_user'] = Session::get('SAMPLE-EDIT-KEY');   // getパラメータ
    $params['sample_status'] = $sample_status;

    return view('contact',compact('params'));
  }

  /**
   * お問合わせ内容の確認画面を表示する
   *
   */
  public function confirm(ContactFormRequest $request)
  {
    $name     = $request->input('name');
    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $kind     = $request->input('kind');
    $group    = $request->input('group');
    $question = $request->input('question');

    $request->flash();
    
    return view('contact_form_confirm', compact('name', 'mail', 'tel', 'kind', 'group', 'question'));
  }

  /**
   * お問合わせの内容をメールで送信する
   *
   * @return \Illuminate\Http\Response
   */
  public function send_mail(ContactFormRequest $request)
  {
    // 宛先
    $to = env('CONTACT_FORM_MAIL_TO');

    $name     = $request->input('name');
    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $question = $request->input('question');
    $kind     = $request->input('kind');
    $group    = $request->input('group');
    $body     = $this->createMailBodyText($name, $mail, $tel, $kind, $group, $question);
    $subject  = 'お問合わせ';

    // メール送信
    $success = Mail::raw($body, function ($message) use ($to, $subject) {
      $message ->to($to)->subject($subject);
    });

    // 画面に表示するメッセージ
    $flush_message = $success ? trans('contact.session_success') : trans('contact.session_fail');
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
  private function createMailBodyText($name, $mail, $tel, $kind, $group, $question) 
  {
    // valueを日本語に変換
    $transfer = [0=>'なし',1=>'デモ機貸出し',2=>'ご購入について',3=>'その他',4=>'見積り依頼'];

    $data = [
      '【施設名】' => $group,
      '【お名前】'        => $name,
      '【メールアドレス】' => $mail,
      '【電話番号】'      => $tel,
      '【お問合わせ種類】' => $transfer[$kind],
      '【お問合わせ内容】' => $question
    ];
    $text = '';
    foreach($data as $key => $value) {
      // 改行は
      $text .= $key . "\n" . $value . "\n\n";
    }
    return $text;
  }
}
