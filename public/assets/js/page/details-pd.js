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
    this.submit();
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
        const data = await getCustomer("62" + whatsappNumber);
        if (data) {
            $('input[name="name"]').val(data.name);
            $('input[name="email"]').val(data.email);
            $('textarea[name="address"]').val(data.address);
            $('input[name="benchmark"]').val(data.benchmark);
            Swal({
                title: 'Success',
                text: 'Data ditemukan',
                type: 'success'
            });
        } else {
            $('input[name="name"]').val('');
            $('input[name="email"]').val('');
            $('textarea[name="address"]').val('');
            $('input[name="benchmark"]').val('');
            Swal({
                title: 'Data tidak ditemukan',
                text: 'Isi data dibawah ya biar kamu terdaftar :)',
                type: 'warning'
            });
        }
        this.innerHTML = 'Check';
    }
});

function getCustomer(number) {
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
                return response;
            })
}

const holidayInNumber = document.querySelector('[name="holiday_in_number"]').value
const holidayInDay = document.querySelector('[name="holiday_in_day"]').value

let minDate = new Date();
let ddMinDate = minDate.getDate();
let mmMinDate = minDate.getMonth() + 1;
let yyyyMinDate = minDate.getFullYear();

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
pickupDateField.setAttribute('min', min);
pickupDateField.setAttribute('max', max);

pickupDateField.addEventListener('input', function(e) {
    const selectedDate = new Date(e.target.value);
    if (selectedDate.getDay() == holidayInNumber) {
        e.target.value = '';
        Swal({
            title: 'Gagal',
            text: `Tidak bisa memilih hari ${holidayInDay}`,
            type: 'error'
        })
    }
});

const pickupTimeField = document.getElementById('pickup_time');
pickupTimeField.addEventListener('input', function(e) {
    const selectedDate = new Date(pickupDateField.value);
    if (selectedDate == 'Invalid Date') {
        e.target.blur()
        e.target.value = '';
        Swal({
            title: 'Gagal',
            text: 'Pilih tanggal pickup terlebih dahulu',
            type: 'error'
        });
    } else {
        const selectedTime = parseInt(e.target.value.split(':').join(''));
        if (selectedTime < 1000 || selectedTime > 1700) {
            e.target.blur()
            e.target.value = '';
            Swal({
                title: 'Gagal',
                text: 'Waktu penjemputan antara 10.00 - 17.00',
                type: 'error'
            });
        } else if (selectedDate.getDay() == minDate.getDay() && selectedTime < parseInt(`${minDate.getHours()}` + minDate.getMinutes())) {
            e.target.value = '';
            e.target.blur()
            Swal({
                title: 'Gagal',
                text: 'Mohon input jam yang sesuai',
                type: 'error'
            });
        }
    }
});


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
    this.submit();
});