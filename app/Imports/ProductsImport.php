<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToCollection, WithChunkReading, ShouldQueue

{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            Product::create([
                'name' => $row[0],
                'detail' => $row[1], 
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
