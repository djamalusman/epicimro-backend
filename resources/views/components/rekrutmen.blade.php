<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Type</label>
                <select class="form-control" name="work_type">
                    <option value="">-</option>
                    @foreach($dmc as $dmcs)
                    <option value="{{$dmcs->id}}" <?= ($dmcs->id == $dt_item->work_type) ? 'selected' : '' ?>>{{$dmcs->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="titleS1">City</label>
                <input type="text" name="city" class="form-control" id="cityS1" value="{{ $dt_item->city ?? ''}}">
                <small id="city_error" class="city_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="titleS1">Division</label>
                <input type="text" name="divisi" class="form-control" id="divisiS1" value="{{ $dt_item->divisi ?? ''}}">
                <small id="divisi_error" class="divisi_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="titleS1">Start Date</label>
                <input type="text" name="start_date" class="form-control" id="startDate2" value="<?= ($dt_item->start_date != null) ? date_format(date_create($dt_item->start_date), 'd-m-Y') : '' ?>" >
                <small id="start_date_error" class="start_date_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="titleS1">End Date</label>
                <input type="text" name="end_date" class="form-control" id="endDate2" value="<?= ($dt_item->end_date != null) ? date_format(date_create($dt_item->end_date), 'd-m-Y') : '' ?>">
                <small id="end_date_error" class="end_date_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="side_list1">Title | ID</label>
                <input type="text" name="title" class="form-control" id="title1" value="{{$dt_item->title}}" value="{{$dt_item->title ?? ''}}">
                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title1">Title | EN</label>
                <input type="text" name="title_en" class="form-control" id="title_en1" value="{{$dt_item->title_en}}" value="{{$dt_item->title_en ?? ''}}">
                <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="descriptionS1">Description | ID</label>
                <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{ $dt_item->description ?? '' }}</textarea>
                <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="descriptionS1">Description | EN</label>
                <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{ $dt_item->description_en ?? '' }}</textarea>
                <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="picture">Update Thumbnail</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="thumbnail" class="custom-file-input" id="picture2">
                        <label class="custom-file-label" for="customFile">{{$dt_item->thumbnail}}</label>
                    </div>
                </div>
                <small id="picture_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                <small id="thumbnail_error" class="thumbnail_error input-group text-sm mt-2 text-danger error"></small>
                <div class="mt-2">
                    <img src="{{ asset('/') }}storage/sliders/{{ $dt_item->item_file }}" width="75px"" alt=" simulasi" class="img-thumbnail simulasi-gambar-picture2" width="140px">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="title1">URL</label>
                <input type="text" name="url" class="form-control" id="url1" value="{{ $dt_item->url ?? '' }}">
                <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
    </div>
</form>

<script>
     $("#startDate2").datepicker({
        // format: "dd-mm-yyyy",
        startDate: new Date(),
    });

    $('#startDate2').change(function (){
        $("#endDate2").datepicker("destroy");
        $('#endDate2').datepicker({
            // format: "dd-mm-yyyy",
            startDate: new Date($('#startDate2').val())
        });
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