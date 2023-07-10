let productSection = document.querySelector(".test");
let prevButton = document.querySelector(".prevDoc");
let nextButton = document.querySelector(".nextBtn");

let scrollWidth = 298;
let scrollDuration = 500;

nextButton.addEventListener("click", function () {
    productSection.scrollBy({
        left: scrollWidth,
        behavior: "smooth",
        duration: scrollDuration,
    });
});
prevButton.addEventListener("click", function () {
    productSection.scrollBy({
        left: -scrollWidth,
        behavior: "smooth",
        duration: scrollDuration,
    });
});
