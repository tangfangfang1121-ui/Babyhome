<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Quick Actions / Header --}}
            <div class="mb-6 flex justify-between items-center bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-2xl font-bold text-gray-700">My Missing Reports</h3>
                <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150">
                    + Create New Report
                </a>
            </div>

            {{-- Reports List --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($posts->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Title / Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Posted</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($posts as $post)
                                <tr>
                                    {{-- Photo --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($post->upload)
                                            <img src="{{ route('file.show', $post->upload->id) }}" class="h-10 w-10 rounded-full object-cover border border-gray-300">
                                        @else
                                            <span class="text-gray-400 text-xs">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Title / Type --}}
                                    <td class="px-6 py-4">
                                        <a href="{{ route('posts.show', $post->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline">{{ $post->title }}</a>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $post->type == 0 ? 'Parent Seeking Child' : 'Child Seeking Family' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            {{ $post->status == 'missing' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($post->status) }}
                                        </span>
                                    </td>
                                    
                                    {{-- Posted Time --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $post->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Actions (Edit/Delete) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="flex space-x-3 justify-center">
                                            <a href="{{ route('posts.edit', $post->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                            
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            <p class="mb-2">You haven't posted any reports yet.</p>
                            <a href="{{ route('posts.create') }}" class="text-blue-600 font-medium hover:underline">Click here to create a new report.</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>