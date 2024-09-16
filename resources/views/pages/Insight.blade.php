@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
@endsection


@section('content')
<div class="content-wrapper">
    
    <section class="content p-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">List {{explode('|',$title_page)[1]}}</h3>

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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control" name="type" onchange="showInputFields(this.value)">
                                            <option value="">-</option>
                                            @foreach($typeLaporan as $value)
                                               
                                                    <option value="{{$value->tipe_kode}}">{{$value->tipe_name}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('store-insight') }}" enctype="multipart/form-data" id="store-insight-list-form-Youtube">
                                    @csrf
                                        <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                                        <input type="hidden" name="idSP" value="{{base64_encode($dataContent->id ?? '') }}">
                                        <input type="hidden" name="id_content_order" value="1">
                                        <div id="commonFields" class="row" style="display:none;">
                                            <input type="hidden" class="form-control" id="selectedValueInputYotube" name="type">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="picture">Input Gambar</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="item_file" class="custom-file-input" id="picture-visi-misi">
                                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb {{ $dataItem->item_file ?? '' }}</small>
                                                    <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="side_list1">Title | ID</label>
                                                    <input type="text" name="title" class="form-control" id="title" placeholder="{{explode('|',$title_page)[1]}}">
                                                    <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title1">Title | EN</label>
                                                    <input type="text" name="title_en" class="form-control" id="title_en" placeholder="{{explode('|',$title_page)[1]}}">
                                                    <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="titleS1">Year</label>
                                                    <input type="text" name="year" class="form-control" id="yearDate">
                                                    <small id="year_error" class="year_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title1">URL</label>
                                                    <input type="text" name="url1" class="form-control" id="url1" placeholder="{{explode('|',$title_page)[1]}}">
                                                    <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="descriptionS1">Deskripsi Full | ID</label>
                                                    <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                                    <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="descriptionS1">Full Description | EN</label>
                                                    <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5"></textarea>
                                                    <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="button" onclick="validatePrompt('store-insight-list-form-Youtube')" class="btn btn-danger start"> Add </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                </form>

                                <form method="POST" action="{{ route('store-insight') }}" enctype="multipart/form-data" id="store-insight-list-form-Article">
                                    @csrf
                                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                                    <input type="hidden" name="idSP" value="{{base64_encode($dataContent->id ?? '') }}">
                                    <input type="hidden" name="id_content_order" value="1">
                                    <div id="articleFields" class="row" style="display:none;">
                                        <input type="hidden" class="form-control" id="selectedValueInputPicture" name="type">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="picture">Input Gambar</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="item_file" class="custom-file-input" id="picture-visi-misi">
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                </div>
                                                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb {{ $dataItem->item_file ?? '' }}</small>
                                                <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="side_list1">Title | ID</label>
                                                <input type="text" name="title" class="form-control" id="title1" placeholder="{{explode('|',$title_page)[1]}}">
                                                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title1">Title | EN</label>
                                                <input type="text" name="title_en" class="form-control" id="title_en1" placeholder="{{explode('|',$title_page)[1]}}">
                                                <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="titleS1">Year</label>
                                                <input type="text" name="year" class="form-control" id="yearDates">
                                                <small id="year_error" class="year_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title1">URL</label>
                                                <input type="text" name="url1" class="form-control" id="url1" placeholder="{{explode('|',$title_page)[1]}}">
                                                <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descriptionS1">Deskripsi Full | ID</label>
                                                <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                                <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descriptionS1">Full Description | EN</label>
                                                <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5"></textarea>
                                                <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" onclick="validatePrompt('store-insight-list-form-Article')" class="btn btn-danger start"> Add </button>
                                            </div>
                                        </div>
                                        
                                    </div>

                                </form>
                            </div>
                            
                        </div>
                    </div>
                
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Side List {{explode('|',$title_page)[1]}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="rekrutmen-tbl" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Thumbnail</th>
                                                    <th>Expiration Date</th>
                                                    <th>Title | ID</th>
                                                    <th>City</th>
                                                    <th>Division</th>
                                                    <th>Job Tipe</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
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
    </section>
</div>

<div class="modal fade" id="edit-item" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="edit-item-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-item-label">
                    Edit Data
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
@endsection

@section('script')
<script src="{{ asset('/') }}dist/js/main.js"></script>
<script src="{{ asset('/') }}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<script>
    $("#yearDate").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });
    $("#yearDates").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });
    $(".desc").summernote({
        height: 250,
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

        var url = "{{ route('edit-list-detail-rekrutmen',':id') }}";
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

    function showInputFields(value) {
            $('#commonFields').hide();
            $('#articleFields').hide();
           
            if (value === '11') {
                $('#commonFields').show();
                var inputField = document.getElementById('selectedValueInputYotube');
                inputField.value = value;
            } else if (value === '12') {
                $('#articleFields').show(); // Assuming you want both fields to show for 'articleFields'
                var inputField = document.getElementById('selectedValueInputPicture');
                inputField.value = value;
            }
            else
            {
                $('#commonFields').hide();
                $('#articleFields').hide();
            }
            
    }

    function deletePrompt(id, isDelete = '') {
        var txt = '';
        var url = "{{ route('delete-rekrutmen-list',['id' => ':id' ,'param' => ':param' ]) }}";
        url = url.replace(":id", id);
        url = url.replace(":param", isDelete);


        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        if(isDelete){
            txt = "Enable/Disable ";
        }else{
            txt = "Delete ";
        }

        Swal.fire({
            title: txt + "this data?",
            showCancelButton: true,
            confirmButtonText: txt,
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

    $(function() {
        $('#rekrutmen-tbl').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            // "responsive": true,
        });
    });
    $("#startDate").datepicker({
        // format: "dd-mm-yyyy",
        startDate: new Date(),
    });

    $('#startDate').change(function (){
        $("#endDate").datepicker("destroy");
        $('#endDate').datepicker({
            // format: "dd-mm-yyyy",
            startDate: new Date($('#startDate').val())
        });
    });

    
</script>
@endsection