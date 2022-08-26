@extends('layouts.app')

@section('content')
    @if ($member)
        <member-edit v-bind:member="{{ ($member) }}" :old="{{ json_encode(Session::getOldInput()) }}" :errors="{{ $errors }}" :last_member_page="{{ json_encode($lastMemberPage) }}"></member-edit>
    @else
        <member-edit  :old="{{ json_encode(Session::getOldInput()) }}" :errors="{{ $errors }}" :last_member_page="{{ json_encode($lastMemberPage) }}"></member-edit>     
    @endif
@endsection           