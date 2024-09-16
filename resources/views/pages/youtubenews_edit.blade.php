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
            background-color: green;
            color: white;
        }
        <?php
           
            if ($databyid->id_category == '29') {
                echo "#yotubeform { display: block; }";
            } else {
                echo "#yotubeform { display: none; }";
            }

            if ($databyid->id_category == '30') {
                echo "#testimoniform { display: block; }";
            } else {
                echo "#testimoniform { display: none; }";
            }
        ?>
    </style>
@section('content')

    <div class="content-wrapper">
        <section class="content p-3">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h2> Edit Video</h2>
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
                    <h3 class="card-title">Edit Video</h3>

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
                                                <select class="form-control" id="category" name="category" @readonly(true)>
                                                    @foreach ($liscategory as $value)
                                                        @if ($value->id == 29)
                                                            
                                                            <option value="{{ $value->id }}" <?= ($value->id == $databyid->id_category) ? 'selected' : '' ?>>{{ $value->nama }}</option>
                                                            
                                                        @endif
                                                        
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Nama Training -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control" readonly value="Nama">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"  value="{{ $databyid->id_category == '29' ? $databyid->nama : ""}}" id="nama_testimoni"
                                                    name="nama_testimoni">
                                            </div>
                                        </div>
                                        <!-- Menu -->
                                        <div class="form-group row" hidden>
                                            <input type="text"class="col-md-2 form-control" readonly value="Menu">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-5">
                                                <select class="form-control" id="menuyoutebe" name="menuyoutebe" readonly>
                                                    @foreach ($menu as $value)
                                                        @if ($value->id == 35)
                                                            <option value="{{ $value->id }}" <?= ($value->id == $databyid->id_menu) ? 'selected' : '' ?>>{{ $value->menu_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <!-- Embed Video -->
                                        <div class="form-group row">
                                            <input type="text" class="col-md-2 form-control" readonly
                                                value="Embed Video">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control embedvideo" placeholder=""
                                                        name="embedvideoytb[]" value="{{$databyid->url}}">
                                                        <div class="input-group-append" hidden>
                                                            <button type="button" class="btn btn-primary btn-add"
                                                                onclick="addInput(this)">+</button>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="iddtl" id="iddtl" value="{{$iddtl }}">
                                        

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
                                    <h4 class="modal-title">Preview data</h4>
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
        $(".desc").summernote({
            toolbar: [
                ['font', [ 'fontsize', 'clear']], // Menampilkan opsi style font dan ukuran font
                //['font', ['fontname', 'fontsize', 'clear']],
                ['color', ['color']], // Tombol warna ditampilkan
                ['para', ['ul', 'ol', 'paragraph']],
            ],
            //
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48', '64'], // Daftar ukuran font
            buttons: {
                recentColor: function() {
                    return $.summernote.ui.button({
                        contents: '<i class="note-icon-note"></i> Recent Color',
                        tooltip: 'Recent Color',
                        click: function() {
                            // Fungsi untuk recent color
                        }
                    }).render();
                }
            },
            disableDragAndDrop: true
        });

        var menuid;
        document.getElementById('category').addEventListener('change', function() {
            var selectedValue = this.value;
            //var getdata = '<?php echo $databyid->id_category; ?>'; // Pastikan PHP mengeluarkan data dengan benar
            var yotube = document.getElementById('yotubeform');
            var testimoni = document.getElementById('testimoniform');
            var poster = document.getElementById('posterform');
            // Reset semua inputan
           
            if (selectedValue === '29') {
                yotube.style.display = 'block';
                menuytb = selectedValue;
                menutest = '';
                document.getElementById('testimoniform').style.display = 'none';
            } else if (selectedValue === '30') {
                testimoni.style.display = 'block';
                document.getElementById('yotubeform').style.display = 'none';
                menutest = selectedValue;
                menuytb = '';
            } 
            // else if (selectedValue === '31') {
            //     poster.style.display = 'block';
            // } 
            else
            {
                
                document.getElementById('yotubeform').style.display = 'none';
                document.getElementById('testimoniform').style.display = 'none';
            }
        });
        function addInput(button) {
            var inputGroup = $(button).closest('.input-group');
            var newInputGroup = inputGroup.clone();
            newInputGroup.find('input').val('');
            newInputGroup.find('.btn-add').remove();
            newInputGroup.append(
                '<button type="button" class="btn btn-danger btn-remove" onclick="removeInput(this)">-</button>');
            newInputGroup.addClass('new-input-group'); // Add class for spacing
            inputGroup.after(newInputGroup);
        }

        function removeInput(button) {
            $(button).closest('.input-group').remove();
        }
        function stripHtmlTags(text) {
                return text.replace(/<\/?[^>]+>/gi, '');
            }



        $('#preview-btn').click(function() {
            var categoryText = $('#category option:selected').text();
            var menuTextytb = $('#menuyoutebe option:selected').text();
            var menuTexttest = $('#menutesti option:selected').text();
            var menuval= menuytb == '29' ? menuTextytb : menuTexttest;
            //console.log(menuid);
            var jenis_sertifikasiText = $('#jenis_sertifikasi option:selected').text();
            var typeText = $('#type option:selected').text();
            var provinsiText = $('#provinsi option:selected').text();
            var formData = {
                title: $('#nama_testimoni').val(),
                category: categoryText,
                menu: menuval,
                deskripsi: $('#description').val(),
                embedvideo: [],

                status: 3
            };

            // Collect all dynamic inputs
            $('.embedvideo').each(function() {
                formData.embedvideo.push($(this).val());
            });


            $('#modal-content').html(`
            <div class="form-group row">
                <label>Nama Testimoni</label>
                <input type="text" class="form-control" value="${formData.title}" readonly>
            </div>
            <div class="form-group row">
                <label>Category</label>
                <input type="text" class="form-control" value="${formData.category}" readonly>
            </div>
            <div class="form-group row">
                <label>Menu</label>
                <input type="text" class="form-control" value="${formData.menu}" readonly>
            </div>
            
            
            <div class="form-group row">
                    <textarea class="form-control" rows="4" readonly>${stripHtmlTags(formData.deskripsi)}</textarea>                                                     
            </div>
            
        `);


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



                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                showLoading(); // Show loading indicator
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });

                $.ajax({
                    url: '/public/update-video',
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
                        }, 3000);

                        $('#previewModal').modal('hide');
                        $('#training-form')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        hideLoading(); // Hide loading indicator
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                        $('#error-message').text(errorMessage);
                        $('#errorModal').modal('show');

                        // setTimeout(function() {
                        //     $('#errorModal').modal('hide');
                        //     location.reload();
                        // }, 3000);
                    }
                });
            }

        });
    </script>

@endsection
