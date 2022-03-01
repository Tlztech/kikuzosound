<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use App\User;
use Carbon\Carbon;
use App\ExamGroup;
use App\Comment;
use App\StethoSound;
use App\QuizStethoSound;
use App\StethoSoundImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\StethoSoundRequest;
use Illuminate\Validation\ValidationException;

class StethoSoundController extends Controller
{
    /**
     * Base Directory.
     *
     * @var string
     */
    private $base_url = '/audio/stetho_sounds/';
    private $video_base_url = '/video/library_videos/';
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $lib =$this->getLibType(\Request::route()->getPath()); //get lib_type in route
        $lib_view=$this->getLibTypeView(\Request::route()->getPath()); //get lib_type view in route
        $stetho_sounds = StethoSound::where('lib_type', $lib)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->paginate(10);
        $count = StethoSound::where('deleted_at', null)->count();
        if (isset($_GET['reorder'])) {
            $stetho_sounds = StethoSound::where('lib_type', $lib)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->paginate($count);
        } else {
            // システム管理者はコンテンツをすべて表示する
            if (\Auth::user()->role == User::$ROLE_ADMIN) {
                $stetho_sounds = StethoSound::where('lib_type', $lib)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->paginate(10);
            }
            // 監修者は自分が担当するコンテンツをすべて表示する
            elseif (\Auth::user()->role == User::$ROLE_SUPERINTENDENT) {
                $stetho_sounds = StethoSound::where('lib_type', $lib)->where('user_id', \Auth::user()->id)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->paginate(10);
            }
        }

        return view($lib_view, compact('stetho_sounds', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $lib_view=$this->getLibTypeView(\Request::route()->getPath()); //get lib_type view in route
        // 監修者の一覧を取得する
        $superintendents = User::superintendents();
        $count = StethoSound::where('deleted_at', null)->count();
        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        return view($lib_view, compact('count', 'exam_groups'))->with('superintendents', $superintendents->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StethoSoundRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validation = $this->validateRequests($request);
        if (count($validation->errors()->all())) {
            // dd($validator->errors());
            return redirect()->back()->withErrors($validation)->withInput($request->except('image_files', 'sound_file','explanatory_image_file','explanatory_image_file_en'));
        }
        DB::beginTransaction();
        $stetho_sound = $this->assignRequestToStethoSound(new StethoSound(), $request);

        $success = $stetho_sound->save();

        //save university id's in pivot table
        $stetho_sound->exam_groups()->detach();
        if ($request->input('group_attr')==0) {
            foreach ($request->input("exam_group", array()) as $key => $value) {
                $stetho_sound->exam_groups()->attach($stetho_sound->id, ['exam_group_id'=>$value]);
            }
        }

        if (!$success) {
            DB::rollback();
            Log::error('データを登録できませんでした。');
            return view('admin.stetho_sounds.create', compact('stetho_sound'))
                ->with('superintendents', User::superintendents()->get())
                ->withErrors(['sound_file' => 'データを登録できませんでした。'])
      ;
        }

        // ID取得
        $last_id = $stetho_sound->id;

        // TODO: 重複削除
        // TODO: サウンドファイルOKが画像ファイルNGの場合に、
        //       サウンドファイルのsound_pathがセッションから削除される問題への対応

        // サウンドファイルを保存する
        if ($request->hasFile('sound_file') || $request->has('sound_path')) {
            $sound_path = $this->moveSoundFile($stetho_sound, $request);
            if (is_null($sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$sound_path);
                return view('admin.stetho_sounds.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['sound_file' => 'サウンドファイルの移動に失敗しました。'])
        ;
            }
            $stetho_sound->sound_path = $sound_path;
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('sound_path'));
            \Session::forget('sound_path');
        }

        // heart
        $a_sound_path = "";
        $p_sound_path = "";
        $t_sound_path = "";
        $m_sound_path = "";
        // pulse
        $pa_sound_path = "";
        $pp_sound_path = "";
        $pt_sound_path = "";
        $pm_sound_path = "";

        // a heart
        if ($request->hasFile('a_sound_file') || $request->has('a_sound_path')) {
            $a_sound_path = $this->moveAPTMSound($stetho_sound, $request, "a");
            if (is_null($a_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$a_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['a_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('a_sound_path'));
            \Session::forget('a_sound_path');
        }

        // a pulse
        if ($request->hasFile('pa_sound_file') || $request->has('pa_sound_path')) {
            $pa_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pa");
            if (is_null($pa_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pa_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['pa_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('pa_sound_path'));
            \Session::forget('pa_sound_path');
        }

        // p heart
        if ($request->hasFile('p_sound_file') || $request->has('p_sound_path')) {
            $p_sound_path = $this->moveAPTMSound($stetho_sound, $request, "p");
            if (is_null($p_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$p_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['p_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('p_sound_path'));
            \Session::forget('p_sound_path');
        }

        // p pulse
        if ($request->hasFile('pp_sound_file') || $request->has('pp_sound_path')) {
            $pp_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pp");
            if (is_null($pp_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pp_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['pp_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('pp_sound_path'));
            \Session::forget('pp_sound_path');
        }

        // t heart
        if ($request->hasFile('t_sound_file') || $request->has('t_sound_path')) {
            $t_sound_path = $this->moveAPTMSound($stetho_sound, $request, "t");
            if (is_null($t_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$t_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['t_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('t_sound_path'));
            \Session::forget('t_sound_path');
        }

        // t pulse
        if ($request->hasFile('pt_sound_file') || $request->has('pt_sound_path')) {
            $pt_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pt");
            if (is_null($pt_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pt_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['pt_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('pt_sound_path'));
            \Session::forget('pt_sound_path');
        }

        // m heart
        if ($request->hasFile('m_sound_file') || $request->has('m_sound_path')) {
            $m_sound_path = $this->moveAPTMSound($stetho_sound, $request, "m");
            if (is_null($m_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$m_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['m_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('m_sound_path'));
            \Session::forget('m_sound_path');
        }

        // m pulse
        if ($request->hasFile('pm_sound_file') || $request->has('pm_sound_path')) {
            $pm_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pm");
            if (is_null($pm_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pm_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['pm_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('pm_sound_path'));
            \Session::forget('pm_sound_path');
        }

        $tr1_sound_path = "";
        $tr2_sound_path = "";
        $br1_sound_path = "";
        $br2_sound_path = "";
        $br3_sound_path = "";
        $br4_sound_path = "";

        if ($request->hasFile('tr1_sound_file') || $request->has('tr1_sound_path')) {
            $tr1_sound_path = $this->moveAPTMSound($stetho_sound, $request, "tr1");
            if (is_null($tr1_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$tr1_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['tr1_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('tr1_sound_path'));
            \Session::forget('tr1_sound_path');
        }

        if ($request->hasFile('tr2_sound_file') || $request->has('tr2_sound_path')) {
            $tr2_sound_path = $this->moveAPTMSound($stetho_sound, $request, "tr2");
            if (is_null($tr2_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$tr2_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['tr2_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('tr2_sound_path'));
            \Session::forget('tr2_sound_path');
        }

        if ($request->hasFile('br1_sound_file') || $request->has('br1_sound_path')) {
            $br1_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br1");
            if (is_null($br1_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br1_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['br1_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('br1_sound_path'));
            \Session::forget('br1_sound_path');
        }

        if ($request->hasFile('br2_sound_file') || $request->has('br2_sound_path')) {
            $br2_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br2");
            if (is_null($br2_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br2_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['br2_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('br2_sound_path'));
            \Session::forget('br2_sound_path');
        }

        if ($request->hasFile('br3_sound_file') || $request->has('br3_sound_path')) {
            $br3_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br3");
            if (is_null($br3_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br3_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['br3_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('br3_sound_path'));
            \Session::forget('br3_sound_path');
        }

        if ($request->hasFile('br4_sound_file') || $request->has('br4_sound_path')) {
            $br4_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br4");
            if (is_null($br4_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br4_sound_path);
                return view('admin.iPax.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['br4_sound_file' => 'サウンドファイルの移動に失敗しました。']);
            }

            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('br4_sound_path'));
            \Session::forget('br4_sound_path');
        }

        if ($request->input("lib_type") == 1) {
            $new_sound_path[] = array(
                "a_sound_path"  => $a_sound_path,
                "p_sound_path"  => $p_sound_path,
                "t_sound_path"  => $t_sound_path,
                "m_sound_path"  => $m_sound_path,
                "pa_sound_path"  => $pa_sound_path,
                "pp_sound_path"  => $pp_sound_path,
                "pt_sound_path"  => $pt_sound_path,
                "pm_sound_path"  => $pm_sound_path,
                "tr1_sound_path" => $tr1_sound_path,
                "tr2_sound_path" => $tr2_sound_path,
                "br1_sound_path" => $br1_sound_path,
                "br2_sound_path" => $br2_sound_path,
                "br3_sound_path" => $br3_sound_path,
                "br4_sound_path" => $br4_sound_path
            );

            for ($i=1; $i <= 12; $i++) {
                if ($request->hasFile('ve'.$i.'_sound_file') || $request->has('ve'.$i.'_sound_path')) {
                    $ve_sound_path = $this->moveAPTMSound($stetho_sound, $request, "ve".$i);
                    if (is_null($ve_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$ve_sound_path);
                        return view('admin.iPax.create', compact('stetho_sound'))
                            ->with('superintendents', User::superintendents()->get())
                            ->withErrors(['ve'.$i.'_sound_file' => 'サウンドファイルの移動に失敗しました。']);
                    }
    
                    // append
                    $new_sound_path[0]["ve".$i."_sound_path"] = $ve_sound_path;
    
                    // セッションにパスがある場合、ファイルとSessionも消す
                    $this->removeTmpFile($request->input('ve'.$i.'_sound_path'));
                    \Session::forget('ve'.$i.'_sound_path');
                } else {
                    // append
                    $new_sound_path[0]["ve".$i."_sound_path"] = "";
                }
            }

            for ($i=1; $i <= 4; $i++) {
                if ($request->hasFile('h'.$i.'_sound_file') || $request->has('h'.$i.'_sound_path')) {
                    $h_sound_path = $this->moveAPTMSound($stetho_sound, $request, "h".$i);
                    if (is_null($h_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$h_sound_path);
                        return view('admin.iPax.create', compact('stetho_sound'))
                            ->with('superintendents', User::superintendents()->get())
                            ->withErrors(['h'.$i.'_sound_file' => 'サウンドファイルの移動に失敗しました。']);
                    }
    
                    // append
                    $new_sound_path[0]["h".$i."_sound_path"] = $h_sound_path;
    
                    // セッションにパスがある場合、ファイルとSessionも消す
                    $this->removeTmpFile($request->input('h'.$i.'_sound_path'));
                    \Session::forget('h'.$i.'_sound_path');
                } else {
                    // append
                    $new_sound_path[0]["h".$i."_sound_path"] = "";
                }
            }

            for ($i=1; $i <= 4; $i++) {
                if ($request->hasFile('p'.$i.'_sound_file') || $request->has('p'.$i.'_sound_path')) {
                    $p_sound_path = $this->moveAPTMSound($stetho_sound, $request, "p".$i);
                    if (is_null($p_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$p_sound_path);
                        return view('admin.iPax.create', compact('stetho_sound'))
                            ->with('superintendents', User::superintendents()->get())
                            ->withErrors(['p'.$i.'_sound_file' => 'サウンドファイルの移動に失敗しました。']);
                    }
    
                    // append
                    $new_sound_path[0]["p".$i."_sound_path"] = $p_sound_path;
    
                    // セッションにパスがある場合、ファイルとSessionも消す
                    $this->removeTmpFile($request->input('p'.$i.'_sound_path'));
                    \Session::forget('p'.$i.'_sound_path');
                } else {
                    // append
                    $new_sound_path[0]["p".$i."_sound_path"] = "";
                }
            }



            $stetho_sound->sound_path = json_encode($new_sound_path[0]);
            $stetho_sound->save();

            // dd($new_sound_path); exit;
        }

        // ビデオファイルを保存する
        if ($request->hasFile('lib_video_file') || $request->has('lib_video_file_path')) {
            $video_path = $this->moveVideoFile($stetho_sound, $request);
            if (is_null($video_path)) {
                DB::rollback();
                Log::error('ビデオファイルの移動に失敗しました：元のファイルパス： '.public_path().$video_path);
                return view('admin.stetho_sounds.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['sound_file' => '動画ファイルの移動に失敗しました。'])
        ;
            }
            $stetho_sound->video_path = $video_path;
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('lib_video_file_path'));
            \Session::forget('lib_video_file_path');
        }
        // else {
        //   DB::rollback();
        //   return $this->create('admin.stetho_sounds.create', compact('stetho_sound'))
        //               ->with('superintendents', User::superintendents()->get())
        //               ->withErrors(['sound_file'=>'サウンドファイルは必須項目です']);
        // }
        // 画像ファイルを保存する
        
        if ($request->hasFile('lib_image_file') || $request->has('lib_image_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', $last_id, 'lib_image_file', 'lib_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                abort(500, 'ファイルの移動に失敗しました');
            }
            $stetho_sound->image_path = $image_path;
            $stetho_sound->save();
            list($w, $t) = getimagesize(public_path($image_path));
            if ($t > 512) {
                // リサイズ
                $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // 上書き保存
                $img->save(public_path($image_path));
            }
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('lib_image_file_path'));
            \Session::forget('lib_image_file_path');
        }
        //explanatory_image_file
        if ($request->hasFile('explanatory_image_file') || $request->has('explanatory_image_file_path')) {
            $image_path = $this->moveMultipleFiles($request, '/img/library_images/',$this->video_base_url, 'explanatory_'.$last_id, 'explanatory_image_file', 'explanatory_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                return redirect()->back()->withErrors(['explanatory_image_file' => '無効なファイルが含まれています'])->withInput($request->except('image_files', 'sound_file','explanatory_image_file','explanatory_image_file_path','explanatory_image_file_en'));
            }
            $stetho_sound->explanatory_image = json_encode($image_path);
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $temp_files = $request->input('explanatory_image_file_path') ? $request->input('explanatory_image_file_path') : [];
            foreach ($temp_files as $index => $f){
                $this->removeTmpFile($f);
            }
            \Session::forget('explanatory_image_file_path');
        }
        //explanatory_image_file_en
        if ($request->hasFile('explanatory_image_file_en') || $request->has('explanatory_image_file_en_path')) {
            $image_path = $this->moveMultipleFiles($request, '/img/library_images/',$this->video_base_url, 'explanatory_en_'.$last_id, 'explanatory_image_file_en', 'explanatory_image_file_en_path');
            if (is_null($image_path)) {
                DB::rollback();
                return redirect()->back()->withErrors(['explanatory_image_file_en' => '無効なファイルが含まれています'])->withInput($request->except('image_files', 'sound_file','explanatory_image_file','explanatory_image_file_en'));
            }
            $stetho_sound->explanatory_image_en = json_encode($image_path);
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $temp_files = $request->input('explanatory_image_file_en_path') ? $request->input('explanatory_image_file_en_path') : [];
            foreach ($temp_files as $index => $f){
                $this->removeTmpFile($f);
            }
            \Session::forget('explanatory_image_file_en_path');
        }
        //explanatory_image_file_en
        if ($request->hasFile('body_image_file') || $request->has('body_image_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', 'body_'.$last_id, 'body_image_file', 'body_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                abort(500, 'ファイルの移動に失敗しました');
            }
            $stetho_sound->body_image = $image_path;
            $stetho_sound->save();
            list($w, $t) = getimagesize(public_path($image_path));
            if ($t > 512) {
                // リサイズ
                $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // 上書き保存
                $img->save(public_path($image_path));
            }
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('body_image_file_path'));
            \Session::forget('body_image_file_path');
        }

        if ($request->hasFile('body_image_back_file') || $request->has('body_image_back_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', 'body_back_'.$last_id, 'body_image_back_file', 'body_image_back_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                abort(500, 'ファイルの移動に失敗しました');
            }
            $stetho_sound->body_image_back = $image_path;
            $stetho_sound->save();
            list($w, $t) = getimagesize(public_path($image_path));
            if ($t > 512) {
                // リサイズ
                $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // 上書き保存
                $img->save(public_path($image_path));
            }
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('body_image_back_file_path'));
            \Session::forget('body_image_back_file_path');
        }
        

        // 画像を保存する
        if ($request->hasFile('image_files') || $request->input('image_paths')) {
            $images = $this->initStethoSoundImages($last_id, $request);

            // 聴診音画像レコード登録
            // $images配列から新しいコレクションインスタンスを作成し、さらにkey[data]のみの配列を作成する
            $success = $stetho_sound->images()->saveMany(collect($images)->lists('data'));
            if (!$success) {
                DB::rollback();
                Log::error('聴診音画像データを登録できませんでした。');
                return view('admin.stetho_sounds.create', compact('stetho_sound'))
                    ->with('superintendents', User::superintendents()->get())
                    ->withErrors(['sound_file' => 'データを登録できませんでした。'])
        ;
            }

            // 画像ファイル保存
            $allowedfileExtension=['jpeg','png','jpg','gif','svg','mp4','mpeg-4'];
            foreach ($images as $image) {
                $file_image = $image['file'];
                $file_extension = $file_image->getClientOriginalExtension();
                $check=in_array(strtolower($file_extension), $allowedfileExtension);
                if (!$check) {
                    $count = StethoSound::where('deleted_at', null)->count();
                    DB::rollback();
                    // Log::error('画像ファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$image_path);
                    return redirect()->back()
                        ->with('superintendents', User::superintendents()->get())
                        ->withInput($request->input())
                        ->withErrors(['image_files' => '画像は .jpg .gif .png のみアップロードできます。']);
                }
                $image_path = $this->moveStethoSoundImageFile($image);
                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('画像ファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$image_path);
                    return view('admin.stetho_sounds.create', compact('stetho_sound'))
                        ->with('superintendents', User::superintendents()->get())
                        ->withErrors(['image_files' => '画像ファイルの移動に失敗しました。'])
          ;
                }

                list($w, $t) = getimagesize(public_path($image_path));

                // GIFアニメはアップしたままでないと動作しないのでリサイズしない
        $path_parts = pathinfo(public_path($image_path)); // パス情報取得
        $extension = mb_strtolower($path_parts['extension']); // 拡張子取得

        if ($t > 440 && 'gif' != $extension) {    // gifの場合はリサイズしない
            // リサイズ
            $img = Image::make(public_path($image_path))->resize(null, 440, function ($constraint) {
                $constraint->aspectRatio();
            });
            // 上書き保存
            $img->save(public_path($image_path));
        }

                $image['data']->image_path = $image_path;
                $image['data']->save();
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($image['path']);
            }
            \Session::forget('stetho_sound_images');
        }

        DB::commit();

        $lib_view=$this->getLibTypeView($request->input('lib_type'));//get lib view
        return redirect()->route($lib_view)->with('message', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $stetho_sound = StethoSound::whereNull("deleted_at")->findOrFail($id);
        $lib_view=$this->getLibTypeView(\Request::route()->getPath());
        return view($lib_view, compact('stetho_sound'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $exam_groups= ExamGroup::orderBy("id", "desc")->get();
        $stetho_sound = StethoSound::whereNull("deleted_at")->findOrFail($id);
        $lib_view=$this->getLibTypeView('edit_type_'.$stetho_sound->lib_type);//get lib view
        // 監修者の一覧を取得する
        $superintendents = User::superintendents()->get();
        $page = $request->query('page');

        return view($lib_view, compact(['stetho_sound', 'superintendents', 'page','exam_groups']));
    }

    /**
     * 監修ステータス変更用API.
     *
     * @param mixed $id
     */
    public function update_status(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $stetho_sound = StethoSound::findOrFail($id);
            $stetho_sound->status = $request->input('status');
            $stetho_sound->save();
            Log::info('監修ステータスの変更に成功しました');
            DB::commit();
        } catch (Exception $e) {
            Log::error('監修ステータスの変更に失敗しました');
            DB::rollback();
            return redirect()->route('admin.stetho_sounds.show', $id)
                ->withErrors(['image_files' => '聴診音画像ファイルを移動できませんでした。'])
      ;
        }
        return redirect()->route('admin.stetho_sounds.show', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StethoSoundRequest $request
     * @param int                $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validateRequestsUpdate($request);

        if (count($validation->errors()->all())) {
            // dd($validator->errors());
            return redirect()->back()->withErrors($validation)->withInput($request->except('image_files', 'sound_file', 'explanatory_image_file', 'explanatory_image_file_en'));
        }
        $page = $request->page;
        $base_dir = public_path().$this->base_url;
        $invalid_files=[];
        // 排他制御処理
        $stetho_sound = StethoSound::findOrFail($id);

        if (!$request->input('is_force_update', false) && Carbon::parse($request->input('updated_at'))->lt(Carbon::parse($stetho_sound->updated_at))) {
            $lib_view=$this->getLibTypeView('edit_type_'.$request->input('lib_type'));//get lib view
            return redirect()
                ->route($lib_view, $id)
                ->withInput()
                ->withErrors([
                    'is_force_update' => '編集中に他のユーザが更新しました。強制的に変更を行う場合は『強制更新』にチェックを入れ、保存ボタンを押してください。',
                ])
            ;
        }


        DB::beginTransaction();

        $stetho_sound = $this->assignRequestToStethoSound($stetho_sound, $request);
        $success = $stetho_sound->save();

        //save university id's in pivot table
        $stetho_sound->exam_groups()->detach();
        if ($request->input('group_attr')==0) {
            foreach ($request->input("exam_group", array()) as $key => $value) {
                $stetho_sound->exam_groups()->attach($stetho_sound->id, ['exam_group_id'=>$value]);
            }
        }

        if (!$success) {
            DB::rollback();
            Log::error('保存に失敗しました');
            $invalid_files['save_error'] = '保存に失敗しました';
        }

        // サウンドファイルを保存する
        if ($request->hasFile('sound_file') || $request->has('sound_path')) {
            $sound_path = $this->moveSoundFile($stetho_sound, $request);
            if (is_null($sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$sound_path);
                $invalid_files['sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                $stetho_sound->sound_path = $sound_path;
                $stetho_sound->save();
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('sound_path'));
                \Session::forget('sound_path');
            }
        }

        // heart
        $a_sound_path = "";
        $p_sound_path = "";
        $t_sound_path = "";
        $m_sound_path = "";
        // pulse
        $pa_sound_path = "";
        $pp_sound_path = "";
        $pt_sound_path = "";
        $pm_sound_path = "";

        // a heart
        if ($request->hasFile('a_sound_file') || $request->has('a_sound_path')) {
            $a_sound_path = $this->moveAPTMSound($stetho_sound, $request, "a");
            if (is_null($a_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$a_sound_path);
                $invalid_files['a_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('a_sound_path'));
                \Session::forget('a_sound_path');
            }
        }
        // a pulse
        if ($request->hasFile('pa_sound_file') || $request->has('pa_sound_path')) {
            $pa_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pa");
            if (is_null($pa_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pa_sound_path);
                $invalid_files['pa_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('pa_sound_path'));
                \Session::forget('pa_sound_path');
            }
        }

        // p heart
        if ($request->hasFile('p_sound_file') || $request->has('p_sound_path')) {
            $p_sound_path = $this->moveAPTMSound($stetho_sound, $request, "p");
            if (is_null($p_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$p_sound_path);
                $invalid_files['p_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('p_sound_path'));
                \Session::forget('p_sound_path');
            }
        }
        // p pulse
        if ($request->hasFile('pp_sound_file') || $request->has('pp_sound_path')) {
            $pp_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pp");
            if (is_null($pp_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pp_sound_path);
                $invalid_files['pp_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('pp_sound_path'));
                \Session::forget('pp_sound_path');
            }
        }
        // t heart
        if ($request->hasFile('t_sound_file') || $request->has('t_sound_path')) {
            $t_sound_path = $this->moveAPTMSound($stetho_sound, $request, "t");
            if (is_null($t_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$t_sound_path);
                $invalid_files['t_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('t_sound_path'));
                \Session::forget('t_sound_path');
            }
        }
        // t pulse
        if ($request->hasFile('pt_sound_file') || $request->has('pt_sound_path')) {
            $pt_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pt");
            if (is_null($pt_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pt_sound_path);
                $invalid_files['pt_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('pt_sound_path'));
                \Session::forget('pt_sound_path');
            }
        }
        // m heart
        if ($request->hasFile('m_sound_file') || $request->has('m_sound_path')) {
            $m_sound_path = $this->moveAPTMSound($stetho_sound, $request, "m");
            if (is_null($m_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$m_sound_path);
                $invalid_files['m_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('m_sound_path'));
                \Session::forget('m_sound_path');
            }
        }
        // m pulse
        if ($request->hasFile('pm_sound_file') || $request->has('pm_sound_path')) {
            $pm_sound_path = $this->moveAPTMSound($stetho_sound, $request, "pm");
            if (is_null($pm_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$pm_sound_path);
                $invalid_files['pm_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('pm_sound_path'));
                \Session::forget('pm_sound_path');
            }
        }

        $tr1_sound_path = "";
        $tr2_sound_path = "";
        $br1_sound_path = "";
        $br2_sound_path = "";
        $br3_sound_path = "";
        $br4_sound_path = "";

        if ($request->hasFile('tr1_sound_file') || $request->has('tr1_sound_path')) {
            $tr1_sound_path = $this->moveAPTMSound($stetho_sound, $request, "tr1");
            if (is_null($tr1_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$tr1_sound_path);
                $invalid_files['tr1_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('tr1_sound_path'));
                \Session::forget('tr1_sound_path');
            }
        }

        if ($request->hasFile('tr2_sound_file') || $request->has('tr2_sound_path')) {
            $tr2_sound_path = $this->moveAPTMSound($stetho_sound, $request, "tr2");
            if (is_null($tr2_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$tr2_sound_path);
                $invalid_files['tr2_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('tr2_sound_path'));
                \Session::forget('tr2_sound_path');
            }
        }

        if ($request->hasFile('br1_sound_file') || $request->has('br1_sound_path')) {
            $br1_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br1");
            if (is_null($br1_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br1_sound_path);
                $invalid_files['br1_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('br1_sound_path'));
                \Session::forget('br1_sound_path');
            }
        }

        if ($request->hasFile('br2_sound_file') || $request->has('br2_sound_path')) {
            $br2_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br2");
            if (is_null($br2_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br2_sound_path);
                $invalid_files['br2_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('br2_sound_path'));
                \Session::forget('br2_sound_path');
            }
        }

        if ($request->hasFile('br3_sound_file') || $request->has('br3_sound_path')) {
            $br3_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br3");
            if (is_null($br3_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br3_sound_path);
                $invalid_files['br3_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('br3_sound_path'));
                \Session::forget('br3_sound_path');
            }
        }

        if ($request->hasFile('br4_sound_file') || $request->has('br4_sound_path')) {
            $br4_sound_path = $this->moveAPTMSound($stetho_sound, $request, "br4");
            if (is_null($br4_sound_path)) {
                DB::rollback();
                Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$br4_sound_path);
                $invalid_files['br4_sound_file'] = 'サウンドファイルの移動に失敗しました。';
            } else {
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('br4_sound_path'));
                \Session::forget('br4_sound_path');
            }
        }

        if ($request->input("lib_type") == 1) {
            $new_sound_path[] = array(
                "a_sound_path"  => $a_sound_path,
                "p_sound_path"  => $p_sound_path,
                "t_sound_path"  => $t_sound_path,
                "m_sound_path"  => $m_sound_path,
                "pa_sound_path"  => $pa_sound_path,
                "pp_sound_path"  => $pp_sound_path,
                "pt_sound_path"  => $pt_sound_path,
                "pm_sound_path"  => $pm_sound_path,
                "tr1_sound_path"  => $tr1_sound_path,
                "tr2_sound_path"  => $tr2_sound_path,
                "br1_sound_path"  => $br1_sound_path,
                "br2_sound_path"  => $br2_sound_path,
                "br3_sound_path" => $br3_sound_path,
                "br4_sound_path" => $br4_sound_path
            );

            for ($i=1; $i <= 12; $i++) {
                if ($request->hasFile('ve'.$i.'_sound_file') || $request->has('ve'.$i.'_sound_path')) {
                    $ve_sound_path = $this->moveAPTMSound($stetho_sound, $request, "ve".$i);
                    if (is_null($ve_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$ve_sound_path);
                        $invalid_files['ve'.$i.'_sound_file'] = 'サウンドファイルの移動に失敗しました。';
                    } else {
                        // セッションにパスがある場合、ファイルとSessionも消す
                        $this->removeTmpFile($request->input('ve'.$i.'_sound_path'));
                        \Session::forget('ve'.$i.'_sound_path');
                    }

                    // append
                    $new_sound_path[0]["ve".$i."_sound_path"] = $ve_sound_path;
                }
            }

            for ($i=1; $i <= 4; $i++) {
                if ($request->hasFile('h'.$i.'_sound_file') || $request->has('h'.$i.'_sound_path')) {
                    $h_sound_path = $this->moveAPTMSound($stetho_sound, $request, "h".$i);
                    if (is_null($h_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$h_sound_path);
                        $invalid_files['h'.$i.'_sound_file'] = 'サウンドファイルの移動に失敗しました。';
                    } else {
                        // セッションにパスがある場合、ファイルとSessionも消す
                        $this->removeTmpFile($request->input('h'.$i.'_sound_path'));
                        \Session::forget('h'.$i.'_sound_path');
                    }

                    // append
                    $new_sound_path[0]["h".$i."_sound_path"] = $h_sound_path;
                }
            }

            for ($i=1; $i <= 12; $i++) {
                if ($request->hasFile('p'.$i.'_sound_file') || $request->has('p'.$i.'_sound_path')) {
                    $p_sound_path = $this->moveAPTMSound($stetho_sound, $request, "p".$i);
                    if (is_null($p_sound_path)) {
                        DB::rollback();
                        Log::error('サウンドファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$p_sound_path);
                        $invalid_files['p'.$i.'_sound_file'] = 'サウンドファイルの移動に失敗しました。';
                    } else {
                        // セッションにパスがある場合、ファイルとSessionも消す
                        $this->removeTmpFile($request->input('p'.$i.'_sound_path'));
                        \Session::forget('p'.$i.'_sound_path');
                    }

                    // append
                    $new_sound_path[0]["p".$i."_sound_path"] = $p_sound_path;
                }
            }

            $stetho_sound->sound_path = json_encode($new_sound_path[0]);
            $stetho_sound->save();
        }

        // ビデオファイルを保存する
        if ($request->hasFile('lib_video_file') || $request->has('lib_video_file_path')) {
            $video_path = $this->moveVideoFile($stetho_sound, $request);
            if (is_null($video_path)) {
                DB::rollback();
                Log::error('ビデオファイルの移動に失敗しました：元のファイルパス： '.public_path().$video_path);
                $invalid_files['lib_video_file'] = '動画ファイルの移動に失敗しました。';
            } else {
                $stetho_sound->video_path = $video_path;
                $stetho_sound->save();
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('lib_video_file_path'));
                \Session::forget('lib_video_file_path');
            }
        }
        // has lib image file
        if ($request->hasFile('lib_image_file') || $request->has('lib_image_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', $stetho_sound->id, 'lib_image_file', 'lib_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                $invalid_files['lib_image_file'] = 'ファイルの移動に失敗しました';
            } else {
                $stetho_sound->image_path = $image_path;
                $stetho_sound->save();
                list($w, $t) = getimagesize(public_path($image_path));
                if ($t > 512) {
                    // リサイズ
                    $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // 上書き保存
                    $img->save(public_path($image_path));
                }
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('lib_image_file_path'));
                \Session::forget('lib_image_file_path');
            }
        }

        $last_id = $stetho_sound->id;
        //explanatory_image_file
        if ($request->hasFile('explanatory_image_file') || $request->has('explanatory_image_file_path')) {
            $image_path = $this->moveMultipleFiles($request, '/img/library_images/',$this->video_base_url, 'explanatory_'.$last_id, 'explanatory_image_file', 'explanatory_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                return redirect()->back()->withErrors(['explanatory_image_file' => '無効なファイルが含まれています'])->withInput($request->except('image_files', 'sound_file','explanatory_image_file','explanatory_image_file_path','explanatory_image_file_en'));
            }
            $stetho_sound->explanatory_image = json_encode($image_path);
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $temp_files = $request->input('explanatory_image_file_path') ? $request->input('explanatory_image_file_path') : [];
            foreach ($temp_files as $index => $f){
                $this->removeTmpFile($f);
            }
            \Session::forget('explanatory_image_file_path');
        }
        //explanatory_image_file_en
        if ($request->hasFile('explanatory_image_file_en') || $request->has('explanatory_image_file_en_path')) {
            $image_path = $this->moveMultipleFiles($request, '/img/library_images/',$this->video_base_url, 'explanatory_en_'.$last_id, 'explanatory_image_file_en', 'explanatory_image_file_en_path');
            if (is_null($image_path)) {
                DB::rollback();
                return redirect()->back()->withErrors(['explanatory_image_file_en' => '無効なファイルが含まれています'])->withInput($request->except('image_files', 'sound_file','explanatory_image_file','explanatory_image_file_en'));
            }
            $stetho_sound->explanatory_image_en = json_encode($image_path);
            $stetho_sound->save();
            // セッションにパスがある場合、ファイルとSessionも消す
            $temp_files = $request->input('explanatory_image_file_en_path') ? $request->input('explanatory_image_file_en_path') : [];
            foreach ($temp_files as $index => $f){
                $this->removeTmpFile($f);
            }
            \Session::forget('explanatory_image_file_en_path');
        }
        //explanatory_image_file_en
        if ($request->hasFile('body_image_file') || $request->has('body_image_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', 'body_'.$last_id, 'body_image_file', 'body_image_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                $invalid_files['body_image_file'] = 'ファイルの移動に失敗しました';
            } else {
                $stetho_sound->body_image = $image_path;
                $stetho_sound->save();
                list($w, $t) = getimagesize(public_path($image_path));
                if ($t > 512) {
                    // リサイズ
                    $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // 上書き保存
                    $img->save(public_path($image_path));
                }
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('body_image_file_path'));
                \Session::forget('body_image_file_path');
            }
        }
        if ($request->hasFile('body_image_back_file') || $request->has('body_image_back_file_path')) {
            $image_path = $this->moveFile($request, '/img/library_images/', 'body_back_'.$last_id, 'body_image_back_file', 'body_image_back_file_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                $invalid_files['body_image_back_file'] = 'ファイルの移動に失敗しました';
            } else {
                $stetho_sound->body_image_back = $image_path;
                $stetho_sound->save();
                list($w, $t) = getimagesize(public_path($image_path));
                if ($t > 512) {
                    // リサイズ
                    $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // 上書き保存
                    $img->save(public_path($image_path));
                }
                // セッションにパスがある場合、ファイルとSessionも消す
                $this->removeTmpFile($request->input('body_image_back_file_path'));
                \Session::forget('body_image_back_file_path');
            }
        }
        // 画像を保存する
        if ($request->hasFile('image_files') || $request->input('image_paths')) {
            $images = $this->initStethoSoundImages($stetho_sound->id, $request);
            // 聴診音画像レコード登録
            $success = $stetho_sound->images()->saveMany(collect($images)->lists('data'));

            if (!$success) {
                DB::rollback();
                Log::error('聴診音画像データを登録できませんでした。');
                $invalid_files['image_files'] = 'データを登録できませんでした。';
            }
            $allowedfileExtension=['jpeg','png','jpg','gif','svg','mp4','mpeg-4'];
            // 画像ファイル保存
            foreach ($images as $image) {
                $image_path = $this->moveStethoSoundImageFile($image);
                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('画像ファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$image_path);
                    $invalid_files['image_files'] = '画像ファイルの移動に失敗しました。';
                }else{
                    // リサイズ
                    // GIFアニメはアップしたままでないと動作しないのでリサイズしない
                    $path_parts = pathinfo(public_path($image_path)); // パス情報取得
                    $extension = mb_strtolower($path_parts['extension']); // 拡張子取得
                    
                    if ('gif' != $extension && 'mp4' != $extension) { // gifの場合はリサイズしない
                        $img = Image::make(public_path($image_path))->resize(null, 440, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        // 上書き保存
                        $img->save(public_path($image_path));
                    }

                    $image['data']->image_path = $image_path;
                    $image['data']->save();
                    // セッションにパスがある場合、ファイルとSessionも消す
                    $this->removeTmpFile($image['path']);
                }

            }
            \Session::forget('stetho_sound_images');
        }
        // 画像ファイルを削除する（削除の指定のあるなしは関数内にある）
        $this->removeStethoSoundImages($request);
        if (count($invalid_files)>0) {
            $lib_view=$this->getLibTypeView('edit_type_'.$request->input('lib_type'));//get lib view
            $url = \URL::route($lib_view, $id).'?page='.$request->input('page');
            return \Redirect::to($url)->withErrors($invalid_files)->withInput($request->input());
        }
        DB::commit();
        $lib_view=$this->getLibTypeView($request->input('lib_type'));//get lib view
        $url = \URL::route($lib_view).'?page='.$request->input('page');
        return \Redirect::to($url)->with('message', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $errors = [];

        $quiz_stetho_sound = QuizStethoSound::where('stetho_sound_id', $id)->get();
        $stetho_sound = StethoSound::where('id', $id)->first();
        $lib_view=$this->getLibTypeView((string)$stetho_sound->lib_type);//get lib view
        
        if ($quiz_stetho_sound->count()) {
            $errors = ['delete_error_quiz' => 'クイズがコンテンツを使用しているため、削除できませんでした。'];
        } else {
            StethoSound::where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect()->route($lib_view)->with('messages', ['コンテンツを削除しました']);
        }

        return redirect()->route($lib_view)->withErrors($errors);
    }

    /**
     * 監修コメントを追加する（API）.
     *
     * @param mixed $id
     */
    public function add_comment(Request $request, $id)
    {
        $stetho_sound = StethoSound::findOrFail($id);
        $comment = new Comment([
            'text' => $request->input('text'),
            'user_id' => \Auth::user()->id,
        ]);
        $stetho_sound->comments()->save($comment);

        return response()->json([
            'name' => $comment->user->name,
            'text' => $comment->text,
            'created_at' => $comment->created_at,
        ]);
    }

    /**
     * sound_path以外を保存する.
     *
     * @param StethoSound $stetho_sound
     * @param Request     $request
     *
     * @return StethoSound
     */
    private function assignRequestToStethoSound($stetho_sound, $request)
    {
        // $this->validate($request, [
        //     'description_en' => "required",
        // ]);
        $stetho_sound->user_id = $request->input('user_id');
        $stetho_sound->title = $request->input('title');
        $stetho_sound->title_en = $request->input('title_en');
        $stetho_sound->area = $request->input('area');
        $stetho_sound->area_en = $request->input('area_en');
        $stetho_sound->sound_path = '';
        $stetho_sound->image_path = '';
        $stetho_sound->video_path = '';
        $stetho_sound->disease = $request->input('disease');
        $stetho_sound->description =  $request->input('description');
        $stetho_sound->description_en = $request->input('description_en');
        $stetho_sound->sub_description = $request->input('sub_description');
        $stetho_sound->sub_description_en = $request->input('sub_description_en');
        $stetho_sound->disp_order = ($request->input('disp_order') != "") ? $request->input('disp_order') : null;
        $stetho_sound->status = $request->input('status');
        $stetho_sound->is_public = (2 == $request->input('status') || 3 == $request->input('status')) ? 1 : 0;
        $stetho_sound->is_video_show = $request->input('is_video_show');
        $stetho_sound->lib_type = $request->input('lib_type');
        
        $stetho_sound->image_description =  $request->input('img_description');
        $stetho_sound->image_description_en =  $request->input('img_description_en');
        // $stetho_sound->configuration =  $request->input('configuration');
        $stetho_sound->sort =  $request->input('sort');
        $stetho_sound->coordinate =  $request->input('coordinate');
        $stetho_sound->is_normal = $request->input('is_normal');

        //required fields in db
        if ($request->input('content_group')) {
            $stetho_sound->type = $request->input('content_group');
        }
        if ($request->input('type')) {
            $stetho_sound->type = $request->input('type');
        }
        if ($request->input('conversion_type')) {
            $stetho_sound->conversion_type = $request->input('conversion_type');
        }
        if ($request->input('disease_en')) {
            $stetho_sound->disease_en = $request->input('disease_en');
        }

        $configuration[] = array(
            "a"  => [
                "x" => $request->input("a_x"),
                "y" => $request->input("a_y"),
                "r" => $request->input("a_r")
            ],
            "p"  => [
                "x" => $request->input("p_x"),
                "y" => $request->input("p_y"),
                "r" => $request->input("p_r")
            ],
            "t"  => [
                "x" => $request->input("t_x"),
                "y" => $request->input("t_y"),
                "r" => $request->input("t_r")
            ],
            "m"  => [
                "x" => $request->input("m_x"),
                "y" => $request->input("m_y"),
                "r" => $request->input("m_r")
            ],
            // Tracheal
            "tr1"  => [
                "x" => $request->input("tr1_x"),
                "y" => $request->input("tr1_y"),
                "r" => $request->input("tr1_r")
            ],
            "tr2"  => [
                "x" => $request->input("tr2_x"),
                "y" => $request->input("tr2_y"),
                "r" => $request->input("tr2_r")
            ],
            // Bronchial
            "br1"  => [
                "x" => $request->input("br1_x"),
                "y" => $request->input("br1_y"),
                "r" => $request->input("br1_r")
            ],
            "br2"  => [
                "x" => $request->input("br2_x"),
                "y" => $request->input("br2_y"),
                "r" => $request->input("br2_r")
            ],
            "br3"  => [
                "x" => $request->input("br3_x"),
                "y" => $request->input("br3_y"),
                "r" => $request->input("br3_r")
            ],
            "br4"  => [
                "x" => $request->input("br4_x"),
                "y" => $request->input("br4_y"),
                "r" => $request->input("br4_r")
            ],
            "h1"  => [
                "x" => $request->input("h1_x"),
                "y" => $request->input("h1_y"),
                "r" => $request->input("h1_r")
            ],
            "h2"  => [
                "x" => $request->input("h2_x"),
                "y" => $request->input("h2_y"),
                "r" => $request->input("h2_r")
            ],
            "h3"  => [
                "x" => $request->input("h3_x"),
                "y" => $request->input("h3_y"),
                "r" => $request->input("h3_r")
            ],
            "h4"  => [
                "x" => $request->input("h4_x"),
                "y" => $request->input("h4_y"),
                "r" => $request->input("h4_r")
            ],
            "p1"  => [
                "x" => $request->input("p1_x"),
                "y" => $request->input("p1_y"),
                "r" => $request->input("p1_r")
            ],
            "p2"  => [
                "x" => $request->input("p2_x"),
                "y" => $request->input("p2_y"),
                "r" => $request->input("p2_r")
            ],
            "p3"  => [
                "x" => $request->input("p3_x"),
                "y" => $request->input("p3_y"),
                "r" => $request->input("p3_r")
            ],
            "p4"  => [
                "x" => $request->input("p4_x"),
                "y" => $request->input("p4_y"),
                "r" => $request->input("p4_r")
            ]
        );

        // Vesicular
        for($i=1; $i <= 12; $i++) {
            $configuration[0]["ve".$i] = [
                    "x" => $request->input("ve".$i."_x"),
                    "y" => $request->input("ve".$i."_y"),
                    "r" => $request->input("ve".$i."_r")
            ]; 
        }

        // dd($configuration[0]); exit;

        $stetho_sound->configuration = json_encode($configuration[0]);

        return $stetho_sound;
    }

    /**
     * サウンドファイルを移動する.
     *
     * @param StethoSound $stetho_sound
     * @param Request     $request
     *
     * @return string サウンドファイルへの相対URLパス（失敗時はnull）
     */
    private function moveSoundFile($stetho_sound, $request)
    {
        $filename = '';
        $base_dir = public_path().$this->base_url;
        $last_id = $stetho_sound->id;
        $success = false;

        // アップロードファイルがある場合、そのファイルを優先する。
        if ($request->hasFile('sound_file')) {
            $file = $request->file('sound_file');
            $ext = $file->getClientOriginalExtension();
            $filename = $last_id.'.'.$ext;
            $success = $file->move($base_dir, $filename);
        }
        // パスだけがある場合
        elseif ($request->has('sound_path') && \File::exists(public_path().$request->input('sound_path'))) {
            $src_filepath = public_path().$request->input('sound_path');
            $ext = \File::extension($src_filepath);
            $filename = $last_id.'.'.$ext;
            $success = \File::move($src_filepath, $base_dir.$filename);
        }
        if ($success) {
            return $this->base_url.$filename;
        }

        return null;
    }

    private function moveAPTMSound($stetho_sound, $request, $type)
    {
        $filename = '';
        $base_dir = public_path().$this->base_url;
        $last_id = $stetho_sound->id;
        $success = false;

        if ($request->hasFile($type.'_sound_file')) {
            $file = $request->file($type.'_sound_file');
            $ext = $file->getClientOriginalExtension();
            $filename = $type.'_'.$last_id.'.'.$ext;
            $success = $file->move($base_dir, $filename);
        } elseif ($request->has($type.'_sound_path') && \File::exists(public_path().$request->input($type.'_sound_path'))) {
            $src_filepath = public_path().$request->input($type.'_sound_path');
            $ext = \File::extension($src_filepath);
            $filename = $type.'_'.$last_id.'.'.$ext;
            $success = \File::move($src_filepath, $base_dir.$filename);
        }
        if ($success) {
            return $this->base_url.$filename;
        }

        return null;
    }
    /**
     * ビデオファイルを移動する.
     *
     * @param StethoSound $stetho_sound
     * @param Request     $request
     *
     * @return string ビデオファイルへの相対URLパス（失敗した場合はnull）
     */
    private function moveVideoFile($stetho_sound, $request)
    {
        $filename = '';
        $base_dir = public_path().$this->video_base_url;
        $last_id = $stetho_sound->id;
        $success = false;

        // アップロードファイルがある場合、そのファイルを優先する。
        if ($request->hasFile('lib_video_file')) {
            $file = $request->file('lib_video_file');
            $ext = $file->getClientOriginalExtension();
            $filename = $last_id.'.'.$ext;
            $success = $file->move($base_dir, $filename);
        }
        // パスだけがある場合
        elseif ($request->has('lib_video_file_path') && \File::exists(public_path().$request->input('lib_video_file_path'))) {
            $src_filepath = public_path().$request->input('lib_video_file_path');
            $ext = \File::extension($src_filepath);
            $filename = $last_id.'.'.$ext;
            $success = \File::move($src_filepath, $base_dir.$filename);
        }
        if ($success) {
            return $this->video_base_url.$filename;
        }

        return null;
    }

    private function moveStethoSoundImageFile($image)
    {
        $base_url = '/img/stetho_sound_images/';
        $base_dir = public_path().$base_url;

        $filename = '';
        $success = false;
        $last_id = $image['data']->id;

        if (!empty($image['file'])) {
            $file = $image['file'];
            $ext = $file->getClientOriginalExtension();
            $filename = $last_id.'.'.$ext;
            $success = $file->move($base_dir, $filename);
        } elseif (!empty($image['path']) && \File::exists(public_path().$image['path'])) {
            $src_filepath = public_path().$image['path'];
            $ext = \File::extension($src_filepath);
            $filename = $last_id.'.'.$ext;
            $success = \File::move($src_filepath, $base_dir.$filename);
        }
        if ($success) {
            return $base_url.$filename;
        }

        return null;
    }

    /**
     * 画像ファイルのリストを初期化する.
     *
     * @param int     $stetho_sound_id
     * @param Request $request
     *
     * @return array
     */
    private function initStethoSoundImages($stetho_sound_id, $request)
    {
        $files = $request->file('image_files');
        $paths = $request->input('image_paths');
        $ids = $request->input('image_ids');
        $titles = $request->input('image_titles');
        $langs = $request->input('lang');
        

        // image_filesとimage_pathsとimage_titlesは同じ個数で順番もセットで入力される
        $count = count($files);
        $images = []; // レコード、ファイル、パスをまとめる配列
        for ($i = 0; $i < $count; ++$i) {
            $file = $files[$i];
            $path = file_exists(public_path().$paths[$i]) ? $paths[$i] :null;
            $id = $ids[$i];
            $title = $titles[$i];
            $lang = $langs[$i];
            $data = StethoSoundImage::findOrNew($id);
            $data->stetho_sound_id = $stetho_sound_id;
            $data->title = $title;
            $data->lang = $lang;
            $data->image_path = '';
            $data->disp_order = $i;

            if($file || $path){
                $images[] = [
                    'data' => $data,
                    'file' => $file,
                    'path' => $path,
                ];
            }
        }

        $remove_ids = $request->input('remove_image_ids');
        if (empty($remove_ids)) {
            return $images;
        }
        // 削除処理
        foreach ($remove_ids as $index => $remove_id) {
            \Log::debug('$remove_id');
            \Log::debug($remove_id);
            $data = StethoSoundImage::findOrNew($remove_id);
            if (!empty($data)) {
                $data->delete();
            }
        }
        return $images;
    }

    /**
     * リクエストから削除する画像のID配列を受け取って削除します。
     *
     * @param Request $request
     */
    private function removeStethoSoundImages($request)
    {
        // 引数がなければ終了
        if (empty($request)) {
            return;
        }
        // 削除する画像のIDを取得
        $remove_ids = $request->input('remove_image_ids');
        // 削除する画像のIDがなければ終了
        if (empty($remove_ids)) {
            return;
        }
        // 画像を削除
        foreach ($remove_ids as $index => $remove_id) {
            $data = StethoSoundImage::find($remove_id);
            if (!empty($data)) {
                // 削除する（モデル内で画像ファイルも削除される）
                $data->delete();
            }
        }
    }

    public function reorderStethoSounds(Request $request)
    {
        $stethoSounds = $request->stetho_sounds;
        $result = null;
        foreach ($stethoSounds as $stethoSound) {
            $result = DB::table("stetho_sounds")
            ->where("id", $stethoSound['stetho_sound_id'])
            ->update([
              'disp_order' => $stethoSound['disp_order']
            ]);
        }
        return $result;
    }

    private function validateRequests($request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'lib_image_file' => 'required_without:lib_image_file_path',
            'lib_image_file_path' => 'max:255',
            // // Laravel5.1ではmp3のmimesバリデーションが意図しない動作をするため検証対象外とした
            // //'sound_file'      => 'required|mimes:mp3',
            // 'sound_file' => 'required_without:sound_path',
            'sound_path' => 'max:255',
            // 'lib_video_file' => 'required_without:lib_video_file_path',
            'lib_video_file_path' => 'max:255',
            'title' => 'required|max:10000',
            'title_en' => 'required|max:10000',
            'type' => 'required|in:1,2,3,9',
            'area' => 'required|max:10000',
            'area_en' => 'required|max:10000',
            'conversion_type' => 'required|in:0,1,2',
            'is_normal' => 'required|boolean',
            'disease' => 'required|max:255',
            'disease_en' => 'required|max:10000',
            'sub_description' => 'max:10000',
            'sub_description_en' => 'max:1000',
            // 'description' => 'required|max:20000',
            // 'description_en' => 'required|max:20000',
            'disp_order' => 'min:1|max:6|regex:/^[-0-9]{1,6}$/',
            'status' => 'required|in:0,1,2,3',
            //ecg
            'explanation' => 'required|max:20000',
            'explanation_en' => 'required|max:20000',
        ];

        $messages = [
            'user_id.required' => 'user id required。',
            'sound_file.required_without' => '音源ファイルは必須です。',
            // 'sound_file.mimes' => 'MP3 か MP4 ファイルのみアップロードできます。',
            'title.required'      => 'タイトル(JP)は必須です。',
            'title.max' => '文字数は10000文字以内で設定してください',
            'title_en.required'      => 'タイトル(EN)は必須です。',
            'title_en.max' => '文字数は10000文字以内で設定してください',
            'type.required' => '聴診音タイプが必要です。',
            'type.in' => '聴診音のタイプは、肺の音、心臓の音、腸の音などでなければなりません。',
            'area.required'    => '聴診部位(JP)は必須です。',
            'area.max' => '文字数は10000文字以内で設定してください',
            'area_en.required'    => '聴診部位(EN)は必須です。',
            'area_en.max' => '文字数は10000文字以内で設定してください',
            'conversion_type.required' => '加工方法が必要です。',
            'conversion_type.in' => '処理方法は、オリジナル、処理音、または人工音でなければなりません。',
            'disease.required'   => '代表疾患(JP)は必須です。',
            'disease.max' => '文字数は10000文字以内で設定してください',
            'disease_en.required'   => '代表疾患(EN)は必須です。',
            'disease_en.max' => '文字数は10000文字以内で設定してください',
            'description.required'   => '説明(JP)は必須です。',
            'description_en.required'   => '説明(EN)は必須です。',
            'description.max' => '説明は20000文字以下にする必要があります',
            'description_en.max' => '説明（EN）は20000文字を超えてはなりません',
            'sub_description.max' => '文字数は10000文字以内で設定してください',
            'sub_description_en.max' => '文字数は10000文字以内で設定してください',
            'status.required' => 'ステータスが必要です。',
            'lib_video_file.required_without' => '動画ファイルは必須です。',
            'lib_image_file.required_without' => '画像ファイルは必須です。',
        ];
        $selected_rules = array_intersect_key($rules, $request->all());
        $validator = \Validator::make($request->all(), $selected_rules, $messages);

        if ($request->hasFile('sound_file')) {
            $sound_file = $request->file('sound_file');
            $sound_ext = $sound_file->getClientOriginalExtension();

            $sound_check=in_array(strtolower($sound_ext), ['mp3','mp4']);
            if (!$sound_check) {
                $validator->errors()->add('sound_file', 'MP3 か MP4 ファイルのみアップロードできます。');
            }
        }

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image) {
                $file_image = $image;
                $file_extension = $file_image->getClientOriginalExtension();
                $check=in_array(strtolower($file_extension), ['jpeg','png','jpg','gif','svg','mp4','mpeg-4']);
                if (!$check) {
                    $validator->errors()->add('image_files', '画像は .jpg .gif .png のみアップロードできます。');
                }
            }
        }
        // dd($validator->errors()->all());
        if (count($validator->invalid()) > 0) {
            //set temp file on sessions
            $this->setTempFiles($request);
        }
        return $validator;
    }

    private function validateRequestsUpdate($request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'sound_path' => 'max:255',
            'title' => 'required|max:10000',
            'title_en' => 'required|max:10000',
            'type' => 'required|in:1,2,3,9',
            'area' => 'required|max:10000',
            'area_en' => 'required|max:10000',
            'conversion_type' => 'required|in:0,1,2',
            'is_normal' => 'required|boolean',
            'disease' => 'required|max:10000',
            'disease_en' => 'required|max:10000',
            'sub_description' => 'max:10000',
            'disp_order' => 'min:1|max:6|regex:/^[-0-9]{1,6}$/',
            'status' => 'required|in:0,1,2,3',
            //ecg
            'explanation' => 'required|max:20000',
            'explanation_en' => 'required|max:20000',
        ];

        $messages = [
            'user_id.required' => 'user id required。',
            'sound_file.required' => '聴診音ファイルは必須です。',
            'title.required'      => 'タイトル(JP)は必須です。',
            'title.max' => '文字数は10000文字以内で設定してください',
            'title_en.required'      => 'タイトル(EN)は必須です。',
            'title_en.max' => '文字数は10000文字以内で設定してください',
            'type.required' => '聴診音タイプが必要です。',
            'type.in' => '聴診音のタイプは、肺の音、心臓の音、腸の音などでなければなりません。',
            'area.required'    => '聴診部位(JP)は必須です。',
            'area_en.required'    => '聴診部位(EN)は必須です。',
            'area_en.max' => '文字数は10000文字以内で設定してください',
            'conversion_type.required' => '加工方法が必要です。',
            'conversion_type.in' => '処理方法は、オリジナル、処理音、または人工音でなければなりません。',
            'disease.required'   => '代表疾患(JP)は必須です。',
            'disease_en.required'   => '代表疾患(EN)は必須です',
            'disease_en.max' => '文字数は10000文字以内で設定してください',
            'description.max' => '説明は20000文字以下にする必要があります',
            'description_en.max' => '説明（EN）は20000文字を超えてはなりません',
            'sub_description.max' => '文字数は10000文字以内で設定してください',
            'status.required' => 'ステータスが必要です。',
            'status.in' => 'ステータスは「監視中」、「監視中」、「現在開いている」または「公開（新規）」である必要があります。',
            'lib_video_file.required' => 'ビデオファイルが必要です。',
            'lib_image_file.required' => '画像ファイルが必要です。',
        ];
        $selected_rules = array_intersect_key($rules, $request->all());
        $validator = \Validator::make($request->all(), $selected_rules, $messages);

        if ($request->hasFile('sound_file')) {
            $sound_file = $request->file('sound_file');
            $sound_ext = $sound_file->getClientOriginalExtension();

            $sound_check=in_array(strtolower($sound_ext), ['mp3','mp4']);
            if (!$sound_check) {
                $validator->errors()->add('sound_file', 'MP3 か MP4 ファイルのみアップロードできます。');
            }
        }

        // dd($validator->errors()->all());
        if (count($validator->invalid()) > 0) {
            //set temp file on sessions
            $this->setTempFiles($request);
        }
        return $validator;
    }

    /**
     * set session temp files when validator fails
     *
     * @return request Request
     */
    public function setTempFiles($request)
    {
        if ($request->hasFile('explanatory_image_file')) {
            $this->setSessionAndMoveSingleFile($request, 'explanatory_image_file', '/tmp/library_files/', 'explanatory_image_file_path',true);
        }
        if ($request->hasFile('explanatory_image_file_en')) {
            $this->setSessionAndMoveSingleFile($request, 'explanatory_image_file_en', '/tmp/library_files/', 'explanatory_image_file_en_path',true);
        }
        if ($request->hasFile('body_image_file')) {
            $this->setSessionAndMoveSingleFile($request, 'body_image_file', '/tmp/library_files/', 'body_image_file_path');
        }
        if ($request->hasFile('body_image_back_file')) {
            $this->setSessionAndMoveSingleFile($request, 'body_image_back_file', '/tmp/library_files/', 'body_image_back_file_path');
        }
        if ($request->hasFile('lib_image_file')) {
            $this->setSessionAndMoveSingleFile($request, 'lib_image_file', '/tmp/library_files/', 'lib_image_file_path');
        }
        if ($request->hasFile('lib_video_file')) {
            $this->setSessionAndMoveSingleFile($request, 'lib_video_file', '/tmp/library_files/', 'lib_video_file_path');
        }
        if ($request->hasFile('sound_file')) {
            $this->setSessionAndMoveSingleFile($request, 'sound_file', '/tmp/library_files/', 'sound_path');
        }
    }

    /**
     * get Lib id
     *
     * @param lib $lib
     * @return lib_type int
     */
    private function getLibType($lib)
    {
        switch ($lib) {
            case "admin/iPax":
                return 1;
            case "admin/palpation_library":
                return 2;
            case "admin/ecg_library":
                return 3;
            case "admin/inspection_library":
                return 4;
            case "admin/xray_library":
                return 5;
            case "admin/ucg_library":
                return 6;
            default:
                return 0;
        }
    }

    /**
     * get Lib Type View
     *
     * @param lib $lib
     * @return route lib type route
     */
    private function getLibTypeView($lib)
    {
        // dd($lib);
        switch ($lib) {
            case "admin/iPax":
            case 1:
                return 'admin.iPax.index';
            case "admin/palpation_library":
            case 2:
                return 'admin.palpation_library.index';
            case "admin/ecg_library":
            case 3:
                return 'admin.ecg_library.index';
            case "admin/inspection_library":
            case 4:
                return 'admin.inspection_library.index';
            case "admin/xray_library":
            case 5:
                return "admin.xray_library.index";
            case "admin/ucg_library":
            case 6:
                return "admin.ucg_library.index";
            //for create routes
            case "admin/ucg_library/create":
                return 'admin.ucg_library.create';
            case "admin/xray_library/create":
                return 'admin.xray_library.create';
            case "admin/iPax/create":
                return 'admin.iPax.create';
            case "admin/palpation_library/create":
                return 'admin.palpation_library.create';
            case "admin/ecg_library/create":
                return 'admin.ecg_library.create';
            case "admin/inspection_library/create":
                return 'admin.inspection_library.create';
            case "admin/stetho_sounds/create":
                return 'admin.stetho_sounds.create';
            //for edit routes
            case "edit_type_0":
                return 'admin.stetho_sounds.edit';
            case "edit_type_1":
                return 'admin.iPax.edit';
            case "edit_type_2":
                return 'admin.palpation_library.edit';
            case "edit_type_3":
                return 'admin.ecg_library.edit';
            case "edit_type_4":
                return 'admin.inspection_library.edit';
            case "edit_type_5":
                return 'admin.xray_library.edit';
            case "edit_type_6":
                return 'admin.ucg_library.edit';
            // for show routes
            case "admin/stetho_sounds/{stetho_sounds}":
                return "admin.stetho_sounds.show";
            case "admin/xray_library/{xray_library}":
                return "admin.xray_library.show";
            case "admin/ecg_library/{ecg_library}":
                return "admin.ecg_library.show";
            case "admin/ucg_library/{ucg_library}":
                return "admin.ucg_library.show";
            case "admin/palpation_library/{palpation_library}":
                return "admin.palpation_library.show";
            case "admin/inspection_library/{inspection_library}":
                return "admin.inspection_library.show";
            case "admin/iPax/{iPax}":
                return "admin.iPax.show";
            default:
                return 'admin.stetho_sounds.index';
        }
    }
    /**
     * Transfer old university id's to new pivot table
     */
    public function transferOldExamGroups()
    {
        foreach (StethoSound::get() as $key => $value) {
            $stetho_sound = StethoSound::findOrFail($value['id']);
            if ($value['university_id'] && $value['id']) {
                $stetho_sound->exam_groups()->attach($stetho_sound->id, ['exam_group_id'=>$value['university_id']]);
            }
        }
        return redirect()->back();
    }
}
