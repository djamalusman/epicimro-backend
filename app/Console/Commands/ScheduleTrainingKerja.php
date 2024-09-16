<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleTrainingKerja  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:update-status-course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of detailCourse table if created_date is less than current date';

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

        DB::table('dtc_training_course_detail')
            ->where('enddate', '<', $currentDate)
            ->update(['status' => 4]); // Ganti 'new_status' dengan status yang diinginkan

        $this->info('Status updated course successfully!');
        return 0;
    }
    
}
