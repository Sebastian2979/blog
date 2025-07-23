<div x-data="{ open: false }">
    <button x-on:click="open = ! open" class="inline-flex gap-2 items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <div class="text-sm">Kategorien</div>
    </button>
    <div x-show="open" class="mt-4">
        <ul class="sm:flex text-sm font-medium text-gray-500">
            <li class="me-2">
                <a href="{{ route('dashboard') }}" class="{{ request('category')
    ? 'inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100'
    : 'inline-block px-4 py-3 text-white bg-blue-600 rounded-lg active' }}">Alle</a>
            </li>
            @foreach ($categories as $category)
                    <li class="me-2">
                        <a href="{{ route('post.byCategory', $category) }}"
                            class="{{
                Route::currentRouteNamed('post.byCategory') && request('category')->id == $category->id
                ? 'inline-block px-4 py-3 text-white bg-blue-600 rounded-lg active'
                : 'inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100'}}">{{ $category->name }}</a>
                    </li>
            @endforeach
        </ul>
    </div>
</div>