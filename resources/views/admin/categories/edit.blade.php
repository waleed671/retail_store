@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('content')
    <div class="bg-white border rounded-lg p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @include('admin.categories._form')
        </form>
    </div>
@endsection
