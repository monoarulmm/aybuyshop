@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Manage Categories</h2>

        <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Category Name" class="border p-2 rounded w-full" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add</button>
        </form>

        <table class="w-full bg-white border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <td class="p-2 border text-center">{{ $cat->name }}</td>
                        <td class="p-2 border text-center">
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
