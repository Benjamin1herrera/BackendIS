<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CarroComprasController;
use App\Models\solicitudesArriendo;
use App\Models\CarroCompras;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class SolicitudesArriendoController extends Controller
{
    public function transferFromCart(Request $request)
    {
        try {
            $cartItems = CarroCompras::join('products', 'carro_compras.id_product', '=', 'products.id')
                ->join('users', 'carro_compras.rut_solicitante', '=', 'users.rut')
                ->select(
                    'carro_compras.*',
                    'products.title as product_name',
                    'products.price',
                    'users.name as client_name'
                )
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío.'
                ], 400);
            }

            $solicitudesCreadas = 0;

            foreach ($cartItems as $item) {
                $totalCost = $item->quantity * $item->price * $item->days_rent;
                
                $solicitud = new solicitudesArriendo();
                $solicitud->rut_solicitante = $item->rut_solicitante;
                $solicitud->name_client = $item->client_name;
                $solicitud->state = 'pendiente';
                $solicitud->cost = $totalCost;
                $solicitud->date = now();
                $solicitud->products = $item->product_name;
                
                if ($solicitud->save()) {
                    $solicitudesCreadas++;
                }
            }

            if ($solicitudesCreadas == $cartItems->count()) {
                $carroController = new CarroComprasController();
                $carroController->clearCart($request);

                return response()->json([
                    'success' => true,
                    'message' => 'Solicitudes de arriendo creadas exitosamente y carrito limpiado.',
                    'solicitudes_creadas' => $solicitudesCreadas
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudieron crear todas las solicitudes.',
                    'solicitudes_creadas' => $solicitudesCreadas,
                    'total_items_carrito' => $cartItems->count()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar las solicitudes de arriendo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function approveRequest(Request $request)
    {
        try {
            $id = $request->id;
            $solicitud = solicitudesArriendo::find($id);
            
            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $solicitud->state = 'aprobada';
            
            if ($solicitud->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request approved successfully',
                    'solicitud' => $solicitud
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not update the request'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing the request',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function rejectRequest(Request $request)
    {
        try {
            $id = $request->id;
            $solicitud = solicitudesArriendo::find($id);
            
            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $solicitud->state = 'rechazada';
            
            if ($solicitud->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request rejected successfully',
                    'solicitud' => $solicitud
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not update the request'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing the request',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}