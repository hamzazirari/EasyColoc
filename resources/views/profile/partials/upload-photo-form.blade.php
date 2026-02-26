<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Photo de profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Uploadez une photo de profil.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        <div>
            @if(auth()->user()->photo)
                <img src="{{ Storage::url(auth()->user()->photo) }}" class="w-20 h-20 rounded-full mb-4">
            @endif

            <input type="file" name="photo" accept="image/*" />

            @error('photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ __('Sauvegarder') }}
        </button>
    </form>
</section>