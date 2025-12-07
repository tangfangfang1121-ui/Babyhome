<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Report') }}: {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Critical: Simulate PUT request --}}

                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Child's Name / Title</label>
                            <input id="title" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                   type="text" name="title" value="{{ old('title', $post->title) }}" required />
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status" class="block font-medium text-sm text-gray-700">Current Status</label>
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="missing" {{ $post->status == 'missing' ? 'selected' : '' }}>Missing (Searching)</option>
                                <option value="found" {{ $post->status == 'found' ? 'selected' : '' }}>Found (Case Closed)</option>
                            </select>
                        </div>

                        {{-- Age, DOB, Birth Place (3-Column Grid) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            {{-- Age --}}
                            <div>
                                <label for="age" class="block font-medium text-sm text-gray-700">Current Age</label>
                                <input id="age" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="number" name="age" value="{{ old('age', $post->age) }}" required />
                                @error('age') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="dob" class="block font-medium text-sm text-gray-700">Date of Birth</label>
                                {{-- Format date to Y-m-d for HTML date input --}}
                                <input id="dob" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="date" name="dob" value="{{ old('dob', $post->dob ? \Carbon\Carbon::parse($post->dob)->format('Y-m-d') : '') }}" />
                                @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Birth Place --}}
                            <div>
                                <label for="birth_place" class="block font-medium text-sm text-gray-700">Birth Place / Origin</label>
                                <input id="birth_place" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="text" name="birth_place" value="{{ old('birth_place', $post->birth_place) }}" />
                                @error('birth_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="mb-4">
                            <label for="location" class="block font-medium text-sm text-gray-700">Last Seen Location</label>
                            <input id="location" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                   type="text" name="location" value="{{ old('location', $post->location) }}" required />
                        </div>

                        {{-- Photo --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Current Photo</label>
                            @if($post->upload)
                                <div class="mt-2 mb-2">
                                    <img src="{{ route('file.show', $post->upload->id) }}" class="h-32 w-auto rounded border">
                                    <p class="text-xs text-gray-500 mt-1">Leave the box below empty to keep this photo.</p>
                                </div>
                            @endif
                            
                            <label for="photo" class="block font-medium text-sm text-gray-700 mt-2">Change Photo (Optional)</label>
                            <input id="photo" class="block mt-1 w-full" type="file" name="photo" />
                            @error('photo') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" 
                                      class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('description', $post->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150">
                                Update Report
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>