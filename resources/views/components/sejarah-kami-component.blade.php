<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="titleS1">Year</label>
                <input type="text" name="title" class="form-control" id="yearDate" placeholder="POWER TO PROGRESS" value="{{$dt_item->extras}}">
                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="descriptionS1">Description | ID</label>
                <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dt_item->description}}</textarea>
                <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="descriptionS1">Description | EN</label>
                <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{$dt_item->description_en}}</textarea>
                <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
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