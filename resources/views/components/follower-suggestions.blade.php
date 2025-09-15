@props(['users'])

<div>
    <p class="text font-semibold mb-4">Entdecke neue Leute</p>
    <ul class="flex flex-col gap-3 md:flex-row md:flex-wrap md:gap-4">
        @foreach($users as $user)
            <li class="bg-gray-100 p-4 rounded flex items-center hover:bg-gray-200 transition
                       w-full md:w-auto md:min-w-[240px]">
                {{-- <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-3"> --}}
                <a href="{{ route('profile.show', ['user' => $user]) }}" class="flex items-center gap-3">
                    @if ($user->imageUrl())
                        <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <img src="https://liccar.com/wp-content/uploads/png-transparent-head-the-dummy-avatar-man-tie-jacket-user.png"
                             alt="Dummy" class="w-10 h-10 rounded-full object-cover">
                    @endif

                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 truncate max-w-[180px]">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-[180px]">{{ '@' . $user->username }}</div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>