<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiController extends Controller
{

    /**
     *  @OAS\SecurityScheme(
       securityScheme="bearerAuth",
       type="http",
       scheme="bearer"
   )
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",

     *      summary="register a user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="authenticate",

     *      summary="authenticate a user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
    /**
     * @OA\Get(
     *      path="/api/logout",
     *      operationId="logout",

     *      summary="logout a user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    /**
     * @OA\Get(
     *      path="/api/stores",
     *      operationId="getAllStore",

     *      summary="Get List Of all stores",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getAllStore()
    {
        $stores = DB::table('store')->get();
        foreach ($stores as $store) {
            $ville = DB::table('ville')->where('id', $store->ville_id)->first();
            $villeName = $ville->label;
            unset($store->ville_id);
            unset($store->id);
            $store->ville = $villeName;
        }
        if (!$stores) {
            return response()->json(['data' => []], 404);
        }
        return response()->json(['data' => $stores], 200);
    }
    /**
     * @OA\Get(
     *      path="/api/cities",
     *      operationId="getCities",

     *      summary="Get List Of Cities",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getCities()
    {
        $cities = DB::table('ville')->get();
        if (!$cities) {
            return response()->json(['data' => []], 404);
        }
        return response()->json(['data' => $cities], 200);
    }
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      operationId="getCategories",

     *      summary="Get List Of Categories",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getCategories()
    {
        $categories = [
            1 => "BOUTIQUE",
            2 => "MAGASIN",
            3 => "RESTAURANT",
            4 => "ENTERTAINMENT",
        ];
        return response()->json(['data' => $categories], 200);
    }

     /**
     * @OA\Get(
     *      path="/api/store/{category_name}/category",
     *      operationId="getStoreByCategory",

     *      summary="Get List Of Stores by category name",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getStoreByCategory($category_name)
    {
        $stores = DB::table('store')->where('type', $category_name)->get();
        if(!$stores){
            return response()->json(['data' => []], 404);
        }
        foreach($stores as &$store){
            $ville = DB::table('ville')->where('id', $store->ville_id)->first();
            $villeName = $ville->label;
            unset($store->ville_id);
            unset($store->id);
            $store->ville = $villeName;
        }
        return response()->json(['data' => $stores], 200);
    }
     /**
     * @OA\Get(
     *      path="/api/store/{id}/city",
     *      operationId="getStoreByCity",

     *      summary="Get List Of stores by city id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getStoreByCity($city_id)
    {
        $city = DB::table('ville')->where('id', $city_id)->first();
        if($city){
            $stores = DB::table('store')->where('ville_id', $city_id)->get();
            if(!$stores){
                return response()->json(['data' => []], 404);
            }
            foreach($stores as &$store){
                $ville = DB::table('ville')->where('id', $store->ville_id)->first();
                $villeName = $ville->label;
                unset($store->ville_id);
                unset($store->id);
                $store->ville = $villeName;
            }
            return response()->json(['data' => $stores], 200);
        }else{

            return response()->json(['data' => "city not found"], 404);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/store/{category_name}/and/{city_id}",
     *      operationId="getStoreByCategoryAndCity",

     *      summary="Get List Of Stores by category name and city id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getStoreByCategoryAndCity($category_name, $city_id)
    {
        $stores = DB::table('store')->where('type', $category_name)->where('ville_id', $city_id)->get();
        if(!$stores){
            return response()->json(['data' => []], 404);
        }
        foreach($stores as &$store){
            $ville = DB::table('ville')->where('id', $store->ville_id)->first();
            $villeName = $ville->label;
            unset($store->ville_id);
            unset($store->id);
            $store->ville = $villeName;
        }
        return response()->json(['data' => $stores], 200);
    }
}
