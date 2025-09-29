<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferDocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            [
                'transfer_id' => 1,
                'conditions' => "・譲渡費用：30000円\n・ワクチン接種済み\n・飼育環境を適切に整えること\n・定期的に写真で報告",
                'contract' => "譲渡契約書フォーマット：\n譲渡者：user3\n譲受者：user1\n譲渡日：" . now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'transfer_id' => 2,
                'conditions' => "・譲渡費用：10000円\n・健康診断済み\n・飼育環境を適切に整えること",
                'contract' => "譲渡契約書フォーマット：\n譲渡者：user4\n譲受者：user2\n譲渡日：" . now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'transfer_id' => 3,
                'conditions' => "・譲渡費用：0円（無料譲渡）\n・飼育経験ありの方に限る\n・定期的に写真で報告",
                'contract' => "譲渡契約書フォーマット：\n譲渡者：user3\n譲受者：user2\n譲渡日：" . now()->subDays(1)->format('Y-m-d'),
            ],
        ];

        foreach ($documents as $document) {
            $document['created_at'] = now();
            $document['updated_at'] = now();
            DB::table('transfer_documents')->insert($document);
        }
    }
}
