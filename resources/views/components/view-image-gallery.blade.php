

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                
                @foreach ( $dt_item as $value )
                    <div class="col-md-4">
                        <div class="form-group">
                            <img src="{{ asset('/') }}storage/{{ $value->file ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-picture-laporan-tahunan-modal" width="140px">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>
