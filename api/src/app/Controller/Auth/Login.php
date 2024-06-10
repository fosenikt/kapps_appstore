<?php
namespace Kapps\Controller\Auth;

class Login {

    /**
     * @OA\Post(
     *     path="/auth/send_login_link",
     *     tags={"Auth"},
     *     summary="Send login link",
     *     description="Sends a login link to the specified email address",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mail",
     *                     type="string",
     *                     format="email",
     *                     example="user@example.com"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function send_login_link()
    {
        $obj = new \Kapps\Model\Auth\Login();
        return $obj->send_login_link($_POST['mail']);
    }

    /**
     * @OA\Post(
     *     path="/auth/validate_code",
     *     tags={"Auth"},
     *     summary="Validate code",
     *     description="Validates the login code sent to the user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mail",
     *                     type="string",
     *                     format="email",
     *                     example="user@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string",
     *                     example="123456"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code validated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid code or email"
     *     )
     * )
     */
    public function validate_code()
    {
        $obj = new \Kapps\Model\Auth\Login();
        return $obj->validate_code($_POST['mail'], $_POST['code']);
    }

    /**
     * @OA\Post(
     *     path="/auth/validate_hash",
     *     tags={"Auth"},
     *     summary="Validate hash",
     *     description="Validates the login hash for the user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="string",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="hash",
     *                     type="string",
     *                     example="abc123"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hash validated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid user ID or hash"
     *     )
     * )
     */
    public function validate_hash()
    {
        $obj = new \Kapps\Model\Auth\Login();
        return $obj->validate_hash($_POST['user_id'], $_POST['hash']);
    }
}
?>
