window.addEventListener('load', function(e) {
    let width = screen.width;
    let text75 = document.querySelector('.text-75');
    
    if (width > 577) {
        text75.classList.add('w-75');
    } else {
        text75.classList.remove('w-75');
    }

    const item = document.querySelector('.item');
    (function typing() {
        new TypeIt(item, { 
            lifeLike: false, 
            loop: true,
            speed: 0 
        })
            .type("S")
            .pause(425)
            .type("e")
            .pause(581)
            .type("p")
            .pause(308)
            .type("a")
            .pause(134)
            .type("t")
            .pause(123)
            .type("u")
            .pause(1882)
            .delete(1)
            .pause(183)
            .delete(1)
            .pause(173)
            .delete(1)
            .pause(160)
            .delete(1)
            .pause(192)
            .delete(1)
            .pause(178)
            .delete(1)
            .pause(812)
            .type("T")
            .pause(648)
            .type("a")
            .pause(188)
            .type("s")
            .pause(2511)
            .delete(1)
            .pause(169)
            .delete(1)
            .pause(145)
            .delete(1)
            .pause(531)
            .type("S")
            .pause(284)
            .type("a")
            .pause(138)
            .type("n")
            .pause(115)
            .type("d")
            .pause(132)
            .type("a")
            .pause(322)
            .type("l")
            .pause(2333)
            .delete(1)
            .pause(174)
            .delete(1)
            .pause(165)
            .delete(1)
            .pause(181)
            .delete(1)
            .pause(173)
            .delete(1)
            .pause(198)
            .delete(1)
            .pause(181)
            .type("T")
            .pause(425)
            .type("o")
            .pause(581)
            .type("p")
            .pause(308)
            .type("i")
            .pause(1882)
            .delete(1)
            .pause(183)
            .delete(1)
            .pause(173)
            .delete(1)
            .pause(160)
            .delete(1)
            .go();
    })();

    let counts = setInterval(counting);
    let upto = 0;
    function counting() {
        let count = document.querySelector(".counter");
        count.innerHTML = ++upto + '+';
        if (upto === 500) {
            clearInterval(counts);
        }
    }

    let navbarMobileOrderButton = document.querySelector('#btn-order-navbar-mobile');
    let navbarDesktopOrderButton = document.querySelector('#btn-order-navbar-desktop');
    let heroOrderButton = document.querySelector('#btn-order-hero');
    window.addEventListener('scroll', function(e) {
        if (window.scrollY > (heroOrderButton.offsetTop - 40)) {
            navbarDesktopOrderButton.classList.remove('btn-my-secondary');
            navbarDesktopOrderButton.classList.add('btn-my-primary');
            navbarMobileOrderButton.classList.remove('btn-my-secondary');
            navbarMobileOrderButton.classList.add('btn-my-primary');
        } else {
            navbarDesktopOrderButton.classList.remove('btn-my-primary');
            navbarDesktopOrderButton.classList.add('btn-my-secondary');
            navbarMobileOrderButton.classList.remove('btn-my-primary');
            navbarMobileOrderButton.classList.add('btn-my-secondary');
        }
    })

    // $('.treatment-list').owlCarousel({
    //     center: true,
    //     loop: true,
    //     responsive: {
    //         0: {
    //             items: 4,
    //             margin: 20,
    //         },
    //         600: {
    //             items: 6,
    //             margin: 30,
    //         },
    //         1000: {
    //             items: 6,
    //             margin: 40,
    //         }
    //     }
    // })

    const treatmentContainer = document.querySelector('.horizontal-scroll')
    document.querySelector('.treatment-indicator .prev-button').addEventListener('click', e => {
        treatmentContainer.scrollLeft -= 300
    })
    document.querySelector('.treatment-indicator .next-button').addEventListener('click', e => {
        treatmentContainer.scrollLeft += 300
    })

    async function getTreatment(treatmentUuid) {
        try {
            const response = await fetch(`/treatment/${treatmentUuid}`)
            if (!response.ok) {
                throw new Error(response.statusText || 'Failed to fetch data')
            }

            const jsonData = await response.json()
            if (!jsonData.data || jsonData.data.length === 0) {
                throw new Error('No data available')
            }

            return jsonData.data
        } catch (error) {
            console.error('Fetch error:', error)
            throw error
        }
    }

    async function getTreatmentDetails(treatmentUuid) {
        try {
            const response = await fetch(`/treatment/${treatmentUuid}/get-details`)
            if (!response.ok) {
                throw new Error(response.statusText || 'Failed to fetch data')
            }

            const jsonData = await response.json()
            if (!jsonData.data || jsonData.data.length === 0) {
                throw new Error('No data available')
            }

            return jsonData.data
        } catch (error) {
            console.error('Fetch error:', error)
            throw error
        }
    }

    // $(document).on('click', '.treatment-item', async function (e) {
    //     const parent = e.target.closest('.treatment-item')
    //     const treatmentUuid = parent.dataset.uuid

    //     $('#treatmentModal').modal('show')
    
    //     try {
    //         const treatment = await getTreatment(treatmentUuid)
    //         const details = await getTreatmentDetails(treatmentUuid)

    //         const treatmentContent = `
    //             <div class="mb-3">
    //                 <img src="/storage/${treatment.photo}" alt="treatment icon" class="rounded-circle m-auto" style="width: 60px !important;">
    //                 <span class="fw-semibold ms-1">${treatment.name}</span>
    //             </div>
    //         `

    //         let treatmentDetailsContent = ''
    //         details.forEach(detail => {
    //             treatmentDetailsContent += `
    //                 <div class="mb-3">
    //                     <h6 class="text-my-primary fw-semibold">${detail.name} (${detail.cost.toLocaleString('id-ID')})</h6>
    //                     <p class="mb-2">${detail.description}</p>
    //                     <span class="d-block fw-bold">Cocok untuk:</span>
    //                     <p>${detail.suitable_for}</p>
    //                 </div>
    //             `
    //         });

    //         const finalContent = treatmentContent + treatmentDetailsContent

    //         $('.modal-body').html(finalContent)

    //     } catch (error) {
    //         console.log(error);
    //         $('#treatmentModal').modal('hide')
    //         setTimeout(() => {
    //             Swal({
    //                 title: 'Oops!',
    //                 text: 'No item related to selected treatment, please contact our Customer Support or choose another treatment',
    //                 type: 'error',
    //             })
    //         }, 500);

    //         return false
    //     }
    // })

    document.querySelectorAll('.treatment-item').forEach(el => {
        el.addEventListener('click', async e => {
            const parent = e.target.closest('.treatment-item')
            const treatmentUuid = parent.dataset.uuid
        
            $('#treatmentModal').modal('show')
        
            try {
                const treatment = await getTreatment(treatmentUuid)
                const details = await getTreatmentDetails(treatmentUuid)
        
                const treatmentContent = `
                    <div class="mb-3">
                        <img src="/storage/${treatment.photo}" alt="treatment icon" class="rounded-circle m-auto" style="width: 60px !important;">
                        <span class="fw-semibold ms-1">${treatment.name}</span>
                    </div>
                `
        
                let treatmentDetailsContent = ''
                details.forEach(detail => {
                    treatmentDetailsContent += `
                        <div class="mb-3">
                            <h6 class="text-my-primary fw-semibold">${detail.name} (${detail.cost.toLocaleString('id-ID')})</h6>
                            <p class="mb-2">${detail.description}</p>
                            <span class="d-block fw-bold">Cocok untuk:</span>
                            <p>${detail.suitable_for}</p>
                        </div>
                    `
                });
        
                const finalContent = treatmentContent + treatmentDetailsContent
        
                $('.modal-body').html(finalContent)
        
            } catch (error) {
                console.log(error);
                $('#treatmentModal').modal('hide')
                setTimeout(() => {
                    Swal({
                        title: 'Oops!',
                        text: 'No item related to selected treatment, please contact our Customer Support or choose another treatment',
                        type: 'error',
                    })
                }, 500);
        
                return false
            }
        })
    })

    $('#treatmentModal').on('hidden.bs.modal', function (e) {
        const loader = `
            <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `

        $('.modal-body').html(loader)
    })
});