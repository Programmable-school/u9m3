<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\User;
use Validator;
use App\Facades\Csv;
use App\Http\Requests\UploadCsvFile;

class UserController extends Controller
{
        $users = User::all();
        return ['users' => $users];
    }


    public function download(Request $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());



  //public function upload(Request $request)
    public function upload(UploadCsvFile $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

        // 拡張子チェックがうまく動かないことがあるので独自で実施
        // -- https://api.symfony.com/3.0/Symfony/Component/HttpFoundation/File/UploadedFile.html
        $file = $request -> file('csvfile');
        if ($file ->getClientOriginalExtension() != 'csv') {
            Log::Debug(__CLASS__.':'.__FUNCTION__.' File Name: '. $file ->getClientOriginalName());
            Log::Debug(__CLASS__.':'.__FUNCTION__.' File Extension: '. $file ->getClientOriginalExtension());
            Log::Debug(__CLASS__.':'.__FUNCTION__.' ClientMimeType: '. $file ->getClientMimeType());
            Log::Debug(__CLASS__.':'.__FUNCTION__.' MimeType: '. $file ->getMimeType());
            return new JsonResponse(['errors' => [ 'csvfile' => 'CSVファイルを指定してください']], 422);
        }

        // CSV をパース
        try {
            $rows = Csv::parse($file);
        } catch (\Exception $e) {
            Log::Debug(__CLASS__.':'.__FUNCTION__.' parse Exception : '. $e -> getMessage());
            return new JsonResponse(['errors' => [
                'csvfile' => 'CSVファイルの読み込みでエラーが発生しました',
                'exception' => $e -> getMessage()
                ]]
            , 422);
        }




    public function store(Request $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

        // 入力項目チェック（必須やら文字数やら）
        $validator = $this->validator($request->all());

        // チェックエラーがあったら処理を返す
        if ($validator -> fails()) {
            $validator -> validate();
        }

        // チェック済みデータを取得
        $data = $validator -> valid();

        // パスワードが設定されていたらハッシュ化
        if (array_key_exists('pass', $data)  && $data['pass'] != '') {
            $data['password'] = Hash::make($data['pass']);
        }

        // ユーザ情報ＤＢ保存
        return $this->storeUser($data);
    }


    public function destroy(Request $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

        // 入力項目チェック（必須やら文字数やら）
        $data = $request->all();

        // loginID 指定あり？
        if (trim($data['loginid'] == '')) {
            return response()->json(['message' => 'loginID Not Found' ], 422);
        }

        // ユーザテーブルから該当者データを取得
        $user = User::where('loginid', $data['loginid'])->first();

        // 該当者データなし -> エラー
        if (! $user) {
            return response()->json(['message' => 'User Not Found' ], 422);
        }


    }


    private function storeUser(array $data)
    {
        // ユーザテーブルから該当者データを取得
        $user = User::where('loginid', $data['loginid'])->first();

        // 該当者データあり、更新要求ならデータ更新
        if ($user) {
            if ($data['type'] == 'U') {
                $user->fill($data)->save();
            }

            // 該当者データあり、更新要求以外ならエラーj
            else {
                return response()->json(['message' => 'User Exists'], 423);
            }
        }

        // 該当者データなし、新規要求ならデータ新規作成
        else {
            if ($data['type'] == 'C') {

                // パスワードが指定されていなければ、loginid を初期地として設定
                if (! array_key_exists('password', $data)) {
                    $data['password'] = Hash::make($data['loginid']);
                }
                $user = User::create($data);
            }

            // 該当者データなし、新規要求以外ならエラー
            else {
                return response()->json(['message' => 'User Not Found' ], 422);
            }
        }


}
