@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <div class="container">
        <form style="display: none" class="form-control mb-3" id="form-manufacturers" method="post">
            <label for="name">Название</label>
            <input type="text" name="title" class="form-control">

            <label for="country">Страна</label>
            <input type="text" class="form-control" name="country">

            <div style="color: red" class="errors"></div>

            <button type="submit" class="btn btn-success mt-2">Добавить</button>
        </form>

        <ul style="list-style-type: none;;display: flex; justify-content: space-evenly">
            <li style="cursor: pointer;" id="sort-name" data-in="desc">
                <button class="btn btn-warning">По дате</button>
            </li>
            <li>
                <select name="" class="form-select" id="sort-manufacturer">
                    <option value="0">Все компании</option>
                    @foreach($manufacturer as $man)
                        <option value="{{ $man->id }}">{{ $man->title }}</option>
                    @endforeach
                </select>
            <li>
                <input type="text" id="search" class="form-control" placeholder="Поиск">
            </li>
            <li>
                <button class="btn btn-primary">Добавить товар</button>
            </li>
            <li>
                <button type="button" class="btn btn-secondary">Добавить производителя</button>
            </li>
        </ul>
        <div class="row mt-5">
            @foreach($products as $product)
                <div class="card m-1 product-block" data-id="{{ $product->id }}" style="width: 18rem;">
                    <img class="card-img-top" src="{{ asset($product->image) }}" alt="" width="200" height="150">
                    <div class="card-body">
                        <b>{{ $product->name }}</b>
                        <p class="card-text">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавление товара</h2>
            <div class="container mt-3">
                <form action="" method="post" id="upload-image-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" name="manufacturers[]" id="multi-select">
                            @foreach($manufacturer as $man)
                                <option value="{{ $man->id }}">{{ $man->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="container mt-3">
                        <label for="name">Название товара</label>
                        <input type="text" id="product-name" class="form-control" name="name">
                    </div>

                    <div class="container mt-3">
                        <label for="exampleFormControlTextarea1">Описание товара</label>
                        <textarea class="form-control" name="description" id="product-description"
                                  rows="3"></textarea>
                    </div>

                    <div class="container mt-3">
                        <label for="image">Изображение</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div style="color: red" class="errors"></div>
                    <div class="container mt-3">
                        <button type="submit" class="btn btn-outline-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModalUpdate" class="modalUpdate">
        <!-- Modal content -->
        <div class="modal-content-update">
            <span class="close-update">&times;</span>
            <h2>Обновление товара</h2>
            <div class="container mt-3">
                <form action="" method="post" id="upload-form-update" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" name="manufacturers[]" id="multi-select">
                            @foreach($manufacturer as $man)
                                <option value="{{ $man->id }}">{{ $man->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="container mt-3">
                        <label for="name">Название товара</label>
                        <input type="text" id="product-name" class="form-control" name="name">
                    </div>

                    <div class="container mt-3">
                        <label for="exampleFormControlTextarea1">Описание товара</label>
                        <textarea class="form-control" name="description" id="product-description"
                                  rows="3"></textarea>
                    </div>

                    <div style="color: red" class="errorsUpdate"></div>
                    <div class="container mt-3">
                        <button type="button" class="btn btn-outline-danger">Удалить</button>
                        <button type="submit" class="btn btn-outline-success">Обновить</button>
                    </div>

                    <input type="hidden" name="product_id" id="product_id">
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.js') }}"></script>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            $('#form-manufacturers').submit(function (e) {
                e.preventDefault();

                var fd = new FormData(this);
                $.ajax({
                    method: "POST",
                    url: "/manufacturers/store",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                            location.reload()
                        alert('Производитель добавлен!')
                    },
                    error: function (result) {
                        if (result.status == 400) {
                            $('.errors').empty()
                            $.each(result, function (index, value) {
                                $.each(value['errors'], function (i, v) {
                                    $('.errors').append(v + '<br>')
                                })
                            })
                        }
                    }
                });
            })

            $(document).on("click", ".row .product-block", function () {
                $.ajax({
                    method: "GET",
                    url: "/products/show/" + $(this).attr('data-id'),
                    success: function (result) {
                        $("#upload-form-update #multi-select>option").map(function () {

                            if (jQuery.inArray(parseInt($(this).val()), result['relations']) != -1) {
                                return $(this).prop("selected", "selected")
                            }
                        });

                        var formUpdate = $('#upload-form-update');
                        formUpdate.find('#product-name').val(result['name']);
                        formUpdate.find('#product-description').val(result['description']);
                        formUpdate.find('#product_id').val(result['id']);
                        $('.modalUpdate').css('display', 'block')
                        formUpdate.submit(function (e) {
                            e.preventDefault();
                        });
                    }
                })
            })

            $('.btn-secondary').click(function () {
                $('#form-manufacturers').toggle()
            });

            $('#upload-image-form').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    method: "POST",
                    url: "/products/store",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result[0] == 'success') {
                            $('.modal').css('display', 'none');
                            $('.row').empty();
                            sortBy();
                            alert('Товар добавлен!')
                        }
                    },
                    error: function (result) {
                        if (result.status == 400) {
                            $('.errors').empty()
                            $.each(result, function (index, value) {
                                $.each(value['errors'], function (i, v) {
                                    $('.errors').append(v + '<br>')
                                })
                            })
                        }
                    }
                });
            });

            $('.btn-outline-danger').click(function () {
                confirm('Вы уверены что хотите удалить товар?')
                $.ajax({
                    method: "POST",
                    url: "/products/delete/" + $('#product_id').val(),
                    success: function (result) {
                        if (result[0] == 'success') {
                            $('.modalUpdate').css('display', 'none');
                            $('.row').empty();
                            sortBy();
                            alert('Товар удален!')
                        }
                    }
                });
            })

            $('#upload-form-update').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    url: "/products/update",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result[0] == 'success') {
                            $('.modalUpdate').css('display', 'none');
                            $('.row').empty();
                            sortBy();
                            alert('Товар обновлен!')

                        }
                    },
                    error: function (result) {
                        if (result.status == 400) {
                            $('.errors').empty()
                            $.each(result, function (index, value) {
                                $.each(value['errors'], function (i, v) {
                                    $('.errorsUpdate').append(v + '<br>')
                                })
                            })
                        }
                    }
                });
            });

            $('#sort-name').click(function () {
                sortBy()
            })

            function sortBy() {
                var sort_name = $('#sort-name')
                $.ajax({
                    method: "GET",
                    url: "/products/sort",
                    data: {
                        order_by: sort_name.attr('data-in')
                    },
                    success: function (result) {
                        if (sort_name.attr('data-in') === 'asc') {
                            sort_name.attr('data-in', 'desc');
                        } else {
                            sort_name.attr('data-in', 'asc');
                        }
                        $('.row').empty();
                        $.each(result, function (index, value) {
                            rowAppend(value)
                        })
                    }
                });
            }

            $('#sort-manufacturer').on("change", function () {
                $.ajax({
                    method: "GET",
                    url: "/products/sort",
                    data: {
                        manufacturer: $(this).find(":selected").val()
                    },
                    success: function (result) {
                        $('.row').empty();
                        $.each(result, function (index, value) {
                            rowAppend(value)
                        })
                    }
                })
            })

            $('#search').bind("keyup", function () {
                $.ajax({
                    method: "GET",
                    url: "/products/sort",
                    data: {
                        search: $(this).val()
                    },
                    success: function (result) {
                        $('.row').empty();
                        $.each(result, function (index, value) {
                            rowAppend(value)
                        })
                    }
                })
            })

            $('.btn-primary').click(function () {
                $('.modal').css('display', 'block')
            })
            $('.close, .close-update').click(function () {
                $('.modal, .modalUpdate').css('display', 'none')
            })

            function rowAppend(value) {
                $('.row').append('<div data-id="' + value['id'] + '" class="card m-1 product-block" style="width: 18rem;">' +
                    '                    <img class="card-img-top" src="' + value['image'] + '" alt="" width="200" height="150">' +
                    '                    <div class="card-body">' +
                    '                        <b>' + value['name'] +
                    '                        <p class="card-text">' +
                    '                            ' + value['description'] +
                    '                        </p>' +
                    '                    </div>' +
                    '                </div>')
            };
        })
    </script>

@endsection


