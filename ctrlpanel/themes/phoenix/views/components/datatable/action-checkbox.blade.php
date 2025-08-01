@props(['action' => '', 'method' => 'POST', 'form_attributes' => [], 'checked' => false, 'name' => ''])

<form class="contents" method="POST" action="{{ $action }}"
  @foreach ($form_attributes as $attribute => $value)
        {{ $attribute }}="{{ $value }}" @endforeach>
  @csrf
  @method($method)
  <x-checkbox id="{{ $name }}" name="{{ $name }}" checked="{{ $checked }}" title=""
    onchange="this.form.submit();" class="!mb-0">
  </x-checkbox>
</form>
