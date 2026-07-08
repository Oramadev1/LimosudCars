<x-mail::message>
# New website contact

**Name:** {{ $contactMessage->name }}

**Email:** {{ $contactMessage->email }}

@if ($contactMessage->phone)
**Phone:** {{ $contactMessage->phone }}
@endif

**Message:**

{{ $contactMessage->message }}

<x-mail::button :url="'mailto:'.$contactMessage->email">
Reply to {{ $contactMessage->name }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
