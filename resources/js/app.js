import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Autoplay, EffectCoverflow } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-coverflow';

window.Alpine = Alpine;

Alpine.start();

const initGallerySliders = () => {
    const sliders = document.querySelectorAll('[data-ys-gallery-swiper]');
    if (!sliders.length) {
        return;
    }

    sliders.forEach((element) => {
        if (element.dataset.swiperReady === '1') {
            return;
        }

        element.dataset.swiperReady = '1';

        new Swiper(element, {
            modules: [EffectCoverflow, Autoplay],
            effect: 'coverflow',
            loop: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            spaceBetween: 0,
            grabCursor: true,
            rtl: true,
            watchSlidesProgress: true,
            speed: 700,
            autoplay: {
                delay: 2200,
                disableOnInteraction: false,
            },
            coverflowEffect: {
                rotate: 15,
                stretch: 75,
                depth: 75,
                modifier: 1,
                scale: 0.85,
                slideShadows: true,
            },
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGallerySliders, { once: true });
} else {
    initGallerySliders();
}
