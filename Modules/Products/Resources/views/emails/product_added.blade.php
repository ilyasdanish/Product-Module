@component('mail::message')
    # New Product Added

    Hi {{ $product->user->name }},

    A new product has been added by you:

    - **Product name:** {{ $product->name }}

    Thank you for using our system.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
