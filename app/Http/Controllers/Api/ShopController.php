<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index()
    {

        $data = DB::table('shops')
        ->Join('divisions', 'divisions.id', 'shops.division_id')
        ->select('shops.id as shop_id',
                'shops.name as shop_name',
                'shops.address as shop_address',
                'divisions.id as division_id',
                'divisions.name as division_name')
        // ->select('shops.id', DB::raw('COUNT(*) as totalEmployee'))
        // ->groupBy('shops.id')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'division_id' => 'required',
            'address' => 'required',
        ]);

        try{
            $user = Shop::create([
                'name' => $request->name,
                'address' => $request->address,
                'division_id' => $request->division_id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Created Succesfull!'
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Created Failed!',
            ], 400);
        }
    }
}
