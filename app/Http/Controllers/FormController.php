<?php

namespace App\Http\Controllers;

use App\Models\AllowedDomains;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::all()->map(fn($m) => collect($m)->except(['created_at', 'updated_at']));
        return response([
            "message" => "Get all forms success",
            "forms" => $forms
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|min:5|max:500",
            "slug" => "required|unique:forms,slug|regex:/^[A-Za-z0-9.-]*$/",
            "description" => "string|min:5|max:255",
            "allowed_domains" => "array",
            "limit_one_response" => "boolean"
        ]);

        if($validator->fails()){
            return response([
                "message" => "Invalid field",
                "errors" => $validator->errors()
            ], 422);
        }


        $validated_data = $validator->validated();

        $allowed_domains = $validated_data['allowed_domains'] ?? [];

        
        $validated_data['creator_id'] = auth()->user()->id;
        $validated_data['limit_one_response'] = $validated_data['limit_one_response'] ?? false;
        
        unset($validated_data['allowed_domains']);
        
        
        $form = Form::create($validated_data);
        

        foreach($allowed_domains as $domain){
            AllowedDomains::create([
                "form_id" => $form->id,
                "domain" => $domain
            ]);
        }
        
        return response([
            "message" => "Create form success",
            "form" => $form
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // $form = Form::with('allowed_domains', 'questions')->where('slug', $slug)->get()->first();
        $form = Form::where('slug', $slug)->get()->first();

        if(!$form){
            return response([
                "message" => "Form not found",
            ], 404);
        }

        $form->questions;

        $form = collect([$form])->map(function($obj){
            return [
                ...$obj->toArray(),
                'allowed_domains' => collect($obj['allowed_domains'])->map(fn($d) => $d->domain),
            ];
        })->first();

        // $form['allowed_domains'] = collect($form->allowed_domains)->map(fn($d) => [$d->domain]) dakjelas asuuu;
        // $form['allowed_domains'] = $form->allowed_domains->map(fn($n) => $n['domain']);
        
        return response([
            "message" => "Get form success",
            "form" => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
