<?php

namespace App\Http\Controllers;

use App\Models\Profiles;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function storeupdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'age' => 'required|integer',
            'biodata' => 'required',
            'address' => 'required',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'integer' => 'inputan :attribute harus berupa angka',
        ]);


        $profile = Profiles::updateOrCreate(
        ['user_id' => $user->id],
        [
            'age' => $request->input('age'),
            'biodata' => $request->input('biodata'),
            'address' => $request->input('address'),

        ]);

        return response([
            "message" => "Profile berhasil diubah",
            "data" => $profile
        ],201);
    }
}