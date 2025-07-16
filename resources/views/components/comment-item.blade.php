<div class="flex flex-col md:flex-row gap-2 bg-white border border-gray-200 rounded-lg shadow-sm mb-4 p-4">
    <div class="flex-1 flex-col">
        <h3 class="text-sm text-gray-400">
            Kommentar von {{$comment->user->name}} am {{ $comment->created_at->format('M d, Y H:i') }}
        </h3>
        <p>{{ $comment->body }}</p>
    </div>
    <div class="flex justify-end md:justify-center items-center mt-2 md:mt-0">
        @if (Auth::user() && Auth::user()->id == $comment->user_id)
            <form class="inline-block" action="{{ route('comment.destroy', $comment) }}" method="post">
                @csrf
                @method('delete')
                <button class="p-2 rounded-full bg-red-600 hover:bg-red-700 text-white transition-all duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         viewBox="0 0 24 24" 
                         fill="currentColor" 
                         class="size-5 transition-transform duration-300 hover:scale-110">
                        <path fill-rule="evenodd"
                              d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                              clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        @endif
    </div>
</div>
