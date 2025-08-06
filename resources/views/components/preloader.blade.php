<div id="preloader" class="preloader-container">
    <span class="loader"><span class="loader-inner"></span></span>
    <p id="loader-quote" class="loader-quote"></p>
</div>

<style>
    .preloader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #242F3F;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: opacity 0.75s ease, visibility 0.75s ease;
    }

    .loader {
        display: inline-block;
        width: 30px;
        height: 30px;
        position: relative;
        border: 4px solid #Fff;
        animation: loader 2s infinite ease;
    }

    .loader-inner {
        vertical-align: top;
        display: inline-block;
        width: 100%;
        background-color: #fff;
        animation: loader-inner 2s infinite ease-in;
    }

    .loader-quote {
        color: #fff;
        margin-top: 20px;
        font-family: 'Figtree', sans-serif;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    @keyframes loader {
        0% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(180deg);
        }

        50% {
            transform: rotate(180deg);
        }

        75% {
            transform: rotate(360deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes loader-inner {
        0% {
            height: 0%;
        }

        25% {
            height: 0%;
        }

        50% {
            height: 100%;
        }

        75% {
            height: 100%;
        }

        100% {
            height: 0%;
        }
    }
</style>

<script>
    function initializeAndHidePreloader() {
        const preloader = document.getElementById('preloader');
        const quoteElement = document.getElementById('loader-quote');

        if (!preloader) return;

        preloader.style.display = 'flex';
        preloader.style.visibility = 'visible';
        preloader.style.opacity = 1;

        quoteElement.textContent = "Productive dulu bolo!🤙";
        quoteElement.style.opacity = 1;

        //setel timer
        setTimeout(() => {
            if (preloader) {
                preloader.style.opacity = 0;
                preloader.style.visibility = 'hidden';
                setTimeout(() => {
                    if (preloader) preloader.style.display = 'none';
                }, 750); 
            }
        }, 2000); 
    }

    document.addEventListener('DOMContentLoaded', initializeAndHidePreloader);

    document.addEventListener('livewire:navigated', initializeAndHidePreloader);
</script>
