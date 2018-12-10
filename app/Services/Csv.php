<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Response;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class Csv
{
    /**
     * CSVダウンロード
     * @param array $csv_data
     * @param array $csv_header
     * @param string $csv_filename
     * @return \Illuminate\Http\Response
     */
    public function download($csv_data, $csv_header, $csv_filename)
    {
        // ヘッダー指定あれば１行目にヘッダーをセット
        if (count($csv_header) > 0) {
            array_unshift($csv_data, $csv_header);
        }

        // ストリームでレスポンス ::  
        //      vendor/laravel/framework/src/Illuminate/Routing/ResponseFactory.php  
        //          streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
        return response() -> streamDownload( 
            function () use($csv_data) {
                $file = new \SplFileObject('php://output', 'w');
                foreach ($csv_data as $row) {
                    $file->fputcsv($row);
                }
            }, 
            $csv_filename,
            array('Content-Type' => 'application/octet-stream')
        );
    }

    /**

        $rows = array();
        try {
            $interpreter->addObserver(function(array $row) use (&$rows) {
                $rows[] = $row;
            });
            $lexer->parse($file, $interpreter);

        } catch (\Exception $e) {
            throw $e;
        }


            if($key == 0) {
                $header = $value;
                continue;
            }


            foreach ($value as $k => $v) {
                $data[$key][$header[$k]] = $v;
            }
        }

