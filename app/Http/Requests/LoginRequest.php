<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Enums\ResponseCode;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest 
{
    /**
     * Determine if the user is authorized to make this request
     * 
     * @return bool
     */
    public function authrize()
    {
        return true;
    }

    /** Get the Validation rules that apply to the request 
     * 
     * @return array
     */
    public function rules()
    {
        if($this->request->has('isLine')) {
            $rules = [
                'id' => 'required',
                'isLine' => 'required',
            ];
        }else {
            $rules = [
                'mail' => 'required',
                'password' => 'required',
            ];
        }
        Log::info('$rules');
        Log::info($rules);
        Log::info($this->request->get('id'));
        Log::info($this->request->get('isLine'));

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $res = response()->json([
            'result' => false,
            'status' => ResponseCode::SUCCESS,
            'message' => '正しい値を入力してください'
        ],ResponseCode::SUCCESS);
        throw new HttpResponseException($res);
    }

    /**
     * @return array
     */
    public function createData()
    {
        return $this->all();
    }


}