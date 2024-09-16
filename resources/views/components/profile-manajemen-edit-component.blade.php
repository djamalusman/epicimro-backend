<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Name | ID</label>
                        <input type="text" name="title_id" class="form-control" id="title_idS1" value="{{$dt_item->title}}">
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
                            <label for="descriptionS1">Position | ID</label>
                            <input type="text" class="form-control" name="description" value="{{$dt_item->description}}">
                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Position | EN</label>
                            <input type="text" class="form-control" name="description_en" value="{{$dt_item->description_en}}">
                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description | ID</label>
                            <textarea class="form-control desc" name="description2" cols="20" rows="5">{{$dt_item->description2}}</textarea>
                            <small id="description2_error" class="description2_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descriptionS1">Description | EN</label>
                            <textarea class="form-control desc" name="description2_en" cols="20" rows="5">{{$dt_item->description2_en}}</textarea>
                            <small id="description2_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
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
                        <img src="{{ asset('/') }}storage/{{ $dt_item->file }}" width="75px"" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-anggota-holding" width="140px">
                    </div>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
    </div>    
</form>

<script>
     $(".desc").summernote({
        height: 250,
    });
    $('input[type="file"]').change(function(e) {
        console.log('simulasi-gambar-' + this.id);
        var files = [];
        for (var i = 0; i < $(this)[0].files.length; i++) {
            files.push($(this)[0].files[i].name);
        }
        const [file] = picture2.files;
        if (file) {

            $(".simulasi-gambar-" + this.id).attr("src", URL.createObjectURL(file));
        }
        $(this).next(".custom-file-label").html(files.join(", "));
    });
</script>