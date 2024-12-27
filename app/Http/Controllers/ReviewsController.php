<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function storeupdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'critic' => 'required',
            'rating' => 'required|integer|between:1,5',
            'movie_id' => 'required|exists:movies,id',
            
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'exist' => 'inputan :attribute tidak ditemukan di table reviews', 
            'integer' => 'inputan antara angka 1 - 5', 
            'between' => 'inputan antara angka 1 - 5', 
        ]);
        


        $reviews = Reviews::updateOrCreate(
        ['user_id' => $user->id],
        [
            'critic' => $request->input('critic'),
            'rating' => $request->input('rating'),
            'movie_id' =>  $request->input('movie_id'),

        ]);

        return response([
            "message" => "Reviews berhsil dibuat/diupdate",
            "data" => $reviews
        ],201);
    }
}