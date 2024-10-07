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
                                            <input type="text"class="col-md-2 form-control" readonly value="Nama Perserta">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="participants_name" name="participants_name">
                                            </div>
                                        </div>
                                        <!-- Email -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control" readonly value="Email Peserta">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="email" name="email">
                                            </div>
                                        </div>
                                        <!-- Nama Training -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control" readonly value="Nama Training">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="nama_training" name="nama_training">
                                            </div>
                                        </div>


                                        <!-- Tanggal Training -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control"  readonly value="Tanggal Training">
                                            <div class="col-md-1"> </div>

                                            <div class="col-md-7">
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


                                        <!-- No Sertifikat -->
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control"  readonly value="No Sertifikat">
                                            <div class="col-md-1"> </div>

                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <input type="number" maxlength="6" pattern="\d{6}" class="form-control" id="no_urut_srt" placeholder="No Urut" name="no_urut_srt">
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" maxlength="3" pattern="[A-Za-z]{3}" class="form-control" id="kode_category_training_srt" placeholder="Kode Training" name="kode_category_training_srt">
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" maxlength="6" pattern="[A-Za-z]{6}" class="form-control" id="kode_srt" placeholder="Kode Sertifikasi" name="kode_srt">
                                                    </div>
                                                    <div class="col-2">
                                                        <select class="form-control" id="tahun_training_srt" name="tahun_training_srt">
                                                            <option>Tahun</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control"  readonly value="Kadaluarsa sertifikat">
                                            <div class="col-md-1"> </div>
                                            <div class="col-md-3" >
                                                    <select name="status_kadaluarsa" class="form-control" id="status_kadaluarsa">
                                                        <option value="">Pilih Tanggal Kadaluarsa Sertifikat</option>
                                                        <option value="1">Permanent</option>
                                                        <option value="0">Tidak Permanent</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <input type="text"class="col-md-2 form-control"  hidden readonly value="Tanggal Kadaluarsa sertifikat">
                                            <div class="col-md-3"> </div>
                                            <div class="col-md-7" id="jadwal-container" style="display: none;">
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
 // Function to validate input by ID
       // Fungsi untuk validasi tambahan menggunakan JavaScript
       document.getElementById('status_kadaluarsa').addEventListener('change', function() {
        var jadwalContainer = document.getElementById('jadwal-container');
        if (this.value == "0") {
            jadwalContainer.style.display = 'block'; // Tampilkan elemen jika 'Tidak Permanent'
        } else {
            jadwalContainer.style.display = 'none'; // Sembunyikan elemen jika 'Permanent'
        }
    });
    $('#status_kadaluarsa').on('change', function() {
    var jadwalContainer = $('#jadwal-container');
    if ($(this).val() == "0") {
        jadwalContainer.show(); // Tampilkan elemen
    } else {
        jadwalContainer.hide(); // Sembunyikan elemen
    }
});
       function validateInput(event) {
        const inputField = event.target;
        const inputId = inputField.id;
        const value = inputField.value;

        let isValid = true;
        let errorMessage = '';

        switch (inputId) {
            case 'no_urut_srt':
                if (!/^\d{1,6}$/.test(value)) {
                    isValid = false;
                    errorMessage = 'No Urut harus berupa angka dan maksimal 6 digit.';
                }
                break;
                case 'kode_category_training_srt':
                    if (!/^[A-Za-z0-9]{1,3}$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Kode Category Training harus berisi huruf atau angka dan maksimal 3 karakter.';
                    }
                    break;
                case 'kode_srt':
                    if (!/^[A-Za-z0-9]{1,6}$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Kode Sertifikasi harus berisi huruf atau angka dan maksimal 6 karakter.';
                    }
                    break;



            default:
                break;
        }

        if (!isValid) {
            alert(errorMessage);
            inputField.value = '';
            inputField.focus();
        }
    }

    // Menambahkan event listener untuk setiap input
    document.getElementById('no_urut_srt').addEventListener('input', validateInput);
    document.getElementById('kode_category_training_srt').addEventListener('input', validateInput);
    document.getElementById('kode_srt').addEventListener('input', validateInput);
    function addInput(button) {
        var inputGroup = $(button).closest('.input-group');
        var newInputGroup = inputGroup.clone();
        newInputGroup.find('input').val('');
        newInputGroup.find('.btn-add').remove();
        newInputGroup.append('<button type="button" class="btn btn-danger btn-remove" onclick="removeInput(this)">-</button>');
        newInputGroup.addClass('new-input-group'); // Add class for spacing
        inputGroup.after(newInputGroup);
    }

    function removeInput(button) {
        $(button).closest('.input-group').remove();
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';

        var parts = dateStr.split(' ')[0].split('-');
        var date = new Date(parts[0], parts[1] - 1, parts[2]);

        var day = date.getDate();
        var month = date.toLocaleString('default', { month: 'long' });
        var year = date.getFullYear();

        return day + ' ' + month + ' ' + year;
    }

    $('#preview-btn').click(function() {
        var categoryText = $('#category option:selected').text();
        var jenis_sertifikasiText = $('#jenis_sertifikasi option:selected').text();
        var typeText = $('#type option:selected').text();
        var provinsiText = $('#provinsi option:selected').text();
        var formData = {
            companyname: $('#company_name').val(),
            title: $('#nama_training').val(),
            yotube: $('#yotube').val(),
            category: categoryText,
            jenis_sertifikasi: jenis_sertifikasiText,
            training_duration: $('#training_duration').val(),
            persyaratan: [],
            materi_training: [],
            fasilitas: [],
            jadwal_mulai_tanggal: formatDate(`${$('#jadwal_mulai_tahun').val()}-${$('#jadwal_mulai_bulan').val()}-${$('#jadwal_mulai_tanggal').val()}`),
            jadwal_selesai_tanggal: formatDate(`${$('#jadwal_selesai_tahun').val()}-${$('#jadwal_selesai_bulan').val()}-${$('#jadwal_selesai_tanggal').val()}`),
            type: typeText,
            provinsi: provinsiText,
            lokasi: $('#lokasi').val(),
            link_pendaftaran: $('#link_pendaftaran').val(),
            status: 3
        };

        // Collect all dynamic inputs
        $('.persyaratan').each(function() {
            formData.persyaratan.push($(this).val());
        });
        $('.materi_training').each(function() {
            formData.materi_training.push($(this).val());
        });
        $('.fasilitas').each(function() {
            formData.fasilitas.push($(this).val());
        });

        $('#modal-content').html(`
            <div class="form-group row">
                <label>Nama Perusahaan</label>
                <input type="text" class="form-control" value="${formData.companyname}" readonly>
            </div>
            <div class="form-group row">
                <label>Nama Training</label>
                <input type="text" class="form-control" value="${formData.title}" readonly>
            </div>
            <div class="form-group row">
                <label>Yotube</label>
                <input type="text" class="form-control" value="${formData.yotube}" readonly>
            </div>
             <div class="form-group row">
                <label>Category</label>
                <input type="text" class="form-control" value="${formData.category}" readonly>
            </div>
            <div class="form-group row">
                <label>Jenis Sertifikasi</label>
                <input type="text" class="form-control" value="${formData.jenis_sertifikasi}" readonly>
            </div>
            <div class="form-group row">
                <label>Durasi Training</label>
                <input type="text" class="form-control" value="${formData.training_duration}" readonly>
            </div>
            <div class="form-group row">
                <label>Persyaratan</label>
                ${formData.persyaratan.map(p => `<input type="text" class="form-control" value="${p}" readonly>`).join('')}
            </div>

            <div class="form-group row">
                <label>Tanggal Mulai Training</label>
                <input type="text" class="form-control" value="${formData.jadwal_mulai_tanggal}" readonly>
            </div>


            <div class="form-group row">
                <label>Tanggal Selesai Training</label>
                <input type="text" class="form-control" value="${formData.jadwal_selesai_tanggal}" readonly>
            </div>

            <div class="form-group row">
                <label>Materi Training</label>
                ${formData.materi_training.map(m => `<input type="text" class="form-control" value="${m}" readonly>`).join('')}
            </div>
            <div class="form-group row">
                <label>Fasilitas</label>
                ${formData.fasilitas.map(f => `<input type="text" class="form-control" value="${f}" readonly>`).join('')}
            </div>

            <div class="form-group row">
                <label>Type</label>
                <input type="text" class="form-control" value="${formData.type}" readonly>
            </div>

            <div class="form-group row">
                <label>Provinsi</label>
                <input type="text" class="form-control" value="${formData.provinsi}" readonly>
            </div>

            <div class="form-group row">
                <label>Lokasi</label>
                <input type="text" class="form-control" value="${formData.lokasi}" readonly>
            </div>

            <div class="form-group row">
                <label>Link Pendaftaran</label>
                <input type="text" class="form-control" value="${formData.link_pendaftaran}" readonly>
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
        // Initialize Select2
        $('select[name="jadwal_mulai_tanggal"], select[name="jadwal_mulai_bulan"], select[name="jadwal_mulai_tahun"]').select2();
        $('select[name="jadwal_selesai_tanggal"], select[name="jadwal_selesai_bulan"], select[name="jadwal_selesai_tahun"]').select2();
        $('select[name="category"], select[name="jenis_sertifikasi"]').select2();
        $('select[name="lokasi"]').select2();
        $('select[name="provinsi"]').select2();
        $('select[name="type"]').select2();
        $('select[name="tahun_training_srt"]').select2();

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

        for (let i = currentYear; i <= currentYear + 10; i++) {
            $('select[name="tahun_training_srt"]').append(`<option value="${i}">${i}</option>`);
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



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            showLoading(); // Show loading indicator

            $.ajax({
                url: '/public/store-sertifikat',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    hideLoading(); // Hide loading indicator
                    data = JSON.parse(data);
                    console.log(data);
                    if (data["status"] == "success") {
                        $('#successModal').modal('show');

                        setTimeout(function() {
                            $('#successModal').modal('hide');
                            location.reload();
                        }, 2000);

                        $('#previewModal').modal('hide');
                        $('#training-form')[0].reset();
                    } else {
                        // Jika status adalah 'failed', tampilkan pesan error
                        var errorMessage = 'Terjadi kesalahan. Nomor Sertifikat sudah ada.';
                        $('#error-message').text(errorMessage); // Mengambil pesan dari respons
                        $('#errorModal').modal('show'); // Tampilkan modal error
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading(); // Hide loading indicator
                    console.log(error);
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                    $('#error-message').text(errorMessage);
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
