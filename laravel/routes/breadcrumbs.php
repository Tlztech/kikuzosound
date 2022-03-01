<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Home > 聴くゾウライブラリ
Breadcrumbs::register('contents', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.kikuzo_library'), route('contents'));
});

// Home > クイズ
Breadcrumbs::register('quizpacks', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.quiz'), route('quizpacks'));
});

Breadcrumbs::register('exams', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.exams'), route('exams'));
});

Breadcrumbs::register('aus', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.aus'), route('aus'));
});

// Home > 聴診専用スピーカ
Breadcrumbs::register('speaker', function($breadcrumbs)
{
    
    $breadcrumbs->parent('home');
    Config::get('app.locale') == 'en' ? $breadcrumbs->push('Auscultation speaker', route('speaker')) : $breadcrumbs->push(@trans('breadcrumbs.aus_speaker'), route('speaker'));
});

// Home > サイトについて
Breadcrumbs::register('about', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.about_page'), route('about'));
});

// Home > 利用規約
Breadcrumbs::register('terms', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('breadcrumbs.terms'), route('terms'));
});

// Home > プライバシーポリシー
Breadcrumbs::register('privacy', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.privacy_policy'), route('privacy'));
});

// Home > お知らせ
Breadcrumbs::register('news', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.notice'), route('news'));
});

// Home > よくある質問
Breadcrumbs::register('faq', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.faq'), route('faq'));
});

// Home > 試聴音利用登録メール認証
Breadcrumbs::register('r-mail-form', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.rmail_form'), route('r-mail-form'));
});

// Home > 試聴音利用登録メール認証 > 試聴音利用登録メール認証確認
Breadcrumbs::register('r-mail-form-confirm', function($breadcrumbs)
{
    $breadcrumbs->parent('r-mail-form');
    $breadcrumbs->push(@trans('breadcrumbs.rmail_confirmation'), route('r-mail-form-confirm'));
});

// Home > 試聴音利用登録完了
Breadcrumbs::register('r-form', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.r_form'), route('r-form'));
});

// Home > お問合わせ
Breadcrumbs::register('contact', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.contact_us'), route('contact'));
});

// Home > お問合わせ > お問い合わせ入力
Breadcrumbs::register('contact_form', function($breadcrumbs)
{
    $breadcrumbs->parent('contact');
    $breadcrumbs->push(@trans('breadcrumbs.inquiry_form'), route('contact_form'));
});

// Home > お問合わせ > お問い合わせ入力 > お問い合わせ入力確認
Breadcrumbs::register('contact_form_confirm', function($breadcrumbs)
{
    $breadcrumbs->parent('contact_form');
    $breadcrumbs->push(@trans('breadcrumbs.confirm_inquiry'), route('contact_form_confirm'));
});

// Home > 認証の登録
Breadcrumbs::register('register', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.register_certification'), route('register'));
});

// Home > 認証の登録 > 認証の登録入力
Breadcrumbs::register('register_form', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    Config::get('app.locale') == 'en' ? $breadcrumbs->push('Authentication registration input', route('register_form')) : $breadcrumbs->push(@trans('breadcrumbs.registration_form'), route('register_form'));
});

// Home > 約款
/*
Breadcrumbs::register('appli', function($breadcrumbs)
{
   $breadcrumbs->parent('home');
   $breadcrumbs->push('契約書', route('appli'));
});
*/

// Home > お申込み
Breadcrumbs::register('appli_form', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.application'), route('appli_form'));
});

// Home > 申込フォーム > 申込フォーム確認
Breadcrumbs::register('appli_form_confirm', function($breadcrumbs)
{
    $breadcrumbs->parent('appli_form');
    $breadcrumbs->push(@trans('breadcrumbs.confirm_application'), route('appli_form_confirm'));
});

// Home > ご解約
Breadcrumbs::register('cancel_form', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.cancel'), route('cancel_form'));
});

// Home > ご解約フォーム > ご解約フォーム確認
Breadcrumbs::register('cancel_form_confirm', function($breadcrumbs)
{
    $breadcrumbs->parent('cancel_form');
    $breadcrumbs->push(@trans('breadcrumbs.cancel_confirm'), route('cancel_form_confirm'));
});

// Home > 使い方
Breadcrumbs::register('use', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.listen_kikuzo'), route('use'));
});

// Home > ベスト
Breadcrumbs::register('vest', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.kikuzo_vest'), route('vest'));
});

// Home > 動画フィジカルアセスメント
Breadcrumbs::register('video', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('video.title'), route('video'));
});

// Home > 動画フィジカルアセスメント　有料フック
Breadcrumbs::register('videofree', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.video_assessment'), route('videofree'));
});

// Home > ニュースレターバックナンバー
Breadcrumbs::register('nl_backnumber', function($breadcrumbs)
{
    $breadcrumbs->parent('nl_001');
    $breadcrumbs->push(@trans('breadcrumbs.back_number'), route('nl_backnumber'));
});

// Home > ニュースレターVol.1
Breadcrumbs::register('nl_001', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.newsletter_vol1'), route('nl_001'));
});

// Home > ニュースレターVol.2
Breadcrumbs::register('nl_002', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.newsletter_vol2'), route('nl_002'));
});

// Home > ニュースレターVol.3
Breadcrumbs::register('nl_003', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.newsletter_vol3'), route('nl_003'));
});

// Home > ニュースレターVol.4
Breadcrumbs::register('nl_004', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.newsletter_vol4'), route('nl_004'));
});

// Home > ニュースレターVol.5
Breadcrumbs::register('nl_005', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.newsletter_vol5'), route('nl_005'));
});

// Home > 聴診音一覧
Breadcrumbs::register('list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.sound_list'), route('list'));
});

// Home > 利用規約個人
Breadcrumbs::register('private', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.terms_service'), route('private'));
});

// Home > 利用規約法人
Breadcrumbs::register('corporate', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.terms_service'), route('corporate'));
});

// Home > ケーススタディ
Breadcrumbs::register('casestudy', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.aus_pbl'), route('casestudy'));
});

// Home > aa01
Breadcrumbs::register('aa01', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.aa01'),route('aa01'));
});

// Home > aa01_eng
Breadcrumbs::register('aa01_eng', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Ausculaide',route('aa01_eng'));
});

// Home > business_parnter
Breadcrumbs::register('business_partner', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.business_partner'),route('business_partner'));
});

// Home > landing page
Breadcrumbs::register('landing page', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('landing page',route('landing-page'));
});

// Home > kk01
Breadcrumbs::register('kk01', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.kikuzo_speaker'),route('kk01'));
});

//mypage 
Breadcrumbs::register('mypage', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(@trans('breadcrumbs.mypage'),route('mypage'));
});




