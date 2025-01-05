<?php

namespace App\Http\Controllers;

use App\Models\CarroCompras;
use App\Models\User;  
use App\Models\Product;  
use Illuminate\Http\Request;

class CarroComprasController extends Controller
{
    public function addProduct(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'rut_solicitante' => 'required|string',
            'id_product' => 'required|integer', 
            'quantity' => 'required|integer|min:1',
            'days_rent' => 'required|integer|min:1',
        ]);

        // Verificar si el rut_solicitante existe en la base de datos
        $user = User::where('rut', $request->rut_solicitante)->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'El RUT proporcionado no está registrado.',
            ], 404);
        }

        // Verificar si el producto existe en la base de datos
        $product = Product::where('id', $request->id_product)->first();

        if (!$product) {
            return response()->json([
                'message' => 'El producto proporcionado no existe.',
                'product_received' => $request->id_product,
                'product_found' => false,
            ], 404);
        }

        // Crear una nueva instancia de CarroCompras
        $carroCompras = new CarroCompras();
        $carroCompras->rut_solicitante = $request->rut_solicitante;
        $carroCompras->id_product = $request->id_product;
        $carroCompras->quantity = $request->quantity;
        $carroCompras->days_rent = $request->days_rent;

        // Intentar guardar el libro en el carrito de compras
        if ($carroCompras->save()) {
            return response()->json([
                'message' => 'Producto agregado al carrito exitosamente.',
                'carro_compras' => $carroCompras
            ], 201);
        } else {
            return response()->json([
                'message' => 'Hubo un problema al agregar el Producto al carrito.',
            ], 500);
        }

    }

    public function removeProduct(Request $request)
    {
        // Validación de los datos incluyendo verificación de números no negativos
        try {
            $validated = $request->validate([
                'id' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1', // Esto evita números negativos y cero
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => [
                    'quantity' => $request->quantity <= 0 ? 
                        'La cantidad debe ser mayor a 0' : 
                        'Error en el formato de los datos',
                ]
            ], 400);
        }
    
        // Buscar el item en el carrito
        $cartItem = CarroCompras::find($request->id);
    
        // Verificar si el item existe
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'El item no existe en el carrito.'
            ], 404);
        }
    
        // Verificar si la cantidad a eliminar es válida
        if ($request->quantity > $cartItem->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Error: La cantidad a eliminar es mayor que la cantidad disponible en el carrito.',
                'details' => [
                    'cantidad_disponible' => $cartItem->quantity,
                    'cantidad_solicitada' => $request->quantity
                ]
            ], 400);
        }
    
        try {
            // Si la cantidad a eliminar es igual a la cantidad del item, eliminar el item completo
            if ($request->quantity == $cartItem->quantity) {
                $cartItem->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Item eliminado completamente del carrito.'
                ], 200);
            }
    
            // Si la cantidad a eliminar es menor, actualizar la cantidad
            $cartItem->quantity -= $request->quantity;
            $cartItem->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada correctamente.',
                'data' => [
                    'cantidad_restante' => $cartItem->quantity
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clearCart(Request $request)
    {
        try {
            // Eliminar todos los registros de la tabla CarroCompras
            CarroCompras::truncate();
            
            return response()->json([
                'success' => true,
                'message' => 'El carrito ha sido limpiado exitosamente.'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar el carrito.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getAllCartItems()
    {
        try {
            // Obtener todos los items del carrito con información del producto
            $cartItems = CarroCompras::join('products', 'carro_compras.id_product', '=', 'products.id')
                ->select(
                    'carro_compras.*',
                    'products.title as product_name', // Cambiado de 'name' a 'title'
                    'products.type'
                )
                ->get();
    
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'El carrito está vacío.',
                    'data' => []
                ], 200);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Items del carrito recuperados exitosamente.',
                'data' => [
                    'items' => $cartItems,
                    'total_items' => $cartItems->count()
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los items del carrito.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateTotalRental()
    {
        try {
            $cartItems = CarroCompras::join('products', 'carro_compras.id_product', '=', 'products.id')
                ->select(
                    'carro_compras.quantity',
                    'carro_compras.days_rent',
                    'products.price',
                    'products.title'
                )
                ->get();
    
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'El carrito está vacío.',
                    'data' => [
                        'total' => 0,
                        'details' => []
                    ]
                ], 200);
            }
    
            $details = [];
            $totalCost = 0;
    
            // Calcular el costo por cada item
            foreach ($cartItems as $item) {
                $itemTotal = $item->quantity * $item->price * $item->days_rent;
                $totalCost += $itemTotal;
    
                $details[] = [
                    'product' => $item->title,
                    'quantity' => $item->quantity,
                    'price_per_day' => $item->price,
                    'days_rent' => $item->days_rent,
                    'subtotal' => $itemTotal
                ];
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Cálculo realizado exitosamente.',
                'data' => [
                    'total_cost' => $totalCost,
                    'details' => $details
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular el costo total del arriendo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
