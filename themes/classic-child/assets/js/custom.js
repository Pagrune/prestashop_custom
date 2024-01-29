$ = jQuery;
$(document).ready(function() {
    if($('#container_aframe')){
        console.log('coucou');
    }
    if(document.querySelector("#index")){
        const carousel = document.querySelector(".carousel");
        const slides = document.querySelectorAll(".slide");
        const controlLinks = document.querySelectorAll(".controls a");
        const slidesProducts = document.querySelectorAll(".slide-product");

        let i = 0,
            j=1,
            intervalId;

        const intervalFn = () => {
            intervalId = setInterval(() => {
                carousel.style.rotate = `-${++i * 90}deg`;
                j = j+1===5 ? 1 : j+1;
                document.querySelector(".slide.active").classList.remove("active");
                const activeSlide = document.querySelector(`.slide:nth-child(${j})`);
                activeSlide.classList.add("active");

                console.log(j)

                document.querySelector(".slide-product.active").classList.remove("active");
                const activeProduct = document.querySelector(`div[data-list="${j}"]`);
                activeProduct.classList.add("active");
                console.log(document.querySelector(`.slide-product:nth-child(${j})`))




            }, 4000);
        };

        intervalFn();

        controlLinks.forEach((control) => {
            control.addEventListener("click", () => {
                clearInterval(intervalId);
                carousel.style.rotate = `-${
                    (i - j + Number(control.dataset.index)) * 90
                }deg`;

                document.querySelector(".slide.active").classList.remove("active");
                const activeSlide = document.querySelector(
                    `.slide:nth-child(${control.dataset.index})`
                );
                activeSlide.classList.add("active");

                document.querySelector("a.active").classList.remove("active");
                control.classList.add("active");
            });
        });

        carousel.addEventListener("mouseenter", () => {
            clearInterval(intervalId);
            console.log("Pause");
        });

        carousel.addEventListener("mouseleave", () => {
            intervalFn();
            console.log("Play");
        });
    }



});


const descriptions = [
    {
        title: "<h2 class='xDeux'>Vanille X Noix de Macadamia & baies sauvage</h2>",
        text: ' <p>Plongez dans un <span class="red">univers de douceur </span> et de <span class="red">croquant</span> avec notre <span class="red">irrésistible</span> glace saveur vanille aux noix de macadamia.</p> <p>Chaque cuillère est une <span class="red">symphonie de saveurs</span>, une danse délicate entre la vanille <span class="red">veloutée</span> et le <span class="red">croquant</span> des noix de macadamia.</p>'
    },
    {
        title: "<h2 class='xDeux'>Chocolat X Oréo</h2>",
        text: "<p>La douceur de l'oréo mélanger à la puissance du chocolat, <span class='red'> un caractère</span> intense et fondant en bouche.</p>"
    },
    {
        title: "<h2 class='xDeux'>Café X Spéculoss et vanille</h2>",
        text: "<p>Une bonne glace au <span class='red'>spéculoss<span> sur un nid de vanille acompagné de café.</p>"
    }
];

const slider = document.querySelector(".items");
const slides = document.querySelectorAll(".item");
const button = document.querySelectorAll(".button");
let title = document.querySelector(".xDeux");
let text = document.querySelector(".margin>.description");

let current = 0;
let prev = 4;
let next = 1;

for (let i = 0; i < button.length; i++) {
    button[i].addEventListener("click", () => i == 0 ? gotoPrev() : gotoNext());
}

const gotoPrev = () => current > 0 ? gotoNum(current - 1) : gotoNum(slides.length - 1);

const gotoNext = () => current < 4 ? gotoNum(current + 1) : gotoNum(0);

const gotoNum = number => {
    current = number;
    prev = current - 1;
    next = current + 1;

    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
        slides[i].classList.remove("prev");
        slides[i].classList.remove("next");
    }

    if (next == 5) {
        next = 0;
    }

    if (prev == -1) {
        prev = 4;
    }


    slides[current].classList.add("active");
    slides[prev].classList.add("prev");
    slides[next].classList.add("next");

    // Mettre à jour la description

    title.innerHTML = descriptions[current].title;
    text.innerHTML = descriptions[current].text;
};

document.querySelector("#menu-icon>.d-inline").addEventListener(
    'click',
    modif
);


function modif(){
    document.querySelector(".header-top>.container>#mobile_top_menu_wrapper").innerHTML = `
            <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:block !important; ">
                <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
                <div class="js-top-menu-bottom">
                    <div id="_mobile_currency_selector"></div>
                    <div id="_mobile_language_selector"></div>
                    <div id="_mobile_contact_link"></div>
                </div>
            </div>
    `;
}

document.querySelector(".material-icons>svg").addEventListener('mouseover',
    change
)

function change(){

    document.querySelector(".none").classList.add('Block');
}

document.querySelector(".material-icons>svg").addEventListener('mousseout',
    change2
)

function change2(){

    document.querySelector(".none").classList.remove('Block');
}