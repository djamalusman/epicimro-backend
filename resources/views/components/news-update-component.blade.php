<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dataPages->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="picture">Image Job Vancancy</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="item_file" class="custom-file-input" id="picture-anggota-holding">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataPages->file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-laporan-tahunan-modal" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleS1">Title | ID</label>
                                <input type="text" name="title" value="{{$dataPages->title}}" class="form-control" id="titleS1" placeholder="">
                                <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Title | EN</label>
                                <input type="text" name="title_en" value="{{$dataPages->title}}" class="form-control" id="titleEn" placeholder="">
                                <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Berita</label>
                                
                                <select class="form-control" name="typenews">
                                    <option value="">-</option>
                                    @foreach($jenisnews as $value)
                                        <option value="{{ $value->id }}" {{ $value->id == $dataPages->id_news_type ? 'selected' : '' }}>
                                            {{ $value->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Tanggal Publikasi</label>
                                <input type="date" id="datepicker" name="implementation_date" class="form-control" value="{{ substr($dataPages->implementation_date, 0, 10) }}" placeholder="YYYY-MM-DD">
                                <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Deskripsi Berita| ID</label>
                            <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dataPages->description}}</textarea>
                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">news description| EN</label>
                            <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{$dataPages->description_en}}</textarea>
                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
    </div>
</form>
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
</script>