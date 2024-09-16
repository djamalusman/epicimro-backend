@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
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
                <form method="POST" action="{{ route('store-page-header') }}" enctype="multipart/form-data" id="background-form">
                    @csrf
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="picture">Update Background Header</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="item_file" class="custom-file-input" id="picture-visi-misi">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                        <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                        <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                        <div class="mt-2">
                                            <img src="{{ asset('/') }}storage/{{ $dataTk->item_file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-visi-misi" width="140px">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="titleS1">Title | ID</label>
                                        <input type="text" name="title" class="form-control" id="titleS1" value="{{ $dataTk->item_title ?? ''}}">
                                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="titleS1">Title | EN</label>
                                        <input type="text" name="title_en" class="form-control" id="title_enS1" value="{{ $dataTk->item_title_en  ?? '' }}">
                                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="validatePrompt('background-form')" class="btn btn-danger start"> Update </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="content p-4">
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
                <form method="POST" action="{{ route('store-penghargaan-dan-sertifikat') }}" enctype="multipart/form-data" id="slider1form">
                    <input type="hidden" name='id_content' value="{{base64_encode($dataTk->id)}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="title1">Year</label>
                                <input type="text" name="item_extras" class="form-control" id="datepicker">
                                <small id="item_extras_error" class="item_extras_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="titleS1">Title | ID</label>
                                <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS">
                                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="titleS1">Title | EN</label>
                                <input type="text" name="title_en" class="form-control" id="title_enS1" placeholder="POWER TO PROGRESS">
                                <small id="title_en_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="descriptionS1">Description | ID</label>
                                <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="descriptionS1">Description | EN</label>
                                <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5"></textarea>
                                <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="picture">File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="item_file" class="custom-file-input" id="picture">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="picture_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="" alt="simulasi" class="img-thumbnail simulasi-gambar-picture" width="140px">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="urlS1">URL</label>
                            <input type="text" name="url" class="form-control" id="urlS1">
                            <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('slider1form')" class="btn btn-danger start"> Add </button>
                </form>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Data {{explode('|',$title_page)[1]}}</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="perhargaan-sertifikat-tbl" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>File Preview</th>
                                                    <th>Year</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataMenu as $key => $dm)
                                                <tr>
                                                    <td>{{ $dm->order }}</td>
                                                    <td><img src="{{ asset('/') }}storage/{{ $dm->file }}" width="75px" alt="" srcset=""></td>
                                                    <td>{{ $dm->extras }}</td>
                                                    <td><?= $dm->title ?></td>
                                                    <td><?= $dm->description ?></td>
                                                    <td>
                                                        <button type="button" onclick="parsingDataToModal('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                        <button type="button" onclick="deletePrompt('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                    </td>
                                                </tr>
                                                @endforeach
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(".desc").summernote({
        height: 250,
    });
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
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

        var url = "{{ route('edit-penghargaan-dan-sertifikat',':id') }}";
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

    $(function() {
        $('#perhargaan-sertifikat-tbl').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection