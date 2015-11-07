<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'id' => 'required|unique:products,id',
			'name' => 'required|unique:products,name',
			'quantity' => 'required:numeric',
			'price' => 'required:numeric'		
		];
	}

}
