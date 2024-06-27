<?php
namespace Kapps\Controller\Auth;

class User {

    /**
     * @OA\Get(
     *     path="/user/me",
     *     tags={"Auth"},
     *     summary="Get current user",
     *     description="Retrieves the current logged-in user's information",
	 *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", example="1"),
     *             @OA\Property(property="mail", type="string", example="user@example.com"),
     *             @OA\Property(property="customer", type="object",
     *                 @OA\Property(property="id", type="string", example="10"),
     *                 @OA\Property(property="name", type="string", example="Company Name")
     *             ),
     *             @OA\Property(property="firstname", type="string", example="John"),
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="initials", type="string", example="JD"),
     *             @OA\Property(property="mobile", type="string", example="+1234567890"),
     *             @OA\Property(property="company_role", type="string", example="Manager"),
     *             @OA\Property(property="admin", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function me()
    {
        $obj = new \Kapps\Model\Auth\User();
        return $obj->me();
    }

    /**
     * @OA\Post(
     *     path="/auth/login/signout",
     *     tags={"Auth"},
     *     summary="Sign out user",
     *     description="Signs out the current logged-in user",
	 *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User signed out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function signout()
    {
        $obj = new \Kapps\Model\Auth\User();
        return $obj->signout();
    }
}
?>
