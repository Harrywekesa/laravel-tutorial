@extends('layouts.app')

@section('title', 'New Task')

@section('content')
<h1 class="text-2xl font-bold mb-6">Create Task</h1>
@include('tasks._form', ['action' => route('tasks.store'), 'method' => 'POST'])
@endsection
