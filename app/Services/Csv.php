<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Request;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class Csv
{
    /**
     * CSVダウンロード
     * @param array $csv_data
     * @param array $csv_haader
     * @param string $csv_filename
     * @\Illuminate\Http\Response
     */
    public function download($csv_data, $csv_header, $csv_filename)
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__);

        // ヘッダー指定があれば一行目にヘッダーをセット
        if (count($csv_header) > 0) {
            array_unshift($csv_data, $csv_header);
        }

        // ストリームでレスポンス ::
        //   vender/laravel/framework/src/Illuminate/Routing/ResponseFactory.php
        //       streamDownload($collback, $name = null, $headers = [], $disposition = "attachment")
        return response() -> streamDownload(
            function() use($csv_data) {
                $file = new \SplFileObject('php://output', 'w');
                foreach ($csv_data as $row){
                    $file->fputcsv($row);
                }
            },$csv_filename,
            array('Content-Type' => 'application/octet-stream')
        );
    }

    /**
     * CSVアップロード(読み取り)
     */
    public function parse($file)
    {
        $config = new LexerConfig();
        $interpreter = new Interpreter();
        $lexer = new Lexer($config);

        // CharsetをUTF-8に変換
        $config->setToCharset('UTF-8');
        $config->setFromCharset('sjis-win');

        // csv データをパース
        $row = array();
        try {
            $interpreter->addObserver(function(array $row) use (&$rows) {
                $rows[] = $row;
            });
            $lexer->parse($file, $interpreter);

        } catch (\Exception $e) {
            throw $e;
        }

        // 1つずつ処理
        $data = array();
        foreach($rows as $key => $value) {

            if($key == 0) {
                $header = $value;
                continue;
            }

            // 配列か - 2行目以降はヘッダーに沿って配列に
            foreach ($value as $k => $v) {
                $data[$key][$header[$k]] = $v;
            }
        }
        // CSV を配列で戻す
        return $data;
    }

}