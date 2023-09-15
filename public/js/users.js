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

    let table = $('#table-users').DataTable({
            serverSide: true,
            ajax: '/users',
            responsive: true,
            columns: [
                { data: 'DT_RowIndex' },
                { data: 'username' },
                { data: 'kepsek' },
                { data: 'email' },
                { data: 'npsn' },
                { data: 'nss' },
                { data: 'akreditasi' },
                { data: 'status' },
                { data: 'action' },
            ],
        });

    $('#create').click(function (e) {
        e.preventDefault();
        $("#userModal").modal('show');
        $('#userModalLabel').html('Tambah Data Sekolah');
        $('.modal-footer').html(
            `<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="store">Simpan</button>`
        );
        $('.novalidate')[0].reset();
        $('#hide').show();
    });

    $(document).on('click', '#store', function (e) {
        e.preventDefault();
        let username = $("#username").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let password_confirmation = $('#password_confirmation').val();

        let data = {
            username: username,
            email: email,
            password: password,
            password_confirmation: password_confirmation
        };

        $.ajax({
            url: '/users',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.errors) {
                    $.each(result.errors, function (key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('.invalid-feedback-' + key).html(val);
                        setTimeout(() => {
                            $('#' + key).removeClass('is-invalid');
                            $('.invalid-feedback-' + key).html('');
                        }, 2000);
                    });
                } else {
                    $('#userModal').modal('hide')
                    table.draw();
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    $('.novalidate')[0].reset();
                    setTimeout(() => {
                        table.draw();
                    }, 1500);
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#unbanned', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: '/users/unbanned/' + id,
            type: 'PUT',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (result) {
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });
                setTimeout(() => {
                    table.draw();
                }, 2000);
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#banned', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let username = $(this).data('username');
        Swal.fire({
            title: 'Warning !',
            text: "Anda yakin ingin menonaktifkan " + username,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Nonaktifkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/banned/' + id,
                    type: 'PUT',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (result) {
                        Toast.fire({
                            icon: 'success',
                            title: result.message
                        });
                        setTimeout(() => {
                            table.draw();
                        }, 2000);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }
        })
    });

    $(document).on('click', '#show', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.wizard-content')[0].reset();
        $("#province_id").html('');
        $("#regencie_id").html('');
        $("#district_id").html('');
        $("#village_id").html('');
        $("#akreditasi").html('');
        $.ajax({
            url: '/users/show/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                $("#showModal").modal('show');
                $('#showModalLabel').html(result.data.username);

                if (result.data.bio == null) {
                    $("#activate").html('<div class="alert alert-danger">Status Sekolah Belum Melengkapi Data</div>');
                } else {
                    $("#activate").html('<div class="alert alert-success">Status Sekolah Sudah Melengkapi Data</div>');
                }

                $("#akreditasi").attr('disabled', true);
                $("#province_id").attr('disabled', true);
                $("#regencie_id").attr('disabled', true);
                $("#district_id").attr('disabled', true);
                $("#village_id").attr('disabled', true);

                $("#username-show").val(result.data.username);
                $("#email-show").val(result.data.email);
                if (result.data.bio != null) {
                    $("#kepsek").val(result.data.bio.kepsek);
                    $("#npsn").val(result.data.bio.npsn);
                    $("#nss").val(result.data.bio.nss);
                    $("#akreditasi").html('<option>'+result.data.bio.akreditasi+'</option>');

                    $("#province_id").html('<option>' + result.data.bio.province.name + '</option>');
                    $("#regencie_id").html('<option>' + result.data.bio.regencie.name + '</option>');
                    $("#district_id").html('<option>' + result.data.bio.district.name + '</option>');
                    $("#village_id").html('<option>' + result.data.bio.village.name + '</option>');
                    $("#alamat").val(result.data.bio.alamat);
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#edit', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: '/users/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                $('#userModal').modal('show')
                $('#userModalLabel').html(result.data.username);
                $('.modal-footer').html(
                    `<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="update">Update</button>`
                );
                $('#hide').hide();

                $("#id").val(result.data.id);
                $("#username").val(result.data.username);
                $("#email").val(result.data.email);
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#update', function (e) {
        e.preventDefault();
        let id = $('#id').val();
        let username = $("#username").val();
        let email = $("#email").val();

        const data = {
            username: username,
            email: email,
        };

        $.ajax({
            url: '/users/update/' + id,
            type: 'PUT',
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.errors) {
                    $.each(result.errors, function (key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('.invalid-feedback-' + key).html(val);
                        setTimeout(() => {
                            $('#' + key).removeClass('is-invalid');
                            $('.invalid-feedback-' + key).html('');
                        }, 2000);
                    });
                } else {
                    $('#userModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    setTimeout(() => {
                        table.draw();
                    }, 2000);
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
        let username = $(this).data('username');
        Swal.fire({
            title: 'Warning !',
            text: "Anda yakin ingin menghapus " + username,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/delete/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    cache: false,
                    success: function (result) {
                        Toast.fire({
                            icon: 'success',
                            title: result.message
                        });
                        setTimeout(() => {
                            table.draw();
                        }, 2000);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }
        })
    });
})
