<?php

namespace App\Http\Controllers;

use Input;
use DB;
use Validator;
use Mail;
use Log;
use App\Http\Requests\ApplicationFormRequest;
use Illuminate\Support\Facades\App;
Use Session;

class ApplicationFormController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /**
   * お問合わせ内容の確認画面を表示する
   *
   */
  public function confirm(ApplicationFormRequest $request)
  {
    $name     = $request->input('name');
    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $kind     = $request->input('kind');
    $group    = $request->input('group');
    $plan1    = $request->input('plan1');
    $plan2    = $request->input('plan2');
    $plan3    = $request->input('plan3');
    $plan4    = $request->input('plan4');
    $purchase = $request->input('purchase');
    $affiliation = $request->input('affiliation');
    $dealer = $request->input('dealer');
    $zip = $request->input('zip');
    $address = $request->input('address');
    $area = $request->input('area');
    $sales = $request->input('sales');
    $salestel = $request->input('salestel');
    $salesmail = $request->input('salesmail');
    $question = $request->input('question');
    $way = $request->input('way');

    $warning = "";  // 表示するワーニング文

    if($kind != "") {   // クーポンがある場合
      $cs = $this->checkCoupon($kind); // 0:正しいクーポン 1:期間外 2:ない

      if($cs == 1) {
        $warning = "【注意】そのまま送信する事は可能ですが、入力したクーポンは期間外のクーポンです。";
      } else if($cs == 2) {
        $warning = "";  // 無効なクーポンと分かってしまうので表示なし
/*
        $warning = "【注意】そのまま送信する事は可能ですが、入力したクーポンは有効なクーポンではありません。";
*/
      }
    }

    $request->flash();
    
    return view('appli_form_confirm', compact('name', 'mail', 'tel', 'kind', 'group', 'plan1', 'plan2', 'plan3', 'plan4', 'purchase', 'question', 'affiliation', 'zip', 'address', 'dealer', 'area', 'sales', 'salestel', 'salesmail', 'way', 'warning'));
  }

  /*
    クーポンのチェック
  */
  private function checkCoupon($kind) {
    $status = 0;    // 0:正しいクーポン 1:期間外 2:ない

    $result = DB::selectOne("SELECT coupons.*,company_id,companys.company FROM coupons INNER JOIN companys ON coupons.company_id=companys.id WHERE coupons.code=? AND coupons.deleted_at IS NULL AND companys.deleted_at IS NULL",[$kind]);

    if(isset($result)) {    // クーポンが存在する
      $now = date("Y-m-d H:i:s"); // 今
      $start_at = $result->start_at;  // 有効期間開始
      $end_at = $result->end_at;  // 有効期間終了

      // 有効期間外
      if(!((strtotime($start_at) <= strtotime($now)) && (strtotime($now) <= strtotime($end_at)))) {
        $status = 1;
      }
    } else {    // クーポンが存在しない
        $status = 2;
    }

    return($status);
  }

  /**
   * お問合わせの内容をメールで送信する
   *
   * @return \Illuminate\Http\Response
   */
  public function send_mail(ApplicationFormRequest $request)
  {
    // 宛先
    $to = env('CONTACT_FORM_MAIL_TO');

    $name     = $request->input('name');
    $mail     = $request->input('mail');
    $tel      = $request->input('tel');
    $question = $request->input('question');
    $kind     = $request->input('kind');
    $group    = $request->input('group');
    $plan1    = $request->input('plan1');
    $plan2    = $request->input('plan2');
    $plan3    = $request->input('plan3');
    $plan4    = $request->input('plan4');
    $purchase = $request->input('purchase');
    $affiliation = $request->input('affiliation');
    $zip = $request->input('zip');
    $address = $request->input('address');
    $dealer = $request->input('dealer');
    $area = $request->input('area');
    $sales = $request->input('sales');
    $salestel = $request->input('salestel');
    $salesmail = $request->input('salesmail');
    $way = $request->input('way');
    $body     = $this->createMailBodyText($name, $mail, $tel, $kind, $group, $question, $plan1,$plan2,$plan3,$plan4,$purchase,$affiliation,$zip,$address,$dealer,$area,$sales,$salestel,$salesmail,$way);
    $subject  = 'お申込み';

    // メール送信
    $success = Mail::raw($body, function ($message) use ($to, $subject) {
      $message ->to($to)->subject($subject);
    });

    // 画面に表示するメッセージ
    $flush_message = $success ? trans('application_form.session_success') : trans('application_form.session_fail');
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
  private function createMailBodyText($name, $mail, $tel, $kind, $group, $question, $plan1,$plan2,$plan3,$plan4,$purchase,$affiliation,$zip,$address,$dealer,$area,$sales,$salestel,$salesmail,$way) 
  {
    $transfer = [0=>'電話',1=>'郵送'];

    $way_disp = ""; // メール以外の連絡方法

    if(empty($way)) {   // チェックボックスがない場合
        $way_disp = ""; // なし
    } else {
        foreach($way as $key => $value) {
            $way_disp .= $transfer[$value]." ";
        }
    }

    $warning = "";  // クーポンに対するワーニング文

    if($kind != "") {   // クーポンがある場合
      $cs = $this->checkCoupon($kind); // 0:正しいクーポン 1:期間外 2:ない

      if($cs == 1) {
        $warning = "【注意】期間外のクーポンです。";
      } else if($cs == 2) {
        $warning = "【注意】有効なクーポンではありません。";
      }
    }

    $data = [
      '【施設名】' => $group,
      '【所属】' => $affiliation,
      '【郵便番号】' => $zip,
      '【住所】' => $address,
      '【お名前】'        => $name,
      '【メールアドレス】' => $mail,
      '【電話番号】'      => $tel,
      '【販売会社名】'      => $dealer,
      '【支店/営業所】'      => $area,
      '【営業担当者氏名】'      => $sales,
      '【販売会社電話番号】'      => $salestel,
      '【営業メールアドレス】'      => $salesmail,
      '【1年/月払】'      => $plan1,
      '【1年/年払】'      => $plan2,
      '【3年/月払】'      => $plan3,
      '【3年/年払】'      => $plan4,
      '【聴くゾウ購入】'  => $purchase,
      '【メール以外の案内】' => $way_disp,
      '【クーポンコード】' => $kind."　".$warning,
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
