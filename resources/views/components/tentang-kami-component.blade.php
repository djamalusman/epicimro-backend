<form action="{{ $route }}" enctype="multipart/form-data" id="{{$formId}}">
    @csrf
    <input type="hidden" name="upt_id" class="form-control" id="idS1-2" value="{{$dt_item->id}}">
    <input name="_method" type="hidden" value="{{$formMethod}}">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="titleS1">Title</label>
                        <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS" value="{{$dt_item->title}}">
                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
                <div class="col-md-6" hidden>
                    <div class="form-group">
                        <label for="titleEn">Title | EN</label>
                        <input type="text" name="title_en" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS" value="{{$dt_item->title_en}}">
                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descriptionS1">Description | ID</label>
                            <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dt_item->description}}</textarea>
                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                        </div>
                    </div>
                    <div class="col-md-6" hidden>
                        <div class="form-group">
                            <label for="descriptionS1">Description | EN</label>
                            <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{$dt_item->description_en}}</textarea>
                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
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
            toolbar: [
                ['font', [ 'fontsize', 'clear']], // Menampilkan opsi style font dan ukuran font
                //['font', ['fontname', 'fontsize', 'clear']],
                ['color', ['color']], // Tombol warna ditampilkan
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['picture']], // Menambahkan tombol untuk menyisipkan gambar
            ],
            //fontNames: ['Arial', 'Courier New', 'Helvetica', 'Times New Roman'], // Daftar font yang tersedia
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48', '64'], // Daftar ukuran font
            buttons: {
                recentColor: function() {
                    return $.summernote.ui.button({
                        contents: '<i class="note-icon-note"></i> Recent Color',
                        tooltip: 'Recent Color',
                        click: function() {
                            // Fungsi untuk recent color
                        }
                    }).render();
                }
            },
            disableDragAndDrop: true
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