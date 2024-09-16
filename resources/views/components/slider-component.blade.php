<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input type="hidden" name="upt_current_order" class="form-control" id="currentOrder1-2" value="{{$dt_item->item_order}}">
    <input type="hidden" name="pages_order" class="form-control" id="pagesOrder" value="{{$dt_item->id_pages_content_order}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="titleS1">Title | ID</label>
                    <input type="text" name="upt_title" class="form-control" id="titleS1-2" placeholder="POWER TO PROGRESS" value="{{$dt_item->item_title}}">
                    <small id="upt_title_error" class="upt_title_error input-group text-sm mt-2 text-danger error"></small>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="descriptionS1">Description | ID</label>
                    <textarea class="form-control desc" name="upt_description" id="descriptionS1-2" cols="20" rows="5">{{$dt_item->item_body}}</textarea>
                    <small id="upt_description_error" class="upt_description_error input-group text-sm mt-2 text-danger error"></small>
                </div>
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
                        <img src="{{ asset('/') }}storage/sliders/{{ $dt_item->item_file }}" width="75px"" alt=" simulasi" class="img-thumbnail simulasi-gambar-picture2" width="140px">
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="url1">URL</label>
                    <input type="text" name="upt_url" class="form-control" id="url1-2" placeholder="www.example.com" value="{{$dt_item->item_link}}">
                    <small id="upt_url_error" class="upt_url_error input-group text-sm mt-2 text-danger error"></small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="titleS1">Title | EN</label>
                    <input type="text" name="upt_title_en" class="form-control" id="titleS1-2" placeholder="POWER TO PROGRESS" value="{{$dt_item->item_title_en}}">
                    <small id="upt_title_en_error" class="upt_title_en_error input-group text-sm mt-2 text-danger error"></small>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="descriptionS1">Description | EN</label>
                    <textarea class="form-control desc" name="upt_description_en" id="descriptionS1-2" cols="20" rows="5">{{$dt_item->item_body_en}}</textarea>
                    <small id="upt_description_en_error" class="upt_description_en_error input-group text-sm mt-2 text-danger error"></small>
                </div>
            </div>
            <div class="col-lg-12">
                <label for="descriptionS1">Order</label>
                <select class="custom-select" name="upt_item_order">
                    <option value="">Pilih Tipe</option>
                    @foreach($chkOrder as $co)
                    @if($co->item_order == $dt_item->item_order)
                    <option value="{{ $co->item_order }}" selected>{{ $co->item_order }}</option>
                    @else
                    <option value="{{ $co->item_order }}">{{ $co->item_order }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update Slider </button>
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