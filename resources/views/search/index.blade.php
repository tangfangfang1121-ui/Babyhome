<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Smart Search') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SEARCH FORM --}}
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <form action="{{ route('search.index') }}" method="GET">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Keyword Input --}}
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keywords</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="Name, Location, Physical Features...">
                        </div>

                        {{-- Birth Place Input --}}
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Birth Place</label>
                            <input type="text" name="birth_place" value="{{ request('birth_place') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="e.g. London">
                        </div>

                        {{-- Category Select --}}
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Categories</option>
                                <option value="0" {{ request('type') == '0' ? 'selected' : '' }}>Parents Seeking Children</option>
                                <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Child Seeking Family</option>
                            </select>
                        </div>

                        {{-- Age Input --}}
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Age (Approx.)</label>
                            <input type="number" name="age" value="{{ request('age') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="e.g. 10">
                        </div>

                        {{-- Search Button --}}
                        <div class="md:col-span-3 flex justify-end items-end">
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded hover:bg-indigo-700 transition font-bold shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Search Records
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SEARCH RESULTS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($results as $post)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                        
                        {{-- Image Section --}}
                        <div class="relative h-56 w-full bg-gray-100">
                            @if($post->upload)
                                <img src="{{ route('file.show', $post->upload->id) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 font-medium">No Photo Available</div>
                            @endif
                            
                            {{-- Status Badge (Top Right) --}}
                            <div class="absolute top-2 right-2">
                                <span class="text-xs font-bold px-2 py-1 rounded shadow text-white {{ $post->status == 'missing' ? 'bg-red-600' : 'bg-green-600' }}">
                                    {{ $post->status == 'missing' ? 'MISSING' : 'FOUND' }}
                                </span>
                            </div>

                            {{-- Category Badge (Top Left) -- Matches Homepage Colors --}}
                            <div class="absolute top-2 left-2">
                                <span class="text-xs font-bold px-2 py-1 rounded shadow text-white {{ $post->type == 0 ? 'bg-blue-600' : 'bg-green-600' }}">
                                    {{ $post->type == 0 ? 'Parent Seeking Child' : 'Child Seeking Family' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Content Section --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="font-bold text-lg text-gray-900 mb-2 leading-tight">{{ $post->title }}</h3>
                            
                            {{-- Key Info Box --}}
                            <div class="bg-gray-50 p-3 rounded-md text-sm text-gray-700 space-y-1 mb-4 border border-gray-100">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Age:</span>
                                    <span class="font-bold">{{ $post->age ?? 'Unknown' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Birth Place:</span>
                                    <span class="font-bold truncate max-w-[10rem]">{{ $post->birth_place ?? 'Unknown' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Last Loc:</span>
                                    <span class="font-bold truncate max-w-[10rem]">{{ $post->location }}</span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm line-clamp-2 mb-4 flex-1">{{ $post->description }}</p>
                            
                            <a href="{{ route('posts.show', $post->id) }}" class="block w-full bg-gray-800 text-white text-center py-2 rounded font-semibold text-sm hover:bg-gray-700 transition duration-150">
                                View Full Details &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No matching records found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your keywords, changing the category, or removing filters.</p>
                        <div class="mt-6">
                            <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                Clear all filters
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $results->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>