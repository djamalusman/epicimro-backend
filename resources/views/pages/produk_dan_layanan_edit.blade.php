@extends('../layouts.mainv2')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
<link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
@endsection


@section('content')
<div class="content-wrapper">
    <section class="content px-4">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-sm-6">
                    <h2>{{explode('|',$title_page)[1] }}</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                        <li class="breadcrumb-item active">{{explode('|',$title_page)[1]}}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Side List {{explode('|',$title_page)[1]}}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('update-title-visi-misi') }}" enctype="multipart/form-data" id="title-produk-dan-layanan-edit-form">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Side List Title | ID</label>
                                        <input type="text" name="title" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS" value="{{ $dataPages->side_list }}">
                                        <small id="title_error" class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">Side List Title | EN</label>
                                        <input type="text" name="title_en" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS" value="{{ $dataPages->side_list_en }}">
                                        <small id="title_en_error" class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="validatePrompt('title-produk-dan-layanan-edit-form')" class="btn btn-danger start"> Update </button>
                </form>
            </div>
        </div>
    </section>

    <section class="content px-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Konten {{ $dataPages->side_list }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('store-produk-dan-layanan') }}" enctype="multipart/form-data" id="produk-dan-layanan-edit-form">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '')}}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '')}}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">File Background</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="pictureproduk1">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="pictureproduk1_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="file_error" class="file_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataTk->file ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-pictureproduk1" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">File Logo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file2" class="custom-file-input" id="pictureproduk2">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="pictureproduk2_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="file2_error" class="file2_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataTk->file2 ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-pictureproduk2" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Company Name | ID</label>
                                        <input type="text" name="title_id" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS" value="{{$dataTk->title ?? '-'}}">
                                        <small id="title_id_error" class="title_id_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">Company Name | EN</label>
                                        <input type="text" name="title_eng" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS" value="{{$dataTk->title_en ?? '-'}}">
                                        <small id="title_eng_error" class="title_eng_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | ID</label>
                                            <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{$dataTk->description ?? '-'}}</textarea>
                                            <small id="description_error" class="description_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | EN</label>
                                            <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{$dataTk->description_en ?? '-'}}</textarea>
                                            <small id="description_en_error" class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Company Vision | ID</label>
                                            <textarea class="form-control desc" name="description2" id="description2S1" cols="20" rows="5">{{$dataTk->description2 ?? ''}}</textarea>
                                            <small id="description2_error" class="description2_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Company Vision | EN</label>
                                            <textarea class="form-control desc" name="description2_en" id="description2_enS1" cols="20" rows="5">{{$dataTk->description2_en ?? '-'}}</textarea>
                                            <small id="description2_en_error" class="description2_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descriptionS1">Youtube</label>
                                <input type="text" name="url" class="form-control" id="titleEn" value="{{$dataTk->url ?? ''}}">
                                <small id="url_error" class="url_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('produk-dan-layanan-edit-form')" class="btn btn-danger start"> Save </button>
                </form>
            </div>
        </div>
    </section>

    <section class="content px-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Kontak {{ $dataPages->side_list }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('store-kontak-produk-dan-layanan') }}" enctype="multipart/form-data" id="kontak-produk-dan-layanan-edit-form">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataContact->id ?? '')}}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Address | ID</label>
                                        <input type="text" name="address" class="form-control" id="titleS1" value="{{$dataContact->address ?? '-'}}">
                                        <small id="address_error" class="address_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">Address | EN</label>
                                        <input type="text" name="address_en" class="form-control" id="titleEn" value="{{$dataContact->address_en ?? '-'}}">
                                        <small id="address_en_error" class="address_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Instagram Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="instagram_icon" class="custom-file-input" id="instagram_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="instagram_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="instagram_icon_error" class="instagram_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->instagram_icon  ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-instagram_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Instagram Url</label>
                                <input type="text" name="instagram_link" class="form-control" id="titleEn" value="{{$dataContact->instagram_link ?? '-'}}">
                                <small id="instagram_link_error" class="instagram_link_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Facebook Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="facebook_icon" class="custom-file-input" id="facebook_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="facebook_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="facebook_icon_error" class="facebook_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->facebook_icon ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-facebook_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Facebook Url</label>
                                <input type="text" name="facebook_link" class="form-control" id="titleEn" value="{{$dataContact->facebook_link ?? '-'}}">
                                <small id="facebook_link_error" class="facebook_link_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Youtube Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="youtube_icon" class="custom-file-input" id="youtube_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="youtube_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="youtube_icon_error" class="youtube_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->youtube_icon ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-youtube_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Youtube Url</label>
                                <input type="text" name="youtube_link" class="form-control" id="titleEn" value="{{$dataContact->youtube_link ?? '-'}}">
                                <small id="youtube_link_error" class="youtube_link_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Email Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="email_icon" class="custom-file-input" id="email_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="email_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="email_icon_error" class="email_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->email_icon ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-email_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Email</label>
                                <input type="text" name="email_link" class="form-control" id="titleEn" value="{{$dataContact->email_link ?? '-'}}">
                                <small id="email_link_error" class="email_link_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Website Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="website_icon" class="custom-file-input" id="website_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="website_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="website_icon_error" class="website_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->website_icon ?? ''}}" alt="simulasi" class="img-thumbnail simulasi-gambar-website_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Webiste Url</label>
                                <input type="text" name="website_link" class="form-control" id="titleEn" value="{{$dataContact->website_link ?? '-'}}">
                                <small id="website_link_error" class="website_link_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Phone Icon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="phone_icon" class="custom-file-input" id="phone_icon">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <small id="phone_icon_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                                <small id="phone_icon_error" class="phone_icon_error input-group text-sm mt-2 text-danger error"></small>
                                <div class="mt-2">
                                    <img src="{{ asset('/') }}storage/{{ $dataContact->phone_icon ?? '' }}" alt="simulasi" class="img-thumbnail simulasi-gambar-phone_icon" width="140px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titleEn">Phone Number</label>
                                <input type="text" name="phone_number_one" class="form-control" id="titleEn" value="{{$dataContact->phone_number_one ?? '-'}}">
                                <small id="phone_number_one_error" class="phone_number_one_error input-group text-sm mt-2 text-danger error"></small>
                                <input type="text" name="phone_number_two" class="form-control" id="titleEn" value="{{$dataContact->phone_number_two ?? '-'}}">
                                <small id="phone_number_two_error" class="phone_number_two_error input-group text-sm mt-2 text-danger error"></small>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('kontak-produk-dan-layanan-edit-form')" class="btn btn-danger start"> Save </button>
                </form>
            </div>
        </div>
    </section>

    <section class="content px-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Produk dan Layanan {{ $dataPages->side_list }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('store-produk-dan-layanan-list') }}" enctype="multipart/form-data" id="produk-dan-layanan-add">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '') }}">
                    <input type="hidden" name="flag" value="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Product and Services | ID</label>
                                        <input type="text" name="product_name" class="form-control" id="titleS1" placeholder="POWER TO PROGRESS">
                                        <small id="product_name_error" class="product_name_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">Product and Services | EN</label>
                                        <input type="text" name="product_name_en" class="form-control" id="titleEn" placeholder="POWER TO PROGRESS">
                                        <small id="product_name_en_error" class="product_name_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('produk-dan-layanan-add')" class="btn btn-danger start"> Add </button>
                </form>
            </div>
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-red">
                                <h3 class="card-title">Product and Services List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="product-and-service-list" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name | ID</th>
                                                <th>Name | EN</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($dataProduct)
                                            @foreach($dataProduct as $key => $dm)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td><?= $dm->product_name ?></td>
                                                <td><?= $dm->product_name_en ?></td>
                                                <td>
                                                    <button type="button" onclick="parsingDataToModal('{{ $dm->id }}')" class="btn btn-warning">Edit</button>
                                                    <button type="button" onclick="deletePromptProductList('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content px-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Apps {{ $dataPages->side_list }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('store-produk-dan-layanan-list-apps') }}" enctype="multipart/form-data" id="produk-dan-layanan-edit-form-app">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataProductPs->id ?? '') }}">
                    <input type="hidden" name="idSPAS" value="{{base64_encode($dataProductAs->id ?? '') }}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '') }}">
                    <input type="hidden" name="flag_ps" value="2">
                    <input type="hidden" name="flag_as" value="3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleS1">Play Store</label>
                                        <input type="text" name="product_name_ps" class="form-control" value="{{$dataProductPs->product_name ?? ''}}">
                                        <small id="product_name_ps_error" class="product_name_ps_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titleEn">Apps Store</label>
                                        <input type="text" name="product_name_as" class="form-control" value="{{$dataProductAs->product_name ?? ''}}">
                                        <small id="product_name_as_error" class="product_name_as_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('produk-dan-layanan-edit-form-app')" class="btn btn-danger start"> Save </button>
                </form>
            </div>
        </div>
    </section>

    <section class="content px-4">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Galeri {{ $dataPages->side_list }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST" action="{{ route('store-produk-dan-layanan-galerry') }}" enctype="multipart/form-data" id="produk-dan-layanan-galeri">
                    @csrf
                    <input type="hidden" name="idSP" value="{{base64_encode($dataTk->id ?? '') }}">
                    <input type="hidden" name="id_content" value="{{base64_encode($dataPages->id ?? '') }}">
                    <div class="form-group">
                        <label for="picture">Picture</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="pictureHolding" class="custom-file-input" id="pictureHolding">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small id="pictureHolding_info" class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size: 100 Mb</small>
                        <small id="pictureHolding_error" class="pictureHolding_error input-group text-sm mt-2 text-danger error"></small>
                        <div class="mt-2">
                            <img src="" alt="simulasi" class="img-thumbnail simulasi-gambar-pictureHolding" width="140px">
                        </div>
                    </div>
                    <button type="button" onclick="validatePrompt('produk-dan-layanan-galeri')" class="btn btn-danger start"> Add </button>
                </form>
            </div>
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-red">
                                <h3 class="card-title">Product and Services List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="product-and-service-gallery" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Preview</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($dataPicture)
                                            @foreach($dataPicture as $key => $dm)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td><img src="{{ asset('/') }}storage/{{ $dm->picture }}" alt="simulasi" class="img-thumbnail simulasi-gambar-pictureproduk1" width="140px"></td>
                                                <td>
                                                    <button type="button" onclick="deletePromptProductGalerry('{{ $dm->id }}')" class="btn btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="edit-item" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="edit-item-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-item-label">
                    Edit Data
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit-data-list-item">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('/') }}dist/js/main.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(".desc").summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic']], //Specific toolbar display
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
        ],
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

    function deletePrompt(id) {
        var url = "{{ route('pages-list-detail-delete',':id') }}";
        url = url.replace(":id", id);

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        Swal.fire({
            title: "Delete data?",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#d33",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: url,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data["status"] == "success") {
                            Toast.fire({
                                icon: "success",
                                title: data["message"],
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data["message"],
                            });
                        }
                    },
                    error: function(reject) {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    },
                });
            }
        });
    }

    function deletePromptProductGalerry(id) {
        var url = "{{ route('delete-produk-dan-layanan-galerry',':id') }}";
        url = url.replace(":id", id);

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        Swal.fire({
            title: "Delete data?",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#d33",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: url,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data["status"] == "success") {
                            Toast.fire({
                                icon: "success",
                                title: data["message"],
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data["message"],
                            });
                        }
                    },
                    error: function(reject) {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    },
                });
            }
        });
    }

    function deletePromptProductList(id) {
        var url = "{{ route('delete-produk-dan-layanan-list',':id') }}";
        url = url.replace(":id", id);

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        Swal.fire({
            title: "Delete data?",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#d33",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: url,
                    type: "GET",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data["status"] == "success") {
                            Toast.fire({
                                icon: "success",
                                title: data["message"],
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data["message"],
                            });
                        }
                    },
                    error: function(reject) {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    },
                });
            }
        });
    }

    function parsingDataToModal(id) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        var url = "{{ route('edit-product-list',':id') }}";
        url = url.replace(":id", id);
        $.ajax({
            url: url,
            type: "GET",
            processData: false,
            contentType: false,
            success: function(data) {
                data = JSON.parse(data);
                if (data["status"] == "success") {
                    $("#edit-data-list-item").html(data["output"]);
                    $("#edit-item").modal("toggle");
                } else {
                    Toast.fire({
                        icon: "error",
                        title: data["message"],
                    });
                }
            },
            error: function(reject) {
                Toast.fire({
                    icon: "error",
                    title: "Something went wrong",
                });
            },
        });
    }

    $(function() {
        $('#product-and-service-list').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#product-and-service-gallery').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection