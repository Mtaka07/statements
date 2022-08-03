<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function memberId()
    {
        return $this->get('searchMemberId');
    }

    public function name()
    {
        return $this->get('searchName');
    }

    public function mail()
    {
        return $this->get('mail');
    }

    public function status()
    {
        return $this->get('searchStatus');
    }
}