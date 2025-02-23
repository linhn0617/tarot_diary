<?php

namespace Database\Seeders;

use App\Models\TarotSpecification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarotSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // 關閉外鍵檢查
        TarotSpecification::truncate();            // 清空 tarots 資料表
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // 重新開啟外鍵檢查

        // 打開 CSV 檔案
        $tarotSpecificationCsvFile = fopen(database_path('data/table_tarotsSpecification.csv'), 'r');
        $firstline = true;

        // 讀取每一行資料
        while (($data = fgetcsv($tarotSpecificationCsvFile)) !== false) {
            if (! $firstline) {
                // 插入資料到 tarots 資料表
                TarotSpecification::create([
                    'tarot_id' => $data[0],  // 第一欄：塔羅牌id
                    'is_upright' => $data[1],  // 第二欄：正逆位(1為正位，0為逆位)
                    'message1' => $data[2],  // 第三欄：關心小語1
                    'message2' => $data[3],  // 第四欄：關心小語2
                ]);
            }

            $firstline = false;
        }

        // 關閉 CSV 檔案
        fclose($tarotSpecificationCsvFile);
    }
}
