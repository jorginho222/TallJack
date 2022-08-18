<button {{ $attributes->merge(['class' => 'mr-2 px-3 py-3 bg-yellow-300 rounded-md text-sm text-orange-500 font-bold uppercase']) }}>
    {{ $slot }}
</button>