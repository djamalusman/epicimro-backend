<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="picture">Image Header</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="item_file" class="custom-file-input" id="picture-anggota-holding">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                        <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                        <div class="mt-2">
                            <img src="{{ asset('/') }}storage/{{ $dt_item->file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-laporan-tahunan-modal" width="140px">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Nama Traning | ID</label>
                        <input type="text" name="traning_name" value="{{$dt_item->traning_name}}" class="form-control" id="titleS1" placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleEn">Training Name | EN</label>
                        <input type="text" name="traning_name_en" value="{{$dt_item->traning_name_en}}" class="form-control" id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Jenis Sertifikat | ID</label>
                        <input type="text" name="cetificate_type" class="form-control" value="{{$dt_item->cetificate_type}}" id="titleS1" placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleEn">Cetificate Type | EN</label>
                        <input type="text" name="cetificate_type_en" class="form-control" value="{{$dt_item->cetificate_type_en}}" id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="titleS1">Tanggal Pelaksanaan</label>
                        <input type="date" id="datepicker" name="startdate" class="form-control" value="{{ substr($dt_item->startdate, 0, 10) }}" placeholder="YYYY-MM-DD">
        
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="titleS1">Tanggal Penutupan</label>
                        <input type="date" id="datepicker" name="enddate" class="form-control" value="{{ substr($dt_item->enddate, 0, 10) }}" placeholder="YYYY-MM-DD">
        
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="titleEn">Durasi Traning</label>
                        <input type="text" name="training_duration" class="form-control" value="{{$dt_item->training_duration}}" id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Nama Training | ID</label>
                        <input type="text" name="training_material" class="form-control" id="titleS1" value="{{$dt_item->training_material}}" placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleEn">Training Material | EN</label>
                        <input type="text" name="training_material_en" class="form-control" value="{{$dt_item->training_material_en}}" id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Fasilitas | ID</label>
                        <input type="text" name="facility" class="form-control" value="{{$dt_item->facility}}" id="titleS1" placeholder="">
                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleEn">Facility | EN</label>
                        <input type="text" name="facility_en" class="form-control" value="{{$dt_item->facility_en}}" id="titleEn" placeholder="">
                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description | ID</label>
                            <textarea class="form-control desc" name="requirements" id="descriptionS1" cols="20" rows="5">{{$dt_item->requirements}}</textarea>
                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description | EN</label>
                            <textarea class="form-control desc" name="requirements_en" id="description_enS1" cols="20" rows="5">{{$dt_item->requirements_en}} </textarea>
                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
    </div>
</form>

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