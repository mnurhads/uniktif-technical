<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\Hobby;

class MemberController extends Controller
{
    public function createMember(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama'  => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:members',
            'phone' => 'required|numeric|min:10',
            'hobby.*' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $hobbys = $request->hobby;
        // print_r($hobbys); exit;

        $member = Member::create(array_merge([
            'nama'  => $request->nama,
            'email' => $request->email,
            'phone' => $request->phone
        ]));

        foreach($hobbys as $hobby => $no) {
            $input['member_id']  = Member::latest()->value('id');
            $input['hobby']      = $hobbys[$hobby];
            Hobby::create($input);
        }

        return response()->json([
            'responseCode' => 200,
            'responseMsg' => 'Berhasil Daftar Member'
        ], 200);
    }

    public function showMember(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $member = Member::with(['hobbys' => function ($query) {
                        $query->select('id', 'member_id', 'hobby');
                    }])
                  ->select('id', 'nama', 'email', 'phone')
                  ->where('email', $request->email)
                  ->first();

        return response()->json([
            'responseCode' => 200,
            'responseMsg'  => 'Detail member',
            'member'       => $member
        ], 200);
    }

    public function updateMember(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama'  => 'required|string|max:50',
            'email' => 'required|string|email|max:50',
            'phone' => 'required|numeric|min:10',
            'hobby.*' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $member = Member::where('email', $request->email)->update(array_merge([
            'nama'  => $request->nama,
            'email' => $request->email,
            'phone' => $request->phone
        ]));

        for ($i=0; $i<count($request->hobby); $i++) {
            $test = $request->hobby[$i];
            
            Hobby::where('id',$test['id'])
                ->update([
                    'hobby' => $test['hobby'],
            ]);
    
        } 

        return response()->json([
            'responseCode' => 200,
            'responseMsg' => 'Berhasil Update Data Member'
        ], 200);
    }
}
