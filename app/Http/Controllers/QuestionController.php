<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {   
        return [
            "message" => "Get all $slug form questions success",
            "questions" => Form::where('slug', $slug)->get()->first()->questions
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug, Request $request)
    {
        $form = Form::where('slug', $slug)->get()->first();

        $rules = [
            'name' => 'required|min:3',
            'choice_type' => ["required", "string", Rule::in('multiple choices', 'dropdown', 'checkboxes', 'paragraph', 'short answer', 'date')],
            "choices" => 'array',
            "is_required" => "boolean"
        ];

        $choicesReq = ['multiple choices', 'dropdown', 'checkboxes'];
        if(in_array(request()->choice_type, $choicesReq)){
            $rules['choices'] = $rules['choices'] . '|required|min:2';
        }


        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response([
                'message' => "Invalid field",
                "errors" => $validator->errors()
            ], 422);
        }

        
        $validated_data = $validator->validated();

        if(isset($validated_data['choices'])){
            $validated_data['choices'] = implode(',',$validated_data['choices']);
        }

        $validated_data['is_required'] = $validated_data['is_required'] ?? false;

        $question = Question::create([
            ...$validated_data,
            "form_id" => $form->id,
        ]);

        return response([
            "message" => "Add question success",
            "question" => $question
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($formSlug, $id)
    {
        $question = Question::find($id);

        if(!$question){
            return response([
                "message" => "Question not found",
            ],404);
        }
        return response([
            "message" => "Get question success",
            "question" => $question
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($slug, Request $request, $id)
    {

        $rules = [
            'name' => 'required|min:3',
            'choice_type' => ["required", "string", Rule::in('multiple choices', 'dropdown', 'checkboxes', 'paragraph', 'short answer', 'date')],
            "choices" => 'array',
            "is_required" => "boolean"
        ];

        $choicesReq = ['multiple choices', 'dropdown', 'checkboxes'];
        if(in_array(request()->choice_type, $choicesReq)){
            $rules['choices'] = $rules['choices'] . '|required';
        }


        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response([
                'message' => "Invalid field",
                "errors" => $validator->errors()
            ], 422);
        }

        $validated_data = $validator->validated();

        if(isset($validated_data['choices'])){
            $validated_data['choices'] = implode(',',$validated_data['choices']);
        }

        $validated_data['is_required'] = $validated_data['is_required'] ?? false;

        $question = Question::find($id);

        if(!$question){
            return [
                "message" => "Question not found",
            ];
        }

        $question->update($validated_data);
        $question = Question::find($id);
        return [
            "message" => "Update question success",
            "question" => $question
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($formSlug, $id)
    {
        $form = Form::where('slug',  $formSlug)->get()->first();
        $question = Question::find($id);

        if($form->id !== $question->form_id){
            return [
                "message" => "Question not found",
            ];
        }

        $question->delete();
        return [
            'message' => 'Remove question success'
        ];
    }

    public function destroyAll($formSlug){
        $formId = Form::where('slug', $formSlug)->get()->first()->id;
        Question::where('form_id', $formId)->delete();

        return response([
            "message" => "All question deleted successfully"
        ]);
    }
}
