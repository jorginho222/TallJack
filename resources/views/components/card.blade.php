<div style="position: relative; width: 90px; height: 130px" class="mr-2 shadow-xl bg-white rounded-lg">
    <div class="ml-1 font-bold text-gray-700">
        {{ $card['figure'] }}
    </div>
    <div class="ml-1">
        <img style="width: 20px; height: 20px" src="{{asset($card['logo'])}}" alt="logo">
    </div>
    <div style="position: absolute; top: 35%; left: 25%;">
        <img style="width: 50px; height: 50px" src="{{asset($card['logo'])}}" alt="logo">
    </div>
    <div style="position: absolute; top: 80%; left: 83%;" class="font-bold text-gray-700">
        {{ $card['figure'] }}
    </div>
</div>