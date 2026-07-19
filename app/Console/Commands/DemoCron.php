<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PreparationDelivery;
use Illuminate\Support\Facades\Log;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $message = "test";
        $this->sendTelegram('-690929411',$message );

        $this->check_delay();
    }

    public function sendTelegram($chat_id, $text)
    {
        $token ='1488492213:AAFkw2dzki-No0W5tuu8JjAwm0mvg__98BU';
        $url = 'https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$chat_id.'&text='.$text;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec ($ch);
        $err = curl_error($ch); 
        curl_close ($ch);
    }

    public function check_delay()
    {
        
        $data_delay_baru_start = PreparationDelivery::where(DB::raw('DATE_FORMAT(CONCAT(plan_date_preparation," ",plan_time_preparation), "%Y-%m-%d %H:%i:%s")'), '<', date("Y-m-d H:i:s"))->where('status', '1')->get();
        $data_delay_belum_start = PreparationDelivery::where(DB::raw('DATE_FORMAT(CONCAT(plan_date_preparation," ",plan_time_preparation), "%Y-%m-%d %H:%i:%s")'), '<', date("Y-m-d H:i:s")  )->where('status', NULL)->get();
        $npk=  Auth::user()->npk;
        $now =date("Y-m-d H:i:s");
        
        // dd($data_delay_baru_start);
        // update ke status delay dulu, data_delay_baru_start
        foreach ($data_delay_baru_start as $item) {
            $selection = PreparationDelivery::find( $item->id);
            DB::beginTransaction();
            try {
                $selection->status = '5';
                $selection->end_preparation = $now;
                $selection->end_by = $npk;
                $selection->time_preparation =   abs(strtotime ( $selection->start_preparation ) - strtotime ( $now))/(60);
                $selection->save();
                DB::commit();
            } catch (\Throwable $th) {
                // throw $th;
                DB::rollback();
            }
        }
        foreach ($data_delay_belum_start as $item2) {
            $selection2 = PreparationDelivery::find( $item2->id);
            DB::beginTransaction();
            try {
                $selection2->end_preparation = $now;
                $selection2->date_preparation = $now;
                $selection2->start_preparation = $now;
                $selection2->status = '5';
                $selection2->end_by = $npk;
                $selection2->start_by = $npk;
                // $selection2->time_preparation =  abs(strtotime ( $selection2->start_preparation ) - strtotime ( $now))/(60);
                $selection2->save();
                DB::commit();

            } catch (\Throwable $th) {
                DB::rollback();

                throw $th;
            }
            
        }

        // dd($data_delay_baru_start);
        $data_delay = PreparationDelivery::where('end_preparation', '>', DB::raw("CONCAT('',plan_date_preparation, plan_time_preparation)"))->where('remark', NULL)->where('status', '5')->where('problem', NULL)->limit(1)->get();
        return $data_delay;
    }
}
