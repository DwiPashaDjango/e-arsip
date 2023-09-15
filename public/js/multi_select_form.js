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

    $('#regencie_id').attr('disabled', true);
    $('#district_id').attr('disabled', true);
    $('#village_id').attr('disabled', true);

    $('#store').click(function (e) {
        e.preventDefault();
        let kepsek = $("#kepsek").val();
        let npsn = $("#npsn").val();
        let nss = $("#nss").val();
        let akreditasi = $("#akreditasi").val();
        let province_id = $("#province_id").val();
        let regencie_id = $("#regencie_id").val();
        let district_id = $("#district_id").val();
        let village_id = $("#village_id").val();
        let alamat = $("#alamat").val();
        let status_sekolah = $("#status_sekolah").val();
        let avatars = $("#avatars")[0].files[0];

        let formData = new FormData();
        formData.append('kepsek', kepsek);
        formData.append('npsn', npsn);
        formData.append('nss', nss);
        formData.append('akreditasi', akreditasi);
        formData.append('province_id', province_id);
        formData.append('regencie_id', regencie_id);
        formData.append('district_id', district_id);
        formData.append('village_id', village_id);
        formData.append('alamat', alamat);
        formData.append('status_sekolah', status_sekolah);
        formData.append('avatars', avatars);

        $.ajax({
            url: '/dashboard/bios',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $('#province_id').change(function (e) {
        e.preventDefault();
        let province_id = $(this).val();

        if (province_id == "") {
            $('#regencie_id').attr('disabled', true);
            $('#regencie_id').html('<option></option>');
        } else {
            $.ajax({
                url: '/dashboard/regencie',
                type: 'POST',
                data: {
                    province_id: province_id
                },
                dataType: 'json',
                success: function (result) {
                    $('#regencie_id').attr('disabled', false);
                    $('#regencie_id').html('<option>- Pilih -</option>');
                    $.each(result.regencie, function (key, val) {
                        $('#regencie_id').append('<option value="'+val.id+'">' + val.name +'</option>');
                    });
                },
                error: function (err) {
                    console.log(err);
                }
            })
        }
    });

    $('#regencie_id').change(function (e) {
        e.preventDefault();
        let regencie_id = $(this).val();
        if (regencie_id == "- Pilih -") {
            $('#district_id').attr('disabled', true);
            $('#district_id').html('<option></option>');
        } else {
            $.ajax({
                url: '/dashboard/district',
                type: 'POST',
                data: {
                    regencie_id: regencie_id
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    $('#district_id').attr('disabled', false);
                    $('#district_id').html('<option>- Pilih -</option>');
                    $.each(result.district, function (key, val) {
                        $('#district_id').append('<option value="'+val.id+'">' + val.name +'</option>');
                    });
                },
                error: function (err) {
                    console.log(err);
                }
            })
        }
    });

    $('#district_id').change(function (e) {
        e.preventDefault();
        let district_id = $(this).val();
        if (district_id == "- Pilih -") {
            $('#village_id').attr('disabled', true);
            $('#village_id').html('<option></option>');
        } else {
            $.ajax({
                url: '/dashboard/village',
                type: 'POST',
                data: {
                    district_id: district_id
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    $('#village_id').attr('disabled', false);
                    $('#village_id').html('<option>- Pilih -</option>');
                    $.each(result.village, function (key, val) {
                        $('#village_id').append('<option value="'+val.id+'">' + val.name +'</option>');
                    });
                },
                error: function (err) {
                    console.log(err);
                }
            })
        }
    });
})
