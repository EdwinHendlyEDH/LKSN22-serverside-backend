<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    public function index($formSlug){
        $form = Form::where('slug', $formSlug)->get()->first();

        return response([
            "message" => "Get responses success", 
            "responses" => Response::with('user', 'answers')->where('form_id', $form->id)->get()
        ]);
    }


    public function store($formSlug, Request $request){
        $validator = Validator::make($request->all(), [
            "answers" => 'required|array',
        ]);
        
        if($validator->fails()){
            return response([
                "message" => "Invalid field",
                "error_type" => "error-fields",
                "errors" => $validator->errors()
            ], 422);
        }
        
        
        $form = Form::where('slug', $formSlug)->get()->first();
        // check if user already response before
        $user_id = request()->user()->id;
        $response_exist = Response::where('user_id', $user_id)->where('form_id', $form->id)->get()->first();
        $limit_one_response = $form->limit_one_response;

        if($limit_one_response && $response_exist){
            return response([
                "message" => "You can not submit form twice",
                "error_type" => "error-submit-twice",
            ], 422);
        }

        $answers = $validator->validated()['answers'];
        $qModels = Question::all();

        // check questions value required/not
        $required_questions_id = [];
        foreach($answers as $a){
            $question = $qModels->where('id', $a['question_id'])->first();

            if($question->is_required){
                if(!$a['value']){
                    $required_questions_id[] = $a['question_id'];
                }
            }

        }

        if(count($required_questions_id) > 0){
            return response([
                "message" => "This question is required",
                "error_type" => "error-required",
                "required_question_id" => $required_questions_id
            ], 422);
        }

        $response = Response::create([
            "form_id" => $form->id,
            "user_id" => $user_id,
            "date" => now()
        ]);

        foreach($answers as $a){
            Answer::create([
                "response_id" => $response->id,
                ...$a
            ]);
        }

        return response([
            "message" => "Submit response success",
        ]);




    }

    public function show($slug, $id){

        $response = Response::find($id);
        $form = Form::where('slug', $slug)->get()->first();
        
        if(!$response){
            return response([
                "message" => "Response not found",
            ], 404);
        }

        return response([
            "message" => "Get response success",
            "form" => $form,
            "response" => $response->load('answers', 'user')
        ]);
    }

    public function checkAllowed(){
        return response([
            "message" => "You are allowed"
        ]);
    }

}
