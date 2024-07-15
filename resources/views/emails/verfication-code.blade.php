<x-mail::message>
![Logo](https://goacquirely.com/themes/tallstack/img/logos/acquirely-logo-blue.png)

# We noticed an attempt to log in to your account that seems suspicious. Was this you?

### If this was you

Just to be safe, to log in to this account you’ll need to confirm this is really you by entering the following single-use code.

<x-mail::panel>
{{ $code }}
</x-mail::panel>

If you didn't request this, you can ignore this email.

Thanks,<br>
{{ config('app.name') }} Team

</x-mail::message>
