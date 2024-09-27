<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function searchUser(Request $request){
        $userSearch = $request->get("user");
        $users = User::where('name', 'LIKE', "%$userSearch%")
            ->orWhere('email', 'LIKE', "%$userSearch%")
            ->get();

        return view("welcome", ["the_users" => $users]);
    }
    public function viewUserProfile(Request $request, $userMail = null){
        if (auth()->user()){
            $the_user = $request->user();
            if($userMail) {
                $the_user = User::where("email", $userMail)->first();
                if (!$the_user){
                    throw new NotFoundHttpException();
                }
            }
            if (!$the_user->isPublic && $the_user !== auth()->user()){
                throw new NotFoundHttpException();
            }

            return view("dashboard", ["user" => $the_user]);
        } else {
            return redirect()->route("login");
        }
    }

    public function switchPrivatePublic(Request $request){
        $current = $request->user()->isPublic;
        $request->user()->isPublic = !$current;
        $request->user()->save();

        return redirect()->back();
    }
}
