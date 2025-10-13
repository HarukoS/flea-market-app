<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'category_name' => 'ファッション',
            'category_name_en' => 'fashion',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '家電',
            'category_name_en' => 'appliances',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'インテリア',
            'category_name_en' => 'interior',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'レディース',
            'category_name_en' => 'ladies',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'メンズ',
            'category_name_en' => 'mens',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'コスメ',
            'category_name_en' => 'cosmetic',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => '本',
            'category_name_en' => 'book',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'ゲーム',
            'category_name_en' => 'game',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'スポーツ',
            'category_name_en' => 'sports',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'キッチン',
            'category_name_en' => 'kitchen',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'ハンドメイド',
            'category_name_en' => 'handmade',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'アクセサリー',
            'category_name_en' => 'accessory',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'おもちゃ',
            'category_name_en' => 'toy',
        ];
        DB::table('categories')->insert($param);

        $param = [
            'category_name' => 'ベビー・キッズ',
            'category_name_en' => 'baby&kids',
        ];
        DB::table('categories')->insert($param);
    }
}
