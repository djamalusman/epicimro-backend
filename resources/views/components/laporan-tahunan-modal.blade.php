<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="titleS1">Year</label>
                <input type="text" name="year" class="form-control" id="yearDate" value="{{$dt_item->date_report}}">
                <small id="year_error" class="year_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Type</label>
                
                @isset($masterTipeItems)
                    <select class="form-control" name="type">
                        @foreach($masterTipeItems as $item)
                            <option value="{{$item->tipe_kode}}"<?= ($item->tipe_kode == $dt_item->type) ? 'selected' : '' ?>>{{$item->tipe_name}}</option>
                        @endforeach
                    </select>
                @endisset
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="side_list1">Title | ID</label>
                <input type="text" name="title" class="form-control" id="title1" value="{{$dt_item->title}}">
                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title1">Title | EN</label>
                <input type="text" name="title_en" class="form-control" id="title_en1" value="{{$dt_item->title_en}}">
                <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="picture">File</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="item_file" class="custom-file-input" id="picture-laporan-tahunan-modal">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg, pdf | Max Size: 100 Mb</small>
                <small id="item_file_error" class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                <div class="mt-2">
                    <img src="{{ asset('/') }}storage/{{ $dt_item->file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-laporan-tahunan-modal" width="140px">
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="picture">Cover File</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="item_file_2" class="custom-file-input" id="picture-laporan-tahunan-modal-2">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <small id="picture-anggota-holding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg, pdf | Max Size: 100 Mb</small>
                <small id="item_file_2_error" class="item_file_2_error input-group text-sm mt-2 text-danger error"></small>
                <div class="mt-2">
                    <img src="{{ asset('/') }}storage/{{ $dt_item->file2 ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-laporan-tahunan-modal-2" width="140px">
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