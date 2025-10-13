<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'item_name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
            'item_image' => 'item_image/ItemId1_fashion_mens.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
            'item_image' => 'item_image/ItemId2_appliances.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
            'item_image' => 'item_image/ItemId3_kitchen.jpg',
            'condition_id' => '3',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => '革靴',
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
            'item_image' => 'item_image/ItemId4_fashion_mens.jpg',
            'condition_id' => '4',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'ノートPC',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'item_image' => 'item_image/ItemId5_appliances.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'マイク',
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'item_image' => 'item_image/ItemId6_appliances.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'ショルダーバッグ',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'item_image' => 'item_image/ItemId7_fashion_ladies.jpg',
            'condition_id' => '3',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'タンブラー',
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'item_image' => 'item_image/ItemId8_kitchen.jpg',
            'condition_id' => '4',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'item_image' => 'item_image/ItemId9_kitchen.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'メイクセット',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'item_image' => 'item_image/ItemId10_ladies_cosmetic.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);
    }
}
