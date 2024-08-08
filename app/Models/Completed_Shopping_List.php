<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completed_Shopping_List extends Model
{
    use HasFactory;

    // model作成時、誤ったモデル名で作成してしまった。
    // migrate実施前に、migrationファイル等(他にもテーブル名を記載するファイル)でテーブル名を修正したが、
    // CompletedShoppingListModelで誤った名称のテーブルを探しているエラーが出てくる
    // その為、
    // 正しいテーブル名を設定
    protected $table = 'completed_shopping_lists';

    /**
     * 複数代入不可能な属性
     */
    protected $guarded = [];
}
