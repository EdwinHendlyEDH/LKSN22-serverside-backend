<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use Illuminate\Http\Request;

class EnsureFormFoundAndCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $formSlug = $request->form; //this property is by default when you make resouce route if you do route:list then you will see there is a /forms/{form}/questions so ->form comes from that inside curly braces to get anything after the /forms and before the /questions 
        
        $form = Form::where('slug', $formSlug)->get()->first();

        if(!$form){
            return response([
                "message" => "Form not found",
            ], 404);
        }else if($form->creator_id !== auth()->user()->id){
            return response([
                "message" => "Forbidden access"
            ], 403);
        }

        return $next($request);
    }
}
