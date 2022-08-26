<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'status' => 'required|numeric', 
        ];
        $id = $this->input('id');
        if($id) {
            $password = $this->input('password');
            if($password) {
                $rules['mail'] = 'required|email';
                $rules['password'] = 'required|confirmed|min: 8';
            }
        }else {
            $rules['mail'] = ['required', 'email', Rule::unique('members','mail')->whereNull('deleted_at')];
            $rules['password'] = 'required|confirmed|min: 8';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function updateData()
    {
        $data = $this->except(['id', '_token', 'referer', 'password', 'password_confirmation']);
        $password = $this->input('password');
        if($password) {
            $password = Hash::make($password);
        }
        $data['password'] = $password;
        return $data;
    }
}
