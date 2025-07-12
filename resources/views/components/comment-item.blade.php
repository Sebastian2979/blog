<div class="flex flex-col gap-2 bg-white border border-gray-200 rounded-lg shadow-sm mb-4 p-4">
    <h3 class="text-sm text-gray-400">Kommentar von {{$comment->user->name}} am {{ $comment->created_at->format('M d, Y H:i')}}</h3>
    <p>{{ $comment->body }}</p>
    @if (Auth::user() && Auth::user()->id == $comment->user_id)
        <form class="inline-block" action="{{ route('comment.destroy', $comment) }}" method="post">
             @csrf
            @method('delete')
            <x-danger-button>
                Delete Comment
            </x-danger-button>
        </form>
    @endif
</div>