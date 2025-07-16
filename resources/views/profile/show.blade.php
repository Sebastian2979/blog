<x-app-layout>

    <!-- User Section -->
    <div class="md:max-w-6xl md:mx-auto md:m-4 bg-white">
        <x-follow-ctr :user="$user">
                <div class="flex flex-col justify-center items-center h-80">
                    <x-user-avatar :user="$user" size="w-36 h-36" />
                    <h3 class="text-3xl mb-2">{{ $user->name }}</h4>
                    <p class="mb-2">{{ $user->bio }}</p>
                    <p class="text-gray-500">
                        <span x-text="followersCount"></span> Followers
                    </p>
                    @if(Auth::user() && Auth::user()->id !== $user->id)
                        <div class="mt-4">
                            <button @click="follow()" class="rounded-full px-4 py-2 text-white"
                                x-text="following ? 'Unfollow' : 'Follow'"
                                :class="following ? 'bg-red-600' : 'bg-emerald-600'">
                            </button>
                        </div>
                    @endif
                </div>
        </x-follow-ctr>
    </div>



    <!-- Post Section -->
    <div class="max-w-6xl mx-auto">
        <div>
            @forelse($posts as $post)
                <x-post-item :post="$post" />
            @empty
                <div class="text-center text-gray-400 py-16">Keine Beiträge gefunden</div>
            @endforelse
        </div>
    </div>


</x-app-layout>