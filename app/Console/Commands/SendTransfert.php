<?php

namespace App\Console\Commands;

use App\helpers\Constant;
use App\Models\Transfert;
use App\Services\WacePayService;
use Illuminate\Console\Command;

class SendTransfert extends Command
{
    protected $wacepayService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-transfert';

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
        $transferts=Transfert::query()->where(['status'=>Constant::PROCESSING])->get();
        foreach ($transferts as $transfert){
            if ($transfert->type="mobilemoney"){
                $this->wacepayService->sendTransactionMobile($transfert);
            }else{
                $this->wacepayService->sendTransaction($transfert);
            }
            $this->info('Transfert ID  ' .$transfert->id. ' as send success.');
        }
        $this->info('File structure for ' . ' created.');
    }
}
