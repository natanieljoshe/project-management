<div {{ $attributes }}>
    <div class="dark:hidden">
        <img src="{{ asset('build/assets/logo/logo-light-mode.svg') }}" alt="Application Logo Light"  class="w-10 sm:w-10 md:w-10 lg:w-10 xl:w-10">
    </div>

    <div class="hidden dark:block">
        <img src="{{ asset('build/assets/logo/logo-dark-mode.svg') }}" alt="Application Logo Dark"  class="w-10 sm:w-10 md:w-10 lg:w-10 xl:w-10">
    </div>
</div>
