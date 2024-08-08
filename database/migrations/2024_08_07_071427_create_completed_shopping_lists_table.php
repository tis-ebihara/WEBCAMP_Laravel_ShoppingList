<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('completed_shopping_lists', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name', 255)->comment('「買うもの」名');
            $table->unsignedBigInteger('user_id')->comment('この「買うもの」の所有者');
            $table->foreign('user_id')->references('id')->on('users'); // 外部キー制約
            // $table->timestamps();
            $table->dateTime('created_at')->useCurrent()->comment('この「買うもの」完了日時');
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            //
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed__shopping__lists');
    }
};
