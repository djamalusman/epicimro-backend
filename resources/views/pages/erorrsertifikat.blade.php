@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>

  .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            text-align: right;
        }
        .btn-custom {
            margin-top: 25px;
        }
        .btn-add {
            margin-top: 0;
        }

  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050; /* Make sure it's above other elements */
}
.spinner-border.medium {
    width: 5rem; /* Atur lebar spinner */
    height: 5rem; /* Atur tinggi spinner */
    border-width: .55em; /* Atur ketebalan border spinner */
}
.new-input-group {
    margin-top: 10px; /* Adjust the margin as needed */
}
/* Efek zoom pada gambar thumbnail */
.img-thumbnail {
    transition: transform 0.3s ease; /* Animasi zoom */
    cursor: pointer; /* Kursor pointer untuk menunjukkan gambar dapat diklik */
}

.img-thumbnail:hover {
    transform: scale(1.6); /* Memperbesar gambar saat di-hover, gunakan nilai yang lebih tinggi untuk zoom lebih besar */
}
@keyframes checkAnimation {
      0% { transform: scale(0); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    .modal-content {
      text-align: center;
      padding: 20px;
    }

    .check-icon {
      font-size: 5em;
      color: green;
      animation: checkAnimation 1s;
    }

    .error-icon {
    font-size: 5em;
    color: red;
    animation: checkAnimation 1s;
}

    .btn-ok {
      background-color: green;
      color: white;
    }
</style>
@section('content')

<div class="content-wrapper">
    <section class="content p-3">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h2> Create Sertifikat</h2>
                </div>
                {{-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                        <li class="breadcrumb-item active">{{explode('|',$title_page)[1]}}</li>
                    </ol>
                </div> --}}
            </div>
        </div>

    </section>

    <section class="content p-4 col-md-12" >
        <div class="card card-default">
            <div class="card-header bg-red">
                <h3 class="card-title">Create Sertifikat</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->

            <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <form id="training-form" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-body">
                                        @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(session('duplicateData') && count(session('duplicateData')) > 0)
                                        <h4>Data yang Gagal Diproses</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Peserta</th>
                                                    <th>Email</th>
                                                    <th>Nama Training</th>
                                                    <th>No Sertifikat</th>
                                                    <th>Pesan Kesalahan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach(session('duplicateData') as $data)
                                                    <tr>
                                                        <td>{{ $data['row_data'][0] }}</td> <!-- Nama Peserta -->
                                                        <td>{{ $data['row_data'][1] }}</td> <!-- Email -->
                                                        <td>{{ $data['row_data'][2] }}</td> <!-- Nama Training -->
                                                        <td>{{ $data['row_data'][4] . "/" . $data['row_data'][5] . "/" . $data['row_data'][6] . "/" . $data['row_data'][7] }}</td> <!-- No Sertifikat -->
                                                        <td>
                                                            <ul>
                                                                @foreach($data['errors'] as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    @else
                                        <p>Tidak ada data yang gagal diproses.</p>
                                    @endif


                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
            </div>
        </div>


    </section>
</div>

@endsection

@section('script')
<script src="{{ asset('/') }}dist/js/main.js"></script>
<script src="{{ asset('/') }}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


@endsection
