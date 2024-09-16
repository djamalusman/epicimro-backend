@extends('../layouts.mainv2')

@section('headers')
    <link rel="stylesheet" href="{{ asset('/') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}plugins/summernote/summernote-bs4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
        rel="stylesheet" />
@endsection


@section('content')
    <div class="content-wrapper">
        <section class="content p-4">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>{{ explode('|', $title_page)[1] }}</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#" class="text-danger">Pages</a></li>
                            <li class="breadcrumb-item active">{{ explode('|', $title_page)[1] }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Konten Life at IFG</h3>

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
                    <form method="POST" action="{{ route('store-karir') }}" enctype="multipart/form-data"
                        id="laporan-tahunan-form">
                        @csrf
                        <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                        <input type="hidden" name="id_content_order" value="1">
                        <input type="hidden" name="idSP" value="{{ base64_encode($dataContent->id ?? '') }}">
                        <input type="hidden" name="idSide" value="{{ base64_encode($dataContent->id_side ?? '') }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="picture">Update Background Header</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="item_file" class="custom-file-input"
                                                        id="picture-visi-misi">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                            </div>
                                            <small id="picture-anggota-holding_info"
                                                class="input-group text-sm mt-2 error">File type: jpeg, png, jpg | Max Size:
                                                100 Mb {{ $dataContent->item_file ?? '' }}</small>
                                            <small id="item_file_error"
                                                class="item_file_error input-group text-sm mt-2 text-danger error"></small>
                                            <div class="mt-2">
                                                <img src="{{ asset('/') }}storage/{{ $dataContent->item_file ?? '' }}"
                                                    alt="simulasi" class="img-thumbnail simulasi-gambar-picture-visi-misi"
                                                    width="140px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="side_list1">Title | ID</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="{{ explode('|', $title_page)[1] }}"
                                                value="{{ $dataContent->item_title ?? '' }}">
                                            <small id="title_error"
                                                class="title_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title1">Title | EN</label>
                                            <input type="text" name="title_en" class="form-control" id="title_en"
                                                placeholder="{{ explode('|', $title_page)[1] }}"
                                                value="{{ $dataContent->item_title_en ?? '' }}">
                                            <small id="title_en_error"
                                                class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | ID</label>
                                            <textarea class="form-control desc" name="description" id="description" cols="20" rows="5">{{ $dataContent->item_body ?? '' }}</textarea>
                                            <small id="description_error"
                                                class="description_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Description | EN</label>
                                            <textarea class="form-control desc" name="description_en" id="description_en" cols="20" rows="5">{{ $dataContent->item_body_en ?? '' }}</textarea>
                                            <small id="description_en_error"
                                                class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="side_list1">Button Selengkapnya</label>
                                            <input type="text" name="item_link" class="form-control" id="item_link"
                                                value="{{ $dataContent->item_link ?? '' }}">
                                            <small id="item_link_error"
                                                class="item_link_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="validatePrompt('laporan-tahunan-form')"
                                    class="btn btn-danger start"> Save </button>

                            </div>

                        </div>
                    </form>

                </div>

            </div>
            <br>
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Konten Image AKHLAK IFG</h3>

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
                    <form method="POST" action="{{ route('store-karir-widget') }}" enctype="multipart/form-data"
                        id="add-widget">
                        @csrf
                        <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                        <input type="hidden" name="id_content_order" value="1">
                        <input type="hidden" name="idSP" value="{{ base64_encode($dataContent->id ?? '') }}">
                        <input type="hidden" name="idSide" value="{{ base64_encode($dataContent->id_side ?? '') }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="side_list1">Widget Title | ID</label>
                                            <input type="text" name="title" class="form-control" id="title1"
                                                placeholder="{{ explode('|', $title_page)[1] }}">
                                            <small id="title_error"
                                                class="title_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title1">Widget Title | EN</label>
                                            <input type="text" name="title_en" class="form-control" id="title_en1"
                                                placeholder="{{ explode('|', $title_page)[1] }}">
                                            <small id="title_en_error"
                                                class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Widget Description | ID</label>
                                            <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5"></textarea>
                                            <small id="description_error"
                                                class="description_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descriptionS1">Widget Description | EN</label>
                                            <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5"></textarea>
                                            <small id="description_en_error"
                                                class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="descriptionS1">Image Widget</label>
                                            <div class="custom-file">
                                                <input type="file" name="item_file_widget" class="custom-file-input"
                                                    id="picture-visi-misi">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                            <span class="label label-default" style="color:red">The actual size for the picture above is : 447 x 884, max size: 1Mb MB, format : JPG, PNG.</span>
                                            <small id="file_en_error"
                                                class="file_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="validatePrompt('add-widget')"
                                    class="btn btn-danger start"> Save </button>

                                <div class="container-fluid mt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-red">
                                                    <h3 class="card-title">Data Slider #1</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <div id="slider1_wrapper"
                                                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6"></div>
                                                                <div class="col-sm-12 col-md-6"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table id="slider1"
                                                                        class="table table-bordered table-hover dataTable no-footer"
                                                                        aria-describedby="slider1_info">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Image Preview</th>
                                                                                <th>Title</th>
                                                                                <th>Title (EN)</th>
                                                                                <th>Description</th>
                                                                                <th>Order</th>
                                                                                <th>Description (EN)</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $i = 1;
                                                                            @endphp
                                                                            @foreach ($dataWidget as $item)
                                                                                <tr class="odd">
                                                                                    <td class="sorting_1">
                                                                                        {{ $i }}</td>
                                                                                    <td>
                                                                                        <img src="{{ asset('/') }}storage/{{ $item->file }}"
                                                                                            width="75px" alt=""
                                                                                            srcset="">
                                                                                    </td>
                                                                                    <td>{!! $item->title !!}</td>
                                                                                    <td>{!! $item->title_en !!}</td>
                                                                                    <td>{!! $item->description !!}</td>
                                                                                    <td>{!! $item->order !!}</td>
                                                                                    <td>{!! $item->description_en !!}</td>

                                                                                    <td>
                                                                                        <button type="button"
                                                                                            data-toggle="modal"
                                                                                            data-target="#edit-item{{ $item->id }}"
                                                                                            class="btn btn-warning">Edit</button>
                                                                                        <button type="button"
                                                                                            onclick="deletePrompt('{{ $item->id }}')"
                                                                                            class="btn btn-danger">Delete</button>
                                                                                    </td>
                                                                                </tr>

                                                                                @php
                                                                                    $i++;
                                                                                @endphp
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-5">
                                                                    <div class="dataTables_info" id="slider1_info"
                                                                        role="status" aria-live="polite">Showing 1 to 4
                                                                        of 4
                                                                        entries</div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-7">
                                                                    <div class="dataTables_paginate paging_simple_numbers"
                                                                        id="slider1_paginate">
                                                                        <ul class="pagination">
                                                                            <li class="paginate_button page-item previous disabled"
                                                                                id="slider1_previous"><a href="#"
                                                                                    aria-controls="slider1"
                                                                                    data-dt-idx="0" tabindex="0"
                                                                                    class="page-link">Previous</a></li>
                                                                            <li class="paginate_button page-item active"><a
                                                                                    href="#" aria-controls="slider1"
                                                                                    data-dt-idx="1" tabindex="0"
                                                                                    class="page-link">1</a></li>
                                                                            <li class="paginate_button page-item next disabled"
                                                                                id="slider1_next"><a href="#"
                                                                                    aria-controls="slider1"
                                                                                    data-dt-idx="2" tabindex="0"
                                                                                    class="page-link">Next</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </form>

                </div>
            </div>
            <br>
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Konten {{ explode('|', $title_page)[1] }} - Header Youtube</h3>

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
                    <form method="POST" action="{{ route('store-karir-youtube') }}" enctype="multipart/form-data"
                        id="des-youtube-form">
                        @csrf
                        <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                        <input type="hidden" name="id_content_order" value="2">
                        <input type="hidden" name="idSP" value="{{ base64_encode($youtubeContent->id ?? '') }}">
                        <input type="hidden" name="idSide"
                            value="{{ base64_encode($youtubeContent->id_side ?? '') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="side_list1">Title | ID</label>
                                    <input type="text" name="title" class="form-control" id="title1"
                                        placeholder="{{ explode('|', $title_page)[1] }}"
                                        value="{{ $youtubeContent->item_title ?? '' }}">
                                    <small id="title_error"
                                        class="title_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title1">Title | EN</label>
                                    <input type="text" name="title_en" class="form-control" id="title_en1"
                                        placeholder="{{ explode('|', $title_page)[1] }}"
                                        value="{{ $youtubeContent->item_title_en ?? '' }}">
                                    <small id="title_en_error"
                                        class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descriptionS1">Description | ID</label>
                                    <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{ $youtubeContent->item_body ?? '' }}</textarea>
                                    <small id="description_error"
                                        class="description_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descriptionS1">Description | EN</label>
                                    <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{ $youtubeContent->item_body_en ?? '' }}</textarea>
                                    <small id="description_en_error"
                                        class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="validatePrompt('des-youtube-form')" class="btn btn-danger start">
                            Save </button>
                    </form>

                </div>

            </div>
            <br>
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Konten {{ explode('|', $title_page)[1] }} - Youtube</h3>

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
                    <form method="POST" action="{{ route('store-karir-widget-youtube') }}"
                        enctype="multipart/form-data" id="add-youtube">
                        @csrf
                        <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                        <input type="hidden" name="id_content_order" value="1">
                        <input type="hidden" name="idSP" value="{{ base64_encode($dataContent->id ?? '') }}">
                        <input type="hidden" name="idSide" value="{{ base64_encode($dataContent->id_side ?? '') }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="side_list1">Widget Title | ID</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="{{ explode('|', $title_page)[1] }}">
                                            <small id="title_error"
                                                class="title_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title1">Widget Title | EN</label>
                                            <input type="text" name="title_en" class="form-control" id="title_en1"
                                                placeholder="{{ explode('|', $title_page)[1] }}">
                                            <small id="title_en_error"
                                                class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="side_list1">Youtube Url</label>
                                            <input type="text" name="url" class="form-control" id="url">
                                            <small id="url_error"
                                                class="url_error input-group text-sm mt-2 text-danger error"></small>
                                        </div>
                                    </div>

                                </div>
                                <button type="button" onclick="validatePrompt('add-youtube')"
                                    class="btn btn-danger start"> Save </button>

                                <div class="container-fluid mt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-red">
                                                    <h3 class="card-title">Data Slider #1</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <div id="slider1_wrapper"
                                                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6"></div>
                                                                <div class="col-sm-12 col-md-6"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table id="slider1"
                                                                        class="table table-bordered table-hover dataTable no-footer"
                                                                        aria-describedby="slider1_info">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Title</th>
                                                                                <th>Title (EN)</th>
                                                                                <th>Url</th>
                                                                                <th>Order</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $i = 1;
                                                                            @endphp
                                                                            @foreach ($dataYoutubeContent as $item)
                                                                                <tr class="odd">
                                                                                    <td class="sorting_1">
                                                                                        {{ $i }}</td>
                                                                                    <td>{!! $item->title !!}</td>
                                                                                    <td>{!! $item->title_en !!}</td>
                                                                                    <td>{!! $item->url !!}</td>
                                                                                    <td>{!! $item->order - 4 !!}</td>


                                                                                    <td>
                                                                                        <button type="button"
                                                                                            data-toggle="modal"
                                                                                            data-target="#edit-youtube{{ $item->id }}"
                                                                                            class="btn btn-warning">Edit</button>
                                                                                        <button type="button"
                                                                                            onclick="deletePrompt('{{ $item->id }}')"
                                                                                            class="btn btn-danger">Delete</button>
                                                                                    </td>
                                                                                </tr>

                                                                                @php
                                                                                    $i++;
                                                                                @endphp
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-5">
                                                                    <div class="dataTables_info" id="slider1_info"
                                                                        role="status" aria-live="polite">Showing 1 to 4
                                                                        of 4
                                                                        entries</div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-7">
                                                                    <div class="dataTables_paginate paging_simple_numbers"
                                                                        id="slider1_paginate">
                                                                        <ul class="pagination">
                                                                            <li class="paginate_button page-item previous disabled"
                                                                                id="slider1_previous"><a href="#"
                                                                                    aria-controls="slider1"
                                                                                    data-dt-idx="0" tabindex="0"
                                                                                    class="page-link">Previous</a></li>
                                                                            <li class="paginate_button page-item active"><a
                                                                                    href="#" aria-controls="slider1"
                                                                                    data-dt-idx="1" tabindex="0"
                                                                                    class="page-link">1</a></li>
                                                                            <li class="paginate_button page-item next disabled"
                                                                                id="slider1_next"><a href="#"
                                                                                    aria-controls="slider1"
                                                                                    data-dt-idx="2" tabindex="0"
                                                                                    class="page-link">Next</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
    @foreach ($dataWidget as $item)
        <div class="modal fade" id="edit-item{{ $item->id }}" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="edit-item{{ $item->id }}-label" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-item-label">
                            Edit Data
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-data-list-item">
                        <form action="{{ route('update-karir-widget') }}" enctype="multipart/form-data"
                            id="slide-tab-edit{{ $item->id }}">

                            @csrf
                            <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                            <input type="hidden" name="id_content_order" value="1">
                            <input type="hidden" name="idSP" value="{{ base64_encode($dataContent->id ?? '') }}">
                            <input type="hidden" name="idSide"
                                value="{{ base64_encode($dataContent->id_side ?? '') }}">
                            <input type="hidden" name="id" value="{{ base64_encode($item->id ?? '') }}">
                            <input name="_method" type="hidden" value="PUT">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Widget
                                            Title |
                                            ID</label>
                                        <input type="text" name="title" class="form-control" id="title"
                                            value="{{ $item->title }}">
                                        <small id="title_error"
                                            class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title1">Widget
                                            Title |
                                            EN</label>
                                        <input type="text" name="title_en" class="form-control" id="title_en1"
                                            value="{{ $item->title_en }}">
                                        <small id="title_en_error"
                                            class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionS1">Widget
                                            Description
                                            |
                                            ID</label>
                                        <textarea class="form-control desc" name="description" id="descriptionS1" cols="20" rows="5">{{ $item->description }}</textarea>
                                        <small id="description_error"
                                            class="description_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descriptionS1">Widget
                                            Description
                                            |
                                            EN</label>
                                        <textarea class="form-control desc" name="description_en" id="description_enS1" cols="20" rows="5">{{ $item->description_en }}</textarea>
                                        <small id="description_en_error"
                                            class="description_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="title1">Widget
                                            Order </label>
                                        <input type="text" name="order" class="form-control" id="order"
                                            value="{{ $item->order }}">
                                        <small id="order_en_error"
                                            class="order_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="descriptionS1">Widget
                                            Image</label>
                                        <div class="custom-file">
                                            <input type="file" name="item_file_widget" class="custom-file-input"
                                                id="picture-visi-misi">
                                            <label class="custom-file-label" for="customFile">Choose
                                                file</label>
                                        </div>
                                        <span class="label label-default" style="color:red">The actual size for the picture above is : 447 x 884, max size: 1Mb MB, format : JPG, PNG.</span>
                                        <small id="file_en_error"
                                            class="file_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <button type="button" onclick="validatePrompt('slide-tab-edit{{ $item->id }}')"
                        class="btn btn-danger m-2">
                        Update Slider </button>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($dataYoutubeContent as $item)
        <div class="modal fade" id="edit-youtube{{ $item->id }}" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="edit-youtube{{ $item->id }}-label" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-item-label">
                            Edit Data
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-data-list-item">
                        <form action="{{ route('update-karir-widget-youtube') }}" enctype="multipart/form-data"
                            id="slide-tab-edit-youtube{{ $item->id }}">

                            @csrf
                            <input type="hidden" name="pages" value="{{ base64_encode($menus->id) }}">
                            <input type="hidden" name="id_content_order" value="1">
                            <input type="hidden" name="idSP" value="{{ base64_encode($dataContent->id ?? '') }}">
                            <input type="hidden" name="idSide"
                                value="{{ base64_encode($dataContent->id_side ?? '') }}">
                            <input type="hidden" name="id" value="{{ base64_encode($item->id ?? '') }}">
                            <input name="_method" type="hidden" value="PUT">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Widget Title | ID</label>
                                        <input type="text" name="title" class="form-control" id="title"
                                            value="{{ $item->title }}">
                                        <small id="title_error"
                                            class="title_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title1">Widget Title | EN</label>
                                        <input type="text" name="title_en" class="form-control" id="title_en1"
                                            value="{{ $item->title_en }}">
                                        <small id="title_en_error"
                                            class="title_en_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Youtube Order </label>
                                        <input type="text" name="order" class="form-control" id="order"
                                            value="{{ intval($item->order) - 4 }}">
                                        <small id="order_error"
                                            class="order_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="side_list1">Url Youtube</label>
                                        <input type="text" name="url" class="form-control" id="url"
                                            value="{{ $item->url }}">
                                        <small id="url_error"
                                            class="url_error input-group text-sm mt-2 text-danger error"></small>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <button type="button" onclick="validatePrompt('slide-tab-edit-youtube{{ $item->id }}')"
                        class="btn btn-danger m-2">
                        Update Slider </button>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script src="{{ asset('/') }}dist/js/main.js"></script>
    <script src="{{ asset('/') }}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="{{ asset('/') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}plugins/summernote/summernote-bs4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
    <script>
        $("#yearDate").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });

        function parsingDataToModal(id) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
            });

            var url = "{{ route('edit-laporan-tahunan-list', ':id') }}";
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
        $(function() {
            $('#side-list-visi-misi').DataTable({
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

        function deletePrompt(id) {
            var url = "{{ route('store-karir-delete', ':id') }}";
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
                        type: "POST",
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


        $(".desc").summernote({
            height: 250,
        });
    </script>
@endsection
