<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hosh;
use App\User;
use Validator;

class UserController extends Controller
{
    public function index()
    {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' index ');

    $users = User::all();
    return ['users' => $users];
    }

    public function store(Request $request)
    {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' store ');

    //入力項目のチェック
    $data = $this->validator($request->all());

    //ユーザ情報DB保存
    return $this->storeUser($data);
    }

    public function storeUser(array $data)
    {
        // ユーザテーブルから該当者データを取得
        $user = User::where('loginid', $data['loginid'])->first();

        // 該当データあり、更新要求ならデータ更新
        if ($user) {
            if ($data['type'] =='U') {
                $user->ifll($data)->save();
            }

            // 同会社データあり、更新要求以外ならエラー
            else {
                return response()->json(['message' => 'User Exists'], 423);
            }
        }
        // 該当者なし、新規要求ならデータ新規作成
        else {
            if ($data['type'] == 'C') {

                // パスワードが指定されていなければ、loginid を初期値として設定
                if (! array_key_exists('password', $data)) {
                    $data['password'] = Hash::make($data['login']);
                }
                $user = User::create($data);
            }

            // 該当車データなし、新規要求以外ならエラー
            else {
                return response()->json(['message' => 'User Not Found'], 422);
            }
        }
        // RETURN
        return ['data' => $user];
    }
}
