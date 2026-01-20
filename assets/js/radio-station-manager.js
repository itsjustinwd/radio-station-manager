jQuery(document).ready(function($) {
    // Initialize Splide slider if hero-slider exists
    if (document.getElementById('hero-slider')) {
        new Splide('#hero-slider', {
            type: 'loop',
            autoplay: true,
            interval: 7000,
            speed: 1000,
            pauseOnHover: true,
            arrows: true,
            pagination: false,
        }).mount();
    }
});