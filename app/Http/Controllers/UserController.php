<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterPost;
use Illuminate\Support\Facades\Hash;
use App\Models\User as UserModel;

class UserController extends Controller
{
    /**
     * 登録画面を表示する
     */
    public function index()
    {
        return view('user.register');
    }

    /**
     * 会員登録機能
     */
    public function register(UserRegisterPost $request)
    {
        // validate済のデータの取得
        $datum = $request->validated();

        // パスワードのハッシュ化
        $datum['password'] = Hash::make($datum['password']);

        // テーブルへのINSERT
        try {
            $r = UserModel::create($datum);
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }

        // タスク登録成功
        $request->session()->flash('front.user_register_success', true);

        // リダイレクト
        return redirect('/');
    }
}
