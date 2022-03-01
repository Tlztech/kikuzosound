<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StethoSound;
use App\Comment;

/**
 * 監修コメントクラスコントローラ
 *
 */
class CommentController extends Controller {

    /**
     * 監修コメント一覧取得API
     * [GET] /admin/stetho_sounds/1/comments
     *
     * @param int $stethoSoundId 聴診音ライブラリID
     * @return Response コメント一覧JSON
     */
    public function index($stethoSoundId) {
        $comments = null;
        $stetho_sound = $this->currentStethoSound($stethoSoundId);
        if ( is_null($stetho_sound) ) {
            return response()->json(['error' => 'Not found'], 404);
        }
        else {
            $comments = $stetho_sound->comments()->get();
        }
        
        if ( is_null($comments) ) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        else {
            return response()->json($comments);
        }
    }

    /**
     * 監修コメント一覧追加API
     * [POST] /admin/stetho_sounds/1/comments
     *
     * @param Request コメントJSON
     * @param int $stethoSoundId 聴診音ライブラリID
     * @return Response コメントJSON
     */
    public function store(Request $request, $stethoSoundId) {
        $stetho_sound = $this->currentStethoSound($stethoSoundId);
        if ( is_null($stetho_sound) ) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $comment = new Comment([
            'text' => $request->input('text'),
            'user_id' => \Auth::user()->id
        ]);
        $stetho_sound->comments()->save($comment);

        return response()->json([
            'id'         => $comment->id,
            'name'       => $comment->user->name,
            'text'       => $comment->text,
            'created_at' => $comment->created_at
        ]);
    }

    /**
     * 監修コメント一覧更新API
     * [PUT] /admin/stetho_sounds/1/comments/1
     *
     * @param Request コメントJSON
     * @param int $stethoSoundId 聴診音ライブラリID
     * @param int $commentId 監修コメントID
     * @return Response コメントJSON
     */
    public function update(Request $request, $stethoSoundId, $commentId) {
        $stetho_sound = $this->currentStethoSound($stethoSoundId);
        if ( is_null($stetho_sound) ) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $comment = $stetho_sound->comments()->find($commentId);
        $comment->text = $request->input('text');
        $comment->save();

        return response()->json([
            'id'         => $comment->id,
            'name'       => $comment->user->name,
            'text'       => $comment->text,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at
        ]);
    }

    /**
     * 監修コメント一覧削除API
     * [DELETE] /admin/stetho_sounds/1/comments/1
     *
     * @param int $stethoSoundId 聴診音ライブラリID
     * @param int $commentId 監修コメントID
     * @return Response コメントJSON
     */
    public function destroy($stethoSoundId, $commentId) {
        $stetho_sound = $this->currentStethoSound($stethoSoundId);
        if ( is_null($stetho_sound) ) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $stetho_sound->comments()->findOrFail($commentId)->delete();
        return response()->json([
            'id'       => $commentId
        ]);
    }

    /**
     * 聴診音を取得する
     *
     * @param $stethoSoundId
     * @return StethoSound
     */
    private function currentStethoSound($stethoSoundId) {
        $stetho_sound = null;
        if ( \Auth::user()->isAdmin() ) {
            $stetho_sound = StethoSound::find($stethoSoundId);
        }
        else if ( \Auth::user()->isSuperintendent() ) {
            $stetho_sound = StethoSound::where([
                'id' => $stethoSoundId,
                'user_id' => \Auth::user()->id
            ])->first();
        }
        
        return $stetho_sound;
    }
}
