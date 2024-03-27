let cIndicators = document.querySelector('.carousel-indicators');
let cIndicatorsChildren = Array.from(cIndicators.children);
console.log(cIndicatorsChildren);

cIndicators.addEventListener('click', function(e) {
    if (e.target.classList.contains('indicators')) {
        cIndicatorsChildren.forEach(el => {
            el.classList.remove('active');
        });
        e.target.classList.add('active');
    }
})