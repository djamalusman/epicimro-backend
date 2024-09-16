<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleJobvacancy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-status-jobvacancy';

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
        $currentDate = Carbon::now()->format('Y-m-d 00:00:00');

        DB::table('djv_job_vacancy_detail')
            ->where('close_date', '<', $currentDate)
            ->update(['status' => 4]); // Ganti 'new_status' dengan status yang diinginkan

        $this->info('Status updated successfully!');
        return 0;
    }
}
