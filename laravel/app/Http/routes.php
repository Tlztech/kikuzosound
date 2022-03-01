<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Lib\Member;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


Route::group(['middleware' => 'user_request'], function () {
Route::group(['middleware' => 'auth.very_basic'], function () {
	//aus api
	//Route::get('api/ausculaide', 'Api\AusculaideController@getAusculaide');
	// Route::get('/ausculaide_app', 'Api\AusculaideController@getAusculaide')->name('aus_app');
	// //ajax ausculaide
	// Route::get('/ajax_ausculaide_app', 'Api\AusculaideController@getAjaxAusculaide')->name('ajax_aus_app');
	
	//React Routes
	Route::get('/group_admin/{path?}', function () {
		return view('router');
	});

	//Ipax Url route
	Route::get('ipax_url/{id}', [
		'as' => 'ipax_url',
		'uses' => 'AusController@getIpax',
	]);
	
	//recommended learn route
	Route::get("/recommend_sample_learn", function () {
		if(Auth::user() && Auth::user()->role == 99){
			return view("recommend_sample.learn");
		}else{
			return redirect("/admin");
		}
	});
				
	Route::group(['middleware' => 'log'], function () {
		Route::post('access_log', 'Admin\AccessLogController@store')->middleware(['member_only']);
		// translation routes
		Route::get('/en', ['as' => 'en', 'uses' => 'HomeController@changeLanguage']);
		Route::get('/ja', ['as' => 'ja', 'uses' => 'HomeController@changeLanguage']);
		// Home画面
		Route::get('/', 'HomeController@index')->middleware(['member_only']);
		//Route::get('/home', [ 'as' => 'home', 'uses' => 'HomeController@index'])->name('home');;
		Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index'])->middleware(['member_only']);

		// Password reset link request routes...
		Route::get('reset_password', [
			'as' => 'reset_password',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('auth.password');
			},
		]);
		Route::post('password/email', ['uses' => 'Auth\PasswordController@postEmail']);

		// Password reset routes...
		Route::get('password/reset/{token}/{lang}', ['uses' => 'Auth\PasswordController@getReset']);
		//univ Password reset routes...
		Route::get('univ/password/reset/{token}', ['uses' => 'Api\PasswordResetControlller@getReset']);
		Route::post('univ/password/reset', ['uses' => 'Api\PasswordResetControlller@postReset']);
		// end univ

		Route::get('change_password', [
			'as' => 'change_password',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}

				if (Session::get('password_reset_token')) {
					return view('auth.reset');
				}
				throw new NotFoundHttpException();
			},
		]);
		Route::post('password/reset', ['uses' => 'Auth\PasswordController@postReset']);

		Route::post('favorites_reorder', 'HomeController@reorderFavorites')->middleware(['member_only']);
		// コンテンツ画面
		Route::get('contents', [
			'as' => 'contents',
			'uses' => 'ContentsController@index',
		])->middleware(['member_only']); //★hyono
		//Route::get('contents', [ 'as' => 'contents', 'uses' => 'ContentsController@index']);

		// 聴診音一覧画面 telmedica 20180320
		Route::get('/list', ['as' => 'list', 'uses' => 'ListController@index']);

		Route::get('iPax', [
			'as' => 'aus',
			'uses' => 'AusController@index',
		])->middleware(['member_only']);

		Route::get('ausculaide_offline/{id}', [
			'as' => 'aus_offline',
			'uses' => 'AusController@indexOffline',
		])->middleware(['member_only']);

		// //test aus
		// Route::get('ajax_ausculaide', [
		// 	'as' => 'ajax_aus',
		// 	'uses' => 'AusController@getAjaxAus',
		// ])->middleware(['member_only']);

		// Route::get('new_ausculaide', [
		// 	'as' => 'new_aus',
		// 	'uses' => 'AusController@getNewAus',
		// ])->middleware(['member_only']);

		Route::get('ajax_recommended', [
			'as' => 'recommended_library',
			'uses' => 'MyPageController@getRecommendedList',
		])->middleware(['member_only']);

		Route::get('ajax_recommended_aus', [
			'as' => 'recommended_ausculaide',
			'uses' => 'MyPageController@getRecommendedAusculaide',
		])->middleware(['member_only']);

		Route::get('/mypage', ['as' => 'mypage', 'uses' => 'MyPageController@index'])->middleware(['member_only']);

		// クイズ画面
		Route::get('quizpacks', [
			'as' => 'quizpacks',
			'uses' => 'QuizPacksController@index',
		])->middleware(['member_only']); //★hyono
		//Route::get('quizpacks', [ 'as' => 'quizpacks', 'uses' => 'QuizPacksController@index']);
		Route::get('exams', [
			'as' => 'exams',
			'uses' => 'QuizPacksController@exams',
		])->middleware(['member_only']);
		// クイズスタート画面
		// Route::get('quizpacks/quiz_start',[ 'as' => 'quiz_start','uses' => 'QuizPacksController@quiz_start']);
		Route::get('quizpacks/{quiz_pack_id}/quiz_start', [
			'as' => 'quiz_start',
			'uses' => 'QuizPacksController@quiz_start',
		]);

		Route::get('quizpacks/{quiz_pack_id}/exam/{exam_id}/exam_start', [
			'as' => 'exam_start',
			'uses' => 'QuizPacksController@quiz_start',
		]);

		//クイズ回答正解画面
		// Route::get('quizpacks/quiz_answer_correct',[ 'as' => 'quiz_answer_correct','uses' => 'QuizPacksController@quiz_answer_correct']);
		// //クイズ回答不正解画面
		// Route::get('quizpacks/quiz_answer_incorrect',[ 'as' => 'quiz_answer_incorrect','uses' => 'QuizPacksController@quiz_answer_incorrect']);
		//クイズ回答選択画面
		// Route::get('quizpacks/quiz_answer_select',[ 'as' => 'quiz_answer_select','uses' => 'QuizPacksController@quiz_answer_select']);
		Route::get('quizpacks/{quiz_pack_id}/quizzes/{quize_id}/exam/{exam_id}', [
			'as' => 'exam_answer_select',
			'uses' => 'QuizPacksController@quiz_answer_select',
		]);
		Route::get('quizpacks/{quiz_pack_id}/quizzes/{quize_id}', [
			'as' => 'quiz_answer_select',
			'uses' => 'QuizPacksController@quiz_answer_select',
		]);

		Route::post('quizpacks/{quiz_pack_id}/quizzes/{quize_id}/contents/{lib_type}/exam/{exam_id}', [
			'as' => 'multi_exam_content_select',
			'uses' => 'QuizPacksController@multi_quiz_content_select',
		]);

		Route::post('quizpacks/{quiz_pack_id}/quizzes/{quize_id}/contents/{lib_type}', [
			'as' => 'multi_quiz_content_select',
			'uses' => 'QuizPacksController@multi_quiz_content_select',
		]);

		Route::post('quizpacks/{quiz_pack_id}/quizzes/{quiz_id}/answer', [
			'as' => 'quiz_answer_choice',
			'uses' => 'QuizPacksController@quiz_answer_choice',
		]);

		Route::post('quizpacks/{quiz_pack_id}/quizzes/{quiz_id}/observation', [
			'as' => 'multi_quiz_observation_choice',
			'uses' => 'QuizPacksController@quiz_observation_choice',
		]);

		//クイズ成績画面
		Route::get('quizpacks/{quiz_pack_id}/quiz_score', [
			'as' => 'quiz_score',
			'uses' => 'QuizPacksController@quiz_score',
		]);
		Route::get('quizpacks/{quiz_pack_id}/exam/{exam_id}/exam_score', [
			'as' => 'exam_score',
			'uses' => 'QuizPacksController@quiz_score',
		]);
		Route::get('/csv/send/exam/{exam_id}/quiz/{quiz_pack_id}', [
			'as' => 'exam_csv',
			'uses' => 'QuizPacksController@sendExamCsv',
		]);
		Route::get('quizpacks/end_quiz', [
			'as' => 'end_exam_quiz',
			'uses' => 'QuizPacksController@endQuiz',
		]);
		//クイズ回答確認画面
		Route::get('quizpacks/{quiz_pack_id}/quizzes/{quiz_id}/answer', [
			'as' => 'quiz_answer_confirm',
			'uses' => 'QuizPacksController@quiz_answer_confirm',
		]);
		// 聴診専用スピーカ画面
		Route::get('speaker', [
			'as' => 'speaker',
			'uses' => 'SpeakerController@index',
		]);
		// サイトについて
		Route::get('about', [
			'as' => 'about',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('about');
			},
		]);

		Route::get('alert', [
			'as' => 'alert',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}

				//create a variable that will check if the
				//customer is registered
				$isRegistered = session('isRegistered');

				//if the user is registered then go to alert page
				if (1 == $isRegistered) {
					//then forget the session
					session()->forget('isRegistered');

					//finally redirect to view alert
					return view('alert');
				}

				//redirect to homepage
				return redirect('home');
			},
		]);
		// 利用規約
		Route::get('terms', [
			'as' => 'terms',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('terms');
			},
		]);
		// プライバシーポリシー
		Route::get('privacy', [
			'as' => 'privacy',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('privacy');
			},
		]);
		//update browser
		Route::post('reapply_browser', ['uses' => 'Auth\ReapplyBrowserController@postEmail']);
		Route::post('email_verification', ['uses' => 'RegisterFormController@send_email_verification']);
		Route::get('update_browser/{token}', ['uses' => 'Auth\ReapplyBrowserController@getReset'])->middleware(['no_auth_only']);
		Route::post('update_browser', ['uses' => 'Auth\ReapplyBrowserController@postReset']);
		Route::get('update_browser_confirm', [
			'as' => 'update_browser_confirm',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('update_browser', ['status' => 'confirm']);
			},
		]);
		Route::get('update_browser_success', [
			'as' => 'update_browser_success',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('update_browser', ['status' => 'success']);
			},
		]);
		//reapply_browser
		Route::get('reapply_browser', [
			'as' => 'reapply_browser',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('reapply_browser');
			},
		]);
		Route::get('email_not_exist', [
			'as' => 'email_not_exist',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('email_not_exist');
			},
		]);
		Route::get('email_verification', [
			'as' => 'email_verification',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('email_verification');
			},
		]);
		// お知らせ
		Route::get('news', [
			'as' => 'news',
			'uses' => 'InformationController@frontendIndex',
		]);
		// お知らせ
		/*Route::get('news', [
            'as' => 'news',
            function () {
                if (Session::get('lang')) {
                    App::setLocale(Session::get('lang'));
                }
                return view('news');
            },
        ]);*/
		// よくある質問
		Route::get('faq', [
			'as' => 'faq',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('faq');
			},
		]);

		// お問い合わせ
		Route::get('contact', [
			'as' => 'contact',
			'uses' => 'ContactFormController@index',
		]);
		// DBからのデータが必要な為、コントローラ経由に変更
		/*
            Route::get('contact', [ 'as' => 'contact', function() {
                    return view('contact');
                }]);
*/

		// 試聴音登録フォームget
		Route::get('r-mail-form', [
			'as' => 'r-mail-form',
			'uses' => 'RMailFormController@rmailform',
		]);
		// 試聴音登録フォーム確認画面
		Route::get('r-mail-form-confirm', [
			'as' => 'r-mail-form-confirm',
			'uses' => 'RMailFormController@confirm',
		]);
		// 試聴音登録フォームメール送信
		Route::post('r-mail-form-send_mail', [
			'as' => 'r-mail-form-send_mail',
			'uses' => 'RMailFormController@send_mail',
		]);
		// 試聴音登録トークン確認
		Route::get('/r-form', [
			'as' => 'r-form',
			'uses' => 'RMailFormController@authtoken',
		]);
		// 試聴音登録メアド変更
		Route::get('/r-changemail', [
			'as' => 'r-changemail',
			'uses' => 'RMailFormController@changemail',
		]);

		// お問い合わせフォーム
		Route::get('contact_form', [
			'as' => 'contact_form',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('contact_form');
			},
		]);
		// お問合わせフォーム確認画面
		Route::get('contact_form_confirm', [
			'as' => 'contact_form_confirm',
			'uses' => 'ContactFormController@confirm',
		]);
		// お問合わせフォームメール送信
		Route::post('contact_form_send_mail', [
			'as' => 'contact_form_send_mail',
			'uses' => 'ContactFormController@send_mail',
		]);

		// 申込フォーム　利用契約
		/*
            Route::get('appli', [ 'as' => 'appli', function() {
                    return view('appli');
                }]);
*/

		// 申込フォーム
		Route::get('appli_form', [
			'as' => 'appli_form',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('appli_form');
			},
		]);
		// 申込フォーム確認画面
		Route::post('appli_form_confirm', [
			'as' => 'appli_form_confirm',
			'uses' => 'ApplicationFormController@confirm',
		]);
		// 申込フォームメール送信
		Route::post('appli_form_send_mail', [
			'as' => 'appli_form_send_mail',
			'uses' => 'ApplicationFormController@send_mail',
		]);

		// telmedica 20180810
		// 解約フォーム
		Route::get('cancel_form', [
			'as' => 'cancel_form',
			'uses' => 'CancelFormController@index',
		])->middleware(['cancel']);
		// 解約フォーム確認画面
		Route::post('cancel_form_confirm', [
			'as' => 'cancel_form_confirm',
			'uses' => 'CancelFormController@confirm',
		])->middleware(['cancel']);
		// 解約フォームメール送信
		Route::post('cancel_form_send_mail', [
			'as' => 'cancel_form_send_mail',
			'uses' => 'CancelFormController@send_mail',
		])->middleware(['cancel']);

		// telmedica 20170606
		// 認証の登録
		Route::get('register', [
			'as' => 'register',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('register');
			},
		]);
		// 認証の登録フォーム
		Route::get('register_form', [
			'as' => 'register_form',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('register_form');
			},
		]);
		// 認証の登録フォーム確認画面
		Route::post('register_form_confirm', [
			'as' => 'register_form_confirm',
			'uses' => 'RegisterFormController@confirm',
		]);
		// 認証の登録フォームメール送信
		Route::post('register_form_send_mail', [
			'as' => 'register_form_send_mail',
			'uses' => 'RegisterFormController@send_mail',
		]);

		// telemedica 20170612
		// ベスト
		Route::get('vest', [
			'as' => 'vest',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('vest');
			},
		]);
		// 使い方
		Route::get('use', [
			'as' => 'use',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('use');
			},
		]);

		// telemedica 20180306
		// ニューズレター　バックナンバー

		Route::get('nl_backnumber', [
			'as' => 'nl_backnumber',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_backnumber');
			},
		]);
		// Vol.1
		Route::get('nl_001', [
			'as' => 'nl_001',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_001');
			},
		]);

		// Vol.2
		Route::get('nl_002', [
			'as' => 'nl_002',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_002');
			},
		]);

		// Vol.3
		Route::get('nl_003', [
			'as' => 'nl_003',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_003');
			},
		]);

		// Vol.4
		Route::get('nl_004', [
			'as' => 'nl_004',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_004');
			},
		]);

		// Vol.5
		Route::get('nl_005', [
			'as' => 'nl_005',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('nl_005');
			},
		]);

		//aa01
		Route::get('aa01', [
			'as' => 'aa01',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('aa01');
			},
		]);

		Route::get('quizzes-lp', [
			'as' => 'quizzes-lp',
			function () {
				return view('quizzes-lp');
			},
		]);

		//aa01_eng
		Route::get('aa01_eng', [
			'as' => 'aa01_eng',
			function () {
				return view('aa01_eng');
			},
		]);

		//business_parnter
		Route::get('business_partner', [
			'as' => 'business_partner',
			function () {
				return view('business_partner');
			},
		]);

		Route::get('landing-page', [
			'as' => 'landing-page',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('landing-page');
			},
		]);

		Route::get('kk01', [
			'as' => 'kk01',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('kk01');
			},
		]);

		//library-lp
		Route::get('library-lp', [
			'as' => 'library-lp',
			function () {
				return view('library-lp');
			},
		]);

		// telemedica 20171031
		// お気に入りajax
		Route::any('ajaxfavo', 'AjaxFavorite@ajax_favo');

		// telemedica 20171120
		// お気に入りセット提供版ajax
		Route::any('ajaxinitset', 'AjaxInitSet@ajax_initset');

		// telemedica 20171120
		// お気に入りセットオリジナル保存ajax
		Route::any('ajaxsaveset', 'AjaxSaveSet@ajax_saveset');

		// telemedica 20171120
		// お気に入りセットオリジナル削除ajax
		Route::any('ajaxdeleteset', 'AjaxDeleteSet@ajax_deleteset');

		// telemedica 20180920
		// 外部リンクカウントajax
		Route::any('ajaxoutlink', 'AjaxOutlink@ajax_outlink');

		// telemedica 20190125
		// 試聴音ログインajax
		Route::any('ajaxsamplelogin', 'AjaxSampleLogin@ajax_samplelogin');
		// 試聴音ログアウトajax
		Route::any('ajaxsamplelogout', 'AjaxSampleLogin@ajax_samplelogout');
		// 試聴音ログインチェックajax
		Route::any(
			'ajaxsamplelogincheck',
			'AjaxSampleLoginCheck@ajax_samplelogincheck'
		);
		// 試聴音登録者削除(暫定版)ajax
		// 確認作業が終わったら、削除する事
		Route::any('ajaxsampledelete', 'AjaxSampleLogin@ajax_sampledelete');

		// telemedica 20180207
		// ビデオ
		Route::get('video', [
			'as' => 'video',
			'middleware' => 'member_only',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('video');
			},
		]);
		
		// Logs Library
		// Create Logs Library ajax
		Route::any('ajaxcreateLibraryLog', 'AjaxCreateLibraryLog@create_library_log');
		
		// telemedica 20180517
		/*
            // ビデオ有料フック　ログインなし視聴
            Route::get('videofree', [ 'as' => 'videofree', function() {
                    return view('videofree');
                }]);

            // 購入　利用契約　個人
            Route::get('private', [ 'as' => 'private', function() {
                    return view('private');
                }]);

            // 購入　利用契約　法人
            Route::get('corporate', [ 'as' => 'corporate', function() {
                    return view('corporate');
                }]);
*/
		// customer login
		Route::get('customer_login', [
			'as' => 'customer_login',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('customer_login');
			},
		]);
		// customer registration
		Route::get('customer_registration', [
			'as' => 'customer_registration',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				$msg_error_userID = '';
				$msg_error_account_mail = '';
				$msg_error_contact_mail = '';
				$msg_error_pass = '';
				$msg_error_confirim_pass = '';
				$msg_error_onetime = '';
				$msg_error_terms = '';

				if (1 == Member::member_auth()) {
					return redirect()->back();
				}

				return view('customer_registration', compact('msg_error_userID', 'msg_error_account_mail', 'msg_error_contact_mail', 'msg_error_pass', 'msg_error_confirim_pass', 'msg_error_onetime', 'msg_error_terms'));
			},
		]);
		// customer registration
		Route::post('customer_registration', [
			'as' => 'customer_registration_post',
			'uses' => 'RegisterFormController@confirm',
		]);

		// ★hyono
		// 利用者ログイン処理
		// ログ出力(screen_cd)の為、名前を全てに追加 20170714 telemedica
		// ログイン画面（表示）
		Route::get('member_login', [
			'as' => 'member_login',
			'uses' => 'MemberController@getMemberLogin',
		]);
		Route::get('member_login/activate/{user_id}/{code}', [
			'as' => 'member_login_activate',
			'uses' => 'MemberController@activateLoginUser',
		]);
		// ログインコードで認証（画面は非表示）
		Route::get('member_login_code/{login_code}', [
			'as' => 'member_login_code',
			'uses' => 'MemberController@getMemberLoginCode',
		]);
		//Route::get('login/{login_code}', 'MemberController@getLoginCode');
		// ログイン処理（実行）
		Route::post('member_login', [
			'as' => 'member_login_post',
			'uses' => 'MemberController@postMemberLogin',
		]);
		// ログアウト処理
		Route::get('member_logout', [
			'as' => 'member_logout',
			'uses' => 'MemberController@getMemberLogout',
		]);
		// add a routes that will logout the
		//member/customer if currently logged id
		Route::get('member_logout_currently_loggged', [
			'as' => 'member_logout_currently_loggged',
			'uses' => 'MemberController@getMemberLogoutIfCurrentlyLoggedIn',
		]);
		// ジャンプ
		Route::get('member_jump', [
			'as' => 'member_jump',
			function () {
				if (Session::get('lang')) {
					App::setLocale(Session::get('lang'));
				}
				return view('member_jump');
			},
		]);

		/*
            // 利用者ログイン処理
            // ログイン画面（表示）
            Route::get('member_login', 'MemberController@getMemberLogin')->name('member_login');
            // ログインコードで認証（画面は非表示）
            Route::get('member_login_code/{login_code}', 'MemberController@getMemberLoginCode');
            //Route::get('login/{login_code}', 'MemberController@getLoginCode');
            // ログイン処理（実行）
            Route::post('member_login', 'MemberController@postMemberLogin');
            // ログアウト処理
            Route::get('member_logout', 'MemberController@getMemberLogout');
            // ジャンプ
            Route::get('member_jump', function() {
                return view('member_jump');
            });
*/
		// ★テスト
		Route::get('test', 'TestController@index');
		//    Route::get('test', 'TestController@index')->middleware(['member_only']);
		Route::get('test/{code}', 'TestController@show');
		// ★hyono
	});

	// ログ出力用API
	Route::post('log', 'LogController@store');

	// 管理画面グループ（認証なし）
	Route::group(['namespace' => 'Auth', 'prefix' => 'admin'], function () {
		// ログイン画面
		Route::get('login', 'AuthController@getLogin');
		// ログイン処理
		Route::post('login', 'AuthController@postLogin');
		// ログアウト処理
		Route::get('logout', 'AuthController@getLogout');
	});

	// 管理画面グループ（認証あり）
	Route::group(
		['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth'],
		function () {
			// -----------システム管理者/監修者が使用可能-----------
			// 管理画面のルート（/admin）にアクセスされた場合、ロールによって画面が異なる
			// システム管理者はクイズパック一覧画面、監修者はコンテンツ一覧画面を表示する
			Route::get('/', [
				'as' => 'admin_root',
				function () {
					return App::abort(503); // フェーズ１では実装しない。システム管理者、監修者ではない場合、エラー画面を表示する
				},
			])->middleware(['role_root']);
			// コンテンツ一覧
			Route::resource('stetho_sounds', 'StethoSoundController');

			//Stethoscope
			Route::resource('iPax', 'StethoSoundController');
			//ecg
			Route::resource('ecg_library', 'StethoSoundController');
			//echo
			Route::resource('ucg_library', 'StethoSoundController');
			//palpation
			Route::resource('palpation_library', 'StethoSoundController');
			//inspection
			Route::resource('inspection_library', 'StethoSoundController');
			// xray_library
			Route::resource('xray_library', 'StethoSoundController');
			//transfer old university id's on pivot table
			Route::resource('exam_old_university_id', 'ExamController@transferOldExamGroups');
			Route::resource('transfer_old_university_id', 'StethoSoundController@transferOldExamGroups');
			Route::resource('transfer_quiz_old_university_id', 'QuizController@transferOldExamGroups');
			Route::resource('quizpack_old_university_id', 'QuizPackController@transferOldExamGroups');
			//end routes for transfer old university id's on pivot table

			Route::post('stetho_sounds_reorder', 'StethoSoundController@reorderStethoSounds');
			Route::post('quizzes_reorder', 'QuizController@reorderQuizzes');
			// 監修コメント追加API
			// Route::post('stetho_sounds/{id}/comments', 'StethoSoundController@add_comment');
			// 監修ステータス変更API
			Route::put('stetho_sounds/{id}/status', [
				'as' => 'admin.stetho_sounds.update_status',
				'uses' => 'StethoSoundController@update_status',
			]);
			// 監修コメントAPI
			Route::resource('stetho_sounds.comments', 'CommentController');

			// -----------システム管理者のみ使用可能-----------
			Route::group(['middleware' => 'admin_only'], function () {
				Route::resource('/', 'QuizPackController', [
					'except' => ['show'],
				]);
				// クイズパック一覧
				Route::resource('quiz_packs', 'QuizPackController', [
					'except' => ['show'],
				]);

				//Exam Library
				Route::resource('exam_library', 'ExamLibraryController', [
					'except' => ['show'],
				]);
				// クイズパックの表示順変更API
				Route::post('quiz_packs_reorder', [
					'as' => 'admin.quiz_packs_reorder',
					'uses' => 'QuizPackController@update_orders',
				]);
				// クイズ一覧
				Route::resource('quizzes', 'QuizController', [
					'except' => ['show'],
				]);
				// 監修者一覧
				Route::resource('users', 'UserController', [
					'except' => ['show'],
				]);
				// 監修者一覧
				Route::resource('informations', 'InformationController', [
					'except' => ['show'],
				]);
				Route::get('access_log', [
					'as' => 'access_log',
					'uses' => 'AccessLogController@index',
				]);
				Route::get('access_log_csv', [
					'as' => 'access_log_csv',
					'uses' => 'AccessLogController@access_log_csv',
				]);
				Route::get('access_log_excel', [
					'as' => 'access_log_excel',
					'uses' => 'AccessLogController@access_log_excel',
				]);
				//exams
				Route::resource('exams', 'ExamController', [
					'except' => ['show'],
				]);
				Route::post('exam_reorder', 'ExamController@reorderExam');

				// Univ admin
				Route::resource('users/university', 'UnivAdminController', [
					'except' => ['show'],
				]);
				// Univ admin
				Route::resource('users/teachers', 'UnivTeacherController', [
					'except' => ['show'],
				]);

				Route::resource('group_management', 'GroupManagementController');
			});
		}
	);
	//Customer Admin Routes
	Route::group(
		[
			'namespace' => 'CustomerAdmin',
			'prefix' => 'customer_admin',
			'middleware' => 'auth_customer_admin',
		],
		function () {
			//Customer Admin Middleware
			Route::group(['middleware' => 'customer_admin_only'], function () {
				//customer admin base route
				Route::get('/', [
					'as' => 'customer_admin',
					'uses' => 'OnetimeKeyController@get_oneTimePassword',
				]);
				Route::get('onetime_key/edit/{id}', [
					'as' => 'customer_admin_onetime_key_edit',
					'uses' => 'OnetimeKeyController@edit',
				]);
				Route::post('onetime_key/update/{id}', [
					'as' => 'customer_admin_onetime_key_update',
					'uses' => 'OnetimeKeyController@update',
				]);
				//Set expiration date for onetime key
				Route::post('/customer_admin_expiration', [
					'as' => 'customer_admin_expiration',
					'uses' => 'OnetimeKeyController@setExpiration',
				]);
				
				//download csv for onetime key
				Route::post('/', [
					'as' => 'customer_admin_download_csv',
					'uses' => 'OnetimeKeyController@downloadCsv',
				]);
				//customer admin new onetime password issue
				Route::get('new_onetime_issue', [
					'as' => 'new_onetime_issue',
					'uses' => 'OnetimeKeyController@create',
				]);
				//customer admin bulk delete unregistered onetime key
				Route::post('bulk_delete_license', [
					'as' => 'bulk_delete_license',
					'uses' => 'OnetimeKeyController@bulkDeleteLicense',
				]);

				//delete all invalid license key
				Route::post('delete_invalid', [
					'as' => 'delete_invalid',
					'uses' => 'OnetimeKeyController@deleteAllInvalidLicense',
				]);

				//customer admin generate new onetime password
				Route::get('update_license/{id}', [
					'as' => 'update_license',
					'uses' => 'OnetimeKeyController@updateLicense',
				]);
				//customer admin stop license
				Route::get('stop_license/{id}', [
					'as' => 'stop_license',
					'uses' => 'OnetimeKeyController@stopLicense',
				]);
				//customer admin delete license
				Route::get('delete_license/{id}', [
					'as' => 'remove_license',
					'uses' => 'OnetimeKeyController@destroy',
				]);
				//customer admin view browser reset history
				Route::get('browser_reset_history/{id}', [
					'as' => 'browser_reset_history',
					'uses' => 'OnetimeKeyController@browserResetHistory',
				]);
				//customer admin post new onetime password issue
				Route::post('new_onetime_issue', [
					'as' => 'new_onetime_issue_post',
					'uses' => 'OnetimeKeyController@newOnetimeIssue',
				]);
				//Customer Admin Coupons
				Route::get('coupons', [
					'as' => 'customer_admin_coupons',
					'uses' => 'CouponController@index',
				]);
				//Create Customer Admin Coupons
				Route::get('coupons/create', [
					'as' => 'customer_admin_coupons.create',
					'uses' => 'CouponController@create',
				]);
				//Edit Customer Admin Coupons
				Route::get('coupons/edit/{coupon}', [
					'as' => 'customer_admin_coupons.edit',
					'uses' => 'CouponController@edit',
				]);
				//Store Customer Admin Coupons
				Route::post('coupons/store', [
					'as' => 'customer_admin_coupons.store',
					'uses' => 'CouponController@store',
				]);
				//Update Customer Admin Coupons
				Route::post('coupons/update/{coupon}', [
					'as' => 'customer_admin_coupons.update',
					'uses' => 'CouponController@update',
				]);
				//Customer Admin exams
				Route::get('exams', [
					'as' => 'customer_admin_exams',
					'uses' => 'ExamController@index',
				]);
				//Customer Admin exams
				Route::get('exams/create', [
					'as' => 'customer_admin_exams.create',
					'uses' => 'ExamController@create',
				]);
				//Customer Admin exams
				Route::post('exams/store', [
					'as' => 'customer_admin_exams.store',
					'uses' => 'ExamController@store',
				]);
				//Customer Admin exams
				Route::get('exams/edit/{exam}', [
					'as' => 'customer_admin_exams.edit',
					'uses' => 'ExamController@edit',
				]);
				//Customer Admin exams
				Route::post('exams/update/{exam}', [
					'as' => 'customer_admin_exams.update',
					'uses' => 'ExamController@update',
				]);
				//Customer Admin exams
				Route::get('exams/delete/{exam}', [
					'as' => 'customer_admin_exams.delete',
					'uses' => 'ExamController@destroy',
				]);
				//Customer Admin universities
				Route::get('universities/create', [
					'as' => 'customer_admin_universities.create',
					'uses' => 'UniversitiesController@create',
				]);
				//Customer Admin registrations
				Route::any('registrations', [
					'as' => 'customer_admin_registrations',
					'uses' => 'RegistrationController@index',
				]);
				//Customer Admin registrations company data
				Route::any('registrations/companies/data', [
					'as' => 'customer_admin_registrations_data',
					'uses' => 'RegistrationController@getCompaniesSearchData',
				]);
				//Customer Admin registrations accounts data
				Route::any('registrations/accounts/data', [
					'as' => 'customer_admin_accounts_data',
					'uses' => 'RegistrationController@getContractsSearchData',
				]);
				//Customer Admin companies data
				Route::post('companies/data', [
					'as' => 'customer_admin_companies_data',
					'uses' => 'CompanyController@getCompanyData',
				]);
				//Customer Admin companies create
				Route::get('companies/create', [
					'as' => 'customer_admin_companies_create',
					'uses' => 'CompanyController@create',
				]);
				//Customer Admin companies srote
				Route::post('companies/store', [
					'as' => 'customer_admin_companies_store',
					'uses' => 'CompanyController@store',
				]);
				//Customer Admin companies edit
				Route::get('companies/edit/{company}', [
					'as' => 'customer_admin_companies_edit',
					'uses' => 'CompanyController@edit',
				]);
				//Customer Admin companies update
				Route::post('companies/update/{company}', [
					'as' => 'customer_admin_companies_update',
					'uses' => 'CompanyController@update',
				]);
				//Customer Admin contacts create
				Route::get('contacts/create', [
					'as' => 'customer_admin_contacts_create',
					'uses' => 'ContactController@create',
				]);
				//Customer Admin contacts store
				Route::post('contacts/store', [
					'as' => 'customer_admin_contacts_store',
					'uses' => 'ContactController@store',
				]);
				//Customer Admin contacts edit
				Route::get('contacts/edit/{contact}', [
					'as' => 'customer_admin_contacts_edit',
					'uses' => 'ContactController@edit',
				]);
				//Customer Admin contacts update
				Route::post('contacts/update/{contact}', [
					'as' => 'customer_admin_contacts_update',
					'uses' => 'ContactController@update',
				]);
				//Customer Admin accounts disabled on univ admin
				Route::post('accounts/disableAnalytics', [
					'as' => 'customer_admin_disable_analytics',
					'uses' => 'AccountController@disableAccountAnalytics',
				]);
				//Customer Admin accounts create
				Route::get('accounts/create', [
					'as' => 'customer_admin_accounts_create',
					'uses' => 'AccountController@create',
				]);
				//Customer Admin accounts Data
				Route::post('accounts/data/list', [
					'as' => 'customer_admin_accounts_data',
					'uses' => 'AccountController@accountData',
				]);
				//Customer Admin accounts store
				Route::post('accounts/store', [
					'as' => 'customer_admin_accounts_store',
					'uses' => 'AccountController@store',
				]);
				//Customer Admin accounts edit
				Route::get('accounts/edit/{contact}', [
					'as' => 'customer_admin_accounts_edit',
					'uses' => 'AccountController@edit',
				]);
				//Customer Admin accounts update
				Route::post('accounts/update/{contact}', [
					'as' => 'customer_admin_accounts_update',
					'uses' => 'AccountController@update',
				]);
				//Customer Admin Products
				Route::get('products/register', [
					'as' => 'customer_admin_products_register',
					'uses' => 'ProductController@register',
				]);
				//Customer Admin Products
				Route::post('products/register', [
					'as' => 'customer_admin_products_register_store',
					'uses' => 'ProductController@store',
				]);
				//Customer Admin Trial
				Route::get('trials', [
					'as' => 'customer_admin_trials',
					'uses' => 'TrialController@index',
				]);
				//Customer Admin Trial
				Route::post('trials/{trial}/destroy', [
					'as' => 'customer_admin_trials_destroy',
					'uses' => 'TrialController@destroy',
				]);
				//Customer Admin Trial CSV Download
				Route::get('trials/csv/download', [
					'as' => 'customer_admin_trials_csv',
					'uses' => 'TrialController@trialCsvDownload',
				]);
				//Customer Admin Trial XLSX Download
				Route::get('trials/xlsx/download', [
					'as' => 'customer_admin_trials_xlsx',
					'uses' => 'TrialController@trialXlsxDownload',
				]);
				//Customer Admin Trial Register Form
				Route::get('trials/register', [
					'as' => 'customer_admin_trials_register',
					'uses' => 'TrialController@getRegisterForm',
				]);
				//Customer Admin Trial CSV Download
				Route::post('trials/register', [
					'as' => 'customer_admin_trials_register_form',
					'uses' => 'TrialController@register',
				]);
				//Application Processing
				Route::get('application_processing', [
					'as' => 'customer_admin_application_processing',
					'uses' => 'ApplicationController@index',
				]);
				//Application Processing
				Route::get('home', [
					'as' => 'customer_admin_home',
					'uses' => 'RegistrationController@home',
				]);
				//Customer Admin Exam Group
				Route::resource('exam_groups', 'ExamGroupController', [
					'except' => ['show'],
				]);
				// Exam Search Data
				Route::get('exam_groups/search/data', [
					'as' => 'exam_groups_search_data',
					'uses' => 'ExamGroupController@searchData',
				]);
				Route::get('cookie/{db?}', [
					'as' => 'cookie',
					function ($db) {
						return view('customer_admin.cookie', compact('db'));
					},
				]);
			});
		}
	);
	Route::group(
		['namespace' => 'Auth', 'prefix' => 'customer_admin'],
		function () {
			// ログイン画面
			Route::get('login', 'AuthController@getLogin');
			// ログイン処理
			Route::post('login', 'AuthController@postLogin');
			// ログアウト処理
			Route::get('logout', 'AuthController@getLogout');
		}
	);
});




/*api route*/

//exam apis
Route::group(['prefix' => 'api'], function () {
	Route::get("/get_learning_data", "Api\Recommend\RecommendController@get_learning_data");
	Route::get("/get_recommended_data/{user_id}", "Api\Recommend\RecommendController@get_recommended_data");
	Route::post("/save_model", "Api\Recommend\TensorflowModelController@save");

	Route::post('login', 'Api\LoginController@authenticate');

	// reset password
	Route::post('postEmail', 'Api\PasswordResetControlller@postEmail');
	//resetPassword
	Route::post('resetPassword', 'Api\PasswordResetControlller@postReset');

	//account expiration emails
	Route::get('expiration_notifications', 'Api\Notification\AccountExpirationMails@sendWarningEmails');
	

	Route::group(['middleware' => 'log'], function () {

		Route::group(['middleware' => 'auth.api'], function () {
			//Exam Management
			// auth logout
			Route::post('logout', 'Api\LoginController@logout');

			// get
			Route::get('getExams/{page}', 'Api\ExamManangementController@index');
			Route::get('createExam', 'Api\ExamManangementController@create');
			Route::get('exam/{id}', 'Api\ExamManangementController@edit');
			Route::get('showExam/{id}', 'Api\ExamManangementController@show');

			//post
			Route::post('createExam', 'Api\ExamManangementController@store');
			Route::post('updateExam/{quiz_pack_id}', 'Api\ExamManangementController@update');

			//delete
			Route::delete('deleteExam/{quiz_pack_id}', 'Api\ExamManangementController@destroy');

			//quizpack
			Route::get('getQuizPacks/{page}', 'Api\QuizPacksController@getQuizPacks');
			Route::get('getQuizPacksIndex', 'Api\QuizPacksController@index');
			Route::post('addQuizPack', 'Api\QuizPacksController@create');
			Route::post('updateQuizPack/{id}', 'Api\QuizPacksController@update');
			Route::delete('deleteQuizPack/{id}', 'Api\QuizPacksController@delete');
			Route::get('send_csv', 'Api\QuizPacksController@sendExamCsv');

			//get quizzes by quizpack
			Route::get('quizStart/{id}', 'Api\QuizPacksController@quiz_start');
			Route::get('getQuizData/{id}', 'Api\QuizPacksController@getQuizData');

			//save Exam Result after taking exam
			Route::post('saveExamResult/{exam_id}/{user_id}', 'Api\QuizPacksController@saveExamResult');

			// Save User Sort Table
			Route::post('userSortTable', 'Api\UserSortTableController@update');
			Route::post('getUserSortTable', 'Api\UserSortTableController@index');
			//library users
			Route::get('getLibraryUser', 'Api\LibUserController@getUser');

			//Libraries
			Route::get('getLib/{type}/{page}', 'Api\LibraryController@index');
			Route::post('addLib', 'Api\LibraryController@store');
			Route::post('updateLib/{id}', 'Api\LibraryController@update');
			Route::post('updateAusculaideUrl/{id}', 'Api\LibraryController@updateAusculaideUrl');
			Route::delete('deleteLibItem/{id}', 'Api\LibraryController@delete');

			// quizzess library
			Route::get('quizzes/{page}', 'Api\QuizzesController@index');
			Route::resource('quizzes', 'Api\QuizzesController', ['only' => [
				'store', 'destroy'
			]]);
			Route::post('updateQuiz/{id}', 'Api\QuizzesController@update');

			Route::get('get_user', 'Api\GetUserController@getUsers');

			// Users Management
			//get
			Route::get('getUsers/{page}/{enabled}', 'Api\UserManagementController@index');
			Route::get('editUser/{id}', 'Api\UserManagementController@edit');
			Route::get('showUser/{id}', 'Api\UserManagementController@show');
			Route::get('allUsersByExamGroup/{examGroupId}', 'Api\UserManagementController@allUsersByExamGroup');
			Route::post('updateUserStatus/{id}', 'Api\UserManagementController@updateStatus');

			//univ user password reset
			Route::post('change_password', 'Api\PasswordResetControlller@changePassword');

			//post
			Route::post('createUser', 'Api\UserManagementController@store');
			Route::post('updateUser/{id}', 'Api\UserManagementController@update');

			//delete
			Route::delete('deleteUser/{id}', 'Api\UserManagementController@destroy');

			//custom
			Route::get('getExamGroup', 'Api\Custom\ExamGroupController@index');

			// Exam Analytics
			Route::get('getExamResults', 'Api\AnalyticsController@index');
			Route::get('getExamResult/{accountId}/{examGroupId}/{scope}/{capability}', 'Api\AnalyticsController@show');
			Route::post('getExamChart', 'Api\AnalyticsController@getExamChart');
			Route::post('getExamlog', 'Api\AnalyticsController@getExamlog');
			Route::get('getExamAnalyticMenu', 'Api\AnalyticsController@getExamAnalyticMenu');
			
			// Log Analitics
			Route::post('logAnalytics', 'Api\LogAnalyticsController@index');
			Route::post('getLogAnalytics', 'Api\LogAnalyticsController@getNewLogAnalytics');
			Route::post('getRanking', 'Api\LogAnalyticsController@getRanking');
			Route::post('getPieChart', 'Api\LogAnalyticsController@getPieChart');
			Route::post('analyticMenu', 'Api\LogAnalyticsController@selectMenu');

			//Information
			Route::get('getManagementInformation/{page}', 'Api\InformationController@index');

		});
	});
});
});