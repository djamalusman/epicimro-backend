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
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                        <li class="breadcrumb-item active">{{explode('|',$title_page)[1]}}</li>
                    </ol>
                </div>
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
                    <input type="hidden" name="id_content" value="{{ base64_encode($content) }}">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="training-form" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Nama Perusahaan -->
                                        <div class="form-group row">
                                            <a type="button" href="{{ route('download-excel')}}" class="btn btn-primary">Download template</a>
                                        </div>

                                        <!-- File -->
                                        <div class="form-group row">
                                            <input type="text" class="col-md-2 form-control" readonly value="file excel">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <input type="file" class="form-control photo" id="import_file" name="import_file">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <!-- Tampilkan data duplikat dalam bentuk tabel -->

                                        <br>
                                        <!-- Buttons -->
                                        <div class="form-group row">
                                            <div class="col-md-6 offset-md-3 d-flex justify-content-center">
                                                {{-- <button type="button" id="preview-btn" class="btn btn-info">Preview</button>&nbsp;&nbsp; --}}
                                                <button type="button" id="pending-btn" class="btn btn-warning">Pending</button>&nbsp;&nbsp;
                                                <button type="button" id="publish-btn" class="btn btn-primary">Publish</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                <div class="modal fade" id="previewModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                            <h4 class="modal-title">Modal Heading</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <!-- Content will be inserted here -->
                                <div class="card">
                                    <div class="card-body">
                                        <div id="modal-content">

                                            <!-- Dynamically filled by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
               <!-- Success Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                        <i class="fas fa-check-circle check-icon"></i>
                        <h4 class="mt-4">Oh Yeah!</h4>
                        <p>Data berhasil disimpan</p>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Failed Modal -->
                <!-- Modal for errors -->
                <div class="modal fade" id="errorModalExcel" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Data Duplikat atau Salah</h5>
                        </div>
                        <div class="modal-body">
                        <table id="errorTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Pesan Kesalahan</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Data duplikat akan dimasukkan di sini oleh JavaScript -->
                            </tbody>
                        </table>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <br>
                            <p id="error-message"></p>
                        </div>

                    </div>
                    </div>
                </div>

                <div id="loadingOverlay" class="loading-overlay" style="display: none;">
                    <div class="spinner-border medium custom-color" role="status">
                        <span class="sr-only">Loading...</span>
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

<script>





       function showLoading() {
           $('#loadingOverlay').show();
       }

       function hideLoading() {
           $('#loadingOverlay').hide();
       }

       $(document).ready(function() {

           $('#pending-btn').click(function() {
               submitFormWithStatus(2);
           });

           $('#publish-btn').click(function() {
               submitFormWithStatus(1);
           });

           function submitFormWithStatus(status) {
               var formData = new FormData($('#training-form')[0]);
               formData.append('status', status);



               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   }
               });

               showLoading(); // Show loading indicator

               $.ajax({
                    url: '/public/store-sertifikat-excel',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        hideLoading(); // Hide loading indicator

                        if (response.success) {



                            if (response.duplicateData.length > 0) {
                                // Tampilkan data duplikat di tabel atau modal
                                var tableContent = '';
                                response.duplicateData.forEach(function(item, index) {
                                    tableContent += '<tr>';
                                    tableContent += '<td>' + (index + 1) + '</td>';
                                    tableContent += '<td><ul>';
                                    item.errors.forEach(function(error) {
                                        tableContent += '<li>' + error + '</li>';
                                    });
                                    tableContent += '</ul></td>';
                                    tableContent += '</tr>';
                                });

                                $('#errorTable tbody').html(tableContent);
                                $('#errorModalExcel').modal('show');
                            }

                            else
                            {
                                $('#successModal').modal('show');
                            }
                        } else {
                            // Handle jika tidak berhasil
                            alert('Proses impor gagal.');
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                        $('#error-message').text(errorMessage);
                        $('#errorModal').modal('show');
                    }
                });
           }

       });
   </script>

@endsection
