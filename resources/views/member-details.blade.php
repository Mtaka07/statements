@extends('layouts.app')

@section('content')
    @if ($member)
        <member-details v-bind:member="{{ ($member) }}" :last_member_page="{{ json_encode($lastMemberPage) }}"></member-details>
    @else
        <member-details v-bind:member="{{ ($member) }}" :last_member_page="{{ json_encode($lastMemberPage) }}"></member-details> 
    @endif
@endsection           