<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title><?= $title; ?></title>
</head>

<body>
    <div class="container">
        <div class="col">
            <div class="row">
                <h1 style="text-align: center;">Login</h1>
                <div class="invalid-feedback">

                </div>
                <form action="#" id="loginForm">
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control" />
                        <label class="form-label" for="email">Email address</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" id="password" name="password" class="form-control" />
                        <label class="form-label" for="password">Password</label>
                    </div>

                    <!-- 2 column grid layout for inline styling -->
                    <div class="row mb-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="showpass" class="custom-control-input" tabindex="3" id="showpass" onclick="showPass()">
                                <label class="custom-control-label" for="showpass">Show Password</label>
                            </div>
                        </div>
                    </div>
                </form>

                <button type="submit" id="login" onclick="login()" class="btn btn-primary btn-block mb-4">Sign in</button>
                <button type="button" onclick="register()" class="btn btn-primary btn-block mb-4">Register</button>



            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRegister" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type=" button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="formRegister">
                    <input type="text" value="" id="id_user" hidden>
                    <div class="modal-body">
                        <div class="invalid-feedback error">

                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="form-group">
                                    <div class="label">Email</div>
                                    <input type="text" value="" class="form-control" placeholder="email" id="email" name="email">

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="label">Birthdate</div>
                                    <input type="number" value="" class="form-control" placeholder="BirthDate" id="birthdate" name="birthdate">

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="label">Password</div>
                                    <input type="password" value="" class="form-control" id="password" name="password">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="showpass" class="custom-control-input" tabindex="3" id="showpass" onclick="showPass()">
                                    <label class="custom-control-label" for="showpass">Show Password</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="modal-footer" id="modal_footer">

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        function showPass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";

            } else {
                x.type = "password";
            }
        }

        function register() {
            $('#modalRegister').modal('show');
            $('.modal-title').text('Add User');
            $('#formRegister')[0].reset();
            $('#edit').hide();
            document.getElementById('modal_footer').innerHTML = '' +
                '<button type="button" id="save" class="btn btn-primary" onclick=save()>Save</button>' +
                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
        }

        function save() {
            $.ajax({
                type: "POST",
                url: "<?= site_url('api/User/create'); ?>",
                data: $('#formRegister').serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#btnSave').prop('disabled', true);
                    $('#btnSave').html('Loading');
                },
                complete: function() {
                    $('#btnSave').prop('disabled', false);
                    $('#btnSave').html('Save');
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $(".error").css('display', 'none');
                    } else {
                        $(".error").css('display', 'block');
                        $(".error").html(data.error);
                    }
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            html: `Register Berhasil`,
                        }).then((result) => {
                            if (result.value) {
                                $('#modalRegister').modal('hide');
                                window.location.reload();
                            }
                        })
                    }
                }
            });
        }

        function login() {
            $.ajax({
                type: "POST",
                url: "<?= base_url('auth/Login/login'); ?>",
                data: $('#loginForm').serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#login').prop('disabled', true);
                    $('#login').html('Loading');
                },
                complete: function() {
                    $('#login').prop('disabled', false);
                    $('#login').html('Login');
                },
                success: function(data) {
                    if (data.success) {
                        console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            html: `Anda Berhasil Login`,
                        }).then((result) => {
                            if (result.value) {
                                window.location.replace("<?= base_url('home'); ?>")
                            }
                        })
                    }
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Username atau password salah',
                        })
                    }
                }
            });
        }
    </script>
</body>

</html>