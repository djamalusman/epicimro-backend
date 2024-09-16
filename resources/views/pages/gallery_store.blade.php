@extends('../layouts.mainv2')

@section('headers')
    <link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
        rel="stylesheet" />
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
            background: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            /* Make sure it's above other elements */
        }

        .spinner-border.medium {
            width: 5rem;
            /* Atur lebar spinner */
            height: 5rem;
            /* Atur tinggi spinner */
            border-width: .55em;
            /* Atur ketebalan border spinner */
        }

        .new-input-group {
            margin-top: 10px;
            /* Adjust the margin as needed */
        }

        /* Efek zoom pada gambar thumbnail */
        .img-thumbnail {
            transition: transform 0.3s ease;
            /* Animasi zoom */
            cursor: pointer;
            /* Kursor pointer untuk menunjukkan gambar dapat diklik */
        }

        .img-thumbnail:hover {
            transform: scale(1.6);
            /* Memperbesar gambar saat di-hover, gunakan nilai yang lebih tinggi untuk zoom lebih besar */
        }

        @keyframes checkAnimation {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
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
            background-color: #008000;
            color: white;
        }

        #galeryform {
            display: none;
        }

        #posterform {
            display: none;
        }
    </style>
@section('content')

    <div class="content-wrapper">
        <section class="content p-3">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h2> Create Gallery</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                            <li class="breadcrumb-item active">{{ explode('|', $title_page)[1] }}</li>
                        </ol>
                    </div>
                </div>
            </div>

        </section>

        <section class="content p-4 col-md-12">
            <div class="card card-default">
                <div class="card-header bg-red">
                    <h3 class="card-title">Create Gallery</h3>

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
                                        <!-- Category -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control" readonly value="Category">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-5">
                                                <select class="form-control" id="category" readonly name="category">
                                                    @foreach ($liscategory as $value)
                                                        @if ($value->id == 31)
                                                            <option value="{{ $value->id }}">{{ $value->nama }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control" readonly
                                                value="Nama Image">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" id="nama_training"
                                                    name="nama_gallery">
                                            </div>
                                        </div>
                                        <!-- Photo -->
                                        <div class="form-group row">
                                            <input type="text" class="col-md-2 form-control" readonly
                                                value="Photo">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <input type="file" class="form-control photo" id="photo"
                                                        name="photo[]">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary btn-add"
                                                            onclick="addInput(this)">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="galeryform_gak_di_pakai">
                                            <!-- Nama Training -->
                                            

                                            
                                             <!-- Menu -->
                                             <div class="form-group row" hidden>
                                                <input type="text"class="col-md-2 form-control" readonly value="Menu">
                                                <div class="col-md-1"> </div>
                                                <div class="col-md-5">
                                                    <select class="form-control" id="menugalery" name="menugalery">
                                                        <option value="">Pilih</option>
                                                        @foreach ($menu as $value)
                                                            @if ($value->id == 1 || $value->id == 3)
                                                                <option value="{{ $value->id }}">{{ $value->menu_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            
                                        </div>

                                        <div id="posterform">
                                            <!-- Nama Training -->
                                            <div class="form-group row">
                                                <input type="text"class="col-md-2 form-control" readonly
                                                    value="Nama Poster">
                                                <div class="col-md-1"> </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" id="nama_training"
                                                        name="nama_poster">
                                                </div>
                                            </div>

                                            <!-- Menu -->
                                            <div class="form-group row">
                                                <input type="text"class="col-md-2 form-control" readonly
                                                    value="Menu">
                                                <div class="col-md-1"> </div>
                                                <div class="col-md-5">
                                                    <select class="form-control" id="menuposter" name="menuposter">
                                                        <option value="">Pilih</option>
                                                        @foreach ($menu as $value)
                                                            @if ($value->id == 35)
                                                                <option value="{{ $value->id }}">{{ $value->menu_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <!-- Photo -->
                                            <div class="form-group row">
                                                <input type="text" class="col-md-2 form-control" readonly value="Photo">
                                                <div class="col-md-1"> </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control photoposter" id="photoposter"
                                                            name="photoposter[]">
                                                        <div class="input-group-append" hidden>
                                                            <button type="button" class="btn btn-primary btn-add"
                                                                onclick="addInput(this)">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <br>
                                        <br>
                                        <!-- Buttons -->
                                        <div class="form-group row">
                                            <div class="col-md-6 offset-md-3 d-flex justify-content-center">
                                                <button type="button" id="preview-btn"
                                                    class="btn btn-info" hidden>Preview</button>&nbsp;&nbsp;
                                                <button type="button" id="pending-btn"
                                                    class="btn btn-warning">Pending</button>&nbsp;&nbsp;
                                                <button type="button" id="publish-btn"
                                                    class="btn btn-primary">Publish</button>
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
                    <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
                        aria-labelledby="successModalLabel" aria-hidden="true">
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
                    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog"
                        aria-labelledby="successModalLabel" aria-hidden="true">
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
       function addInput(button) {
    var inputGroup = $(button).closest('.input-group');
    var newInputGroup = inputGroup.clone();
    newInputGroup.find('input').val('');
    newInputGroup.find('.btn-add').remove();
    newInputGroup.append(
        '<button type="button" class="btn btn-danger btn-remove" onclick="removeInput(this)">-</button>');
    newInputGroup.addClass('new-input-group');
    inputGroup.after(newInputGroup);
}

function removeInput(button) {
    $(button).closest('.input-group').remove();
}

        var menuid;
        document.getElementById('category').addEventListener('change', function() {
            var selectedValue = this.value;

            var galery = document.getElementById('galeryform');
            var poster = document.getElementById('posterform');
            //var poster = document.getElementById('posterform');

            if (selectedValue === '31') {
                galery.style.display = 'block';
                menuytb = selectedValue;
                menutest = '';
                document.getElementById('posterform').style.display = 'none';
            } else if (selectedValue === '32') {
                poster.style.display = 'block';
                document.getElementById('galeryform').style.display = 'none';
                menutest = selectedValue;
                menuytb = '';
            }
            // else if (selectedValue === '31') {
            //     poster.style.display = 'block';
            // } 
            else {

                document.getElementById('galeryform').style.display = 'none';
                document.getElementById('posterform').style.display = 'none';
            }
        });


        $('#preview-btn').click(function() {

            var formData = {
                title: $('#nama_training').val(),

                status: 3
            };


            $('#modal-content').html(`
            <div class="form-group row">
                <label>Nama Training</label>
                <input type="text" class="form-control" value="${formData.title}" readonly>
            </div>
             
        `);

        function handleFileUploads() {
    var fileInput = document.querySelectorAll('.photo');
    if (fileInput.length > 0) {
        var imageUrls = [];
        var totalFiles = 0;
        var filesLoaded = 0;

        // Hitung total file yang valid (tidak kosong)
        fileInput.forEach(function(input) {
            if (input.files.length > 0) {
                totalFiles += input.files.length;
            }
        });

        fileInput.forEach(function(input) {
            var files = input.files;
            if (files.length > 0) {
                for (var i = 0; i < files.length; i++) {
                    (function(file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imageUrls.push(e.target.result);
                            filesLoaded++;
                            if (filesLoaded === totalFiles) {
                                var imagesHtml = imageUrls.map((url, index) => `
                                <div class="form-group row" style="text-align: left;">
                                    <label for="picture">Photo ${index + 1}</label>
                                </div>
                                <div class="form-group row">
                                    <img src="${url}" alt="Preview Image ${index + 1}" class="img-thumbnail" width="250px">
                                </div>
                            `).join('');
                                $('#modal-content').append(imagesHtml);
                            }
                        };
                        reader.readAsDataURL(file);
                    })(files[i]);
                }
            }
        });
    }
}

// Panggil handleFileUploads setelah semua input file telah dipilih
document.querySelectorAll('.photo').forEach(input => {
    input.addEventListener('change', handleFileUploads);
});

            $('#previewModal').modal('show');
        });

        function showLoading() {
            $('#loadingOverlay').show();
        }

        function hideLoading() {
            $('#loadingOverlay').hide();
        }
        $('input[type="file"]').change(function(e) {
            console.log('Picture Changed');
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                files.push($(this)[0].files[i].name);
            }
            const [file] = $(this)[0].files;
            if (file) {
                $(".simulasi-gambar-" + this.id).attr("src", URL.createObjectURL(file));
            }
            $(this).next(".custom-file-label").html(files.join(", "));
        });
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

                var fileInput = $('#photo')[0];
                for (var i = 0; i < fileInput.files.length; i++) {
                    formData.append('photo[]', fileInput.files[i]);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                showLoading(); // Show loading indicator

                $.ajax({
                    url: '/public/gallery-store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        hideLoading(); // Hide loading indicator
                        $('#successModal').modal('show');

                        setTimeout(function() {
                            $('#successModal').modal('hide');
                            location.reload();
                        }, 2000);

                        $('#previewModal').modal('hide');
                        $('#training-form')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        hideLoading(); // Hide loading indicator
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                        $('#error-message').text('Terjadi kesalahan. Silakan coba lagi');
                        $('#errorModal').modal('show');

                        // setTimeout(function() {
                        //     $('#errorModal').modal('hide');
                        //     location.reload();
                        // }, 2000);
                    }
                });
            }

        });
    </script>

@endsection
