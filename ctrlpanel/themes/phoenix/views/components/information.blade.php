{{-- imprint and privacy policy --}}
@php($website_settings = app(App\Settings\WebsiteSettings::class))
<div class="my-2 text-gray-700" {{ $attributes }}>
  <div class="container text-center">
    @if ($website_settings->show_imprint)
      <a target="_blank" href="{{ route('terms', 'imprint') }}">{{ __('Imprint') }}</a> |
    @endif
    @if ($website_settings->show_privacy)
      <a target="_blank" href="{{ route('terms', 'privacy') }}">{{ __('Privacy') }}</a>
    @endif
    @if ($website_settings->show_tos)
      | <a target="_blank" href="{{ route('terms', 'tos') }}">{{ __('Terms of Service') }}</a>
    @endif
  </div>
</div>
