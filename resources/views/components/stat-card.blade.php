@props([
    'title',
    'value',
    'note' => null,
    'dark' => false,
])

<div {{ $attributes->merge(['class' => $dark 
    ? 'bg-[#0D0E12] border border-[#2A2E38] rounded-[20px] p-6 flex flex-col justify-between text-[#F2EFE9] shadow-sm' 
    : 'bg-[#FFFFFF] border border-[#E4E1D9] rounded-[20px] p-6 flex flex-col justify-between shadow-sm']) }}>
    
    <div>
        <div class="text-xs font-semibold {{ $dark ? 'text-[#A9ADB9]' : 'text-[#62656F]' }} mb-2">
            {{ $title }}
        </div>
        <div class="text-3xl sm:text-4xl font-extrabold font-['Bricolage_Grotesque',sans-serif] tracking-tight {{ $dark ? 'text-[#F5A524]' : 'text-[#17181C]' }}">
            {{ $value }}
        </div>
    </div>

    @if ($note)
        <div class="text-xs {{ $dark ? 'text-[#A9ADB9]/80' : 'text-[#62656F]' }} mt-4">
            {{ $note }}
        </div>
    @endif
</div>
