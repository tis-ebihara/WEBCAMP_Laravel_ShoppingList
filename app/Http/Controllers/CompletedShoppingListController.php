<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Completed_Shopping_List as CompletedShoppingListModel;

class CompletedShoppingListController extends Controller
{
    /**
     * タスク一覧ページ を表示する
     *
     */
    public function list()
    {
         // 1Page辺りの表示アイテム数を設定
        $per_page = 2;

        //  一覧の取得
        $list = CompletedShoppingListModel::where('user_id', Auth::id())
                                ->orderBy('name')
                                ->orderBy('created_at')
                                ->paginate($per_page);
                                // ->get();

        return view('shopping_list.completed_shopping_list', ['list' => $list]);
    }
    
    
}
