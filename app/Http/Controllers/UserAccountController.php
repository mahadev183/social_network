<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAccountController extends Controller
{
    // Retrieve all user accounts
    public function index()
    {
        $userAccounts = UserAccount::all();
        return response()->json($userAccounts, 200);
    }

    // Create a new user account
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phoneno' => 'required|string|max:10|unique:user_accounts',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255|unique:user_accounts',
            'password' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'about' => 'nullable|string',
        ]);

        if (UserAccount::where('email', $validatedData['email'])->exists()) {
            return response()->json(['error' => 'Email already exists'], 400);
        }

        $hashedPassword = Hash::make($validatedData['password']);
        $validatedData['password'] = $hashedPassword;

        try {
            $auth = app('firebase.auth');
            $firebaseUser = $auth->createUserWithEmailAndPassword($validatedData['email'], $request->input('password'));
            $validatedData['firebase_id'] = $firebaseUser->uid;
            // $validatedData['active'] = true;

            $userAccount = UserAccount::create($validatedData);
            return response()->json($userAccount, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Firebase error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $userAccount = UserAccount::findOrFail($id);
        return response()->json($userAccount, 200);
    }

    public function update(Request $request, $id)
    {
        $userAccount = UserAccount::findOrFail($id);

        $validatedData = $request->validate([
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'phoneno' => 'sometimes|required|string|max:15|unique:user_accounts,phoneno,' . $id,
            'dob' => 'sometimes|required|date',
            'email' => 'sometimes|required|string|email|max:255|unique:user_accounts,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'gender' => 'sometimes|required|in:male,female,other',
            'about' => 'nullable|string',
        ]);

        $userAccount->update($validatedData);
        return response()->json($userAccount, 200);
    }

    public function destroy($id)
    {
        $userAccount = UserAccount::findOrFail($id);
        $userAccount->delete();
        return response()->json(null, 204);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Email and password are required',
                'details' => $validator->errors(),
            ], 400);
        }

        $userAccount = UserAccount::where('email', $request->input('email'))->first();
        $uid=$userAccount->firebase_id;
        // echo $uid;
        if (!$userAccount) {
            return response()->json(['error' => 'Email not found'], 400);
        }

        if (!Hash::check($request->input('password'), $userAccount->password)) {
            return response()->json(['error' => 'Password does not match'], 400);
        }

        try {
            $auth = app('firebase.auth');
            // $customToken=$auth->createCustomToken($uid);
            // $customTokenString = $customToken->toString();        
            $signInResult = $auth->signInWithEmailAndPassword($request->input('email'), $request->input('password'));
            $idToken = $signInResult->idToken();

            return response()->json([
                'id' => $userAccount->id,
                'email' => $userAccount->email,
                'id_token' => $idToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Firebase login error: ' . $e->getMessage()], 500);
        }
    }

    public function getUserDetails(Request $request)
    {
        $userAccount = $request->attributes->get('authenticated_user');
    
        // Now you can work with $userAccount as the authenticated user
        return response()->json([
            'id' => $userAccount->id,
            'email' => $userAccount->email,
            'first_name'=> $userAccount->firstname,
            'last_name'=> $userAccount->lastname,

        ], 200);
    }
}

