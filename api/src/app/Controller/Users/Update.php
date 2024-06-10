<?php
namespace Kapps\Controller\Users;

use \Kapps\Model\Auth\User as AuthUser;

class Update {

    private $AuthUser;

    public function __construct()
    {
        $this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }
    }

    /**
     * @OA\Post(
     *     path="/admin/user/update",
     *     tags={"Users Admin"},
     *     summary="Update a user",
     *     description="Updates a user's details. This endpoint is only available for admin.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer", description="User ID", example=123),
     *                 @OA\Property(property="firstname", type="string", description="First name of the user", example="John"),
     *                 @OA\Property(property="lastname", type="string", description="Last name of the user", example="Doe"),
     *                 @OA\Property(property="mail", type="string", description="Email address of the user", example="john.doe@example.com"),
     *                 @OA\Property(property="mobile", type="string", description="Mobile number of the user", example="+1234567890"),
     *                 @OA\Property(property="company_role", type="string", description="Role of the user in the company", example="Manager"),
     *                 @OA\Property(property="status", type="string", description="Status of the user", example="active"),
     *                 @OA\Property(property="customer_id", type="integer", description="Customer ID associated with the user", example=1),
     *                 @OA\Property(property="admin", type="boolean", description="Admin status of the user", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="id", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or user update failed"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update_user()
    {
        $obj = new \Kapps\Model\Users\Update();
        return $obj->update_user($_POST);
    }

    /**
     * @OA\Post(
     *     path="/myprofile/update",
     *     tags={"Users"},
     *     summary="Update user's profile",
     *     description="Updates the profile of the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="firstname", type="string", description="First name of the user", example="John"),
     *                 @OA\Property(property="lastname", type="string", description="Last name of the user", example="Doe"),
     *                 @OA\Property(property="mail", type="string", description="Email address of the user", example="john.doe@example.com"),
     *                 @OA\Property(property="mobile", type="string", description="Mobile number of the user", example="+1234567890"),
     *                 @OA\Property(property="company_role", type="string", description="Role of the user in the company", example="Manager")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or profile update failed"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update_profile()
    {
        $obj = new \Kapps\Model\Users\Update();
        return $obj->update_profile($_POST);
    }
}
?>
