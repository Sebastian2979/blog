<x-app-layout>
    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl md:text-3xl mb-1">{{ $post->title }}</h1>
                <h2 class="text-1xl md:text-2xl mb-4 text-gray-700">{{ $post->subtitle }}</h2>
                <!-- User Avatar -->
                <div class="flex gap-4 p-2">
                    <x-user-avatar :user="$post->user" />
                    <div>
                        <x-follow-ctr :user="$post->user" class="flex gap-2">
                            <a href="{{ route('profile.show', $post->user)}}" class="hover:underline">
                                {{ $post->user->name }}
                            </a>
                            @auth
                                @if (Auth::user() && Auth::user()->id !== $post->user_id)
                                    &middot;
                                    <button x-text="following ? 'Unfollow' : 'Follow'"
                                        :class="following ? 'text-red-600' : 'text-emerald-600'" @click="follow()"></button>
                                @endif
                            @endauth
                        </x-follow-ctr>
                        <div class="flex gap-2 text-gray-500 text-sm">

                            {{ $post->readTime() }} min Lesezeit

                            &middot;

                            {{ $post->created_at->format('M d, Y') }} </br>
                        </div>
                    </div>
                </div>
                @if($post->user_id === Auth::id())
                    <div class="py-4 mt-8 border-t border-gray-200">
                        <x-primary-button href="{{ route('post.edit', $post->slug) }}">
                            Bearbeiten
                        </x-primary-button>
                        <form class="inline-block" action="{{ route('post.destroy', $post) }}" method="post">
                            @csrf
                            @method('delete')
                            <x-danger-button>
                                Löschen
                            </x-danger-button>
                        </form>
                    </div>
                @endif
                <!-- Clap and CommentCounter Section Begin -->
                <div class="flex justify-between items-center border-t border-b">
                    <x-clap-button :post="$post" />
                    <div class="py-2 text-sm text-sky-700">
                        @if($post->comments->count() == 1)
                            <div class="bg-sky-200 rounded p-2">
                                {{ $post->comments->count() }} Kommentar
                            </div>
                        @else
                            <div class="bg-sky-200 rounded p-2">
                                {{ $post->comments->count() }} Kommentare
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Clap Section End -->
                <!-- Content Section Begin -->
                <div class="mt-4">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full">
                    <div class="prose max-w-none mt-4">
                        {!! $post->content !!}
                    </div>
                </div>
                <!-- Content Section End -->
                <div class="mt-8">
                    <span class="px-4 py-2 bg-gray-200 rounded-lg">
                        {{ $post->category->name }}
                    </span>
                </div>
                <!-- Comment Section -->
                @auth
                    <div class="mt-8">
                        @if (session('success'))
                            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('comment.store') }}" method="post">
                            @csrf
                            <div class="mt-4">
                                <x-input-label for="comment" :value="__('Kommentar')" />
                                <x-input-textarea id="comment" class="block mt-1 w-full" type="textarea" name="comment">
                                    {{ old('comment') }}
                                </x-input-textarea>
                                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                            </div>

                            {{-- Versteckte post_id --}}
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="mt-4">
                                <x-primary-button>
                                    Absenden
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @endauth
                <div class="mt-8">
                    @forelse ($comments as $comment)
                        <x-comment-item :comment="$comment" />
                    @empty
                        <p class="text-sm text-gray-500">Keine Kommentare gefunden</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>