<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\User;
use Validator;
use App\Facades\Csv;
use App\Http\Response\UploadCsvFile;

class UserController extends Controller
{
  public function index()
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__);

    $users = User::all();
    return ['users' => $users];
  }

  public function download(Request $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

    $csv_data = User::get(['loginid', 'name', 'role'])->toArray();
    $csv_header = ['loginid', 'name', 'role'];
    return Csv::download($csv_data, $csv_header, 'test.csv');
  }

  public function upload(UploadCsvFile $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

    $file = $request->file('csvfile');
    if ($file->getClientOriginalExtension() != 'csv') {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Name: '.$file ->getClientOriginalExtension());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Extension: '.$file->getClientOriginalExtention());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' ClientMimeType: '.$file->getClientMimeType());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' MineType: '.$file->getMimeType());

      return new JsonResponse(['error' => [ 'csvfile' => 'CSVファイルを指定してください']], 422);
    }

    // csv をパース
    try {
      $rows = Csv::parse($file);
    } catch (\Exception $e) {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' Parse Exception : '.$e -> getMessage());
      return new JsonResponse(['errors'=> [
        'csvfile' => 'CSVファイルの読み込みエラーが発生しました',
        'exception' => $e ->getMessage()
      ]]
      , 422);
    }

    // 1行ずつ処理
    $ret = array();
    foreach ($rows as $line => $value) {

      // 行データに対してのばりデート（必須・内容の確認）
      $validator->$this->validator($value);
      
      // データに問題があればエラーを記録 => 処理は継続
      if ($validator->fails()) {
        foreach ($validator->errors()->all() as $message) {
          Log::Debug(__CLASS__.':'.__FUNCTION__.' ERROR line('.$line.') '.$message);
          $ret['errors'][] = ['line' => $line, 'message' => $message];
        }
        continue;
      }

      // CSVに問題がなければ 更新 or 挿入
      $user = User::where('loginig', $value['loginid'])->first();

      // 存在したら、更新
      if ($user) {
        Log::Debug(__CLASS__.':'.__FUNCTION__.' UPDATE line('.$line.') '.$value['name']);
        $user->fill($value)->save();
        $user['updata'][] = ['line' => $line, 'message' =>$value['name']];
      }

      // DB未登録なら新規登録
      else {
        Log::Debug(__CLASS__.':'.__FUNCTION__.' INSERT line('.$line.') '.$vale['name']);
        $value['password'] = Hash::make($value['loginid']); //とりあえず初期パスワードは loginIDと同じにしておく
        User::create($value);
        $ret['insert'][] = ['line' => $line, 'message' => $value['name']];
      }
    } //i行ずつ処理

    // 結果を戻す
    return ['import' => $ret];

  }

  public function store(Request $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());

    // 入力項目チェック（必須やら文字数やら）
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      $validator->validate();
    }

    $data = $validator->valid();

    if (array_key_exists('pass', $data) && $data['pass'] != '') {
      $data['password'] = Hash::make($data['pass']);
    }

    // ユーザ情報ＤＢ保存
    return $this->storeUser($data);
  }


  public function destroy(Request $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' destroy :'. print_r($request->all(),true));

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

    // 該当者削除
    $user->delete();

    // RETURN
    return ['data' => $user];
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

    // RETURN
    return ['data' => $user];
  }

  private function validator(array $data)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__.' validator :'. print_r($data,true));

    // ログインＩＤに許可する 「 記号 」
    //          ,-.@_  |
    $ID_KIGO = ',-.@_\\|';

    // パスワードに許可する「 記号 」
    //       !"#$%& '()*+,-. /:;<=>?@   \[  ]^_`{  |}~
    $KIGO = '!"#$%&\'()*+,-.\/:;<=>?@\\\\[\\]^_`{\\|}~';

    // 入力項目チェック（必須やら文字数やら）
    $validator = Validator::make($data, [

      // 氏名：　必須、最小２文字、　最大６４文字
      'name' => [
        'required',  // 必須
        'min:2',  // 最低２文字 (中国人でも２文字はあるよね)
        'max:64',  // 最大６４文字 （アルファベットの名前だと足りないか？）
      ],

      // ログインＩＤ：　
      'loginid' => [
        'required',  // 必須
        'min:6',  // 最低６文字（a@a.aa の形でも最低６文字は必要だよね）
        'max:128',  // 最長128文字（なんとなく）
        'unique:users,loginid,'.$data['loginid'].',loginid',  // 同じログインIDは登録不可
        'regex:/[a-zA-Z\d'.$ID_KIGO.']+\z/',
        // 英小文字(a-z) or 英大文字(A-Z)、数字(\d)、記号($ID_KIGO)以外の文字はエラー
      ],

      // パスワード：
      'pass' => [
        'nullable',  // 空の場合は LoginIDに！
        'min:4',  // 最低４文字
        'max:128',  // 最長128文字（なんとなく）
        'regex:/\A(?=.*?[a-zA-Z])(?=.*?\d)(?=.*?['.$KIGO.'])[a-zA-Z\d'.$KIGO.']+\z/',
        // 必ず英小文字(a-z)or英大文字(A-Z)、数字(\d)、記号($KIGO)を１文字含む(\A)こと
      ],

      // 権限:
      'role' => [
        'nullable',
        'numeric',
        Rule::in([5,10]),
      ],

    ]);
    return $validator;
  }
}
