<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dataPages->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
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
                        <label for="titleS1">Nama Lowongan</label>
                        <input type="text" name="vacancy_name" class="form-control" value="{{$dataPages->vacancy_name}}"  id="titleS1" placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleEn">Vancancy Name</label>
                        <input type="text" name="vacancy_name_en" class="form-control" value="{{$dataPages->vacancy_name_en}}"  id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="titleS1">Lokasi</label>
                        <input type="text" name="location" class="form-control" id="titleS1" value="{{$dataPages->location}}"  placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="title1">Category Lowongan</label>
                        <textarea id="inputTextEdit" class="form-control" placeholder="Type something and press Enter..." name="name"></textarea>
                        <div id="badgesContainerEdit"></div>
                        <input type="hidden" name="status_vacancy" id="badgesInputEdit" value="{{ $dataPages->status_vacancy }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="title1">Tingkatan Lowongan</label>
                        <textarea id="inputTextLevelEdit" class="form-control" placeholder="Type something and press Enter..." name="name"></textarea>
                        <div id="badgesContainerLevelEdit"></div>
                        <input type="hidden" name="vacancy_level" value="{{$dataPages->vacancy_level}}" id="badgesInputLevelEdit">
                    </div>
                </div>
                
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Tanggal Posting</label>
                        <input type="date" name="posted_date" class="form-control" id="titleS1" placeholder="" value="{{ substr($dataPages->posted_date, 0, 10) }}" placeholder="YYYY-MM-DD">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Tanggal Tutup Posting</label>
                        <input type="date" name="close_date" class="form-control" id="titleS1" placeholder="" value="{{ substr($dataPages->close_date, 0, 10) }}" placeholder="YYYY-MM-DD">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description Persyaratan| ID</label>
                            <textarea class="form-control desc" name="vacancy_description" id="descriptionS1" cols="20" rows="5">{{$dataPages->vacancy_description}}</textarea>
                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description Requirements| EN</label>
                            <textarea class="form-control desc" name="vacancy_description_en" id="description_enS1" cols="20" rows="5">{{$dataPages->vacancy_description_en}}</textarea>
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