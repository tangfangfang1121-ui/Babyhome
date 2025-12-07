<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Start Form --}}
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Child Name / Title --}}
                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Child's Name / Title</label>
                            <input id="title" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                   type="text" name="title" value="{{ old('title') }}" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category Selection --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Category</label>
                            <div class="mt-2 flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-blue-600" name="type" value="0" checked>
                                    <span class="ml-2">Parent Seeking Child</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-green-600" name="type" value="1">
                                    <span class="ml-2">Child/Volunteer Seeking Family</span>
                                </label>
                            </div>
                        </div>

                        {{-- Age, DOB, Birth Place (3-Column Grid) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            {{-- Age --}}
                            <div>
                                <label for="age" class="block font-medium text-sm text-gray-700">Current Age</label>
                                <input id="age" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="number" name="age" value="{{ old('age') }}" placeholder="e.g. 10" required />
                                @error('age') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="dob" class="block font-medium text-sm text-gray-700">Date of Birth</label>
                                <input id="dob" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="date" name="dob" value="{{ old('dob') }}" />
                                @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Birth Place --}}
                            <div>
                                <label for="birth_place" class="block font-medium text-sm text-gray-700">Birth Place / Origin</label>
                                <input id="birth_place" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                       type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="e.g. London" />
                                @error('birth_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="mb-4">
                            <label for="location" class="block font-medium text-sm text-gray-700">Last Seen Location</label>
                            <input id="location" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" 
                                   type="text" name="location" value="{{ old('location') }}" required />
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Photo Upload --}}
                        <div class="mb-4">
                            <label for="photo" class="block font-medium text-sm text-gray-700">Photo (Required)</label>
                            <input id="photo" class="block mt-1 w-full" 
                                   type="file" name="photo" required />
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Detailed Description</label>
                            <textarea id="description" name="description" rows="4" 
                                      class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md transition duration-150">
                                Submit Report
                            </button>
                        </div>
                    </form>
                    {{-- End Form --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>