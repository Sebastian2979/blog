<x-app-layout>
    <div class="p-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl mb-4">Neuen Beitrag erstellen</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('post.store') }}" enctype="multipart/form-data" method="post"
                    x-data="imagePreview()">
                    @csrf

                    <!-- Image Preview -->
                    <div class="space-y-3">
                        <label class="block">
                            <span class="text-sm font-medium">Bild auswählen</span>
                            <input x-ref="input" type="file" name="image" accept="image/*"
                                x-on:change="handleChange($event)" class="mt-1 block w-full">
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </label>

                        <!-- Fehlermeldung -->
                        <template x-if="error">
                            <p class="text-sm text-red-600" x-text="error"></p>
                        </template>

                        <!-- Dateiname + Größe (immer) -->
                        <template x-if="fileName">
                            <p class="text-sm" :class="error ? 'text-red-600' : 'text-gray-600'">
                                <span x-text="fileName"></span> –
                                <span x-text="fileSize"></span>
                            </p>
                        </template>

                        <!-- Vorschau nur wenn OK -->
                        <template x-if="previewSrc">
                            <img :src="previewSrc" alt="Vorschau" class="max-h-64 rounded-lg shadow"
                                x-on:load="revoke()">
                        </template>

                        <div class="flex gap-2">
                            <button type="button" class="px-3 py-1.5 rounded bg-gray-200"
                                x-on:click="clear()">Zurücksetzen</button>
                        </div>
                    </div>

                    <script>
                        function imagePreview() {
                            return {
                                previewSrc: null,
                                _url: null,
                                error: null,
                                fileName: null,
                                fileSize: null,

                                handleChange(e) {
                                    this.error = null;
                                    const file = e.target.files?.[0];
                                    this.clear(false); // Reset, Input behalten

                                    if (!file) return;

                                    this.fileName = file.name;
                                    this.fileSize = this.formatSize(file.size);

                                    // Typ prüfen
                                    if (!file.type.startsWith('image/')) {
                                        this.error = 'Bitte eine Bilddatei auswählen.';
                                        return;
                                    }

                                    // Größe prüfen (max. 2 MB)
                                    if (file.size > 2 * 1024 * 1024) {
                                        this.error = 'Das Bild ist zu groß (max. 2 MB).';
                                        return;
                                    }

                                    // Vorschau vorbereiten
                                    if (this._url) URL.revokeObjectURL(this._url);
                                    this._url = URL.createObjectURL(file);
                                    this.previewSrc = this._url;
                                },

                                revoke() {
                                    // Speicher könnte hier freigegeben werden,
                                    // aber wir warten bis clear()
                                },

                                clear(resetInput = true) {
                                    this.previewSrc = null;
                                    this.error = null;
                                    this.fileName = null;
                                    this.fileSize = null;
                                    if (this._url) { URL.revokeObjectURL(this._url); this._url = null; }
                                    if (resetInput && this.$refs.input) this.$refs.input.value = '';
                                },

                                formatSize(bytes) {
                                    if (bytes < 1024) return bytes + ' Bytes';
                                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                                }
                            }
                        }
                    </script>

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
                        <select id="category_id" name="category_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">Wähle eine Kategorie</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" @selected(old('category_id') == $category->id)>
                                    {{$category->name}}
                                </option>
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
                        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local"
                            name="published_at" :value="old('published_at')" autofocus />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button x-bind:disabled="!!error"
                            x-bind:class="error ? 'bg-gray-400 cursor-not-allowed' : ''">
                            Beitrag speichern
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>