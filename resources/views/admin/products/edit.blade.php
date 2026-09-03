@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="bg-white border rounded-lg p-6 max-w-4xl">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @include('admin.products._form')
        </form>
    </div>
@endsection
