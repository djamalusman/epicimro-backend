<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Type</label>
                <select class="form-control" name="type">
                    <option value="" >-</option>
                    <option value="alamat" <?= ($dt_item->extras == 'alamat') ? 'selected' : '' ?>>Alamat</option>
                    <option value="hubungi-kami" <?= ($dt_item->extras == 'hubungi-kami') ? 'selected' : '' ?>>Hubungi Kami</option>
                    <option value="ikuti-kami" <?= ($dt_item->extras == 'ikuti-kami') ? 'selected' : '' ?>>Ikuti Kami</option>
                </select>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="picture">ICON</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="item_file" class="custom-file-input" id="picture-laporan-tahunan-modal">
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
                <label for="descriptionS1">Description | ID</label>
                <textarea class="form-control desc" name="description" id="description2S1" cols="20" rows="5">{{$dt_item->description}}</textarea>
                <small id="description2_error" class="description2_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="descriptionS1">Description | EN</label>
                <textarea class="form-control desc" name="description_en" id="description2_enS1" cols="20" rows="5">{{$dt_item->description_en}}</textarea>
                <small id="description2_en_error" class="description2_en_error input-group text-sm mt-2 text-danger error"></small>
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