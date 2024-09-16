<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titleS1">Product and Services | ID</label>
                        <textarea type="text" name="product_name" class="form-control desc"> <?=$dt_item->product_name?>  </textarea>
                        <small id="product_name_error" class="product_name_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_nameEn">Product and Services | EN</label>
                        <textarea type="text" name="product_name_en" class="form-control desc"> <?=$dt_item->product_name_en?> </textarea>
                        <small id="product_name_en_error" class="product_name_en_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
    </div>
</form>

<script>
    $(".desc").summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic']], //Specific toolbar display            
            ['para', ['ul', 'ol', 'paragraph']]
        ],
    });
</script>