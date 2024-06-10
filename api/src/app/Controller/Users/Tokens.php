<?php
namespace Kapps\Controller\Users;

use \Kapps\Model\Auth\User as AuthUser;

class Tokens {

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
     *     path="/admin/user/token/create",
     *     tags={"Users Admin"},
     *     summary="Create a token",
     *     description="Creates a token for the specified user",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", description="User ID", example=123),
     *                 @OA\Property(property="title", type="string", description="Token title", example="API Access")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="generated_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or token creation failed"
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
    public function create_token()
    {
        $obj = new \Kapps\Model\Users\Tokens();
        return $obj->create_token($_POST['user_id'], $_POST['title']);
    }

    /**
     * @OA\Get(
     *     path="/admin/user/token/get/{id}",
     *     tags={"Users Admin"},
     *     summary="Get user tokens",
     *     description="Retrieves tokens for the specified user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=123
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tokens retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="API Access"),
     *                 @OA\Property(property="time_created", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="time_expires", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="time_last_active", type="string", format="date-time", example="2023-01-15T12:00:00Z"),
     *                 @OA\Property(property="user_agent", type="string", example="Mozilla/5.0"),
     *                 @OA\Property(property="ip_address", type="string", example="192.168.1.1"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function get_tokens($id)
    {
        $obj = new \Kapps\Model\Users\Tokens();
        return $obj->get_tokens($id);
    }

    /**
     * @OA\Delete(
     *     path="/admin/user/token/delete/{id}",
     *     tags={"Users Admin"},
     *     summary="Delete a token",
     *     description="Deletes the specified token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=123
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or token deletion failed"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token not found"
     *     )
     * )
     * 
     * @OA\Get(
     *     path="/admin/user/token/delete/{id}",
     *     tags={"Users Admin"},
     *     summary="Delete a token (GET)",
     *     description="Deletes the specified token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=123
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or token deletion failed"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token not found"
     *     )
     * )
     */
    public function delete_token($id)
    {
        $obj = new \Kapps\Model\Users\Tokens();
        return $obj->delete_token($id);
    }
}
?>
