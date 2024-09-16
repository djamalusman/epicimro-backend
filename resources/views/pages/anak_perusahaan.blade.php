@extends('../layouts.mainv2')

@section('headers')
@endsection


@section('content')
<div class="content-wrapper">
    <section class="content p-4">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h2><?=explode('|',$title_page)[1] ?></h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                        <li class="breadcrumb-item active"><?=explode('|',$title_page)[1]?></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Konten Header <?=explode('|',$title_page)[1]?></h3>

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
                <form method="POST" action="{{ route('store-page-header') }}" enctype="multipart/form-data" id="company-form">
                    @csrf
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="picture">Update Background Header</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="item_file" class="custom-file-input" id="picture-anak-perusahaan">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                    <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                    <div class="mt-2">
                                        <img src="{{ asset('/') }}storage/{{ $dataTk->item_file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-anak-perusahaan" width="140px">
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="validatePrompt('company-form')" class="btn btn-danger start"> Update Header </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="content p-4">

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Konten <?=explode('|',$title_page)[1]?></h3>

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
                <form method="POST" action="{{ route('store-anak-perusahaan') }}" enctype="multipart/form-data" id="company-form-2">
                    @csrf
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="idSPD" value="{{base64_encode($dataTkDetail->id ?? '') }}">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleS1">Title | ID</label>
                                <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS" value="{{$dataTk->item_title ?? ''}}">
                                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Title | EN</label>
                                <input type="text" name="title_en" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS" value="{{$dataTk->item_title_en ?? ''}}">
                                <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="picture">Picture | ID</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="item_file_2" class="custom-file-input" id="picture-anak-perusahaan-2">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="item_file_2_error" class="item_file_2_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataTkDetail->file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-anak-perusahaan-2" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="picture">Picture | EN</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="item_file_en" class="custom-file-input" id="picture-anak-perusahaan-3">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="item_file_en_error" class="item_file_en_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataTkDetail->file2 ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-anak-perusahaan-3" width="140px">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="validatePrompt('company-form-2')" class="btn btn-danger start"> Save </button>

                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="{{ asset('/') }}dist/js/main.js"></script>
<script src="{{ asset('/') }}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script>
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