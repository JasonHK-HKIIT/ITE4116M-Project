@extends("layouts::base")

@section("content")
<x-main full-width>
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
