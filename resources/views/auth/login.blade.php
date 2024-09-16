@extends('../layouts.main')

@section('headers')
<link rel="stylesheet" href="{{ asset('/') }}dist/css/login.css">
@endsection

@section('content')

<body class="hold-transition login-page  text-dark">
  <div class="login-box justify-content-center align-items-center">
    <div class="card">
      <div class="card-header text-center">
        <img src="{{ asset('/') }}dist/img/logo-red.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8" width="75">
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="{{ route('login-action') }}" method="post" novalidate id="login-form">
          @csrf
          <div class="input-group mb-3">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            <small id="email_error" class="input-group text-sm mt-2 text-danger error"></small>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <small id="password_error" class="input-group text-sm mt-2 text-danger error"></small>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="button" onclick="validate()" class="btn btn-danger btn-block">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="{{ asset('/') }}plugins/jquery/jquery.min.js"></script>
  <script src="{{ asset('/') }}plugins/sweetalert2/sweetalert2.all.min.js"></script>
  <script>
    function validate() {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
      })

      var form = $('#login-form');
      $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        beforeSend: function() {
          $('.error').text('');
        },
        success: function(data) {
          data = JSON.parse(data);
          if (data['status'] == 'success') {
            Toast.fire({
              icon: 'success',
              title: data['message']
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "{{ route('dashboard') }}";
              }
            })
          } else {
            Toast.fire({
              icon: 'error',
              title: data['message']
            })
          }
        },
        error: function(reject) {
          var response = $.parseJSON(reject.responseText);
          if (response) {
            $.each(response.errors, function(key, val) {
              $("#" + key + "_error").text(val[0]);
            })
            if (response.errors == null) {
              Toast.fire({
                icon: 'error',
                title: 'Signed in failed'
              })
            }
          } else {
            Toast.fire({
              icon: 'error',
              title: 'Signed in failed'
            })
          }
        }
      });
    }
  </script>
</body>

</html>
@endsection