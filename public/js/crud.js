$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    })

    let table = $('#table-petugas').DataTable({
        serverSide: true,
        ajax: '/petugas',
        responsive: true,
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'username'},
            {data: 'email' },
            {data: 'status'},
            {data: 'action'},
        ]
    });

    $('#create').click(function(e) {
        e.preventDefault();
        $('#userModal').modal('show');
        $('#userModalLabel').html('Tambah Petugas Baru');
        $('.modal-footer').html(
            `<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="store">Simpan</button>`
        );
        $('#hide').show();
        $('.novalidate')[0].reset();
    });

    $(document).on('click', '#store', function(e) {
        e.preventDefault();
        let username = $('#username').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();

        $.ajax({
            url: '/petugas',
            type: 'POST',
            data: {
                username: username,
                email: email,
                password: password,
                password_confirmation: password_confirmation
            },
            success: function(result) {
                if (result.errors) {
                    $.each(result.errors, function (key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('.invalid-feedback-' + key).html(val)
                    });
                } else {
                    $('#userModal').modal('hide')
                    table.draw();
                    Toast.fire({
                        icon: 'success',
                        title: result.success
                    });
                    $('.novalidate')[0].reset();
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $(document).on('click', '#banned', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            title: 'Warning !',
            text: "Anda yakin ingin menonaktifkan petugas ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Nonaktifkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/petugas/banned/' + id,
                    type: 'PUT',
                    dataType: 'json',
                    success: function (result) {
                        table.draw();
                        Toast.fire({
                            icon: 'success',
                            title: result.success
                        });
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }
        })
    });

    $(document).on('click', '#unbanned', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: '/petugas/unbanned/' + id,
            type: 'PUT',
            dataType: 'json',
            success: function (result) {
                table.draw();
                Toast.fire({
                    icon: 'success',
                    title: result.success
                });
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#edit', function (e) {
        let id = $(this).data('id');
        $.ajax({
            url: '/petugas/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                $('#userModal').modal('show');
                $('#userModalLabel').html(result.username)
                $('.modal-footer').html(
                    `<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="update">Update</button>`
                );

                $('#id').val(result.id);
                $('#username').val(result.username);
                $('#email').val(result.email);
                $('#hide').hide();
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#update', function (e) {
        e.preventDefault();
        let id = $('#id').val();
        let username = $('#username').val();
        let email = $('#email').val();

        $.ajax({
            url: '/petugas/' + id,
            type: 'PUT',
            data: {
                username: username,
                email: email
            },
            success: function (result) {
                if (result.errors) {
                    $.each(result.errors, function (key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('.invalid-feedback-' + key).html(val)
                    });
                } else {
                    $('#userModal').modal('hide')
                    table.draw();
                    Toast.fire({
                        icon: 'success',
                        title: result.success
                    });
                    $('.novalidate')[0].reset();
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            title: 'Warning !',
            text: "Anda yakin ingin menghapus petugas ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/petugas/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function (result) {
                        table.draw();
                        Toast.fire({
                            icon: 'success',
                            title: result.success
                        });
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }
        })
    });
})
