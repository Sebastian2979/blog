<x-app-layout>
    <div class="p-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl mb-4">Neuen Beitrag erstellen</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('post.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <!-- Image -->
                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Beitragsbild')" />
                        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image"
                            :value="old('image')" autofocus />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <!-- Title -->
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Titel')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                            :value="old('title')" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <!-- Subtitle -->
                    <div class="mt-4">
                        <x-input-label for="subtitle" :value="__('Untertitel')" />
                        <x-text-input id="subtitle" class="block mt-1 w-full" type="text" name="subtitle"
                            :value="old('subtitle')" autofocus />
                        <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                    </div>
                    <!-- Category -->
                    <div class="mt-4">
                        <x-input-label for="category_id" :value="__('Kategorie')" />
                        <select id="category_id" name="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">Wähle eine Kategorie</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" @selected(old('category_id') == $category->id)>{{$category->name}}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                    <!-- Content -->
                    <div class="mt-4">
                        <x-input-label for="content" :value="__('Inhalt')" />
                        <x-input-textarea id="content" class="block mt-1 w-full" type="textarea" name="content">
                            {{ old('content') }}
                        </x-input-textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <!-- Published At -->
                    <div class="mt-4">
                        <x-input-label for="published_at" :value="__('Veröffentlicht am')" />
                        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local" name="published_at"
                            :value="old('published_at')" autofocus />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>Beitrag speichern</x-primary-button>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</x-app-layout>