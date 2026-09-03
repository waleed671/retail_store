@extends('layouts.admin')
@section('title', 'Add Category')
@section('page-title', 'Add Category')
@section('content')
    <div class="bg-white border rounded-lg p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @include('admin.categories._form')
        </form>
    </div>
@endsection
