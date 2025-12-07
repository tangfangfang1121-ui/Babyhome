<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Main Content Card --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8 border border-gray-100">
                <div class="md:flex">
                    
                    {{-- Left Side: Image Display --}}
                    <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-6 border-b md:border-b-0 md:border-r border-gray-200">
                        @if($post->upload)
                            <img src="{{ route('file.show', $post->upload->id) }}" 
                                 alt="{{ $post->title }}" 
                                 class="max-h-[500px] w-auto object-contain rounded-lg shadow-lg border-4 border-white">
                        @else
                            <div class="flex flex-col items-center text-gray-400">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>No Photo Provided</span>
                            </div>
                        @endif
                    </div>

                    {{-- Right Side: Details --}}
                    <div class="p-8 md:w-1/2 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full {{ $post->status == 'missing' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $post->status == 'missing' ? 'MISSING' : 'FOUND' }}
                                </span>
                                <span class="text-xs text-gray-400">ID: #{{ $post->id }}</span>
                            </div>
                            
                            <h1 class="block mt-4 text-3xl leading-tight font-extrabold text-gray-900">{{ $post->title }}</h1>
                            
                            <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-3">Key Information</h3>
                                <div class="grid grid-cols-2 gap-y-3 text-sm text-gray-700">
                                    <div>
                                        <span class="block text-gray-500 text-xs">Category</span>
                                        <span class="font-semibold">{{ $post->type == 0 ? 'Parent Seeking Child' : 'Child Seeking Family' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 text-xs">Current Age</span>
                                        <span class="font-semibold">{{ $post->age ?? 'Unknown' }} years old</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 text-xs">Date of Birth</span>
                                        <span class="font-semibold">{{ $post->dob ? \Carbon\Carbon::parse($post->dob)->format('d M Y') : 'Unknown' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 text-xs">Birth Place</span>
                                        <span class="font-semibold">{{ $post->birth_place ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-blue-200">
                                    <span class="block text-gray-500 text-xs">Last Seen Location</span>
                                    <span class="font-bold text-base text-gray-900">{{ $post->location }}</span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h3 class="font-bold text-gray-900 mb-2">Description / Distinctive Features</h3>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-wrap text-sm bg-gray-50 p-4 rounded border border-gray-100">{{ $post->description }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <span>Posted by <span class="font-bold text-gray-700">{{ $post->user->name }}</span></span>
                                <span>{{ $post->created_at->format('d M Y, h:i A') }}</span>
                            </div>

                            {{-- Admin/Author Actions --}}
                            @can('update', $post)
                                <div class="flex space-x-3">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-2 px-4 rounded shadow transition">
                                        Edit Report
                                    </a>

                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold py-2 px-4 rounded shadow-sm transition">
                                            Delete Report
                                        </button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 border border-gray-100">
                <h3 class="text-xl font-bold mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Leads & Comments <span class="ml-2 text-gray-400 text-sm font-normal">({{ $post->comments->count() }})</span>
                </h3>

                {{-- Comments List --}}
                <div class="space-y-6 mb-8">
                    @forelse($post->comments as $comment)
                        <div class="flex space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-sm text-gray-700 leading-relaxed">{{ $comment->body }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border-dashed border-2 border-gray-200">
                            No comments yet. If you have any information, please leave a message below.
                        </div>
                    @endforelse
                </div>

                {{-- Comment Form --}}
                @auth
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-6">
                        @csrf
                        <div>
                            <label for="body" class="block font-medium text-sm text-gray-700 mb-2">Provide a Tip / Leave a Message</label>
                            <textarea id="body" name="body" rows="3" 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                      placeholder="Enter any details you know..." required></textarea>
                            @error('body')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4 text-right">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                Submit Tip
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-yellow-50 p-4 rounded-md text-yellow-800 flex items-center justify-center border border-yellow-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Please <a href="{{ route('login') }}" class="font-bold underline mx-1">Log In</a> to provide information.
                    </div>
                @endauth
            </div>

        </div>
    </div>
</x-app-layout>