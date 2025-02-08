<x-layout>
  {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
  <h1 class="page-title">Welcome {{ Auth::user()->name }}</h1>
</x-layout>