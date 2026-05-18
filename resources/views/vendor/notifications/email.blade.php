<x-mail::message>
    {{-- Custom Header/Logo Area --}}
    <div style="text-align: center; margin-bottom: 25px;">
        <h1
            style="color: #221a03; font-style: italic; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; margin: 0;">
            {{ config('app.name') }}
        </h1>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">
    </div>

    {{-- Greeting --}}
    @if (!empty($greeting))
        <h2 style="color: #1a202c; font-size: 20px; font-weight: 700;">{{ $greeting }}</h2>
    @else
        @if ($level === 'error')
            <h2 style="color: #e53e3e; font-size: 20px; font-weight: 700;">@lang('Whoops!')</h2>
        @else
            <h2 style="color: #1a202c; font-size: 20px; font-weight: 700;">@lang('Hello!')</h2>
        @endif
    @endif

    {{-- Intro Lines --}}
    <div style="color: #4a5568; line-height: 1.6; font-size: 16px;">
        @foreach ($introLines as $line)
            {{ $line }}
        @endforeach
    </div>

    {{-- Action Button --}}
    @isset($actionText)
        <?php
        $color = match ($level) {
            'success' => '#10b981',
            'error' => '#ef4444',
            default => '#EAB308', // আপনার সাইটের সিগনেচার ইয়েলো কালার
        };
        ?>
        <div style="text-align: center; margin: 30px 0;">
            <x-mail::button :url="$actionUrl" :color="$color">
                <span style="color: {{ $color == '#EAB308' ? '#000' : '#fff' }}; font-weight: 900;">
                    {{ $actionText }}
                </span>
            </x-mail::button>
        </div>
    @endisset

    {{-- Outro Lines --}}
    <div style="color: #4a5568; line-height: 1.6; font-size: 16px; margin-top: 20px;">
        @foreach ($outroLines as $line)
            {{ $line }}
        @endforeach
    </div>

    {{-- Salutation --}}
    <div style="margin-top: 35px; padding-top: 20px; border-top: 1px solid #edf2f7; color: #718096;">
        @if (!empty($salutation))
            {{ $salutation }}
        @else
            @lang('Regards,')<br>
            <strong>{{ config('app.name') }}</strong> Team
        @endif
    </div>

    {{-- Subcopy --}}
    @isset($actionText)
        <x-slot:subcopy>
            <div style="font-size: 12px; color: #a0aec0;">
                @lang("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" . 'into your web browser:', [
                    'actionText' => $actionText,
                ])
                <br>
                <a href="{{ $actionUrl }}" style="color: #EAB308; word-break: break-all;">{{ $displayableActionUrl }}</a>
            </div>
        </x-slot:subcopy>
    @endisset
</x-mail::message>
