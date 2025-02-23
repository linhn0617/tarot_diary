<?php

namespace Database\Seeders;

use App\Models\Tarot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarotCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // 關閉外鍵檢查
        Tarot::truncate();   // 清空 tarots 資料表
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // 重新開啟外鍵檢查

        // 打開 CSV 檔案
        $tarotCsvFile = fopen(database_path('data/table_tarots.csv'), 'r');
        $firstline = true;

        // 讀取每一行資料
        while (($data = fgetcsv($tarotCsvFile)) !== false) {
            if (! $firstline) {
                // 插入資料到 tarots 資料表
                Tarot::create([
                    'name' => $data[0],  // 第一欄：塔羅牌名稱
                    'element' => $data[1],  // 第二欄：元素
                    'image_path' => $data[2],  // 第三欄：圖片路徑
                ]);
            }

            $firstline = false;
        }

        // 關閉 CSV 檔案
        fclose($tarotCsvFile);
    }
}
