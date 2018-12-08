<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    
    'accepted' => ':attributeを承認してください。',
    'active_url' => ':attributeは正しいURLではありません。',
    'after' => ':attributeは:date以降の日付にしてください。',
    'after_or_equal' => ':attributeは:date以降の日付にしてください。',
    'alpha' => ':attributeは英字のみにしてください。',
    'alpha_dash' => ':attributeは英数字とハイフンのみにしてください。',
    'alpha_num' => ':attributeは英数字のみにしてください。',
    'array' => ':attributeは配列にしてください。',
    'before' => ':attributeは:date以前の日付にしてください。',
    'before_or_equal' => ':attributeは:date以前の日付にしてください。',
    'between' => [
        'numeric' => ':attributeは:min 〜 :maxまでにしてください。',
        'file' => ':attributeは:min 〜 :max KBまでのファイルにしてください。',
        'string' => ':attributeは:min 〜 :max文字にしてください。',
        'array' => ':attributeは:min 〜 :max個までにしてください。',
    ],
    'boolean' => ':attributeはtrueかfalseにしてください。',
    'confirmed' => ':attributeは確認用項目と一致していません。',
    'date' => ':attributeは正しい日付ではありません。',
    'date_format' => ':attributeは:format書式と一致しません。',
    'different' => ':attributeと:otherと違うものにしてください。',
    'digits' => ':attributeは:digits桁にしてください。',
    'digits_between' => ':attributeは:min 〜 :max桁にしてください。',
    'dimensions' => ':attributeは画像サイズが不正です。',
    'distinct' => ':attributeは重複してます。',
    'email' => ':attributeは正しくしてください。',
    'exists' => '選択された:attributeは正しくありません。',
    'file' => ':attributeはファイルにしてください。',
    'filled' => ':attributeは必須です。',
    'gt' => [
        'numeric' => ':attributeは:value以上にしてください。',
        'file' => ':attributeは:value KB以上にしてください。',
        'string' => ':attributeは:value文字以上にしてください。',
        'array' => ':attributeは:value個以上にしてください。',
    ],
    'gte' => [
        'numeric' => ':attributeは:value以上にしてください。',
        'file' => ':attributeは:value KB以上にしてください。',
        'string' => ':attributeは:value文字以上にしてください。',
        'array' => ':attributeは:value個以上にしてください。',
    ],
    'image' => ':attributeは画像ファイルではありません。',
    'in' => '選択された:attributeは正しくありません。',
    'in_array' => ':otherに:attributeはありません。',
    'integer' => ':attributeは整数にしてください。',
    'ip' => ':attributeを正しく設定してください。',
    'ipv4' => ':attributeを正しく設定してください。',
    'ipv6' => ':attributeを正しく設定してください。',
    'json' => ':attributeを正しいJSON形式にしてください。',
    'lt' => [
        'numeric' => ':attributeは:valueより小さくなければなりません。',
        'file' => ':attributeは:value KBより小さくなければなりません。',
        'string' => ':attributeは:value文字より少なくなければいけません。',
        'array' => ':attributeは:value個より少なくなければいけません。',
    ],
    'lte' => [
        'numeric' => ':attributeは:value以下にしてください。',
        'file' => ':attributeは:value KB以下のファイルにしてください。',
        'string' => ':attributeは:value文字以下にしてください。',
        'array' => ':attributeは:value個以下にしてください。',
        
    ],
    'max' => [
        'numeric' => ':attributeは:max以下にしてください。',
        'file' => ':attributeは:max KB以下のファイルにしてください。',
        'string' => ':attributeは:max文字以下にしてください。',
        'array' => ':attributeは:max個以下にしてください。',
    ],
    'mimes' => ':attributeは:valueタイプのファイルにしてください。',
    'mimetypes' => ':attributeは:valueタイプのファイルにしてください。',
    'min' => [
        'numeric' => ':attributeは:min以上にしてください。',
        'file' => ':attributeは:min KB以上の数字にしてください。',
        'string' => ':attributeは:min文字以上にしてください。',
        'array' => ':attributeは:min個以上にしてください。',
    ],
    'not_in' => '選択された:attributeは正しくありません。',
    'not_regex' => ':attributeは書式が正しくありません。',
    'numeric' => ':attributeは数字にしてください。',
    'present' => ':attributeは存在する必要があります。',
    'regex' => ':attributeの書式が正しくありません。',
    'required' => ':attributeは必須です。',
    'required_if' => ':otherが:valueの時、:attributeは必須です。',
    'required_unless' => ':otherが:valueにない時、:attributeは必須です。',
    'required_with' => ':valueが存在する時、:attributeは必須です。',
    'required_with_all' => ':valueが存在する時、:attributeは必須です。',
    'required_without' => ':valueが存在しない時、:attributeは必須です。',
    'required_without_all' => ':valueが存在しない時、:attributeは必須です。',
    'same' => ':attributeと:otherは一致していません。',
    'size' => [
        'numeric' => ':attributeを:sizeにしてください。',
        'file' => ':attributeは:size KBにしてください。',
        'string' => ':attributeは:size文字にしてください。',
        'array' => ':attributeは:size個にしてください。',
    ],
    'string' => ':attributeは文字列にしてください。',
    'timezone' => ':attributeは正しく設定してください。',
    'unique' => ':attributeは既に存在しています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'url' => ':attributeは正しい書式にしてください。',
    'uuid' => ':attributeは正しく設定してください。',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'pass' => [
            'regex' => '必ず英文字と数字、記号を1文字ずつ含みこと',
        ],
        'rule' => [
            'in' => ':attributeは 5 か 10 を指定してください',
        ],
        'csvfile' => [
            'required' => 'ファイルを洗濯してください',
            'file' => 'ファイルのアップロードに失敗しました',
            'mimetypes' => 'ファイル形式が不正です(CSVファイルを洗濯してください)',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'loginid' => 'ログインID',
        'pass' => 'パスワード',
    ],

];
