<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use App\User;
use App\Timestamp;
use Carbon\Carbon;


class TimestampsController extends Controller
{
    /**
     * 
     * 
     */
    public function punchIn()
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__);

        $user = Auth::user();
        $timestamp = Timestamp::create([
            'user_id' => $user->id,
            'punchIn' => Carbon::now()
        ]);
    }
    
    public function punchOut()
    {
        Log::Debug(__CLASS__.':'.__FUNCTION__);
        
        $user = Auth::user();
        $timestamp = Timestamp::where('user_id', $user->id)->latest()->first();

        if(!empty($timestamp->punchOut)) {
            return response()->json(['message' => '既に退勤の打刻がされているか、出勤打刻がされていません']);
        }
        $timestamp->update([
            'punchOut' => Carbon::now()
        ]);
    }
}
