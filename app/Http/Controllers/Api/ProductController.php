<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Jobs\NotifyUserOfCompletedImport;

class ProductController extends Controller
{
    public function import(Request $request) 
    {
        Excel::queueImport(new ProductsImport, $request->file('product_file'))->chain([
            new NotifyUserOfCompletedImport(request()->user()),
        ]);
        
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }
    
}
