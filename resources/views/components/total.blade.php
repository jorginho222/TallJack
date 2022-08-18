<div style="width: 80px; height: 40px" class="mt-3 px-3 pt-2 pb-3 bg-gray-300">
    <div class="text-center font-bold text-gray-700">
        @if($showPrimaryTotal && !$showSecondaryTotal)
            {{ $primaryTotal }}
        @endif
        @if(!$showPrimaryTotal && $showSecondaryTotal)
            {{ $secondaryTotal }}
        @endif
        @if($showPrimaryTotal && $showSecondaryTotal)
            <div class="grid grid-cols-2 gap-2">
                <p>
                    {{ $primaryTotal }}
                </p>
                <p>
                    {{ $secondaryTotal }}
                </p>
            </div>
        @endif
    </div>
</div>