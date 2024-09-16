function validatePrompt(formName) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true,
    });
    Swal.fire({
        title: "Save this data?",
        showCancelButton: true,
        confirmButtonText: "Save",
        confirmButtonColor: "#ea0a2a",
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var form = $("#" + formName);
            console.log(form[0]);
            var formData = new FormData(form[0]);
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Mohon Tunggu ...',     
                        allowOutsideClick: false,     
                        confirmButtonText: "Terjadi Kesalahan",
                        confirmButtonColor: "#ea0a2a",
                        didOpen: () => {                            
                          Swal.showLoading()                        
                        }
                      })
                },
                success: function (data) {
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
                error: function (reject) {
                    Swal.hideLoading();
                    var response = $.parseJSON(reject.responseText);
                    if (response) {
                        $.each(response.errors, function (key, val) {
                            console.log(key);
                            $("." + key + "_error").text(val[0]);
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    }
                },
            });
        }
    });
}
