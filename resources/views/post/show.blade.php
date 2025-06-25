<x-app-layout>
    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-3xl mb-4">{{ $post->title }}</h1>
                <!-- User Avatar -->
                <div class="flex gap-4">
                    <x-user-avatar :user="$post->user" />

                    <div>
                        <x-follow-ctr :user="$post->user" class="flex gap-2">
                            <a href="{{ route('profile.show', $post->user)}}" class="hover:underline">
                                {{ $post->user->name }}
                             </a>
                            @auth
                            @if (Auth::user() && Auth::user()->id !== $post->user_id)
                                &middot;
                                <button x-text="following ? 'Unfollow' : 'Follow'" :class="following ? 'text-red-600' : 'text-emerald-600'" @click="follow()"></button> 
                            @endif
                            @endauth
                        </x-follow-ctr>
                        <div class="flex gap-2 text-gray-500 text-sm">

                            {{ $post->readTime() }} min read

                            &middot;

                            {{ $post->created_at->format('M d, Y') }}

                        </div>
                    </div>
                </div>
                @if($post->user_id === Auth::id())
                <div class="py-4 mt-8 border-t border-gray-200">
                    <x-primary-button href="{{ route('post.edit', $post->slug) }}">
                        Edit Post
                    </x-primary-button>
                    <form class="inline-block" action="{{ route('post.destroy', $post) }}" method="post">
                        @csrf
                        @method('delete')
                        <x-danger-button>
                            Delete Post
                        </x-danger-button>
                    </form>
                </div>
                @endif
                <!-- Clap Section Begin -->
                <x-clap-button :post="$post"/>
                <!-- Clap Section End -->
                <!-- Content Section Begin -->
                <div class="mt-4">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full">
                    <div class="mt-4">
                        {{ $post->content }}
                    </div>
                </div>
                <!-- Content Section End -->
                <div class="mt-8">
                    <span class="px-4 py-2 bg-gray-200 rounded-lg">
                        {{ $post->category->name }}
                    </span>
                </div>  
            </div>
        </div>
    </div>
</x-app-layout>