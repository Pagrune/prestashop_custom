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