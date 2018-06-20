<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Shipment;

class ShipmentPurchased implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shipment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = route("admin.shipment.edit", $this->shipment);
        $msg = "<a href=\"{$url}\">发货单已采购【待发货】</a>";
        $ids = [];
        Department::permission("ship")
            ->select("id")
            ->get()
            ->each(function ($d) use (&$ids) {
                $ids[] = $d->id;
            });
        $work = EasyWeChat::work();
        $work->messenger
            ->ofAgent(env("WECHAT_WORK_AGENT_ID"))
            ->message($msg)
            ->toParty($ids)
            ->send();
    }
}