<x-mail::message>
# New website contact

**Name:** {{ $contact->name }}

**Email:** {{ $contact->email }}

@if ($contact->phone)
**Phone:** {{ $contact->phone }}
@endif

**Message:**

{{ $contact->message }}

<x-mail::button :url="'mailto:'.$contact->email">
Reply to {{ $contact->name }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
