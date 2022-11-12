<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Form;
use Illuminate\Http\Request;

class EnsureAllowedDomain
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
        $form = Form::where('slug', $request->form)->get()->first();

        $domains = $form->allowed_domains->map(fn($d) => $d->domain)->toArray();
        $user_request_domain = $request->user()->email;
        $domain_post = strpos($user_request_domain, '@');
        $user_request_domain = substr($user_request_domain, $domain_post + 1);


        if($form->creator_id === auth()->user()->id){
            return $next($request);
        }   

        if( count($domains) && !in_array($user_request_domain, $domains)){
            return response([
                "message" => "Forbidden Access"
            ], 403);
        }
        return $next($request);
    }
}
