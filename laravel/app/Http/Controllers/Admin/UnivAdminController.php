<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UnivAdminRequest;
use App\Http\Controllers\Controller;
use App\ExamGroup;
use App\User;

class UnivAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::universityadmin()->with("exam_group")->paginate(10);
        return view('admin.users.university.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exam_groups= ExamGroup::all();
        return view('admin.users.university.create', compact('exam_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnivAdminRequest $request)
    {
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = bcrypt($request->input("password"));
        $user->enabled = $request->input("enabled");
        $user->university_id = $request->input("university_id");
        $user->role = User::$ROLE_UNIVERSITY_ADMIN; // 第1フェーズは監修者として固定値で登録する。

        $user->save();

        return redirect()->route('admin.users.university.index')->with('message', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam_groups= ExamGroup::all();
        $user = User::universityadmin()->with("exam_group")->findOrFail($id);

        return view('admin.users.university.edit', compact('user', 'exam_groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:10000',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:8',
            'university_id'=>'required'
        ], [
            'name.required' => '氏名は必須です。',
            'email.email' => "メールは有効なメールアドレスである必要があります。",
            'email.required' => "メールアドレスは必須です。",
            'email.unique' => "このメールは既に使用されています。",
            'password.required' => "パスワードは必須です。",
            'university.required' => "大学のフィールドは必須です。",
            'password.min' => "パスワードは8文字以上にする必要があります。",
        ]);

        $user = User::universityadmin()->findOrFail($id);

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        // パスワードが空でない場合は変更する
        if ($request->input("password") != $user->password) {
            $user->password = bcrypt($request->input("password"));
        }
        $user->enabled = $request->input("enabled");
        $user->university_id = $request->input("university_id");
        $user->role = User::$ROLE_UNIVERSITY_ADMIN; // 第1フェーズは監修者として固定値で登録する。
        $user->save();

        return redirect()->route('admin.users.university.index')->with('message', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::universityadmin()->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.university.index')->with('message', 'Item deleted successfully.');
    }
}
