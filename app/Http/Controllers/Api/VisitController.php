<?php

namespace App\Http\Controllers\Api;


use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{



    public function index()
    {
        return 'uo';
    }




    public function store(Request $request)
    {

        // return $request->all();

        $validator = Validator::make($request->all(), [
            'shop_id' => 'required',
        ]);

        if ($validator->fails()) {
             return response()->json([
                'success' => false,
                'message' => $validator->messages()->first(),
            ], 400);
        }

        if ($request->hasFile('image'))
        {
            $image = '';
            try {
                $image = $request->file('image')->storeAs(
                    'public/visits', Str::random(20).'.'.$request->file('image')->getClientOriginalExtension()
                );
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Visit image Failed!',
                ], 400);
            }
            $image = explode("public/",$image);
            try {
                $visits = Visit::create([
                    'user_id'=> auth()->id(),
                    'shop_id'=> $request->shop_id,
                    'remarks'=> $request->remarks,
                    'image'=> $image[1]
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Visit Store Succesfully!',
                    'data' => $visits
                ], 200);
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Visit Store Failed!',
                ], 400);
            }

        }

            try {
                $visits = Visit::create([
                    'user_id'=> auth()->user()->id(),
                    'shop_id'=> $request->shop_id,
                    'remarks'=> $request->remarks,
                    'image'=> NULL
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Visit Store Succesfully!',
                    'data' => $visits
                ], 200);
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'User Registration Failed!', error($th->getMessage()),
                ], 400);
            }
        // }
    }
}