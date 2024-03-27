@extends('dashboard.layouts.main')

@section('content')
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <form action="/pickup-delivery" method="post">
                    @csrf
                    <div class="card">
                        <h4 class="card-header">Pickup & Delivery Order</h4>
                        <div class="card-body">
                            <input type="hidden" name="transaction_type" value="pickup-delivery">
                            <input type="hidden" name="lat">
                            <input type="hidden" name="long">
                            <div class="mb-3">
                                <label for="whatsapp_number" class="form-label">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon14">+62</span>
                                    <input class="form-control @error('whatsapp_number') is-invalid @enderror" type="text" id="whatsapp_number" name="whatsapp_number" placeholder="81234567890" autocomplete="off" value="{{ old('whatsapp_number') }}" required>
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback text-start">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <a href="javascript:void(0)" class="btn btn-danger btn-check-number" style="min-width: 80px">Check</a>
                                </div>
                                <small class="text-danger">* periksa nomor terdaftar terlebih dahulu</small>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Nama kamu" autocomplete="off" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" rows="2" name="address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback text-start">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input class="form-check-input" type="checkbox" name="coordinateOption" id="coordinateOption" checked>
                                <label class="form-check-label ms-1" for="coordinateOption">
                                    Gunakan koordinat saya saat ini agar pengantaran lebih akurat
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="item-container mt-3" id="item-container">
                        <div class="card">
                            <h4 class="card-header">Detail Item</h4>
                            <div class="card-body detail-item-container">
                                <div class="item-1">
                                    <h5>Item - 1</h5>
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="detail_transactions[0][type]" id="sepatu[0]" value="sepatu" checked/>
                                            <label class="form-check-label" for="sepatu[0]">Sepatu</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="detail_transactions[0][type]" id="tas[0]" value="tas" />
                                            <label class="form-check-label" for="tas[0]">Tas</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="detail_transactions[0][item_name]" class="form-label">Merk Item</label>
                                        <input class="form-control @error('detail_transactions[0][item_name]') is-invalid @enderror" type="text" id="detail_transactions[0][item_name]" name="detail_transactions[0][item_name]" placeholder="Eg: Nike Air Jordan / Tas Gucci" autocomplete="off" value="{{ old('detail_transactions[0][item_name]') }}" required>
                                        @error('detail_transactions[0][item_name]')
                                            <div class="invalid-feedback text-start">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="input-group" id="colorSizeField">
                                            <span class="input-group-text">Warna & Ukuran</span>
                                            <input type="text" name="detail_transactions[0][color]" class="form-control" autocomplete="off" placeholder="Eg: Hitam" required>
                                            <input type="text" name="detail_transactions[0][size]" class="form-control" autocomplete="off" id="detail_transactions[0][size]" placeholder="Eg: 41">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="detail_transactions[0][treatment_id]" class="form-label">Treatment</label>
                                        <select name="detail_transactions[0][treatment_id]" id="detail_transactions[0][treatment_id]" class="form-select">
                                            @foreach ($treatments as $treatment)
                                            <option value="{{ $treatment->id }}" {{ (old('detail_transactions[0][treatment_id]') == $treatment->id) ? 'selected' : '' }}>{{ $treatment->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-add"><i class="bx bx-plus"></i></button>
                                        {{-- <button class="btn btn-danger"><i class="bx bx-trash"></i></button> --}}
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark">Submit</button>
                    <a href="/dashboard/master-data/campaign" class="btn btn-secondary ms-1">Back</a>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    let lat =  position.coords.latitude;
                    let long =  position.coords.longitude;

                    $('input[name="lat"]').val(lat);
                    $('input[name="long"]').val(long);
                }.bind(this),
                function(error) {
                    Swal({
                        title: 'Akses lokasi ditolak',
                        text: 'Mohon izinkan akses lokasi',
                        type: 'warning'
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        } else {
                            location.reload();
                        }
                    });
                    // location.reload();
                }
            )
        } else { 
            // console.log('ditolak :)');
            // Swal({
            //     title: 'Akses lokasi ditolak',
            //     text: 'Mohon izinkan akses lokasi',
            //     type: 'warning'
            // }).then(() => {
            //     location.reload();
            // })
        }

        function showPosition(position) {
            let lat =  position.coords.latitude;
            let long =  position.coords.longitude;

            $('input[name="lat"]').val(lat);
            $('input[name="long"]').val(long);
        }

        let x = 1;
        const maxField = 10;
        const addButton = $('.btn-add');
        const wrapper = $('.detail-item-container');

        addButton.click(function() {
            if (x < maxField) {
                $(wrapper).append(addField(x));
            } else {
                Swal({
                    title: 'Maximum Limit',
                    text: 'A maximum of ' + maxField + ' fields are allowed to be added',
                    type: 'warning'
                })
            }
            x++;
        })

        function addField(no) {
            let field = `
                <div class="item-${no + 1}">
                    <h5>Item - ${no + 1}</h5>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="detail_transactions[${no}][type]" id="sepatu[${no}]" value="sepatu" checked/>
                            <label class="form-check-label" for="sepatu[${no}]">Sepatu</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="detail_transactions[${no}][type]" id="tas[${no}]" value="tas" />
                            <label class="form-check-label" for="tas[${no}]">Tas</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="detail_transactions[${no}][item_name]" class="form-label">Merk Item</label>
                        <input class="form-control @error('detail_transactions[${no}][item_name]') is-invalid @enderror" type="text" id="detail_transactions[${no}][item_name]" name="detail_transactions[${no}][item_name]" placeholder="Eg: Nike Air Jordan / Tas Gucci" autocomplete="off" value="{{ old('detail_transactions[${no}][item_name]') }}" required>
                        @error('detail_transactions[${no}][item_name]')
                            <div class="invalid-feedback text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group" id="colorSizeField">
                            <span class="input-group-text">Warna & Ukuran</span>
                            <input type="text" name="detail_transactions[${no}][color]" class="form-control" autocomplete="off" placeholder="Eg: Hitam" required>
                            <input type="text" name="detail_transactions[${no}][size]" class="form-control" id="detail_transactions[${no}][size]" autocomplete="off" placeholder="Eg: 41">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="detail_transactions[${no}][treatment_id]" class="form-label">Treatment</label>
                        <select name="detail_transactions[${no}][treatment_id]" id="detail_transactions[${no}][treatment_id]" class="form-select">g</option>
                            @foreach ($treatments as $treatment)
                            <option value="{{ $treatment->id }}" {{ (old('detail_transactions[${no}][treatment_id]') == $treatment->id) ? 'selected' : '' }}>{{ $treatment->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-danger btn-remove"><i class="bx bx-trash"></i></button>
                    </div>
                    <hr>
                </div>
            `;
            return field;
        }

        $(wrapper).on('click', '.btn-remove', function(e) {
            // const parent = e.target.parentElement.parentElement.parentElement;
            // const parent = e.target.parentElement.parentElement;
            // parent.remove();
            // console.log(parent);
            // let parent = $(this).parent();
            // console.log(parent);
            removeField(x);
            x--;
        })

        function removeField(no) {
            $(`.item-${no}`).remove();
        }

        $('.btn-check-number').click(async function(e) {
            e.preventDefault();
            this.innerHTML = `
                <div class="spinner-border spinner-border-sm mt-1" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            const whatsappNumber = "62" + $('#whatsapp_number').val();
            // console.log(whatsappNumber);
            // window.location.href = `/api/customer/${whatsappNumber}`;
            const data = await getCustomer(whatsappNumber);
            // console.log(data);
            if (data) {
                $('input[name="name"]').val(data.name);
                $('input[name="name"]').prop('disabled', true);
                $('textarea[name="address"]').val(data.address);
                Swal({
                    title: 'Data ditemukan',
                    text: 'Kamu sudah terdaftar :)',
                    type: 'success'
                })
            } else {
                $('input[name="name"]').val('');
                $('input[name="name"]').prop('disabled', false);
                $('textarea[name="address"]').val('');
                Swal({
                    title: 'Data tidak ditemukan',
                    text: 'Isi data dibawah ya biar kamu terdaftar :)',
                    type: 'warning'
                })
            }
            this.innerHTML = 'Check';
        })


        function getCustomer(number) {
            return fetch('http://localhost:8000/api/customer/' + number)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .then(response => {
                        // console.log(response);
                        // if (response.status == false) {
                        //     throw new Error(response.message)
                        // }
                        return response.data;
                    })
                    .catch(response => {
                        return response;
                    })
        }

    </script>
@endsection