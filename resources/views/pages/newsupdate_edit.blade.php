@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />

<style>
 
 #inputText {
        width: 100%;
        height: 40px;
        padding: 0.5em;
        margin-bottom: 1em;
        font-size: 1em;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5em 1em;
        margin: 0.2em;
        background-color: #007bff;
        color: white;
        border-radius: 0.5em;
        font-size: 1em;
        line-height: 1.5; /* Menyesuaikan tinggi baris */
        height: 2.5em; /* Menyesuaikan tinggi badge */
    }
    
    .badge .close {
        margin-left: 0.5em;
        cursor: pointer;
        font-weight: bold;
    }
    

    #inputTextLevel {
        width: 100%;
        height: 40px;
        padding: 0.5em;
        margin-bottom: 1em;
        font-size: 1em;
    }
    
    .badgeLevel {
        display: inline-flex;
        align-items: center;
        padding: 0.5em 1em;
        margin: 0.2em;
        background-color: #007bff;
        color: white;
        border-radius: 0.5em;
        font-size: 1em;
        line-height: 1.5; /* Menyesuaikan tinggi baris */
        height: 2.5em; /* Menyesuaikan tinggi badge */
    }
    
    .badgeLevel .close {
        margin-left: 0.5em;
        cursor: pointer;
        font-weight: bold;
    }


    #inputTextEdit {
        width: 100%;
        height: 40px;
        padding: 0.5em;
        margin-bottom: 1em;
        font-size: 1em;
    }
    
    .badgeEdit {
        display: inline-flex;
        align-items: center;
        padding: 0.5em 1em;
        margin: 0.2em;
        background-color: #007bff;
        color: white;
        border-radius: 0.5em;
        font-size: 1em;
        line-height: 1.5; /* Menyesuaikan tinggi baris */
        height: 2.5em; /* Menyesuaikan tinggi badge */
    }
    
    .badgeEdit .close {
        margin-left: 0.5em;
        cursor: pointer;
        font-weight: bold;
    }

    #inputTextLevelEdit {
        width: 100%;
        height: 40px;
        padding: 0.5em;
        margin-bottom: 1em;
        font-size: 1em;
    }
    
    .badgeLevelEdit {
        display: inline-flex;
        align-items: center;
        padding: 0.5em 1em;
        margin: 0.2em;
        background-color: #007bff;
        color: white;
        border-radius: 0.5em;
        font-size: 1em;
        line-height: 1.5; /* Menyesuaikan tinggi baris */
        height: 2.5em; /* Menyesuaikan tinggi badge */
    }
    
    .badgeLevelEdit .close {
        margin-left: 0.5em;
        cursor: pointer;
        font-weight: bold;
    }

.modal-body {
    max-height: 400px;
    max-width: 500px;
    overflow-y: auto;
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
    .form-group {
    margin-bottom: 1rem; /* Menambahkan jarak bawah antar grup form */
  }
  .form-group label {
    display: block; /* Menampilkan label sebagai block untuk memastikan label berada di atas input */
    margin-bottom: .5rem; /* Menambahkan jarak antara label dan input */
  }
  .form-control {
    width: 100%; /* Pastikan input mengambil lebar penuh dari form group */
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

.spinner-border.custom-color {
    border-color: rgba(0, 0, 0, 0.1); /* Warna border spinner yang lebih terang */
    border-top-color: #e8f0fa; /* Warna spinner (warna utama) */
}
/* Efek zoom pada gambar thumbnail */
.img-thumbnail {
    transition: transform 0.3s ease; /* Animasi zoom */
    cursor: pointer; /* Kursor pointer untuk menunjukkan gambar dapat diklik */
}

.img-thumbnail:hover {
    transform: scale(1.6); /* Memperbesar gambar saat di-hover, gunakan nilai yang lebih tinggi untuk zoom lebih besar */
}
.modal-body img {
    max-width: 100%; /* Memastikan gambar tidak melebihi lebar modal */
    height: 100px!important; /* Menjaga rasio aspek gambar */
}
</style>
@section('content')
<div class="content-wrapper">
    <section class="content p-4">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h2> Edit News & Update</h2>
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
                <h3 class="card-title">Edit News & Update</h3>

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
                <form  enctype="multipart/form-data" id="training-form">
                    
                    <input type="hidden" name="iddtl" id="iddtl" value="{{$iddtl }}">
            
                    <div class="row">
                        <!-- Left Card -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Jenis Berita">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" name="category" id="category"  >
                                                <option value="">Pilih</option>
                                                @foreach($liscategory as $value)
                                                <option value="{{ $value->id }}" {{ $databyid->id_m_news == $value->id ? 'selected' : '' }}>
                                                    {{ $value->nama }}
                                                </option>
                                                 @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Nama Berita">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" name="title" value="{{$databyid->title}}" class="form-control" id="title" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Tanggal Publikasi">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" name="startdate" value="{{$implementation_date}}"  class="form-control" id="startdate" placeholder="">
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <!-- Right Card -->
                         <!-- Right Card -->
                         <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <div class="form-group row">
                                        <div class="input-group">
                                            <div class="custom-file ">
                                                <input type="text" class="col-md-2 form-control" readonly value="Photo">
                                                <div class="col-md-1"> </div>
                                                <div class="col-md-8">
                                                    <input type="file" name="photo"    class="custom-file-input" id="item_files">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($databyid->file != null)
                                                        <button type="button" class="btn btn-primary start" data-toggle="modal" data-target="#filterButtonImage">view Image</button>
                                                     @endif
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Isi Berita">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <textarea class="form-control desc" name="requirements" id="requirements" rows="4" cols="50">{{$databyid->description}}</textarea>
                                        </div>
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
                            <button type="button" id="preview-btn" class="btn btn-info">Preview</button>&nbsp;&nbsp;
                            <button type="button" id="pending-btn" class="btn btn-warning">Pending</button>&nbsp;&nbsp;
                            <button type="button" id="publish-btn" class="btn btn-primary">Publish</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
       <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
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

        <!-- Modal untuk menampilkan gambar -->
        <div class="modal fade" id="filterButtonImage" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Photo Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-contentPhoto">
                        <img src="{{ asset('/') }}storage/{{ $databyid->file ?? '' }}" alt="Preview Image" class="img-thumbnail" width="250px">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<script>

$(document).ready(function() {
        // Function to show loading indicator
        function showLoading() {
            $('#loadingOverlay').show();
        }

        // Function to hide loading indicator
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

        $('#preview-btn').click(function() {
            // Fungsi untuk mengubah format tanggal MM/DD/YYYY ke YYYY-MM-DD
            function formatDate(inputDate) {
                if (!inputDate) return null;
                const [month, day, year] = inputDate.split('/');
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }

            function stripHtmlTags(text) {
                return text.replace(/<\/?[^>]+>/gi, '');
            }

            var formData = {
                title: $('#title').val(),
                category: $('#category').val(),
                requirements: $('#requirements').val(),
                startdate: formatDate($('#startdate').val()),
                status: 3
            };

            $('#modal-content').html(`
                <div class="form-group row">
                <label>Category</label>
                <select class="form-control" readonly>
                    <option value="${formData.category}" selected>${$('#category option:selected').text()}</option>
                </select>
                </div>
                <div class="form-group row">
                <label>Nama Berita</label>
                <input type="text" class="form-control" value="${formData.title}" readonly>
                </div>
                
                
             
                <div class="form-group row">
                <label>Tanggal Publikasi</label>
                <input type="text" class="form-control" value="${formData.startdate}" readonly>
                </div>
         
                <div class="form-group row">
                <label>Persyaratan</label>
                <textarea class="form-control" rows="4" readonly>${stripHtmlTags(formData.requirements)}</textarea>
                </div>
            `);

            $('#previewModal').modal('show');

            var fileInput = document.getElementById('item_files');
            if (fileInput) {
                var files = fileInput.files;
                if (files.length > 0) {
                    var imageUrls = [];

                    function handleFilesLoaded() {
                        var imagesHtml = imageUrls.map((url, index) => `
                            <div class="form-group row">
                                
                                <label for="picture">Photo ${index + 1}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <img src="${url}" alt="Preview Image ${index + 1}" class="img-thumbnail simulasi-gambar-picture-visi-misi" width="140px">
                                        <div class="input-group">
                                            <div class="custom-file">
                                               
                                            </div>
                                        </div>
                                
                            </div>
                        `).join('');

                        $('#modal-content').append(imagesHtml);
                    }

                    for (var i = 0; i < files.length; i++) {
                        (function(file) {
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                imageUrls.push(e.target.result);

                                if (imageUrls.length === files.length) {
                                    handleFilesLoaded();
                                }
                            };

                            reader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            }
        });

        $('#pending-btn').click(function() {
            submitFormWithStatus(2);
        });

        $('#publish-btn').click(function() {
            submitFormWithStatus(1);
        });

        function submitFormWithStatus(status) {
            var formData = new FormData($('#training-form')[0]);
            formData.append('status', status);

            var fileInput = $('#item_files')[0];
            for (var i = 0; i < fileInput.files.length; i++) {
                formData.append('item_files[]', fileInput.files[i]);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            showLoading(); // Show loading indicator

            $.ajax({
                url: '/public/update-news-update',
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
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                    $('#error-message').text('Terjadi kesalahan. Silakan coba lagi');
                    $('#errorModal').modal('show');

                    setTimeout(function() {
                        $('#errorModal').modal('hide');
                        //location.reload();
                    }, 2000);
                }
            });
        }
    });




    $("#registration_schedule").datepicker({
        // format: "dd-mm-yyyy",
        startDate: new Date(),
    });
    $("#startdate").datepicker({
        // format: "dd-mm-yyyy",
        startDate: new Date(),
    });
    $("#enddate").datepicker({
        // format: "dd-mm-yyyy",
        startDate: new Date(),
    });
    $(".desc").summernote({
        height: 250, 
            toolbar: [
                ['font', [ 'fontsize', 'clear']], // Menampilkan opsi style font dan ukuran font
                //['font', ['fontname', 'fontsize', 'clear']],
                ['color', ['color']], // Tombol warna ditampilkan
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['picture']], // Menambahkan tombol untuk menyisipkan gambar
            ],
            //fontNames: ['Arial', 'Courier New', 'Helvetica', 'Times New Roman'], // Daftar font yang tersedia
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
    $(function() {
        $('#side-list-traning-course').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
    function deletePrompt(id) {
        var url = "{{ route('pages-list-detail-delete',':id') }}";
        url = url.replace(":id", id);

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        Swal.fire({
            title: "Delete data?",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#d33",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: url,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data["status"] == "success") {
                            Toast.fire({
                                icon: "success",
                                title: data["message"],
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data["message"],
                            });
                        }
                    },
                    error: function(reject) {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    },
                });
            }
        });
    }

    // function parsingDataToModal(id) {
    //     const Toast = Swal.mixin({
    //         toast: true,
    //         position: "top-end",
    //         showConfirmButton: false,
    //         timer: 1500,
    //         timerProgressBar: true,
    //     });

    //     var url = "{{ route('edit-traningcourse-detail',':id') }}";
    //     url = url.replace(":id", id);
    //     $.ajax({
    //         url: url,
    //         type: "GET",
    //         processData: false,
    //         contentType: false,
    //         success: function(data) {
    //             data = JSON.parse(data);
    //             if (data["status"] == "success") {
    //                 $("#edit-data-list-item").html(data["output"]);
    //                 $("#edit-item").modal("toggle");
    //             } else {
    //                 Toast.fire({
    //                     icon: "error",
    //                     title: data["message"],
    //                 });
    //             }
    //         },
    //         error: function(reject) {
    //             Toast.fire({
    //                 icon: "error",
    //                 title: "Something went wrong",
    //             });
    //         },
    //     });
    // }
</script>
@endsection