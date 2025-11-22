document.querySelector('#detailOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = document.querySelector('.btn-order');
    const backButton = document.querySelector('.btn-back');
    submitButton.setAttribute('disabled', true);
    backButton.classList.add('disabled');
    submitButton.innerHTML = `
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    return this.submit();
});

document.querySelector('.btn-submit-mobile').addEventListener('click', e => {
    const backButton = document.querySelector('.btn-back-mobile');
    const form = document.querySelector('#detailOrderForm')

    if (form.checkValidity()) {
        e.target.setAttribute('disabled', true);
        e.target.innerHTML = `
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        backButton.classList.add('disabled');
        
        form.submit();
    } else {
        form.reportValidity();
    }
})

document.querySelector('.btn-expand').addEventListener('click', e => {
    e.preventDefault()
    const summaryMobile = document.querySelector('.summary-mobile')
    e.target.classList.toggle('bx-rotate-180')
    summaryMobile.classList.toggle('show')
})

$('.btn-check-number').click(async function(e) {
    e.preventDefault();
    const whatsappNumber = $('#whatsapp_number').val();
    if (whatsappNumber.length < 10) {
        Swal({
            title: 'Error',
            text: 'Masukkan minimal 10 angka',
            type: 'error'
        });
    } else {
        this.innerHTML = `
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        try {
            const data = await getCustomerData("62" + whatsappNumber);
            console.log(data);
            
            if (data) {
                $('input[name="name"]').val(data.name);
                $('textarea[name="address"]').val(data.address);
                $('input[name="email"]').val(data.email);
                $('input[name="benchmark"]').val(data.benchmark);
                Swal({
                    title: 'Success',
                    text: 'Data ditemukan',
                    type: 'success'
                });
            } else {
                $('input[name="name"]').val('');
                $('textarea[name="address"]').val('');
                $('input[name="email"]').val('');
                $('input[name="benchmark"]').val('');
                Swal({
                    title: 'Data tidak ditemukan',
                    text: 'Isi data dibawah ya biar kamu terdaftar :)',
                    type: 'warning'
                });
            }
        } catch (error) {
            $('input[name="name"]').val('');
            $('textarea[name="address"]').val('');
            $('input[name="email"]').val('');
            $('input[name="benchmark"]').val('');
            Swal({
                title: 'Error',
                text: error.message,
                type: 'error'
            })
        }
        this.innerHTML = 'Check';
    }
});

function getCustomerData(number) {
    return fetch('/api/customer/' + number)
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .then(response => {
                return response.data;
            })
            .catch(response => {
                throw new Error(response.message);
            })
}

const holidayInNumber = document.querySelector('[name="holiday_in_number"]').value
const holidayInDay = document.querySelector('[name="holiday_in_day"]').value
const selangWaktuPenjemputan = document.querySelector('[name="selang_waktu_penjemputan"]').value

let minDate = new Date();
let ddMinDate = minDate.getDate();
let mmMinDate = minDate.getMonth() + 1;
let yyyyMinDate = minDate.getFullYear();
let hhiiMinDate = (minDate.getHours() + parseInt(selangWaktuPenjemputan, 10)) + '' + minDate.getMinutes();
// console.log(hhiiMinDate)

if (ddMinDate < 10) {
    ddMinDate = '0' + ddMinDate;
}

if (mmMinDate < 10) {
    mmMinDate = '0' + mmMinDate;
}

let maxDate = new Date(minDate.getTime() + (86400000 * 7));
let ddMaxDate = maxDate.getDate();
let mmMaxDate = maxDate.getMonth() + 1;
let yyyyMaxDate = maxDate.getFullYear();

if (ddMaxDate < 10) {
    ddMaxDate = '0' + ddMaxDate;
}

if (mmMaxDate < 10) {
    mmMaxDate = '0' + mmMaxDate;
}

min = yyyyMinDate + '-' + mmMinDate + '-' + ddMinDate;
max = yyyyMaxDate + '-' + mmMaxDate + '-' + ddMaxDate;

const pickupDateField = document.getElementById('pickup_date');
if (pickupDateField) {
    pickupDateField.setAttribute('min', min);
    pickupDateField.setAttribute('max', max);
    
    pickupDateField.addEventListener('input', function(e) {
        const selectedDate = new Date(e.target.value);
        if (Date.parse(selectedDate) < Date.parse(min) || Date.parse(selectedDate) > Date.parse(max)) {
            e.target.value = '';
            return Swal({
                title: 'Gagal',
                text: 'Maksimal tanggal penjemputan 7 hari dari hari ini',
                type: 'error'
            })
        } else if (selectedDate.getDay() == holidayInNumber) {
            e.target.value = '';
            return Swal({
                title: 'Gagal',
                text: `Tidak bisa memilih hari ${holidayInDay}`,
                type: 'error'
            })
        }
    });
}

const pickupTimeField = document.getElementById('pickup_time');
if (pickupTimeField) {
    pickupTimeField.addEventListener('blur', function(e) {
        const selectedDate = new Date(pickupDateField.value);
        if (selectedDate == 'Invalid Date') {
            e.target.blur()
            e.target.value = '';
            return Swal({
                title: 'Gagal',
                text: 'Pilih tanggal pickup terlebih dahulu',
                type: 'error'
            });
        } else {
            const selectedTime = parseInt(e.target.value.split(':').join(''));
            if (selectedTime < 1000 || selectedTime > 1700) {
                e.target.blur()
                e.target.value = '';
                return Swal({
                    title: 'Gagal',
                    text: 'Waktu penjemputan antara 10.00 - 17.00',
                    type: 'error'
                });
            } else if (selectedDate.getDay() == minDate.getDay() && selectedTime < hhiiMinDate) {
                e.target.blur()
                e.target.value = '';
                return Swal({
                    title: 'Gagal',
                    text: `Selang waktu penjemputan minimal ${selangWaktuPenjemputan} jam dari waktu sekarang`,
                    type: 'error'
                });
            }
        }
    });
}