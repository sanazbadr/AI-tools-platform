   <?php

   namespace App\Http\Middleware;

   use Closure;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Session;

   class SingleSession
   {
       public function handle($request, Closure $next)
       {
           if (Auth::check()) {
               $currentSessionId = Session::getId();
               $user = Auth::user();

               if ($user->session_id && $user->session_id !== $currentSessionId) {
                   Auth::logout();
                   return redirect('/login')->withErrors(['Your session has expired.']);
               }

               $user->session_id = $currentSessionId;
               $user->save();
           }

           return $next($request);
       }
   }