<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;

class UserController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $users = User::superintendents()->paginate(10);
    return view('admin.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return view('admin.users.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param UserRequest $request
   * @return Response
   */
  public function store(UserRequest $request)
  {
    $user = new User();

    $user->name = $request->input("name");
    $user->email = $request->input("email");
    $user->password = bcrypt($request->input("password"));
    $user->enabled = $request->input("enabled");
    $user->role = User::$ROLE_SUPERINTENDENT; // 第1フェーズは監修者として固定値で登録する。

    $user->save();

    return redirect()->route('admin.users.index')->with('message', 'Item created successfully.');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @param UserRequest $request
   * @return Response
   */
  public function update(UserRequest $request, $id)
  {
    // 排他制御処理
    $user = User::findOrFail($id);
    $is_force_update = $request->input('is_force_update',false);
    if (!$is_force_update) {
      $client_up_at = Carbon::parse($request->input('updated_at'));
      $sever_up_at  = $user->updated_at;
      if ($client_up_at->lt($sever_up_at)) {
        return redirect()
          ->route('admin.users.edit', $id)
          ->withInput()
          ->withErrors(['is_force_update'=>'編集中に他のユーザが更新しました。強制的に変更を行う場合は『強制更新』にチェックを入れ、保存ボタンを押してください。']);
      }
    }

    $user->name = $request->input("name");
    $user->email = $request->input("email");
    // パスワードが空でない場合は変更する
    if ( $request->input("password") != $user->password ) {
      $user->password = bcrypt($request->input("password"));
    }
    $user->enabled = $request->input("enabled");
    $user->role = User::$ROLE_SUPERINTENDENT; // 第1フェーズは監修者として固定値で登録する。
    $user->save();

    return redirect()->route('admin.users.index')->with('message', 'Item updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.users.index')->with('message', 'Item deleted successfully.');
  }

}
