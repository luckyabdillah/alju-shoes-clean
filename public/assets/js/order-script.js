const flashDataFailed = $('.flash-data-failed').data('flash');

if (flashDataFailed) {
    Swal({
        title: 'Failed',
        text: flashDataFailed,
        type: 'error'
    })
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
        $('input[name="name"]').prop('readonly', true);
        $('textarea[name="address"]').val(data.address);
        Swal({
            title: 'Data ditemukan',
            text: 'Kamu sudah terdaftar :)',
            type: 'success'
        })
    } else {
        $('input[name="name"]').val('');
        $('input[name="name"]').prop('readonly', false);
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

$(document).ready(function() {
    $(document).on('change', '.treatment-select', function() {
        let price = $(this).find(':selected').data('price');
        let priceId = $(this).attr('id').replace('treatment_detail_id', 'price');
        document.getElementById(priceId).innerHTML = 'Rp' + price.toLocaleString('en-US');
        let subtotal = 0;
        document.querySelectorAll('.item-price').forEach((element) => {
            let sub = (element.innerHTML).split(',').join('');
            sub = sub.split('Rp').join('');
            subtotal += parseInt(sub);
        })
        document.querySelector('.subtotal').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');
        document.querySelector('.total').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');
    });
    
    const wrapper = document.querySelector('.item-container');
    let count = wrapper.querySelectorAll('.detail-item').length;
    console.log(count);
    const maxItems = 10;

    $(document).on('click', '.btn-add', function() {
        let totalFields = wrapper.querySelectorAll('.detail-item').length;
        if (totalFields < maxItems) {
            wrapper.querySelectorAll('.btn-add').forEach(button => {
                button.classList.add('d-none');
            });
            wrapper.querySelectorAll('.btn-remove').forEach(button => {
                button.classList.remove('d-none');
            });
            $(wrapper).append(addField(count));
            document.querySelector('.total-item').innerHTML = totalFields + 1;
            let lastField = wrapper.querySelector('.detail-item:last-child');
            lastField.querySelector('.btn-add').classList.remove('d-none');
            count++;
        } else {
            Swal({
                title: 'Maximum Limit',
                text: 'Hanya boleh maksimal ' + maxItems + ' item per transaksi',
                type: 'warning'
            })
        }
    })
    
    $(wrapper).on('click', '.btn-remove', function(e) {
        let parent = $(this).attr('id').replace('btn-remove', 'item');
        removeField(parent);

        let totalFields = wrapper.querySelectorAll('.detail-item').length;
        document.querySelector('.total-item').innerHTML = totalFields;

        let subtotal = 0;
        document.querySelectorAll('.item-price').forEach((element) => {
            let sub = (element.innerHTML).split(',').join('');
            sub = sub.split('Rp').join('');
            subtotal += parseInt(sub);
        })
        document.querySelector('.subtotal').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');
        document.querySelector('.total').innerHTML = 'Rp' + subtotal.toLocaleString('en-US');

        let lastField = wrapper.querySelector('.detail-item:last-child');
        lastField.querySelector('.btn-add').classList.remove('d-none');

        let firstField = wrapper.querySelector('.detail-item:first-child');
        firstField.querySelector('hr').remove();
        if (totalFields === 1) {
            lastField.querySelector('.btn-remove').classList.add('d-none');
            lastField.querySelector('.btn-add').classList.remove('d-none');
            lastField.querySelector('hr').remove();
        }
    })

    function removeField(element) {
        $(`.${element}`).remove();
    }
});