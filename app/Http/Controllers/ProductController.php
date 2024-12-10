<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function registerNewProduct(Request $request)
    {
        try {
            $messages = $this->makeMessage();
            $request->validate([
                'title' => 'required|string|max:255',  
                'creator' => 'required|string|max:255', 
                'year' => 'required|integer',            
                'price' => 'required|numeric|min:0',    
                'isEnable' => 'required|boolean',       
                'type' => 'required|string|max:255',   
                'ISBN' => 'nullable|string|max:255|unique:products,ISBN',
                'stock' => 'required|integer',     
            ], $messages);

            $product = new Product();
            $product->title = $request->title;
            $product->creator = $request->creator;
            $product->year = $request->year;
            $product->price = $request->price;
            $product->isEnable = $request->isEnable;
            $product->type = $request->type;
            $product->ISBN = $request->ISBN;
            $product->stock = $request->stock;
            $product->save();

            return response([
                'message' => 'Producto registrado',
                'data' => $product,
                'error' => false,
            ], 201);

        } catch (\Exception $e) {
            return response([
                'message' => 'Error al registrar el Producto',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function registerNewBook(Request $request)
    {
        try
        {
            $messages = $this->makeMessage();
            $request->validate([
                'title' => 'required|string|max:255',  
                'creator' => 'required|string|max:255', 
                'year' => 'required|integer',            
                'price' => 'required|numeric|min:0',           
                'ISBN' => 'nullable|string|max:255|unique:products',  
                'stock' => 'required|integer',     
            ], $messages);

            $product = new Product();
            $product->title = $request->title;
            $product->creator = $request->creator;
            $product->year = $request->year;
            $product->price = $request->price;
            $product->isEnable = true;
            $product->type = "book";
            $product->ISBN = $request->ISBN;
            $product->stock = $request->stock;
            $product->save();

            return response([
                'message' => 'Producto registrado',
                'data' => $product,
                'error' => false,
            ], 201);

        } catch (\Exception $e) {
            return response([
                'message' => 'Error al registrar el Producto',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function registerNewMovie(Request $request)
    {
        try
        {
            $messages = $this->makeMessage();
            $request->validate([
                'title' => 'required|string|max:255',  
                'creator' => 'required|string|max:255', 
                'year' => 'required|integer',            
                'price' => 'required|numeric|min:0',           
                'stock' => 'required|integer',     
            ], $messages);

            $product = new Product();
            $product->title = $request->title;
            $product->creator = $request->creator;
            $product->year = $request->year;
            $product->price = $request->price;
            $product->isEnable = true;
            $product->type = "movie";
            $product->ISBN = $request->ISBN;
            $product->stock = $request->stock;
            $product->save();

            return response([
                'message' => 'Producto registrado',
                'data' => $product,
                'error' => false,
            ], 201);

        } catch (\Exception $e) {
            return response([
                'message' => 'Error al registrar el Producto',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function makeMessage()
    {
        $messages = [
            'title.required' => 'El título es requerido',
            'creator.required' => 'El nombre del autor es requerido',
            'year.required' => 'El año es requerido y debe ser un número entero',
            'price.required' => 'El precio es requerido y debe ser un número',
            'isEnable.required' => 'El estado es requerido y debe ser verdadero o falso',
            'type.required' => 'El tipo es requerido',
            'ISBN.string' => 'El ISBN debe ser un texto',
            'stock.required' => 'El Stock es requerido',
            'ISBN.unique' => 'El ISBN ya está registrado. Por favor, use un ISBN diferente.',

        ];
        return $messages;
    }
}
