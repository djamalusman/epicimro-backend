@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

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
                    <h2> Edit Job Vacancy</h2>
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
                <h3 class="card-title">Edit Job Vacancy</h3>

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
                            <input type="hidden" name="iddtl" value="{{ $iddtl }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Company Name">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" value="{{ $databyid->companyName}}" id="companyName" name="companyName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Company Logo">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control photo" id="photo" name="photo">
                                            <br>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#previewModalPhoto">
                                                View file
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Jobs Title">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="jobTitle" value="{{ $databyid->job_title}}" name="jobTitle">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Employment Status">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">

                                            <select class="form-control" name="employmentStatus" id="employmentStatus">
                                                <option>--Pilih Employee status--</option>
                                                @foreach($listemployeestatus as $value)
                                                <option value="{{ $value->id }}" {{ $databyid->id_m_employee_status == $value->id ? 'selected' : '' }}> {{ $value->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Placement">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="workLocation" name="workLocation">
                                                <option>--Pilih Work Location--</option>
                                                @foreach($listworklocation as $value)
                                                <option value="{{ $value->id }}" {{ $databyid->id_m_work_location == $value->id ? 'selected' : '' }}>
                                                    {{ $value->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Est. Salary">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-4">
                                            <select class="form-control" id="estSalary" name="estSalary">
                                                <option>--Pilih Salaray--</option>

                                                @foreach($listsalary as $value)
                                                    <option value="{{ $value->id }}" {{ $databyid->id_m_salaray == $value->id ? 'selected' : '' }}>
                                                    {{ $value->nama }}
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" id="salaryDateMonth" name="salaryDateMonth">
                                                <option>--Pilih Fee--</option>

                                                @foreach($listfee as $value)
                                                    <option value="{{ $value->id }}" {{ $databyid->id_m_salaray_date_mont == $value->id ? 'selected' : '' }}>
                                                    {{ $value->nama }}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Sector">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="sector" name="sector">
                                                <option>--Pilih Sector--</option>

                                                @foreach($listsector as $value)
                                                    <option value="{{ $value->id }}" {{ $databyid->id_m_sector == $value->id ? 'selected' : '' }}>
                                                    {{ $value->nama }}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Provinsi -->
                                    <div class="form-group row">
                                        <input type="text"class="col-md-2 form-control" readonly value="Provinsi">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="provinsi" name="provinsi">
                                                <option>Pilih Provinsi</option>
                                                @foreach($listprovinsi as $value)
                                                    <option value="{{ $value->id }}" {{ $databyid->id_provinsi == $value->id ? 'selected' : '' }}> {{ $value->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Lokasi -->
                                    <div class="form-group row">
                                        <input type="text"class="col-md-2 form-control" readonly value="City">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" value="{{$databyid->lokasi}}" id="lokasi" name="lokasi">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text"class="col-md-2 form-control" value="Link Pendaftaran" readonly >
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="link_pendaftaran" placeholder=""  value="{{$databyid->linkpendaftaran}}"  name="link_pendaftaran">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="form-section-title col-md-2" style="color: #007bff"><h4><b>Requirtment</b></h4></div>
                                    </div>



                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Education">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="education" name="education">
                                                <option>--Pilih Education--</option>
                                                    @foreach($listeducation as $value)
                                                        <option value="{{ $value->id }}" {{ $databyid->id_m_education == $value->id ? 'selected' : '' }}>
                                                        {{ $value->nama }}
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text" class="col-md-2 form-control" readonly value="Experience Level">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="experienceLevel" name="experienceLevel">
                                                <option>--Pilih Experience Level--</option>
                                                    @foreach($listexperiencelevel as $value)
                                                        <option value="{{ $value->id }}" {{ $databyid->id_m_experience_level == $value->id ? 'selected' : '' }}>
                                                        {{ $value->nama }}
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <input type="text" class="col-md-2 form-control" readonly value="Certification">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="certification" value="{{ $databyid->sertifikasi}}" name="certification">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                         <input type="text" class="col-md-2 form-control" readonly value="Jobs Description">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-9"> 
                                            <textarea class="form-control descJobdesc" name="jobdescripsi" id="jobdescripsi" rows="4" cols="50">{{ $databyid->job_description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <input type="text" class="col-md-2 form-control" readonly value="Skill Requirement">
                                           <div class="col-md-1"> </div>
                                           <div class="col-md-9">
                                               <textarea class="form-control descReqdesc" name="skillRequirement" id="skillRequirement" rows="4" cols="50">{{ $databyid->skill_requirment}}</textarea>
                                           </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="form-section-title col-md-2" style="color: #007bff"><h4><b>Schedule</b></h4></div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="text"class="col-md-2 form-control"  readonly value="Publish Date">
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-1">
                                            <input type="" readonly class="form-control" style="background-color: yellow" placeholder="Mulai">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_mulai_tanggal" name="jadwal_mulai_tanggal">
                                                        <option>Tanggal</option>
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_mulai_bulan" name="jadwal_mulai_bulan">
                                                        <option>Bulan</option>
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_mulai_tahun" name="jadwal_mulai_tahun">
                                                        <option>Tahun</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-1 offset-md-3">
                                            <input type="text" class="form-control" style="background-color: yellow" placeholder="Selesai" >
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_selesai_tanggal" name="jadwal_selesai_tanggal">
                                                        <option>Tanggal</option>
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_selesai_bulan" name="jadwal_selesai_bulan">
                                                        <option>Bulan</option>
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <select class="form-control" id="jadwal_selesai_tahun" name="jadwal_selesai_tahun">
                                                        <option>Tahun</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-3 d-flex justify-content-center">
                                            <button type="button" id="preview-btn" class="btn btn-info">Preview</button>&nbsp;&nbsp;
                                            <button type="button" id="pending-btn" class="btn btn-warning">Pending</button>&nbsp;&nbsp;
                                            <button type="button" id="publish-btn" class="btn btn-primary">Publish</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal photo -->
        <div class="modal fade" id="previewModalPhoto" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
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
                    <div class="modal-body"  id="modal-content">
                        <!-- Content will be inserted here -->

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
                  <p>Data berhasil diupdate</p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Event listener for dynamically added elements (if any)


    function formatDate(dateStr) {
        if (!dateStr) return '';

        var parts = dateStr.split(' ')[0].split('-');
        var date = new Date(parts[0], parts[1] - 1, parts[2]);

        var day = date.getDate();
        var month = date.toLocaleString('default', { month: 'long' });
        var year = date.getFullYear();

        return day + ' ' + month + ' ' + year;
    }

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
            // function formatDate(inputDate) {
            //     if (!inputDate) return null;
            //     const [month, day, year] = inputDate.split('/');
            //     return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            // }
            function stripHtmlTags(text) {
                return text.replace(/<\/?[^>]+>/gi, '');
            }


            var formData = {
                jobTitle: $('#jobTitle').val(),
                employmentStatus: $('#employmentStatus').val(),
                workLocation: $('#workLocation').val(),
                salaryDateMonth: $('#salaryDateMonth').val(),
                estSalary: $('#estSalary').val(),
                sector: formatDate($('#sector').val()),
                companyName: $('#companyName').val(),
                provinsi: $('#provinsi').val(),
                lokasi: $('#lokasi').val(),
                link_pendaftaran: $('#link_pendaftaran').val(),
                education: formatDate($('#education').val()),
                experienceLevel: $('#experienceLevel').val(),
                certification: $('#certification').val(),
                jobdescripsi: $('#jobdescripsi').val(),
                skillRequirement: $('#skillRequirement').val(),
                jadwal_mulai_tanggal: formatDate(`${$('#jadwal_mulai_tahun').val()}-${$('#jadwal_mulai_bulan').val()}-${$('#jadwal_mulai_tanggal').val()}`),
                jadwal_selesai_tanggal: formatDate(`${$('#jadwal_selesai_tahun').val()}-${$('#jadwal_selesai_bulan').val()}-${$('#jadwal_selesai_tanggal').val()}`),
                status: 3
            };

            $('#modal-content').html(`
                <div class="form-group row">
                <label>Company Name</label>
                <input type="text" class="form-control" value="${formData.companyName}" readonly>
                </div>
                <div class="form-group row">
                <label>Job Title</label>
                    <input type="text" class="form-control" value="${formData.jobTitle}" readonly>
                </div>

                <div class="form-group row">
                <label>Employee Stataus</label>
                <select class="form-control" readonly>
                    <option value="${formData.employmentStatus}" selected>${$('#employmentStatus option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Work Location Stataus</label>
                <select class="form-control" readonly>
                    <option value="${formData.workLocation}" selected>${$('#workLocation option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Fee</label>
                <select class="form-control" readonly>
                    <option value="${formData.salaryDateMonth}" selected>${$('#salaryDateMonth option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Salaray</label>
                <select class="form-control" readonly>
                    <option value="${formData.estSalary}" selected>${$('#estSalary option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Sector</label>
                <select class="form-control" readonly>
                    <option value="${formData.estSalary}" selected>${$('#sector option:selected').text()}</option>
                </select>
                </div>
                 <div class="form-group row">
                    <label>Provinsi</label>
                     <select class="form-control" readonly>
                         <option value="${formData.provinsi}" selected>${$('#provinsi option:selected').text()}</option>
                    </select>
                </div>
                <div class="form-group row">
                    <label>City</label>
                    <input type="text" class="form-control" value="${formData.lokasi}" readonly>
                </div>
                <div class="form-group row">
                    <label>Link Pendaftaran</label>
                    <input type="text" class="form-control" value="${formData.link_pendaftaran}" readonly>
                </div>
                <div class="form-group row">
                <label>Education</label>
                <select class="form-control" readonly>
                    <option value="${formData.education}" selected>${$('#education option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Experience Level</label>
                <select class="form-control" readonly>
                    <option value="${formData.experienceLevel}" selected>${$('#experienceLevel option:selected').text()}</option>
                </select>
                </div>

                <div class="form-group row">
                <label>Certification</label>
                    <input type="text" class="form-control" value="${formData.certification}" readonly>
                </div>

                <div class="form-group row">
                <label>Job Descripsi</label>
                    <textarea   class="form-control" placeholder="Type something and press Enter..." name="name" readonly>${stripHtmlTags(formData.jobdescripsi)}</textarea>
                </div>

                <div class="form-group row">
                <label>Skill Requirement</label>
                    <textarea   class="form-control" placeholder="Type something and press Enter..." name="name" readonly>${stripHtmlTags(formData.skillRequirement)}</textarea>
                </div>

                <div class="form-group row">
                    <label>Tanggal Mulai</label>
                    <input type="text" class="form-control" value="${formData.jadwal_mulai_tanggal}" readonly>
                </div>

                <div class="form-group row">
                    <label>Tanggal Selesai</label>
                    <input type="text" class="form-control" value="${formData.jadwal_selesai_tanggal}" readonly>
                </div>
            `);

            var fileInput = document.querySelectorAll('.photo');
            if (fileInput.length > 0) {
                var imageUrls = [];
                var filesLoaded = 0;

                fileInput.forEach(function(input) {
                    var files = input.files;
                    if (files.length > 0) {
                        for (var i = 0; i < files.length; i++) {
                            (function(file) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    imageUrls.push(e.target.result);
                                    filesLoaded++;
                                    if (filesLoaded === fileInput.length) {
                                        var imagesHtml = imageUrls.map((url, index) => `
                                        <div class="form-group row" style=" text-align: left;">
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

            $('#previewModal').modal('show');


        });
        $('select[name="jadwal_mulai_tanggal"], select[name="jadwal_mulai_bulan"], select[name="jadwal_mulai_tahun"]').select2();
        $('select[name="jadwal_selesai_tanggal"], select[name="jadwal_selesai_bulan"], select[name="jadwal_selesai_tahun"]').select2();
        // $('select[name="category"], select[name="jenis_sertifikasi"]').select2();
        $('select[name="employmentStatus"]').select2();
        $('select[name="workLocation"]').select2();
        $('select[name="salaryDateMonth"]').select2();
        $('select[name="estSalary"]').select2();
        $('select[name="sector"]').select2();
        $('select[name="education"]').select2();
        $('select[name="experienceLevel"]').select2();
        $('select[name="provinsi"]').select2();

        // Populate days
        for (let i = 1; i <= 31; i++) {
            $('select[name="jadwal_mulai_tanggal"], select[name="jadwal_selesai_tanggal"]').append(`<option value="${i}">${i}</option>`);
        }

        // Populate months
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        months.forEach((month, index) => {
            $('select[name="jadwal_mulai_bulan"], select[name="jadwal_selesai_bulan"]').append(`<option value="${index + 1}">${month}</option>`);
        });

        // Populate years
        const currentYear = new Date().getFullYear();
        for (let i = currentYear; i <= currentYear + 10; i++) {
            $('select[name="jadwal_mulai_tahun"], select[name="jadwal_selesai_tahun"]').append(`<option value="${i}">${i}</option>`);
        }

        $('#pending-btn').click(function() {
            submitFormWithStatus(2);
        });

        $('#publish-btn').click(function() {
            submitFormWithStatus(1);
        });

        function submitFormWithStatus(status) {
            var formData = new FormData($('#training-form')[0]);
            formData.append('status', status);

            // var fileInput = $('#item_files')[0];
            // for (var i = 0; i < fileInput.files.length; i++) {
            //     formData.append('item_files[]', fileInput.files[i]);
            // }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            showLoading(); // Show loading indicator

            $.ajax({
                url: '/public/update-job-vacancy',
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

                    // setTimeout(function() {
                    //     $('#errorModal').modal('hide');
                    //     location.reload();
                    // }, 2000);
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
    
    
    
    $(".descJobdesc").summernote({
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
    
    $(".descReqdesc").summernote({
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

    $(document).ready(function() {
            // Data tanggal
            var tanggalStartDate = '{{ $posted_date }}';

            // Pecah data tanggal menjadi tahun, bulan, dan hari
            var tanggalParts = tanggalStartDate.split("-");
            var tahun = tanggalParts[0];
            var bulan = tanggalParts[1];
            var hari = tanggalParts[2];

            // Isi nilai input tanggal mulai
            $('select[name="jadwal_mulai_tanggal"]').val(tanggalStartDate);

            // Buat opsi untuk tanggal, bulan, dan tahun
            for (var i = 1; i <= 31; i++) {
                $('select[name="jadwal_mulai_tanggal"]').append($('<option>', {
                    value: i,
                    text: i,
                    selected: (i == parseInt(hari)) // Set opsi terpilih jika nilai sesuai
                }));
            }
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];


            months.forEach((month, index) => {
                $('select[name="jadwal_mulai_bulan"]').append($('<option>', {
                    value: index + 1,
                    text: month,
                    selected: (index + 1 == parseInt(bulan)) // Set opsi terpilih jika nilai sesuai
                }));
            });

            var currentYear = new Date().getFullYear();
            for (var i = currentYear - 100; i <= currentYear + 20; i++) {
                $('select[name="jadwal_mulai_tahun"]').append($('<option>', {
                    value: i,
                    text: i,
                    selected: (i == parseInt(tahun)) // Set opsi terpilih jika nilai sesuai
                }));
            }

            var tanggalEndDate = '{{ $close_date }}';

            console.log(tanggalEndDate)

            // Pecah data tanggal menjadi tahun, bulan, dan hari
            var tanggalPartsEndate = tanggalEndDate.split("-");
            var tahunEndate = tanggalPartsEndate[0];
            var bulanEndate = tanggalPartsEndate[1];
            var hariEndate = tanggalPartsEndate[2];

            // Isi nilai input tanggal selesai
            $('select[name="jadwal_selesai_tanggal"]').val(tanggalEndDate);

            // Buat opsi untuk tanggal, bulan, dan tahun
            for (var i = 1; i <= 31; i++) {
                $('select[name="jadwal_selesai_tanggal"]').append($('<option>', {
                    value: i,
                    text: i,
                    selected: (i == parseInt(hariEndate)) // Set opsi terpilih jika nilai sesuai
                }));
            }
            const monthsend = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            monthsend.forEach((month, index) => {
                $('select[name="jadwal_selesai_bulan"]').append($('<option>', {
                    value: index + 1,
                    text: month,
                    selected: (index + 1 == parseInt(bulanEndate)) // Set opsi terpilih jika nilai sesuai
                }));
            });

            var currentYearEndate = new Date().getFullYear();
            for (var i = currentYearEndate - 100; i <= currentYearEndate + 20; i++) {
                $('select[name="jadwal_selesai_tahun"]').append($('<option>', {
                    value: i,
                    text: i,
                    selected: (i == parseInt(tahunEndate)) // Set opsi terpilih jika nilai sesuai
                }));
            }

            // Set nilai dropdown ke nilai yang ada
            // $('select[name="jadwal_mulai_tanggal"]').val(parseInt(hari));
            // $('select[name="jadwal_mulai_bulan"]').val(parseInt(bulan));
            // $('select[name="jadwal_mulai_tahun"]').val(parseInt(tahun));
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

    function parsingDataToModal(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('edit-traningcourse-detail',':id') }}";
        url = url.replace(":id", id);
        $.ajax({
            url: url,
            type: "GET",
            processData: false,
            contentType: false,
            success: function(data) {
                data = JSON.parse(data);
                if (data["status"] == "success") {
                    $("#edit-data-list-item").html(data["output"]);
                    $("#edit-item").modal("toggle");
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
</script>
@endsection
