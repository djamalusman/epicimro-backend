@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


<style>
    /* Style the input field */
    #myInput {
      padding: 20px;
      margin-top: -6px;
      border: 0;
      border-radius: 0;
      background: #f1f1f1;
    }
    .dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}

.list-group-item {
    cursor: pointer;
}

    </style>
@endsection


@section('content')
<div class="content-wrapper">
    <section class="content p-4">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h2>{{explode('|',$title_page)[1] }}</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                        <li class="breadcrumb-item active">{{explode('|',$title_page)[1]}}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Konten {{explode('|',$title_page)[1]}}</h3>

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

                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Side List {{explode('|',$title_page)[1]}}</h3>
                                </div>
                                <div class="card-body">
                                        <div class="row">
                                                  <div class="col-1">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                      </button>
                                                    <a type="button" id="filterButton" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i></a>
                                                  </div>

                                                  <div class="col-1">
                                                  <!--<a type="button" id="filterButton" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i></a>-->
                                                  {{-- <a href="{{ route('export-sertifikat')}}" type="button" id="filterButton" class="btn btn-primary"><i class="fa fa-download"></i></i></a> --}}
                                                  </div>


                                        </div>

                                    <br>
                                    <div class="table-responsive">
                                        <table id="side-list-visi-misi" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Peserta</th>
                                                    <th>Email</th>

                                                    <th>Nama Training</th>
                                                    <th>No Sertifikat</th>
                                                    <th>Tanggal Training</th>
                                                    <th>Tanggal Status Sertifikat</th>
                                                    <th>Status Sertifikat</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <!-- Data akan diisi melalui AJAX -->
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center"> <!-- Kelas text-center ditambahkan di sini -->
                        <a type="button" href="{{ route('get-view-store-sertifikat',  ['id' => base64_encode($menus->id)])}}" class="btn btn-primary">Add by Form</a>
                        <a type="button" href="{{ route('get-view-excel-sertifikat',  ['id' => base64_encode($menus->id)])}}" class="btn btn-info">Add by Excel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="titleSelect">Nama Peserta</label>
                            <select id="titleSelect" class="form-control">
                                <!-- Options will be appended here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nosertSelect">No Sertifikat</label>
                            <select id="nosertSelect" class="form-control">
                                <!-- Options will be appended here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="namatrainigSelect">Nama Training</label>
                            <select id="namatrainigSelect" class="form-control">
                                <!-- Options will be appended here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="statussertifikat">Status Sertifikat</label>
                            <select id="statussertifikatSelect" class="form-control">
                                <option value="">All</option>
                                <option value="1">Permanent</option>
                                <option value="0">Active</option>
                                <option value="2">Kadaluarsa</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="applyFilter">Apply Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-item" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="edit-item-label" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-item-label">
                            Image
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-data-list-item">
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
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') {
        return '';
    }
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatDateRange(postedDateStr, closeDateStr) {
    if (!postedDateStr || !closeDateStr) return '';

    var postedParts = postedDateStr.split(' ')[0].split('-');
    var closeParts = closeDateStr.split(' ')[0].split('-');

    var startDate = new Date(postedParts[0], postedParts[1] - 1, postedParts[2]);
    var endDate = new Date(closeParts[0], closeParts[1] - 1, closeParts[2]);

    var startDay = startDate.getDate();
    var endDay = endDate.getDate();
    var month = startDate.toLocaleString('default', { month: 'long' });

    return startDay + '-' + endDay + ' ' + month;
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

$(document).ready(function() {
    // Initialize DataTable with Excel export button
    // Initialize DataTable with hidden search box and Excel export button
    var table = $('#side-list-visi-misi').DataTable({
        dom: 'Bfrtip', // B: Buttons, f: search input, r: processing, t: table, i: table info, p: pagination
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Data Export Certificate',
                text: 'Export to Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6,7] // Exclude Status and Action columns
                }
            }
        ],
        dom: 'Brtip' // This removes the search input by excluding 'f' (the filter/search input box)
    });


    // Function to load table data based on filters
    function loadTableData(filterValues) {
        $.ajax({
            url: '/public/get-data-sertifikat',
            type: 'GET',
            data: filterValues,
            success: function(data) {
                table.clear().draw(); // Clear existing data

                $.each(data, function(key, value) {
                    var statusBadge = value.status == '1' ? '<span class="badge badge-primary">Publish</span>' :
                                      value.status == '2' ? '<span class="badge badge-warning">Pending</span>' :
                                      value.status == '3' ? '<span class="badge badge-secondary">Non Publish</span>' :
                                      value.status == '4' ? '<span class="badge badge-danger">Kadaluarsa</span>' : '';

                    var status_permanent = value.tanggal_kadauarsa_srt && new Date(value.tanggal_kadauarsa_srt.replace(' ', 'T')) < Date.now() ?
                                           '<span class="badge badge-danger">Kadaluarsa</span>' :
                                           value.permanent_srt == '0' ? '<span class="badge badge-success">Aktif</span>' :
                                           value.permanent_srt == '1' ? '<span class="badge badge-primary">Permanent</span>' : '';

                    table.row.add([
                        key + 1,
                        value.nama_peserta,
                        value.email,
                        value.nama_training,
                        value.no_sertifikat,
                        formatDate(value.tanggal_training),
                        formatDate(value.tanggal_kadauarsa_srt),
                        status_permanent,
                        statusBadge,
                        `
                        <div class="container mt-0">
                            <div class="row">
                                <div class="ml-auto d-flex">
                                    <div class="col text-right mb-3">
                                        <a type="button" style="color:Green" href="/public/edit-sertifikat/${btoa(value.id)}" title="Edit Banner">
                                            <i class="fa fa-bars"></i>
                                        </a>
                                    </div>
                                    <div class="col text-right mb-3">
                                        <a type="button" href="#" style="color:red" onclick="removeSertifikat('${value.id}')" title="Delete Sertifikat">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `
                    ]).draw(false);
                });
            },
            error: function() {
                console.log("Error fetching table data.");
            }
        });
    }

    // Call loadTableData when the page loads
    loadTableData();

    // Filter modal logic
    $('#filterButton').on('click', function() {
        $('#filterModal').modal('show');
    });

    $('#applyFilter').on('click', function() {
        applyFilterAndReset();
    });

    $('#filterModal').on('hidden.bs.modal', function () {
        resetSelectOptions();
    });

    function applyFilterAndReset() {
        var title = $('#titleSelect').val();
        var nosert = $('#nosertSelect').val();
        var namatrainig = $('#namatrainigSelect').val();
        var statussertifikat = $('#statussertifikatSelect').val();

        // Pass the filter values to loadTableData
        loadTableData({
            title: title,
            nosert: nosert,
            namatrainig: namatrainig,
            statussertifikat: statussertifikat,
        });

        // Hide the filter modal and reset the selections
        $('#filterModal').modal('hide');
        resetSelectOptions();
    }

    function resetSelectOptions() {
        $('#titleSelect').val('');
        $('#nosertSelect').val('');
        $('#namatrainigSelect').val('');
        $('#statussertifikatSelect').val('');
    }

    function loadDropdownData() {
        $.ajax({
            url: '/public/get-filters-sertifikat',
            type: 'GET',
            success: function(data) {
                var titleSelect = $('#titleSelect');
                titleSelect.empty();
                titleSelect.append('<option value="">All</option>');

                var nosertSelect = $('#nosertSelect');
                nosertSelect.empty();
                nosertSelect.append('<option value="">All</option>');

                var namatrainigSelect = $('#namatrainigSelect');
                namatrainigSelect.empty();
                namatrainigSelect.append('<option value="">All</option>');

                // Unique Title (Nama Peserta)
                var uniqueTitle = new Set();
                $.each(data, function(key, value) {
                    uniqueTitle.add(value.nama_peserta);
                });
                uniqueTitle.forEach(function(item) {
                    titleSelect.append('<option value="' + escapeHtml(item) + '">' + escapeHtml(item) + '</option>');
                });

                // Unique No Sertifikat
                var uniqueNosert = new Set();
                $.each(data, function(key, value) {
                    uniqueNosert.add(value.no_sertifikat);
                });
                uniqueNosert.forEach(function(item) {
                    nosertSelect.append('<option value="' + escapeHtml(item) + '">' + escapeHtml(item) + '</option>');
                });

                // Unique Nama Training
                var uniqueNamatrainig = new Set();
                $.each(data, function(key, value) {
                    uniqueNamatrainig.add(value.nama_training);
                });
                uniqueNamatrainig.forEach(function(item) {
                    namatrainigSelect.append('<option value="' + escapeHtml(item) + '">' + escapeHtml(item) + '</option>');
                });
            },
            error: function() {
                console.log("Error fetching data.");
            }
        });
    }

    loadDropdownData();
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

    function saveSelectedValue() {
        var selectElement = document.getElementById('sideLists');
        var selectedValue = selectElement.options[selectElement.selectedIndex].value;
        document.getElementById('side_list1').value = selectedValue;
        document.getElementById('side_list_en1').value = selectedValue;
    }

    function parsingDataToModal(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('view-image-banner',':id') }}";
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

    function removeSertifikat(id) {
        var url = "{{ route('delete-sertifikat',':id') }}";
        url = url.replace(":id", id);

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        Swal.fire({
            title: "Hapus data?",
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

</script>
@endsection
