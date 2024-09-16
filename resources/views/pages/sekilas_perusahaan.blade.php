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
                <form method="POST" action="{{ route('store-company-overview') }}" enctype="multipart/form-data" id="company-form">
                    @csrf
                    <input type="hidden" name="pages" value="{{base64_encode($menus->id)}}">
                    <input type="hidden" name="id_content_order" value="1">
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="idSide" value="{{base64_encode($dataTk->id_side ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-12">
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
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Update Side List Title | ID</label>
                                        <input type="text" name="side_list" class="form-control" id="side_list1" placeholder="{{explode('|',$title_page)[1]}}" value="{{$dataTk->side_list ?? ''}}">
                                        <small id="side_list_error" class="side_list_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Title | ID</label>
                                        <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS" value="{{$dataTk->item_title ?? ''}}">
                                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" hidden>
                                        <label for="side_list1">Update Side List Title | EN</label>
                                        <input type="text" name="side_list_en" class="form-control" id="side_list_en1" placeholder="{{explode('|',$title_page)[1]}}" value="-">
                                        <small id="side_list_en_error" class="side_list_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" hidden>
                                        <label for="titleEn">Title | EN</label>
                                        <input type="text" name="title_en" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS" value="-">
                                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | ID</label>
                                            <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dataTk->item_body ?? ''}}</textarea>
                                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6" hidden>
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | EN</label>
                                            <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">-</textarea>
                                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="validatePrompt('company-form')" class="btn btn-danger start"> Save </button>
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
</script>
@endsection