<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all patients$patients
        
        try {
            $order = $request->order ?? 'asc';
            $sort = $request->sort ?? 'name';
            $pageSize = $request->page_size ?? 5;
            $pageNumber = $request->page_number ?? 1;
            $offset = ($pageNumber - 1) * $pageSize;

            $patients = Patient::query();

            // Filter
            if ($request->has('name')) {
                $patients->where('name', $request->name);
            }

            if ($request->has('address')) {
                $patients->where('address', $request->address);
            }

            if ($request->has('status')) {
                $patients->where('status', $request->status);
            }   

            // Sort
            $patients->orderBy($sort, $order);

            $totalData = $patients->count();
            $totalPage = ceil($totalData / $pageSize);

            $patients = $patients->offset($offset)->limit($pageSize)->get();

            $pages = [
                'pageSize' => (int) $pageSize,
                'pageNumber' => (int) $pageNumber,
                'totalData' => $totalData,
                'totalPage' => $totalPage,
            ];

            $data = [
                'pages' => $pages,
                'table' => $patients,
            ];

            $message = count($patients) > 0 ? 'get all patient' : 'data not found';

            return response()->json(['message' => $message, 'data' => $data], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'error', 'error' => $th], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::find($id);
        $input = [
            'name' => $request->name ?? $patient->name,
            'phone' => $request->phone ?? $patient->phone,
            'address' => $request->address ?? $patient->address,
            'status' => $request->status ?? $patient->status,
            'in_date' => $request->in_date_at ?? $patient->in_date,
            'out_date' => $request->out_date_at ?? $patient->out_date
        ];
        $patient->update($input);
        $data = [
            'message' => 'patients is Update successfully',
            'data' => $patient
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::find($id);
        $patient->delete();

        $data = [
            'message' => 'patients is Delete successfully',
            'data' => $patient
        ];

        return response()->json($data, 200);
    }
}
