<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_idr = [
            // ["1", "110001", "Kas", "1", "0"],
            // ["2", "110009", "Prive", "1", "0"],
            // ["2", "110002", "Rekening Bank", "1", "0"],
            // ["2", "110003", "Giro", "1", "0"],
            // ["3", "110100", "Piutang Usaha", "1", "0"],
            // ["4", "110101", "Piutang Usaha Belum Ditagih", "1", "0"],
            // ["5", "110200", "Persediaan Barang", "1", "0"],
            // ["6", "110402", "Biaya Bayar Di Muka", "1", "0"],
            // ["7", "110500", "PPN Masukan", "1", "0"],
            // ["8", "110305", "Aset Tetap Perlengkapan Kantor", "1", "0"],
            // ["9", "220100", "Hutang Usaha", "1", "0"],
            // ["10", "220101", "Hutang Belum Ditagih", "1", "0"],
            // ["11", "220203", "Pendapatan Diterima Di Muka", "1", "0"],
            // ["12", "220500", "PPN Keluaran", "1", "0"],
            // ["13", "300001", "Ekuitas Saldo Awal", "1", "0"],
            // ["14", "440000", "Pendapatan Jasa", "1", "0"],
            // ["15", "440100", "Diskon Penjualan", "1", "0"],
            // ["16", "440200", "Retur Penjualan", "1", "0"],
            // ["14", "440010", "Pendapatan Lain", "1", "0"],
            // ["17", "550000", "Beban Pokok Pendapatan", "1", "0"],
            // ["18", "550100", "Diskon Pembelian", "1", "0"],
            // ["19", "550500", "Biaya Produksi", "1", "0"],
            // ["19", "550501", "Uang Muka kepada Vendor", "1", "0"],
            // ["20", "660216", "Pengeluaran Barang Rusak", "1", "0"],
            // ["21", "770099", "Pengeluaran Lain", "1", "0"]
        ];

        $array_sgd = [];
        foreach ($array_idr as $item) {
            $item[3] = "2";
            $array_sgd[] = $item;
        }

        $array_usd = [];
        foreach ($array_idr as $item) {
            $item[3] = "3";
            $array_usd[] = $item;
        }

        $array_full = array_merge($array_idr, $array_sgd, $array_usd);

        $length = count($array_full);
        for ($i=0; $i < $length; $i++) { 
            DB::table('master_account')->insert([
                'account_type_id' => $array_full[$i][0],
                'code' => $array_full[$i][1],
                'account_name' => $array_full[$i][2],
                'master_currency_id' => $array_full[$i][3],
                'can_delete' => $array_full[$i][4],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
