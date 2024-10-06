@extends('../layouts.mainv2')

@section('headers')
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
                <form method="POST" action="{{ route('store-home-registrasion') }}" enctype="multipart/form-data" id="company-form">
                    @csrf
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="idSide" value="{{base64_encode($dataTk->id_side ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-6" hidden>
                                    <div class="form-group">
                                        <label for="picture-sekilas-perusahaan">Upload Image</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="item_file" class="custom-file-input" id="picture-sekilas-perusahaan">
                                                <label class="custom-file-label" for="picture-sekilas-perusahaan">Choose file</label>
                                            </div>
                                        </div>
                                        <small id="picture-sekilas-perusahaan_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                        <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                        <div class="mt-2">
                                            <img src="{{ asset('/') }}storage/{{ $dataTk->item_file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-sekilas-perusahaan" width="140px">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6" hidden>
                                    <div class="form-group">
                                        <label for="logo-sekilas-perusahaan">Upload Image</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="item_file_2" class="custom-file-input" id="logo-sekilas-perusahaan">
                                                <label class="custom-file-label" for="logo-sekilas-perusahaan">Choose file</label>
                                            </div>
                                        </div>
                                        <small id="logo-sekilas-perusahaan_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                        <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                        <div class="mt-2">
                                            <img src="{{ asset('/') }}storage/{{ $dataTk->item_file_2 ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-logo-sekilas-perusahaan" width="140px">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">URL REGISTER</label>
                                        <input type="text" name="title_en" class="form-control" id="titleEn" placeholder="URL Video" value="{{$dataTk->item_link ?? ''}}">
                                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="side_list1">Update Side List Title | ID</label>
                                        <input type="text" name="side_list" class="form-control" value="-" id="side_list1" placeholder="{{explode('|',$title_page)[1]}}" value="1">
                                        <small id="side_list_error" class="side_list_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="side_list1">Update Side List Title | EN</label>
                                        <input type="text" name="side_list_en" class="form-control" value="-" id="side_list_en1" placeholder="{{explode('|',$title_page)[1]}}" value="1">
                                        <small id="side_list_en_error" class="side_list_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="titleS1">Title Header</label>
                                        <input type="text" name="title" class="form-control" id="titleS1" placeholder="Title" value="{{$dataTk->item_title ?? ''}}">
                                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="descriptionS1">Description Header</label>
                                        <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dataTk->item_body ?? ''}}</textarea>
                                        <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="descriptionS1">Description Footer</label>
                                        <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{$dataTk->item_body_en ?? ''}}</textarea>
                                        <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                        <button type="button" onclick="validatePrompt('company-form')" class="btn btn-danger start"> Save </button>
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
<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(".desc").summernote({
        height: 250,
        toolbar: [
            ['font', ['clear']], // Tombol font tidak ditampilkan
            ['color', ['color']], // Tombol warna tidak ditampilkan
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['picture']]
        ],
        buttons: {
            // Menambahkan tombol custom recent color jika diperlukan
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
        // Menyembunyikan toolbar default
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
            // Mengganti sumber gambar untuk elemen dengan kelas `simulasi-gambar-` sesuai dengan ID input
            $(".simulasi-gambar-" + this.id).attr("src", URL.createObjectURL(file));
        }
        // Menampilkan nama file pada label
        $(this).next(".custom-file-label").html(files.join(", "));
    });

</script>
@endsection
