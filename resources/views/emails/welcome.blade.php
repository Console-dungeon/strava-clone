<x-mail::message>
# Witaj, {{ $name }}!

Dziękujemy za rejestrację w **Straba Clone**. Możesz teraz logować swoje aktywności fizyczne, importować dane z Garmin i śledzić postępy.

<x-mail::button :url="config('app.url')">
Przejdź do aplikacji
</x-mail::button>

Pozdrawiamy,<br>
Zespół Straba Clone
</x-mail::message>
