@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}dist/css/mycss.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection
<style>
    .card-body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    }
    canvas {
        max-width: 100% !important;
    }
</style>
@section('script')
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="{{ asset('/') }}dist/js/main.js"></script>
<script src="{{ asset('/') }}plugins/chart.js/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            var data = {
                trainingTotal: @json($trainingTotal),
                trainingCategory: @json($trainingCategory),
                trainingCategoryLabels: @json($trainingCategoryLabels),
                trainingStatus: @json($trainingStatus),
                trainingStatusLabels: @json($trainingStatusLabels),
                jobTotal: @json($jobTotal),
                jobCategory: @json($jobCategory),
                jobCategoryLabels: @json($jobCategoryLabels),
                jobStatus: @json($jobStatus),
                jobStatusLabels: @json($jobStatusLabels)
            };

            var trainingTotalData = {
                labels: ['Total Training'],
                datasets: [{
                    data: [data.trainingTotal],
                    backgroundColor: ['#FF6384']
                }]
            };

            var trainingCategoryData = {
                labels: data.trainingCategoryLabels,
                datasets: [{
                    data: data.trainingCategory,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            };

            var trainingStatusData = {
                labels: data.trainingStatusLabels,
                datasets: [{
                    data: data.trainingStatus,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            };

            var jobTotalData = {
                labels: ['Total Lowongan'],
                datasets: [{
                    data: [data.jobTotal],
                    backgroundColor: ['#36A2EB']
                }]
            };

            var jobCategoryData = {
                labels: data.jobCategoryLabels,
                datasets: [{
                    data: data.jobCategory,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            };

            var jobStatusData = {
                labels: data.jobStatusLabels,
                datasets: [{
                    data: data.jobStatus,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            };

            // Render charts
            var trainingTotalCtx = document.getElementById('trainingTotal').getContext('2d');
            new Chart(trainingTotalCtx, {
                type: 'doughnut',
                data: trainingTotalData
            });

            var trainingCategoryCtx = document.getElementById('trainingCategory').getContext('2d');
            new Chart(trainingCategoryCtx, {
                type: 'pie',
                data: trainingCategoryData
            });

            var trainingStatusCtx = document.getElementById('trainingStatus').getContext('2d');
            new Chart(trainingStatusCtx, {
                type: 'pie',
                data: trainingStatusData
            });

            var jobTotalCtx = document.getElementById('jobTotal').getContext('2d');
            new Chart(jobTotalCtx, {
                type: 'doughnut',
                data: jobTotalData
            });

            var jobCategoryCtx = document.getElementById('jobCategory').getContext('2d');
            new Chart(jobCategoryCtx, {
                type: 'pie',
                data: jobCategoryData
            });

            var jobStatusCtx = document.getElementById('jobStatus').getContext('2d');
            new Chart(jobStatusCtx, {
                type: 'pie',
                data: jobStatusData
            });
        });
</script>
   
@endsection

@section('content')
<!-- Content Header (Page header) -->


<div class="content-wrapper">
   
	<section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <br>
                <div class="row">
                    <div class="container mt-7">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Seluruh Training
                                    </div>
                                    <div class="card-body">
                                        <canvas id="trainingTotal"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Training Sesuai Category
                                    </div>
                                    <div class="card-body">
                                        <canvas id="trainingCategory"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Training Status
                                    </div>
                                    <div class="card-body">
                                        <canvas id="trainingStatus"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Seluruh Lowongan
                                    </div>
                                    <div class="card-body">
                                        <canvas id="jobTotal"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Lowongan Sesuai Category
                                    </div>
                                    <div class="card-body">
                                        <canvas id="jobCategory"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        Jumlah Lowongan Status
                                    </div>
                                    <div class="card-body">
                                        <canvas id="jobStatus"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


@endsection