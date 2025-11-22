$(document).ready(function() {

    // $('.js-example-placeholder-single').select2({
    //     theme: 'bootstrap-5',
    //     placeholder: "Pilih Type Terlebih Dahulu",
    //     allowClear: true
    // });

    const capitalizeWord = (string) => {
        const firstChar = string.charAt(0).toUpperCase();
        const remainingChars = string.slice(1);
        return `${firstChar}${remainingChars}`;
    }

    document.querySelectorAll('[name="transaction_type"]').forEach(el => {
        const outletField = document.querySelector('.outlet-field')
        el.addEventListener('change', e => {
            if (e.target.value == 'dropzone') {
                outletField.classList.remove('d-none')
            } else {
                outletField.classList.add('d-none')
            }
        })
    })

    $(document).on('change', '.type', function(e) {
        const parent = e.target;
        let selected = parent.value;
        let selectedDom = $(parent).find('option:selected')
        const placeholder = selectedDom.data('placeholder')
        const itemParent = e.target.closest('.detail-item')
        let treatmentField = itemParent.querySelector('.treatment-select');
        let sizeField = itemParent.querySelector('.size');
        if (selected) {
            // treatmentField.querySelector('.select-type-first').innerText = `Pilih Treatment ${capitalizeWord(selected)}`;
            treatmentField.removeAttribute('disabled');
            treatmentField.value = '';
            treatmentField.dispatchEvent(new Event('change', {"bubbles": true}));

            let dynamicTreatments = `<option value="" data-price="0" class="select-type-first">Pilih Treatment ${capitalizeWord(selected)}</option>`;
            groupedTreatments = treatments.filter(treatment => treatment.type == selected);
            groupedTreatments.forEach(treatment => {
                dynamicTreatments += `<optgroup label="${treatment.name}" id="${treatment.type}">`
                treatment.treatment_details.forEach(item => {
                    dynamicTreatments += `<option value="${item.id}" data-price="${item.cost}">${item.name} (${item.processing_time} hari kerja)</option>`
                });
                dynamicTreatments += `</optgroup>`
            });

            treatmentField.innerHTML = dynamicTreatments;

            itemParent.querySelector('.size-placeholder').setAttribute('value', placeholder)
            sizeField.setAttribute('placeholder', `Cth: ${placeholder}`)
        } else {
            treatmentField.value = '';
            treatmentField.dispatchEvent(new Event('change', {"bubbles": true}));
            treatmentField.querySelector('.select-type-first').innerText = 'Pilih Type Terlebih Dahulu';
            treatmentField.setAttribute('disabled', true);
            itemParent.querySelector('.size-placeholder').setAttribute('value', '(Jika Ada)')
            sizeField.setAttribute('placeholder', '(Jika Ada)')
        }
    });

    $(document).on('change', '.treatment-select', function(e) {
        let price = $(this).find(':selected').data('price');

        let priceId = $(this).attr('id').replace('treatment_details_id', 'price');
        document.getElementById(priceId).innerHTML = 'Rp' + price.toLocaleString('id-ID');
        let subtotal = 0;
        document.querySelectorAll('.item-price').forEach((element) => {
            let sub = (element.innerHTML).split('.').join('');
            sub = sub.split('Rp').join('');
            subtotal += parseInt(sub);
        })
        document.querySelectorAll('.subtotal').forEach(el => {
            el.innerHTML = 'Rp' + subtotal.toLocaleString('id-ID')
        });
        document.querySelectorAll('.total').forEach(el => {
            el.innerHTML = 'Rp' + subtotal.toLocaleString('id-ID')
        });
    });
    
    const wrapper = document.querySelector('.item-container');
    let count = wrapper.querySelectorAll('.detail-item').length;
    const maxItems = 10;

    wrapper.querySelector('.detail-item:last-child').querySelector('.btn-add').classList.remove('d-none');

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
            document.querySelectorAll('.total-item').forEach(el => {
                el.innerHTML = totalFields + 1
            });
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
            let sub = (element.innerHTML).split('.').join('');
            sub = sub.split('Rp').join('');
            subtotal += parseInt(sub);
        })
        document.querySelectorAll('.subtotal').forEach(el => {
            el.innerHTML = 'Rp' + subtotal.toLocaleString('id-ID')
        });
        document.querySelectorAll('.total').forEach(el => {
            el.innerHTML = 'Rp' + subtotal.toLocaleString('id-ID')
        });

        let lastField = wrapper.querySelector('.detail-item:last-child');
        lastField.querySelector('.btn-add').classList.remove('d-none');

        if (totalFields === 1) {
            lastField.querySelector('.btn-remove').classList.add('d-none');
            lastField.querySelector('.btn-add').classList.remove('d-none');

            let firstField = wrapper.querySelector('.detail-item:first-child');
            try {
                firstField.querySelector('hr').remove();
            } catch (error) {
                return false;
            }
        }
    })

    function removeField(element) {
        $(`.${element}`).remove();
    }
    
    document.querySelectorAll('.btn-submit').forEach(btn => {
        btn.addEventListener('click', formValidate)
    })

    function formValidate(e) {
        e.preventDefault();

        const transactionType = $('input[name="transaction_type"]:checked').val();
        if (!transactionType) {
            $('input[name="transaction_type"]').focus()
            return Swal({
                title: 'Oops!',
                text: 'Pilih tipe transaksi terlebih dahulu',
                type: 'warning'
            })
        }

        const outletId = document.querySelector('[name="outlet_id"]')
        outletId.classList.remove('is-invalid')
        if (transactionType == 'dropzone') {
            if (!outletId.value) {
                outletId.classList.add('is-invalid')
            }
        }

        document.querySelectorAll('.type').forEach((el) => {
            let parent = el.parentElement;
            let selected = el.value;
            let invalidFeedback = parent.querySelector('.invalid-feedback');

            if (!selected) {
                el.classList.add('is-invalid')
                invalidFeedback.classList.add('d-block');
                invalidFeedback.innerText = 'Type wajib diisi';
            } else {
                el.classList.remove('is-invalid')
                invalidFeedback.classList.remove('d-block');
                invalidFeedback.innerText = '';
            };
        });

        document.querySelectorAll('.merk').forEach((el, i) => {
            let parent = el.parentElement;
            let invalidFeedback = parent.querySelector('.invalid-feedback');
            if (el.value.length > 100) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Merk tidak boleh lebih dari 100 kata';
            } else if (el.value.length == 0) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Merk wajib diisi';
            } else {
                el.classList.remove('is-invalid');
                invalidFeedback.innerText = '';
            }
        });

        document.querySelectorAll('.size').forEach((el, i) => {
            let parent = el.parentElement;
            let invalidFeedback = parent.querySelector('.invalid-feedback');

            if (el.value.length > 100) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Ukuran tidak boleh lebih dari 100 kata';
            } else if (el.value.length == 0) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Ukuran wajib diisi';
            } else {
                el.classList.remove('is-invalid');
                invalidFeedback.innerText = '';
            }
        });

        document.querySelectorAll('.treatment-select').forEach((el, i) => {
            let parent = el.parentElement.parentElement;
            let invalidFeedback = parent.querySelector('.invalid-feedback');

            if (el.value.length == 0) {
                el.classList.add('is-invalid');
                invalidFeedback.classList.add('d-block');
                invalidFeedback.innerText = 'Treatment wajib diisi';
            } else {
                el.classList.remove('is-invalid');
                invalidFeedback.classList.remove('d-block');
                invalidFeedback.innerText = '';
            }
        });
        
        document.querySelectorAll('.description').forEach((el, i) => {
            let parent = el.parentElement;
            let invalidFeedback = parent.querySelector('.invalid-feedback');
            if (el.value.length > 100) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Deskripsi tidak boleh lebih dari 100 kata';
            } else if (el.value.length == 0) {
                el.classList.add('is-invalid');
                invalidFeedback.innerText = 'Deskripsi wajib diisi';
            } else {
                el.classList.remove('is-invalid');
                invalidFeedback.innerText = '';
            }
        });

        const invalid = document.querySelectorAll('.is-invalid').length;
        const orderForm = document.querySelector('#orderForm');
        if (invalid == 0) {
            document.querySelectorAll('.btn-submit').forEach(btn => {
                btn.setAttribute('disabled', true);
                btn.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
            });
            document.querySelectorAll('.btn-back').forEach(btn => {
                btn.classList.add('disabled');
            });
            return orderForm.submit();
        } else {
            document.querySelector('.is-invalid').focus();
        }
    }

    // document.querySelector('#orderForm').addEventListener('submit', function(e) {
    //     // e.preventDefault();
    //     const submitButton = document.querySelector('.btn-submit');
    //     const backButton = document.querySelector('.btn-back');

    //     document.querySelectorAll('.treatment-select').forEach((el) => {
    //         let parent = el.parentElement.parentElement;
    //         let invalidFeedback = parent.querySelector('.invalid-feedback');
    //         // let type = el.find(':selected').data('type');
    //         let treatmentType = el.options[el.selectedIndex];
    //         treatmentType = treatmentType.dataset.type;
    //         console.log(treatmentType);

    //         let itemParent = el.parentElement.parentElement.parentElement;
    //         let typeField = itemParent.querySelector('.type-select');
    //         let checkedType = $(typeField).find(':checked').val();
    //         console.log(checkedType);

    //         if (treatmentType != checkedType) {
    //             el.classList.add('is-invalid');
    //             invalidFeedback.classList.add('d-block');
    //             invalidFeedback.innerText = 'Treatment wajib diisi';
    //         } else {
    //             el.classList.remove('is-invalid');
    //             invalidFeedback.classList.remove('d-block');
    //             invalidFeedback.innerText = '';
    //         }
    //     });
    // })
});