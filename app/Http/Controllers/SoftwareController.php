<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SoftwareController extends Controller
{
    public function dashboard(Request $request)
    {
        $softwares = DB::table('softwares')->get();
        $selectedSoftware = $request->get('software', $softwares->first()->name ?? null);
        
        return view('index', compact('softwares', 'selectedSoftware'));
    }

    public function index()
    {
        $softwares = DB::table('softwares')->get();
        return view('index', compact('softwares'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:softwares,name',
            'type' => 'required|string|max:255',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try
        {
            DB::table('softwares')->insert([
                'name' => $request->name,
                'type' => $request->type,
                'url' => $request->url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return redirect()->route('software.manage')->with('success', 'Software added successfully!');
        }
        catch(Exception $error)
        {
            return redirect()->route('software.manage')->with('error','Something went wrong!');
        }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:softwares,name,' . $id,
            'url' => 'nullable|url',
        ]);

        // dd($request->name);
        if ($validator->fails()) 
        {
            return response()->json([
                'success' => 422,
                'message' => $validator->errors()->first(),
                'data' => ''
            ]);
        }

        $software = DB::table('softwares')->where('id', $id)->first();

        if (!$software) 
        {
            return response()->json([
                'success' => 404,
                'message' => 'Software not found',
                'data' => ''
            ]);
        }

        try 
        {
            DB::table('softwares')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'url' => $request->url,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => 200,
                'message' => 'Software updated successfully',
                'data' => ''
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'success' => 500,
                'message' => 'Failed to update software: ' . $e->getMessage(),
                'data' => ''
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $software = DB::table('softwares')->where('id', $id)->first();

        if (!$software) 
        {
            return response()->json([
                'success' => 404,
                'message' => 'Software not found',
                'data' => ''
            ]);
        }

        try 
        {
            DB::table('softwares')->where('id', $id)->delete();

            return response()->json([
                'success' => 200,
                'message' => 'Software deleted successfully',
                'data' => ''
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'success' => 500,
                'message' => 'Failed to delete software'. $e->getMessage(),
                'data' => ''
            ]);
        }
    }
}