@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
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
                <form method="POST" action="{{ route('store-company-overview') }}" enctype="multipart/form-data" id="history-form">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="idSide" value="{{base64_encode($dataTk->id_side)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <div class="form-group">
                        <label for="picture">Update Background Header</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="item_file" class="custom-file-input" id="picture-sekilas-perusahaan">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                        <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                        <div class="mt-2">
                            <img src="{{ asset('/') }}storage/{{ $dataTk->item_file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-sekilas-perusahaan" width="140px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Update Side List Title | ID</label>
                                        <input type="text" name="side_list" class="form-control" id="side_list1" placeholder="{{explode('|',$title_page)[1]}}" value="{{ $dataTk->side_list ?? '' }}">
                                        <small id="side_list_error" class="side_list_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Update Side List Title | EN</label>
                                        <input type="text" name="side_list_en" class="form-control" id="side_list_en1" placeholder="{{explode('|',$title_page)[1]}}" value="{{ $dataTk->side_list_en ?? '' }}">
                                        <small id="side_list_en_error" class="side_list_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title1">Title | ID</label>
                                        <input type="text" name="title" class="form-control" id="title1" placeholder="{{explode('|',$title_page)[1]}}" value="{{ $dataTk->item_title ?? '' }}">
                                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title2">Title | EN</label>
                                        <input type="text" name="title_en" class="form-control" id="title_en1" placeholder="{{explode('|',$title_page)[1]}}" value="{{ $dataTk->item_title_en ?? '' }}">
                                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionS1">Description | ID</label>
                                        <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"><?php echo $dataTk->item_body ?? '' ?> </textarea>
                                        <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionS1">Description | EN</label>
                                        <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5"><?php echo $dataTk->item_body_en ?? '' ?></textarea>
                                        <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="validatePrompt('history-form')" class="btn btn-danger start"> Update </button>
                        </div>
                    </div>
                </form>
                @if(isset($dataTk))
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Side List {{explode('|',$title_page)[1]}}</h3>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#edit-item">
                                        Add Detail
                                    </button>
                                    <div class="table-responsive">
                                        <table id="side-list-visi-misi" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Year</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($dataTkDetail))
                                                @foreach($dataTkDetail as $key => $dm)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $dm->extras }}</td>
                                                    <td><?php echo $dm->description ?></td>
                                                    <td>
                                                        <button type="button" onclick="parsingDataToModal('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                        <button type="button" onclick="deletePrompt('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <form method="POST" action="{{ route('store-history') }}" enctype="multipart/form-data" id="history-detail-form">
                                        @csrf
                                        <input type="hidden" name="id_content" value="{{base64_encode($dataTk->id_side ?? '') }}">
                                        <div class="form-group">
                                            <label for="title1">Year</label>
                                            <input type="text" name="title_id" class="form-control" id="datepicker">
                                            <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="descriptionS1">Description | ID</label>
                                                    <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                                    <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="descriptionS1">Description | EN</label>
                                                    <textarea class="form-control desc" name="description_en" id="descriptionS1" cols="20" rows="5"></textarea>
                                                    <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="validatePrompt('history-detail-form')" class="btn btn-danger start"> Add </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });
    $(".desc").summernote({
        height: 250,
    });
    $(function() {
        $('#side-list-visi-misi').DataTable({
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

        var url = "{{ route('edit-list-detail-tk',':id') }}";
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
</script>
@endsection