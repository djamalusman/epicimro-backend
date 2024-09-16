<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input type="hidden" name="upt_current_order" class="form-control" id="currentOrder1-2" value="{{$dt_item->order}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="subs_name1">Subsidiarie Name</label>
                <input type="text" name="subs_name" class="form-control" id="subs_name2" value="{{$dt_item->nama_holding}}">
                <small id="subs_name_error" class="subs_name_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <label for="descriptionS1">Order</label>
            <select class="custom-select" name="upt_item_order">
                <option value="">Pilih Tipe</option>
                @foreach($chkOrder as $co)
                @if($co->order == $dt_item->order)
                <option value="{{ $co->order }}" selected>{{ $co->order }}</option>
                @else
                <option value="{{ $co->order }}">{{ $co->order }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="url1">URL</label>
                <input type="text" name="upt_url" class="form-control" id="url1-2" placeholder="www.example.com" value="{{$dt_item->url}}">
                <small id="upt_url_error" class="upt_url_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Holding Type</label>
                <select name="jenis_holding" class="form-control select2" style="width: 100%;">
                    <option value="" selected="selected">-</option>
                    @foreach($dataHoldingType as $dht)
                    <option value="{{$dht->menu_name}}" <?= ($dt_item->jenis_holding == $dht->menu_name) ? 'selected' : '' ?>>{{$dht->menu_name}}</option>
                    @endforeach
                </select>
                <small id="jenis_holding_error" class="jenis_holding_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        <div class="col-lg-6">
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
                    <img src="{{ asset('/') }}storage/{{ $dt_item->gambar_holding }}" width="75px" alt=" simulasi" class="img-thumbnail simulasi-gambar-picture2" width="140px">
                </div>
            </div>
        </div>
    </div>
    <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
</form>

<script>
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