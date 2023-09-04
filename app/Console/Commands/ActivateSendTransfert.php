<?php

namespace App\Console\Commands;

use App\helpers\Constant;
use App\Models\Transfert;
use App\Services\WacePayService;
use Illuminate\Console\Command;

class ActivateSendTransfert extends Command
{
    protected $wacepayService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activate-send-transfert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * SendTransfert constructor.
     * @param $wacepayService
     */
    public function __construct(WacePayService $wacepayService)
    {
        parent::__construct();
        $this->wacepayService = $wacepayService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transferts=Transfert::query()->where(['status'=>Constant::PENDING])->get();
        foreach ($transferts as $transfert){
           $transfert->status=Constant::PROCESSING;
           $transfert->save();
            $this->info('Transfert ID  ' .$transfert->id. ' as activate.');

        }
    }
}
