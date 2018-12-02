<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hosh;
use App\User;
use Validator;

class UserController extends Controller
{
    /**
     * User index
     */
    public function index()
    {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' index ');

    $users = User::all();
    return ['users' => $users];
    }

    /**
     * user store $ createも
     */
    public function store(Request $request)
    {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' store ');

    //入力項目のチェック
    $data = $this->validator($request->all());

    //ユーザ情報DB保存
    return $this->storeUser($data);
    }

    /**
     * ユーザ削除
     */
    public function destroy(Request $request)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__.' destroy :'. print_r($request->all(),true));

        $data = $request->all();

        // loginID指定あり？
        if (trim($data['loginid'] == '')) {
            return response()->json(['message' => 'loginID Not Found' ], 422);
        }

        // ユーザテーブルから該当者のデータを取得
        $user = User::where('loginid', $data['loginid'])->first();

        // 当事者データなし -> エラー
        if (! $user) {
            return response()->json(['message' => 'User Not Found'], 422);
        }
        $user->delete();

        return ['data', $user];
    }
     


    /**
     * ユーザの新規登録か、更新を切り分け対処する
     * 判定でエラーになった場合は自動的にリダイレクト、json吐き出す
     */
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

    private function validator(array $data)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__.' validator :'. print_r($data, true));

        // ログインIDに許可する「記号」
        //          ,-.@_  |;
        $ID_KIGO = ',-.@_\\|';

        // パスワードに許可する「記号」
        //       !"#$%& '()*+,-. /:;<=>?@   \[  ]^_`{  |}~
        $KIGO = '!"#$%&\'()*+,-.\/:;<=>?@\\\\[\\]^_`{\\|}~';

        // 入力項目チェック(必須、文字数など)
        $validator = Validator::make($data, [

            // 指名：必須、最長2文字、橙64文字
            'name' => [
                'required',
                'min:2',
                'max:64',
            ],

            // ログインID
            'loginid' => [
                'required',
                'min:6',
                'max:128',
                'unique:users,loginid,'.$data['loginid'].',loginid',  // 同じログインIDは登録不可
                'regex:/[a-zA-Z\d'.$id_KIGO.']+\z/',
                // 英小文字(a-z) or 英大文字(A-Z)、数字(\z)、記号($ID_KIGO)以外の文字はエラー
            ],

            // パスワード
            'pass' => [
                'nullable',
                'min:4',
                'max:128',
                'regex:/\A(?=.*?[a-zA-Z])(?=.*?\d)(?=.*?['.$KIGO.'])[a-zA-Z\d'.$KIGO.']+\z/',
                // 必ず英小文字、英大文字、記号、数字を1文字含む(\A)こと
            ],
        ])->validate();

        if ($data['pass'] != '') {
            $data['password'] = Hash::make($data['pass']);
        }

        return $data;
    }

}
