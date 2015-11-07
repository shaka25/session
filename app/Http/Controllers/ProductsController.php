<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$strings = file_get_contents(public_path().'/products.json');
		$datas=json_decode($strings,true);
		$totalQuantity = 0;	$totalPrice = 0;
		foreach($datas['products'] as $key=>$data) {
			$totalQuantity += $totalQuantity+$data["quantity"];
			$totalPrice += $data["price"]*$data["quantity"];
		}
 		return view('products.index', compact('datas', 'totalQuantity', 'totalPrice'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
    {
		$this->validate($request, [
			'name' => 'required|max:255',
			'quantity' => 'required|numeric',
			'price' => 'required|numeric'	
		]);
		
		$strings = file_get_contents(public_path().'/products.json');
		$datas=json_decode($strings,true);
		
		$jsonLght = count($datas['products']);
		if($jsonLght == 0) {
			$lastId = 0;
		} else {
			$lastRc = $jsonLght - 1;
			$lastId = $datas['products'][$lastRc]['id'];
		}
		$newRc = $jsonLght;	
		
		$datas['products'][$newRc]['id'] = $lastId + 1;
		$datas['products'][$newRc]['name'] = $request->name;
		$datas['products'][$newRc]['quantity'] = $request->quantity;
		$datas['products'][$newRc]['price'] = $request->price;
		$datas['products'][$newRc]['created'] = date("Y-m-d h:i:s");
		$datas['products'][$newRc]['updated'] = date("Y-m-d h:i:s");
		
		$newJsonString = json_encode($datas);
		file_put_contents(public_path().'/products.json', $newJsonString);
		
        return redirect('/products');
    }

	public function update(Request $request, $id)
	{
		if($this->validate($request, [
			'name' => 'required|max:255',
			'quantity' => 'required|numeric',
			'price' => 'required|numeric'	
		])) {
			return response()->json(array(
				'message'	=> 'Validate false',
				'status'	=> 'error'
			));
			exit();
		}
		
		$strings = file_get_contents(public_path().'/products.json');
		$datas=json_decode($strings,true);
		
		$datas['products'][$id]['name'] = $request->name;
		$datas['products'][$id]['quantity'] = $request->quantity;
		$datas['products'][$id]['price'] = $request->price;
		$datas['products'][$id]['updated'] = date("Y-m-d h:i:s");
		
		$newJsonString = json_encode($datas);
		file_put_contents(public_path().'/products.json', $newJsonString);
		
		return response()->json(array(
			'status'=>'success',
			'message'=>'Saved'
		));
		exit();
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$strings = file_get_contents(public_path().'/products.json');
		$datas=json_decode($strings);
		
		unset($datas->products[$id]);
		
		$datas->products = array_values($datas->products);
		
		$newJsonString = json_encode($datas);
		file_put_contents(public_path().'/products.json', $newJsonString);
		
		return response()->json(array(
			'status'=>'success',
			'message'=>'Deleted'
		));
		exit();
	}

}
