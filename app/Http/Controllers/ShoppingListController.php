<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Shopping_listRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopping_list as ShoppingListModel;
use Illuminate\Support\Facades\DB;
use App\Models\Completed_Shopping_List as CompletedShoppingListModel;

class ShoppingListController extends Controller
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
        $list = ShoppingListModel::where('user_id', Auth::id())
                                ->orderBy('name')
                                ->paginate($per_page);
                                // ->get();

        return view('shopping_list.list', ['list' => $list]);
    }

    /**
     * 「買うもの」の新規登録
     */
    public function register(Shopping_listRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        //
        // user_id の追加
        $datum['user_id'] = Auth::id();

        // テーブルへのINSERT
        try {
            $r = ShoppingListModel::create($datum);
        } catch(\Throwable $e) {
            echo $e->getMessage();
            exit;
        }

        // タスク登録成功
        $request->session()->flash('front.shopping_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');
    }

    /**
     * 削除処理
     */
    public function delete(Request $request, $shopping_list_id)
    {
        // shopping_list_idのレコードを取得する
        $shopping_list = $this->getShoppingListModel($shopping_list_id);

        // タスクを削除する
        if ($shopping_list !== null) {
            $shopping_list->delete();
            $request->session()->flash('front.shoppng_delete_success', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }

    /**
     * 「買うもの」の完了
     */
    public function complete(Request $request, $shopping_list_id)
    {
        /* 「買うもの」を完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // shopping_list_idのレコードを取得する
            $shopping_list = $this->getShoppingListModel($shopping_list_id);
            if ($shopping_list === null) {
                // shopping_list_idが不正なのでトランザクション終了
                throw new \Exception('');
            }
            // shopping_lists側を削除する
            $shopping_list->delete();

            // completed_shopping_lists側にinsertする
            $dask_datum = $shopping_list->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = CompletedShoppingListModel::create($dask_datum);
            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.shopping_completed_success', true);
        } catch(\Throwable $e) {
            // var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
            // 完了失敗メッセージ出力
            $request->session()->flash('front.shopping_completed_failure', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }

    /**
     * 「単一の「買うもの」」Modelの取得
     */
    protected function getShoppingListModel($shopping_list_id)
    {
        // shopping_list_idのレコードを取得する
        $shopping_list = ShoppingListModel::find($shopping_list_id);
        if ($shopping_list === null) {
            return null;
        }
        // 本人以外のタスクならNGとする
        if ($shopping_list->user_id !== Auth::id()) {
            return null;
        }

        return $shopping_list;
    }
}
