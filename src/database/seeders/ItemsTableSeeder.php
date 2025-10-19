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
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'condition_id' => '3',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => '革靴',
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition_id' => '4',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'item_name' => 'ノートPC',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'マイク',
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'ショルダーバッグ',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'condition_id' => '3',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'タンブラー',
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'condition_id' => '4',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'condition_id' => '1',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '2',
            'item_name' => 'メイクセット',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition_id' => '2',
        ];
        DB::table('items')->insert($param);
    }
}
