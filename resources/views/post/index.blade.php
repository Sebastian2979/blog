<x-app-layout>
    <div class="p-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Mobile Menu for Categories --}}
                <div x-data="{ open: false }" x-on:click="open = ! open" class="sm:hidden flex flex-col justify-center items-center cursor-pointer text-gray-500 p-4">
                    <div class="text-2xl sm:hidden">Kategorien</div>
                    <div x-show="open" class="mt-6"><x-category-tabs /></div>
                </div>
                {{-- Desktop Menu for Categories --}}
                <div class="hidden md:flex justify-center p-4">
                    <x-category-tabs />
                </div>
            </div>
            <div class="mt-4 text-gray-900">
                @forelse($posts as $post)
                    <x-post-item :post="$post" />
                @empty
                    <div class="text-center text-gray-400 py-16">Keine Beiträge gefunden</div>
                @endforelse
            </div>
            {{ $posts->links() }}
        </div>
    </div>
    </div>
</x-app-layout>