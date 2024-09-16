<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-danger navbar-dark border-0">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <div class="user-panel d-flex dropdown" data-toggle="dropdown">
          <div class="image">
            <i class="fas fa-user" style="font-size: 25px;"></i>
          </div>
          <div class="info">
            <span class="d-block">Hi, {{session()->get('name')}}</span>
          </div>
        </div>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editPassword">
              {{ __('Ubah Password') }}
            </button>
            <button type="submit" class="dropdown-item">
              {{ __('Log Out') }}
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Modal -->
  <div class="modal fade" id="editPassword" tabindex="-1" aria-labelledby="editPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPasswordLabel">Ubah Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('ubah-password') }}" id="ubah-password">
            @csrf
            <div class="form-group">
              <label for="content-title">Password Lama</label>
              <div class="row">
                <div class="col">
                  <input type="password" class="form-control" name="old_password">
                </div>                
              </div>
            </div>
            <div class="form-group">
              <label for="content-title">Password Baru</label>
              <div class="row">
                <div class="col">
                  <input type="password" class="form-control" name="new_password">
                </div>                
              </div>
            </div>
            <div class="form-group">
              <label for="content-title">Ulangi Password Baru</label>
              <div class="row">
                <div class="col">
                  <input type="password" class="form-control" name="new_password_confirmation">
                </div>                
              </div>
            </div>
            <small id="new_password_error" class="new_password_error input-group text-sm mt-2 text-danger error"></small>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" onclick="validatePrompt('ubah-password')">Save changes</button>
        </div>
      </div>
    </div>
  </div>