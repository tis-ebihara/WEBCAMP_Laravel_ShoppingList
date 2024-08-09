<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;

class UserController extends Controller
{
    /**
     * ユーザ一覧画面を表示する
     */
    public function list()
    {
        // データの取得
        $group_by_column = ['users.id', 'users.name'];
        $list = UserModel::select($group_by_column)
                        ->selectRaw('count(completed_shopping_lists.id) as completed_shopping_lists_num')
                        ->leftJoin('completed_shopping_lists', 'users.id', '=', 'completed_shopping_lists.user_id')
                        ->groupBy($group_by_column)
                        ->orderBy('users.id')
                        ->get();

        // echo "<pre>\n";
        // var_dump($list->toArray()); exit;

        return view('admin.user', ['users' => $list]);
    }

}
