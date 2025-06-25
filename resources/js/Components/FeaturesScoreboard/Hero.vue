<template>

    <div data-v-32346a9c="" class="sc-container" bis_skin_checked="1">
        <picture data-v-32346a9c="">
            <source data-v-32346a9c="" srcset="
                    https://download.smoothcomp.com/features/scoreboard/product-hero@2x.webp 2x,
                    https://download.smoothcomp.com/features/scoreboard/product-hero@1x.webp 1x
                " type="image/webp">
            <source data-v-32346a9c="" srcset="
                    https://download.smoothcomp.com/features/scoreboard/product-hero@2x.png 2x,
                    https://download.smoothcomp.com/features/scoreboard/product-hero@1x.png 1x
                " type="image/jpg"><img data-v-32346a9c="" loading="lazy"
                src="https://download.smoothcomp.com/features/scoreboard/product-hero@1x.png" class="hero-image">
        </picture>
        <h1 data-v-32346a9c=""><strong data-v-32346a9c="">A digital scoreboard for all sports</strong></h1>
        <div data-v-453f1426="" data-v-32346a9c="" class="button-container" id="buttonsId" bis_skin_checked="1">
            <a
                data-v-453f1426="" class="sc-btn-medium sc-btn-secondary"
                href="https://download.smoothcomp.com/scoreboard/mac-x64/Smoothcomp-1.13.6.dmg"><svg data-v-453f1426=""
                    width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path data-v-453f1426=""
                        d="M14.1892 6.13681C14.0848 6.21782 12.2414 7.25653 12.2414 9.56616C12.2414 12.2376 14.587 13.1827 14.6572 13.2061C14.6464 13.2637 14.2846 14.5005 13.4205 15.7606C12.65 16.8695 11.8453 17.9766 10.6212 17.9766C9.39709 17.9766 9.08206 17.2655 7.66892 17.2655C6.29178 17.2655 5.80213 18 4.68242 18C3.56271 18 2.78143 16.9739 1.88314 15.7138C0.842637 14.234 0.00195312 11.9352 0.00195312 9.75338C0.00195312 6.25383 2.27738 4.39784 4.5168 4.39784C5.70672 4.39784 6.69862 5.17912 7.4457 5.17912C8.15677 5.17912 9.26568 4.35104 10.6194 4.35104C11.1325 4.35104 12.9758 4.39784 14.1892 6.13681V6.13681ZM9.97675 2.86949C10.5366 2.20522 10.9326 1.28353 10.9326 0.361836C10.9326 0.234023 10.9218 0.10441 10.8984 0C9.98755 0.0342034 8.90384 0.606661 8.25038 1.36454C7.73733 1.94779 7.25848 2.86949 7.25848 3.80378C7.25848 3.94419 7.28188 4.08461 7.29268 4.12961C7.35029 4.14041 7.4439 4.15302 7.53751 4.15302C8.35479 4.15302 9.38269 3.60576 9.97675 2.86949Z"
                        fill="white"></path>
                </svg> &nbsp; Download for Mac 
            </a>
            <a data-v-453f1426="" class="sc-btn-medium sc-btn-primary"
                href="https://download.smoothcomp.com/scoreboard/win-x64/Smoothcomp Setup 1.13.6.exe"><svg
                    data-v-453f1426="" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path data-v-453f1426=""
                        d="M2 4.28311L8.54296 3.38813V9.6895H2M9.33104 3.26027L18 2V9.6347H9.33104M2 10.3543H8.54296V16.674L2 15.7607M9.33104 10.4384H18V18L9.33104 16.7763"
                        fill="white"></path>
                </svg> &nbsp; Download for Windows 
            </a>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
.sc-container {
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    padding: 16px;
}

picture {
    display: flex;
    justify-content: center;
}

.hero-image[data-v-32346a9c] {
    margin-bottom: -4rem;
}

img {
    max-width: 100%;
    vertical-align: middle;
    border: 0;
}

h1,
.h1 {
    font-size: 2rem;
    font-weight: 500;
    text-transform: none;
    margin: 0;
    color: rgba(244, 246, 251, 0.5);
    text-align: center;
}

strong {
    color: #f4f6fb;
    font-weight: normal;
}

.button-container[data-v-453f1426] {
    display: flex;
    flex-direction: column; /* Stack buttons in a column */
    justify-content: center; /* Center vertically */
    align-items: center; /* Center horizontally */
    margin-top: 2rem;
    gap: 1rem;
}

.button-container a[data-v-453f1426] {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.06rem;
    padding: 0.56rem 1.25rem;
    border-radius: 6.25rem;
    cursor: pointer;
    width: 100%; /* Ensure buttons are responsive */
    max-width: 300px; /* Optional: Limit button width */
    text-align: center;
}

.sc-btn-secondary {
    background: rgba(244, 246, 251, 0.1);
    color: rgba(244, 246, 251, 0.9);
    border: none;
    -webkit-backdrop-filter: blur(0.5rem);
    backdrop-filter: blur(0.5rem);
}

.sc-btn-primary {
    background: #2c3edc;
    color: #f4f6fb;
    border: none;
}

@media screen and (min-width: 768px) {
    .button-container[data-v-453f1426] {
        flex-direction: row; /* Align buttons horizontally on larger screens */
    }

    .button-container a[data-v-453f1426]:not(:last-child) {
        margin-right: 0.5rem;
    }
}

</style>