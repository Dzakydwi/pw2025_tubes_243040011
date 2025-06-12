let experienceCard1 = document.querySelector('.experience_1');
let experienceCard2 = document.querySelector('.experience_2');
let experienceCard3 = document.querySelector('.experience_3');

let experienceLink1 = document.querySelector('.experience_link1');
let experienceLink2 = document.querySelector('.experience_link2');
let experienceLink3 = document.querySelector('.experience_link3');

experienceLink1.addEventListener('click', () => {
    experienceCard1.style.display = 'flex';
    experienceCard2.style.display = 'none';
    experienceCard3.style.display = 'none';
})
experienceLink2.addEventListener('click', () => {
    experienceCard1.style.display = 'none';
    experienceCard2.style.display = 'flex';
    experienceCard3.style.display = 'none';
})
experienceLink3.addEventListener('click', () => {
    experienceCard1.style.display = 'none';
    experienceCard2.style.display = 'none';
    experienceCard3.style.display = 'flex';
})

var swiper = new Swiper('.ProjectSwiper', {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,
    autoplay: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        1400: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        0: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
    }
});

let bar = document.querySelector('.bars');
let menu = document.querySelector('.menu');

bar.addEventListener('click', () =>{
    menu.classList.toggle('show_menu');
});