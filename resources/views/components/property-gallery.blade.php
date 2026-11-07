@props(['property'])

@php($photos = collect($property->images)->map(fn ($path) => asset('storage/' . $path))->values())

<div x-data="{ photos: @js($photos), active: 0 }" class="space-y-3">
    @if($photos->isNotEmpty())
        <div class="aspect-[16/10] overflow-hidden rounded-3xl bg-slate-100 shadow-sm">
            <img :src="photos[active]" alt="{{ $property->title }}" class="h-full w-full object-contain">
        </div>
        @if($photos->count() > 1)
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach($photos as $index => $photo)
                    <button type="button" @click="active = {{ $index }}" :aria-pressed="active === {{ $index }}" aria-label="Ver foto {{ $index + 1 }}" class="overflow-hidden rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <img src="{{ $photo }}" alt="Foto {{ $index + 1 }} de {{ $property->title }}" class="aspect-video w-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    @else
        <div class="grid aspect-[16/10] place-items-center rounded-3xl bg-slate-100 text-slate-300">
            <svg class="h-20 w-20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
        </div>
    @endif
</div>
