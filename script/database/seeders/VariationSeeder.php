<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;
use App\Term;
use App\Stock;

class VariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = Term::with(['stocks', 'stock', 'prices'])->orderBy('id')->get();

        foreach ($terms as $term) {
            if ($term->stocks->isEmpty()) {
                if (!$term->stock->sku) {
                    $term->stock->sku = '00'.$term->id;
                    $term->stock->save();
                }
            } else {
                $stockSku = $term->stock->sku;
                $stocks = $term->stocks;
                $stocksCount = $stocks->count();
                if (!$term->stock->sku) {
                    $term->stock->sku = $term->stock->sku?:'00'.$term->id;
                }
                foreach ($stocks as $index => $stock) {
                    $stockSkuPart = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    $stock->sku = $stock->sku ?:  $term->stock->sku . '-' . $stockSkuPart;
                    $stock->save();
                }
                $term->stock->save();
            }
        }
        $stocks = Stock::all();
        foreach($stocks as $stock)
        {
            $updateSku = Price::where('term_id', $stock->term_id)->where('variation_id_code', json_encode($stock->variation_id_code))->update(['sku'=>$stock->sku]);
            $updateSku1 = Price::where('term_id', $stock->term_id)->where('variation_id_code', $stock->variation_id_code)->update(['sku'=>$stock->sku]);
        }
    }
}
