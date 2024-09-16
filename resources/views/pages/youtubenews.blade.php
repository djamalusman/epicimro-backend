@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                        <div class="col-0">
                                            
                                              <a type="button" href="{{ route('get-view-store-video',  ['id' => base64_encode($menus->id)])}}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                              </a>
                                        </div>
                                        <div class="col-1">
                                            
                                            <a type="button" id="filterButton" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="side-list-visi-misi" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Category</th>
                                                    <th>Tanggal</th>
                                                    <th>Video</th>
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
                            <label for="titleSelect">Nama</label>
                            <select id="titleSelect" class="form-control">
                                <!-- Options will be appended here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="categorySelect">Category</label>
                            <select id="categorySelect" class="form-control">
                                <!-- Options will be appended here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="menuSelect">Menu</label>
                            <select id="menuSelect" class="form-control">
                                <!-- Options will be appended here -->
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
                            Video
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
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
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
    // Initialize dropdown data and table data on page load
    loadDropdownData();
    loadTableData();

    // Show modal on filter button click
    $('#filterButton').on('click', function() {
        $('#filterModal').modal('show');
    });

    // Handle apply filter button click
    $('#applyFilter').on('click', function() {
        applyFilterAndReset();
    });

    // Reset select options when modal is hidden
    $('#filterModal').on('hidden.bs.modal', function () {
        resetSelectOptions();
    });
    
    // Function to apply filter and reset modal
    function applyFilterAndReset() {
        var title = $('#titleSelect').val();
        var category = $('#categorySelect').val();
        var menu = $('#menuSelect').val();

        // Load table data based on selected filter
        loadTableData({
            title: title,
            category: category,
            menu: menu,
        });

        $('#filterModal').modal('hide'); // Hide the modal
        resetSelectOptions(); // Reset select options to "All"
    }

    // Function to reset select options to "All"
    function resetSelectOptions() {
        $('#titleSelect').val('');
        $('#categorySelect').val('');
        $('#menuSelect').val('');
    }

    // Function to populate dropdown list
    function loadDropdownData() {
        $.ajax({
            url: '/public/get-filters-video', // URL endpoint to fetch data
            type: 'GET',
            success: function(data) {
                var titleSelect = $('#titleSelect');
                var categorySelect = $('#categorySelect');
                var menuSelect = $('#menuSelect');

                titleSelect.empty(); // Clear existing items
                categorySelect.empty(); // Clear existing items
                menuSelect.empty(); // Clear existing items

                // Append "All" option to the select elements
                titleSelect.append('<option value="">All</option>');
                categorySelect.append('<option value="">All</option>');
                menuSelect.append('<option value="">All</option>');

                // Using Sets to store unique items
                var uniqueTitle = new Set();
                var uniqueCategory = new Set();
                var uniqueMenu = new Set();

                $.each(data, function(key, value) {
                    uniqueTitle.add(value.nama);
                    uniqueCategory.add(value.category);
                    uniqueMenu.add(value.menu_name);
                });

                // Function to append unique items to the select elements
                function appendUniqueItems(set, selectElement) {
                    set.forEach(function(item) {
                        selectElement.append('<option value="' + escapeHtml(item) + '">' + escapeHtml(item) + '</option>');
                    });
                }

                // Append unique items for each attribute to the select elements
                appendUniqueItems(uniqueTitle, titleSelect);
                appendUniqueItems(uniqueCategory, categorySelect);
                appendUniqueItems(uniqueMenu, menuSelect);
            },
            error: function() {
                console.log("Error fetching data.");
            }
        });
    }

    // Function to load table data
    function loadTableData(filterValues) {
        $.ajax({
            url: '/public/get-data-video',
            type: 'GET',
            data: filterValues,
            success: function(data) {
                var table = $('#side-list-visi-misi').DataTable();
                table.clear().draw();

                $.each(data, function(key, value) {
                    var statusBadge = 
                            value.status == '1' ? '<span class="badge badge-primary">Publish</span>' :
                            value.status == '2' ? '<span class="badge badge-warning">Pending</span>' :
                            value.status == '3' ? '<span class="badge badge-secondary">Non Publish</span>' :
                            value.status == '4' ? '<span class="badge badge-danger">Kadaluarsa</span>' : '';
                    var viewvideo=`<span type="button" class="badge badge-primary"  onclick="parsingDataToModal('${value.id}')">View video</span>`;
                    table.row.add([
                        key + 1,
                            value.nama,
                            value.category,
                            formatDate(value.updated_at),
                            viewvideo,
                            statusBadge,
                            `
                            <div class="container mt-0">
                                <div class="row">
                                    <div class="ml-auto d-flex">
                                        <div class="col text-right mb-3">
                                            <a type="button" style="color:Green" href="/public/edit-video/${btoa(value.id)}" title="Edit Course">
                                                <i class="fa fa-bars"></i>
                                            </a>
                                        </div>
                                        <div class="col text-right mb-3">
                                            <a type="button" href="#" style="color:red" onclick="stopPrompt('${value.id}')" title="Stop Course">
                                                <i class="fa fa-stop"></i>
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

    // Initialize DataTable
    $('#side-list-visi-misi').DataTable();
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

    function parsingDataToModal(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('view-video-popup',':id') }}";
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

    function saveSelectedValue() {
        var selectElement = document.getElementById('sideLists');
        var selectedValue = selectElement.options[selectElement.selectedIndex].value;
        document.getElementById('side_list1').value = selectedValue;
        document.getElementById('side_list_en1').value = selectedValue;
    }
 

</script>
@endsection