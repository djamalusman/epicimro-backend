<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input type="hidden" name="upt_current_order" class="form-control" id="currentOrder1-2" value="{{$dt_item->item_order}}">
    <input type="hidden" name="pages_order" class="form-control" id="pagesOrder" value="{{$dt_item->id_pages_content_order}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="titleS1">Year</label>
                <input type="text" name="upt_item_extras" class="form-control" id="item_extrasS1-2" placeholder="POWER TO PROGRESS" value="{{$dt_item->extras}}">
                <small id="upt_item_extras_error" class="upt_item_extras_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="titleS1">Title | ID</label>
                <input type="text" name="upt_title" class="form-control" id="titleS1-2" placeholder="POWER TO PROGRESS" value="{{$dt_item->title}}">
                <small id="upt_title_error" class="upt_title_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="titleS1">Title | EN</label>
                <input type="text" name="upt_title_en" class="form-control" id="titleS1-2" placeholder="POWER TO PROGRESS" value="{{$dt_item->title_en}}">
                <small id="upt_title_en_error" class="upt_title_en_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="descriptionS1">Description | ID</label>
                <textarea class="form-control desc" name="upt_description" id="descriptionS1-2" cols="20" rows="5">{{$dt_item->description}}</textarea>
                <small id="upt_description_error" class="upt_description_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="descriptionS1">Description | EN</label>
                <textarea class="form-control desc" name="upt_description_en" id="descriptionS1-2" cols="20" rows="5">{{$dt_item->description_en}}</textarea>
                <small id="upt_description_en_error" class="upt_description_en_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-12">
            <label for="descriptionS1">Order</label>
            <select class="custom-select" name="upt_item_order">
                <option value="">Pilih Order</option>
                @foreach($chkOrder as $co)
                @if($co->order == $dt_item->order)
                <option value="{{ $co->order }}" selected>{{ $co->order }}</option>
                @else
                <option value="{{ $co->order }}">{{ $co->order }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="picture">File</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="upt_item_file" class="custom-file-input" id="picture2">
                        <label class="custom-file-label" for="customFile">{{$dt_item->item_file}}</label>
                    </div>
                </div>
                <small id="picture_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                <small id="upt_item_file_error" class="upt_item_file_error input-group text-sm mt-2 text-danger error"></small>
                <div class="mt-2">
                    <img src="{{ asset('/') }}storage/{{ $dt_item->file }}" width="75px"" alt=" simulasi" class="img-thumbnail simulasi-gambar-picture2" width="140px">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="titleS1">URL</label>
                <input type="text" name="upt_url" class="form-control" id="urlS1-2" value="{{$dt_item->url}}">
                <small id="upt_url_error" class="upt_url_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
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