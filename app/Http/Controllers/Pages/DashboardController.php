<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\MenuModel;
use Exception;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\MetricAggregation;
use Google\Analytics\Data\V1beta\NumericValue;
use Google\Analytics\Data\V1beta\OrderBy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = 'Training Kerja | Dashboard';
        $data['title_page'] = 'Dashboard | Dashboard';
        $data['menu'] = MenuModel::all();

            // $data['trainingTotal'] = 100;
            // $data['trainingCategory'] = [30, 50, 20];
            // $data['trainingStatus'] = [25, 25, 25, 25];
            // $data['jobTotal'] = 50;
            // $data['jobCategory'] = [20, 20, 10];
            // $data['jobStatus'] = [10, 20, 10, 10];

            // Query database
                $trainingCategories = DB::table('dtc_training_course_detail')
                ->join('m_category_training_course', 'dtc_training_course_detail.id_m_category_training_course', '=', 'm_category_training_course.id')
                ->select('m_category_training_course.nama as category', DB::raw('count(*) as total'))
                ->groupBy('m_category_training_course.nama')
                ->pluck('total', 'category')
                ->toArray();

                $trainingStatuses = DB::table('dtc_training_course_detail')
                ->select(DB::raw('CASE
                WHEN dtc_training_course_detail.status = 1 THEN "Publish"
                WHEN dtc_training_course_detail.status = 2 THEN "Pending"
                WHEN dtc_training_course_detail.status = 3 THEN "Non Publish"
                WHEN dtc_training_course_detail.status = 0 THEN "Kadaluarsa"
                ELSE "Unknown"
                END as status')
                , DB::raw('count(*) as total'))
                ->groupBy('dtc_training_course_detail.status')
                ->pluck('total', 'status')
                ->toArray();

                $jobCategories = DB::table('djv_job_vacancy_detail')
                ->join('m_employee_status', 'djv_job_vacancy_detail.id_m_employee_status', '=', 'm_employee_status.id')
                ->select('m_employee_status.nama as category', DB::raw('count(*) as total'))
                ->groupBy('m_employee_status.nama')
                ->pluck('total', 'category')
                ->toArray();



                $jobStatuses = DB::table('dtc_training_course_detail')
                ->select(DB::raw('CASE
                WHEN dtc_training_course_detail.status = 1 THEN "Publish"
                WHEN dtc_training_course_detail.status = 2 THEN "Pending"
                WHEN dtc_training_course_detail.status = 3 THEN "Non Publish"
                WHEN dtc_training_course_detail.status = 0 THEN "Kadaluarsa"
                ELSE "Unknown"
                END as status')
                , DB::raw('count(*) as total'))
                ->groupBy('dtc_training_course_detail.status')
                ->pluck('total', 'status')
                ->toArray();

                // Convert to arrays for the charts
                $data['trainingCategory'] = array_values($trainingCategories);
                $data['trainingCategoryLabels'] = array_keys($trainingCategories);
                $data['trainingStatus'] = array_values($trainingStatuses);
                $data['trainingStatusLabels'] = array_keys($trainingStatuses);
                $data['jobCategory'] = array_values($jobCategories);
                $data['jobCategoryLabels'] = array_keys($jobCategories);
                $data['jobStatus'] = array_values($jobStatuses);
                $data['jobStatusLabels'] = array_keys($jobStatuses);

                // Static data
                $data['trainingTotal'] = array_sum($data['trainingCategory']);
                $data['jobTotal'] = array_sum($data['jobCategory']);

        return view('pages.dashboard', $data);
    }

    public function analytics($key, $month = '', $year = '',)
    {
        try {

            $path = "credentialsrevamp.json";
            // $path = "credentials.json";
            putenv("GOOGLE_APPLICATION_CREDENTIALS=" . storage_path() . '/' . $path);

            $property_id = '360582427';
            $client = new BetaAnalyticsDataClient();

            if ($key == 'oneMonthBefore') {
                $startDate = date("Y-m-01", strtotime('-1 month'));
                $endDate = date("Y-m-t", strtotime('-1 month'));
            } else if ($key == 'currentMonth') {
                $startDate = date("Y-m-01");
                $endDate = date("Y-m-t");
            } else {
                $startDate = date("Y-m-01", strtotime($month . ' ' . $year));
                $endDate = date("Y-m-t", strtotime($month . ' ' . $year));
            }

            $response = $client->runReport([
                'property' => 'properties/' . $property_id,
                'dateRanges' => [
                    new DateRange([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]),
                ],
                'dimensions' => [
                    new Dimension(
                        [
                            'name' => 'day',
                            // "sortOrder"=> "DESCENDING"
                        ]
                    ),
                ],
                'metrics' => [
                    new Metric(
                        [
                            'name' => 'activeUsers',
                        ],

                    ),
                    new Metric([
                        "name" => "totalUsers"
                    ]),
                    new Metric([
                        "name" => "newUsers"
                    ])
                ],

                'orderBys' => [
                    new OrderBy([
                        'dimension' => new OrderBy\DimensionOrderBy([
                            'dimension_name' => 'day', // your dimension here
                            'order_type' => OrderBy\DimensionOrderBy\OrderType::NUMERIC
                        ]),
                        'desc' => false,
                    ]),
                ],
                'keepEmptyRows' => true,
                'metricAggregations' => [
                    MetricAggregation::TOTAL,
                ],
            ]);
        } catch (Exception $e) {
            $response = $e;
        }
        return $response;
    }
}
