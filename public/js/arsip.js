$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    getJsonArsip();
    var imageUrl = '../assets/img/not_found_arsip.jpg';

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

    $('.daterange-cus').daterangepicker({
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
    }, function (start, end) {
        var start_date = start.format('YYYY-MM-DD');
        var end_date = end.format('YYYY-MM-DD');
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
    });

    $('#create').click(function (e) {
        e.preventDefault();
        $('#arsipModal').modal('show');
        $('#arsipModalLabel').html('Buat Arsip Baru');
        $('.modal-footer').html(
            `<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="store">Simpan</button>`
        );
        $('#hide').show();
        $('.novalidate')[0].reset();
    });

    $(document).on('click', '#store', function (e) {
        e.preventDefault();
        let title = $('#title').val();
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let description = $('#description').val();

        let formData = new FormData();
        formData.append('title', title);
        formData.append('start_date', start_date);
        formData.append('end_date', end_date);
        formData.append('description', description);

        $.ajax({
            url: '/arsip',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                if (result.errors) {
                    $.each(result.errors, function (key, val) {
                        $('#' + key).fadeIn();
                        $('#' + key).addClass('is-invalid');
                        $('.invalid-feedback-' + key).html(val);
                        setTimeout(() => {
                            $('#' + key).removeClass('is-invalid');
                            $('.invalid-feedback-' + key).html('');
                        }, 2000);
                    });
                } else {
                    $('#arsipModal').modal('hide');
                    $('.novalidate')[0].reset();
                    Toast.fire({
                        icon: 'success',
                        title: result.success
                    });
                    getJsonArsip();
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    });

    $(document).on('click', '#link-open', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let title = $(this).data('title');
        window.location.href = '/arsip/&title=' + title + '&arsip=' + id
    });

    $('#search').keyup(function (e) {
        let search = $('#search').val();
        let page = '';
        searchContent(search, page);
    });

    $(document).on('click', '.page-link', function (e) {
        let url = $(this).data('page');
        console.log(url);
        getJsonArsip(url);
    });

    $('#years').change(function (e) {
        e.preventDefault();
        let years = $(this).val();
        filterByYear(years);
    });

    function getJsonArsip(url = '/arsip/json') {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                let page = result.arsip;
                let html = '';
                paginationArsip(page);
                if (result.arsip.data.length > 0) {
                    $.each(result.arsip.data, function (key, value) {
                        html += `<div class="col-lg-3 col-md-6">
                                    <div class="wizard-step wizard-step-active" id="link-open" data-id="${value.id}" data-title="${value.title}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            ${value.title}
                                        </div>
                                    </div>
                                </div>`;
                    });
                    $('#append').html(html);
                    $('#append-2').html('');
                } else {
                    $('#append-2').html(
                        ` <img src="${imageUrl}" class="image-not-found" alt="" />`
                    );
                    $('#paginate').html('')
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    }

    function paginationArsip(page)
    {
        let paginate = '';
        paginate += `<li class="page-item">
                            <a class="page-link" href="javascript:void(0)" data-page="${page.prev_page_url}" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">${page.current_page} <span class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" data-page="${page.next_page_url}"><i class="fas fa-chevron-right"></i></a>
                        </li>`;
        $('#paginate').html(paginate);
    }

    function searchContent(search, page, url = '/arsip/json/search') {
        if (search === '') {
            getJsonArsip();
            // paginationArsip(page);
        } else {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    search: search
                },
                dataType: 'json',
                success: function (result) {
                    $('#paginate').html('')
                    if (result.data.length > 0) {
                        $('#append').html('');
                        let html = '';
                        $.each(result.data, function (key, value) {
                            html += `<div class="col-md-3">
                                        <div class="wizard-step wizard-step-active" id="link-open" data-title="${value.title}">
                                            <div class="wizard-step-icon">
                                                <i class="fas fa-folder-open"></i>
                                            </div>
                                            <div class="wizard-step-label">
                                                ${value.title}
                                            </div>
                                        </div>
                                    </div>`;
                        });
                        $('#append').html(html);
                        $('#append-2').html('');
                    } else {
                        $('#append').html('');
                        $('#append-2').html(
                            ` <img src="${imageUrl}" class="image-not-found" alt="" />`
                        );
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    }

    function filterByYear(years, page) {
        if (years == "") {
            getJsonArsip();
        }
        $.ajax({
            url: '/arsip/filter',
            type: 'POST',
            data: {
                years: years
            },
            dataType: 'json',
            success: function (result) {
                let html = '';
                if (result.data.length > 0) {
                   $.each(result.data, function (key, value) {
                        html += `<div class="col-md-3">
                                    <div class="wizard-step wizard-step-active" id="link-open" data-title="${value.title}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            ${value.title}
                                        </div>
                                    </div>
                                </div>`;
                    });
                    $('#append').html(html);
                    $('#append-2').html('');
                    $('#paginate').html('');
                } else {
                    $('#append').html('');
                    $('#append-2').html(
                        ` <img src="${imageUrl}" class="image-not-found" alt="" />`
                    );
                }
            },
            error: function (err) {
                console.log(err);
            }
        })
    }
});
