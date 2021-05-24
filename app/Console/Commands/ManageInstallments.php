<?php

namespace App\Console\Commands;

use App\Models\Installment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManageInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:manage-installments';

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
     * @return void
     */
    public function handle(): void
    {
        $admin = User::find(1);

        $penalty_data = $admin->administration->penalty_data;

        Installment::where('status', 'unpaid')
            ->whereDate('due_date', '<=', today())
            ->update([
                'status' => 'due',
            ]);

        $installments = Installment::where('status', 'due')
            ->whereDate('due_date', '<=', today())
            ->get();

        foreach ($installments as $installment) {
            $penalty = 0;
            $due_days = Carbon::parse($installment->due_date)->diffInDays();
            $due_months = Carbon::parse($installment->due_date)->diffInMonths();

            if ($due_months > 0) {
                $penalty += $due_months * (int)($installment->amount / 2);
            }

            if ($due_days >= 31) {
                $due_days = 31;
            }

//            info('Penalty: ' . $penalty. ' Installment ID: ' . $installment->id);

            foreach ($penalty_data as $penalty_datum) {
                if ($penalty_datum['day'] === $due_days) {
                    $penalty += (int)$penalty_datum['amount'];
                    break;
                }
            }

            $amount = $installment->amount;

            $installment->update([
                'penalty_amount' => $penalty,
                'total_amount' => $penalty + $amount,
            ]);
        }
    }
}
