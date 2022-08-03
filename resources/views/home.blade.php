@extends('layouts.app')

@section('content')
    <member-component v-bind:members="{{ ($members) }}" :constants="{{ ($constants) }}"></member-components>
@endsection
