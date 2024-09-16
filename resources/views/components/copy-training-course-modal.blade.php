<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
       
        <div class="col-md-6">
            <div class="form-group">
                <label for="side_list1">Link Pendaftaran</label>
                <input type="text" name="link_pendaftaran" class="form-control" id="title1" value="{{$dt_item->link_pendaftaran}}">
                <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-2">
            <button type="button" onclick="validatePrompt('{{$formId}}')" class="btn btn-danger m-2"> Update </button>
        </div>
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