<div class="container">
    <div class="row">
        <div class="col">
            <div class="section">
                <div class="section-body">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" onclick="btnAdd()" class="btn btn-primary">
                                Add
                            </button>
                            <table class="table" id="user">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Umur</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type=" button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="formUser">
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



</div>
<script>
    var save_method;

    function showPass() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";

        } else {
            x.type = "password";
        }
    }

    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "<?= base_url('api/User'); ?>",
            dataType: "json",
            success: function(data) {
                let user = data.users;
                var no = 1;
                $.each(user, function(data, item) {
                    var html = '<tr>' +
                        '<td>' + no++ + '</td>' +
                        '<td>' + item.email + '</td>' +
                        '<td>' + item.birthdate + ' Tahun' + '</td>' +
                        '<td>' +
                        '<button class="btn btn-primary" onClick="btnEdit(' + item.id + ')">' + 'Edit' +
                        '<span class="ion ion-ios-trash" data-pack="ios" data-tags="delete, remove, dispose, waste, basket, dump, kill">' +
                        '</span>' +
                        '</button>' +
                        '<button class="btn btn-danger" id="btnDel" onClick="btnDel(' + item.id + ')">' + 'Delete' + '</button>' +
                        '</td>' +
                        '</tr>';

                    $('#user tr').last().after(html);
                });
            },
        });
        $('#edit').hide();
    })



    function btnAdd() {
        $('#modalUser').modal('show');
        $('.modal-title').text('Add User');
        $('#formUser')[0].reset();
        $('#edit').hide();
        document.getElementById('modal_footer').innerHTML = '' +
            '<button type="button" id="save" class="btn btn-primary" onclick=save()>Save</button>' +
            '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
    }


    function btnEdit(id) {
        $('#modalUser').modal('show');
        $('.modal-title').text('Edit User');
        $('#formUser')[0].reset();
        $('#save').hide();
        $.ajax({
            type: "GET",
            url: "api/User/show/" + id,
            dataType: "json",
            success: function(data) {
                let user = data.users;
                $('#id_user').val(user.id);
                $('#email').val(user.email);
                $('#birthdate').val(user.birthdate);
                document.getElementById('modal_footer').innerHTML = '' +
                    '<button type="button" id="edit" class="btn btn-primary" onclick=edit("' + user.id + '")>Edit</button>' +
                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';

                $('#edit').show();
            },
        });
    }

    function edit(id) {
        $.ajax({
            type: "PUT",
            url: "<?= base_url('api/User/edit/'); ?>" + id,
            data: $('#formUser').serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#btnSave').prop('disabled', true);
                $('#btnSave').html('Loading');
            },
            complete: function() {
                $('#btnSave').prop('disabled', false);
                $('#btnSave').html('Edit');
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
                        html: `Data Berhasil Di edit`,
                    }).then((result) => {
                        if (result.value) {
                            $('#modalUser').modal('hide');
                            window.location.reload();
                        }
                    })
                }
            }
        });
    }

    function save() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('api/User/create'); ?>",
            data: $('#formUser').serialize(),
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
                        html: `Data Berhasil Di tambahkan`,
                    }).then((result) => {
                        if (result.value) {
                            $('#modalUser').modal('hide');
                            window.location.reload();
                        }
                    })
                }
            }
        });
    }

    function btnDel(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda Akan Menghapus Data Ini!",
            icon: 'warning',
            reverseButtons: true,
            showCancelButton: true,
            confirmButtonText: 'Yes, Hapus Data!',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "<?= site_url('api/User/delete/'); ?>" + id,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Data Berhasil Di Delete',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                    var btn = $('#btnDel');
                                    btn.prop('disabled', true);
                                    setTimeout(function() {
                                        btn.prop('disabled', false);
                                    }, 3000);
                                }
                            })
                        }
                    }
                });
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Data Tidak Jadi Di Hapus',
                    'error'
                )
            }
        })
    }
</script>