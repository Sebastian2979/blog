<x-app-layout>
    <div class="flex p-2">
        <div class="max-w-7xl mx-auto">
            <div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex-1 gap-8 md:flex">
                    <x-follow-ctr :user="$user" class="mb-4 md:mb-0">
                        <div class="flex items-center justify-center">
                            <div class="flex flex-col items-center">
                                <x-user-avatar :user="$user" size="w-24 h-24" />
                                <h3 class="text-3xl">{{ $user->name }}</h4>
                                <p class="text-gray-500">
                                    <span x-text="followersCount"></span> Followers</p>
                                <p>{{ $user->bio }}</p>
                                @if(Auth::user() && Auth::user()->id !== $user->id)
                                    <div class="mt-4">
                                        <button @click="follow()" class="rounded-full px-4 py-2 text-white" 
                                        x-text="following ? 'Unfollow' : 'Follow'"
                                        :class="following ? 'bg-red-600' : 'bg-emerald-600'"
                                        ></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </x-follow-ctr>
                    <div class="flex-1">
                        <h1 class="text-5xl text-center hidden">{{ $user->name }}</h1>
                        <div>
                            @forelse($posts as $post)
                                <x-post-item :post="$post" />
                            @empty
                                <div class="text-center text-gray-400 py-16">No Posts Found</div>
                            @endforelse
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>