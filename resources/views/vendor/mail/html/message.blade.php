<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            {{ config('app.name') }}
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {!! $slot !!}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {!! $subcopy !!}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} UMK PG Tracker &mdash; Faculty of Data Science and Computing (FSDK)<br>
            Universiti Malaysia Kelantan, Kampus Kota, 16100 Kota Bharu, Kelantan.<br>
            <a href="https://www.umk.edu.my" style="color: #2A9D8F;">www.umk.edu.my</a>
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>