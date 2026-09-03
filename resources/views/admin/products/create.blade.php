@extends('layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
    <div class="bg-white border rounded-lg p-6 max-w-4xl">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.products._form')
        </form>
    </div>
@endsection
