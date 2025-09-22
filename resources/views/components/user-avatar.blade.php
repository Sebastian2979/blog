@props(['user', 'size' => 'w-12 h-12'])

@if ($user->imageUrl())
    <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="{{ $size }} rounded-full object-cover">
@else
    <img src="/avatar.png"
        alt="Dummy" class="{{ $size }} rounded-full object-cover">
@endif