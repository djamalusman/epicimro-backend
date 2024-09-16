@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/select2/css/select2.min.css">
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
                <h3 class="card-title">Slider #1 (Carousel)</h3>
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
                <form method="POST" action="{{ route('beranda-store-first-slider') }}" enctype="multipart/form-data" id="slider1form">
                    <input type="hidden" name='id_content' value="1">
                    <input type="hidden" name='id_content_order' value="1">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="titleS1">Title | ID</label>
                                    <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS">
                                    <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="descriptionS1">Description | ID</label>
                                    <textarea class="form-control" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                    <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="titleS1">Title | EN</label>
                                    <input type="text" name="title_en" class="form-control" id="title_enS1" placeholder="POWER TO PROGRESS">
                                    <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="descriptionS1">Description | EN</label>
                                    <textarea class="form-control" name="description_en" id="description_enS1" cols="20" rows="5"></textarea>
                                    <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
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
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="url1">URL</label>
                                    <input type="text" name="url" class="form-control" id="url1" placeholder="www.example.com">
                                    <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('slider1form')" class="btn btn-danger start"> Add Slider </button>
                </form>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Data Slider #1</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="slider1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>File Preview</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>URL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataMenu as $key => $dm)
                                                @if($dm->id_pages_content_order == 1 && $dm->item_extras == "-")
                                                <tr>
                                                    <td>{{ $dm->item_order }}</td>
                                                    <td>
                                                        @if($dm->item_file != "-" && ( explode(".", $dm->item_file)[1] == "mp4" || explode(".", $dm->item_file)[1] == "pdf"))
                                                        <i class="fas fa-file"></i>
                                                        @else
                                                        <img src="{{ asset('/') }}storage/{{ $dm->item_file }}" width="75px" alt="" srcset="">
                                                        @endif
                                                    </td>
                                                    <td>{{ $dm->item_title }}</td>
                                                    <td><?php echo $dm->item_body ?></td>
                                                    <td>{{ $dm->item_link }}</td>
                                                    <td>
                                                        <button type="button" onclick="parsingDataToModal('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                        <button type="button" onclick="deletePrompt('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                    </td>
                                                </tr>
                                                @endif
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

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Slider #1 (Video)</h3>
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
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-on-danger">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" <?= ($dataMenuVid->item_extras ?? '') ? 'checked' : '' ?> onclick="toggleVideo('{{ $dataMenuVid->id ?? null }}')">
                        <label class="custom-control-label" for="customSwitch3">Toggle this to turn off / on video on Website</label>
                    </div>
                </div>
                <form method="POST" action="{{ route('update-slider-video') }}" enctype="multipart/form-data" id="slider1formVideo">
                    <input type="hidden" name='id_content' value="1">
                    <input type="hidden" name='id_content_order' value="1">
                    <input type="hidden" name='idSp' value="{{ base64_encode($dataMenuVid->id ?? '') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="picture">File</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="item_file" class="custom-file-input" id="picture">
                                            <label class="custom-file-label" for="customFile">{{ $dataMenuVid->item_file  ?? ''}}</label>
                                        </div>
                                    </div>
                                    <small id="picture_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg, mp4 | Max Size: 100 Mb</small>
                                    <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                    <div class="mt-2">
                                        <img src="" alt="simulasi" class="img-thumbnail simulasi-gambar-picture" width="140px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('slider1formVideo')" class="btn btn-warning start"> Update Video </button>
                    <!-- <button type="button" onclick="deletePrompt('<?= $dataMenuVid->id ?? '' ?>')" class="btn btn-danger start"> Delete Video </button> -->
                </form>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Slider #2</h3>

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
                <form method="POST" action="{{ route('beranda-store-first-slider') }}" enctype="multipart/form-data" id="slider2form">
                    <input type="hidden" name='id_content' value="1">
                    <input type="hidden" name='id_content_order' value="2">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="titleS2">Title | ID </label>
                                    <input type="text" name="title" class="form-control" id="titleS2" placeholder="POWER TO PROGRESS">
                                    <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="descriptionS2">Description | ID</label>
                                    <textarea class="form-control desc" name="description" id="descriptionS2" cols="20" rows="5"></textarea>
                                    <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="titleS2">Title | EN</label>
                                    <input type="text" name="title_en" class="form-control" id="title_enS2" placeholder="POWER TO PROGRESS">
                                    <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="descriptionS2">Description | EN</label>
                                    <textarea class="form-control desc" name="description_en" id="description_enS2" cols="20" rows="5"></textarea>
                                    <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="picture">File</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="item_file" class="custom-file-input" id="picture-slider2">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <small id="picture_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                    <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                    <div class="mt-2">
                                        <img src="" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-slider2" width="140px">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="url1">URL</label>
                                    <input type="text" name="url" class="form-control" id="url2" placeholder="www.example.com">
                                    <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('slider2form')" class="btn btn-danger start"> Add Slider </button>
                </form>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Data Slider #2</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="slider2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>File Preview</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>URL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataMenu as $key => $dm)
                                                @if($dm->id_pages_content_order == 2)
                                                <tr>
                                                    <td>{{ $dm->item_order }}</td>
                                                    <td><img src="{{ asset('/') }}storage/{{ $dm->item_file }}" width="75px" alt="" srcset=""></td>
                                                    <td>{{ $dm->item_title }}</td>
                                                    <td><?php echo $dm->item_body ?></td>
                                                    <td>{{ $dm->item_link }}</td>
                                                    <td>
                                                        <button type="button" onclick="parsingDataToModal('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                        <button type="button" onclick="deletePrompt('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                    </td>
                                                </tr>
                                                @endif
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

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Youtube</h3>

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
                <form method="POST" action="{{ route('beranda-store-youtube') }}" enctype="multipart/form-data" id="youtubeForm">
                    <input type="hidden" name='id_content' value="1">
                    <input type="hidden" name='id_content_order' value="3">
                    <input type="hidden" name='idSPA' value="{{ base64_encode($dataYoutube->id ?? '')}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="url_youtube1">URL</label>
                                <input type="text" name="url_youtube" class="form-control" id="url_youtube1" placeholder="www.example.com" value="{{ $dataYoutube->item_link ?? '-'}}">
                                <small id="url_youtube_error" class="url_youtube_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="title_youtube">Title | ID</label>
                                <input type="text" name="title_youtube" class="form-control" id="title_youtube" placeholder="POWER TO PROGRESS" value="{{ $dataYoutube->item_title ?? '-' }}">
                                <small id="title_youtube_error" class="title_youtube_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="title_youtube">Title | EN</label>
                                <input type="text" name="title_youtube_en" class="form-control" id="title_youtube_en" placeholder="POWER TO PROGRESS" value="{{ $dataYoutube->item_title_en ?? '-' }}">
                                <small id="title_youtube_en_error" class="title_youtube_en_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="description_youtube">Description | ID</label>
                                <textarea class="form-control desc" name="description_youtube" id="description_youtube" cols="20" rows="5">{{ $dataYoutube->item_body ?? '-'}}</textarea>
                                <small id="description_youtube_error" class="description_youtube_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="description_youtube">Description | EN</label>
                                <textarea class="form-control desc" name="description_youtube_en" id="description_youtube_en" cols="20" rows="5">{{ $dataYoutube->item_body_en ?? '-'}}</textarea>
                                <small id="description_youtube_en_error" class="description_youtube_en_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('youtubeForm')" class="btn btn-danger start"> Update </button>
                </form>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Anggota Holding</h3>

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
                <form method="POST" action="{{ route('store-anggota-holding') }}" enctype="multipart/form-data" id="anggolaHoldingForm">
                    <input type="hidden" name='id_content' value="1">
                    <input type="hidden" name='id_content_order' value="4">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="subs_name1">Subsidiaris Name</label>
                                <input type="text" name="subs_name" class="form-control" id="subs_name2" placeholder="JASA RAHARJA">
                                <small id="subs_name_error" class="subs_name_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="url1">URL</label>
                                <input type="text" name="url" class="form-control" id="url2" placeholder="www.example.com">
                                <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="item_file" class="custom-file-input" id="picture-anggota-holding">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-anggota-holding" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Holding Type</label>
                                <select name="jenis_holding" class="form-control select2" style="width: 100%;">
                                    <option value="" selected="selected">-</option>
                                    @foreach($dataHoldingType as $dht)
                                    <option value="{{$dht->menu_name}}">{{$dht->menu_name}}</option>
                                    @endforeach
                                </select>
                                <small id="jenis_holding_error" class="jenis_holding_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('anggolaHoldingForm')" class="btn btn-danger start"> Add </button>
                </form>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-red">
                                    <h3 class="card-title">Anggota Holding</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="anggotaHolding" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Subsidiari Name</th>
                                                    <th>File Preview</th>
                                                    <th>URL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataHolding as $key => $dm)
                                                <tr>
                                                    <td>{{ $dm->order }}</td>
                                                    <td>{{ $dm->nama_holding }}</td>
                                                    <td><img src="{{ asset('/') }}storage/{{ $dm->gambar_holding }}" width="75px" alt="" srcset=""></td>
                                                    <td>{{ $dm->url }}</td>
                                                    <td>
                                                        <button type="button" onclick="parsingDataToModalAnggota('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                        <button type="button" onclick="deletePromptAnggotaHolding('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
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
<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script src="{{ asset('/') }}plugins/select2/js/select2.full.min.js"></script>
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

    function parsingDataToModal(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('beranda-detail-item-edit',':id') }}";
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

    function parsingDataToModalAnggota(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('edit-list-anggot-holding',':id') }}";
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
        var url = "{{ route('beranda-detail-item-delete',':id') }}";
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

    function toggleVideo(id) {
        var isEnable = 1;
        if ($('#customSwitch3').is(':checked')) {
            isEnable = 1;
        } else {
            isEnable = 0;
        }
        // ['id' => ':post_id', 'up_or_down_vote' => ':vote']
        var url = "{{ route('beranda-toggle-video',['id' => ':id' ,'param1' => ':param1' ])}}";
        url = url.replace(":id", id);
        url = url.replace(":param1", isEnable);

        console.log(url);


        $.ajax({
            url: url,
            type: "GET",
            processData: false,
            contentType: false,
            success: function(data) {
                data = JSON.parse(data);
                if (data["status"] == "success") {

                } else {

                }
            },
            error: function(reject) {

            },
        });

    }

    function deletePromptAnggotaHolding(id) {
        var url = "{{ route('anggota-holding-delete',':id') }}";
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
        $('.select2').select2();
        $('#slider1').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#slider2').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        $('#anggotaHolding').DataTable({
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

    $(".desc").summernote({
        height: 250,
    });
</script>
@endsection