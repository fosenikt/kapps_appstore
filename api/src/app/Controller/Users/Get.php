<?php
namespace Kapps\Controller\Users;

use \Kapps\Model\Auth\User as AuthUser;


class Get {

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
     * @OA\Get(
     *     path="/users/get",
     *     tags={"Users Admin"},
     *     summary="Get all users",
     *     description="Retrieves a list of all users. This endpoint is only available for admin.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="o365_id", type="string", example="o365id"),
     *                 @OA\Property(property="customer", type="object",
     *                     @OA\Property(property="public_id", type="string", example="publicId"),
     *                     @OA\Property(property="domain", type="string", example="example.com"),
     *                     @OA\Property(property="title", type="string", example="Company Title"),
     *                     @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
     *                 ),
     *                 @OA\Property(property="firstname", type="string", example="John"),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="initials", type="string", example="JD"),
	 *                 @OA\Property(property="displayname", type="string", example="John Doe"),
     *                 @OA\Property(property="mail", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="mobile", type="string", example="+1234567890"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="photo", type="string", example="http://example.com/photo.jpg"),
     *                 @OA\Property(property="company_role", type="string", example="Manager"),
     *                 @OA\Property(property="last_update", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="system_user", type="boolean", example=true),
     *                 @OA\Property(property="admin", type="boolean", example=true),
     *                 @OA\Property(property="color", type="string", example="#FFFFFF")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_users()
    {
        $obj = new \Kapps\Model\Users\Get();
        return $obj->get_users();
    }

    /**
     * @OA\Get(
     *     path="/user/get/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Retrieves a user by their ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="customer", type="object",
     *                 @OA\Property(property="public_id", type="string", example="publicId"),
     *                 @OA\Property(property="domain", type="string", example="example.com"),
     *                 @OA\Property(property="title", type="string", example="Company Title"),
     *                 @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
     *             ),
     *             @OA\Property(property="firstname", type="string", example="John"),
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="initials", type="string", example="JD"),
	 * 	           @OA\Property(property="displayname", type="string", example="John Doe"),
     *             @OA\Property(property="mail", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="mobile", type="string", example="+1234567890"),
     *             @OA\Property(property="company_role", type="string", example="Manager"),
     *             @OA\Property(property="photo", type="string", example="http://example.com/photo.jpg"),
     *             @OA\Property(property="last_update", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *             @OA\Property(property="color", type="string", example="#FFFFFF"),
     *             @OA\Property(property="admin", type="boolean", example=true),
     *             @OA\Property(property="status", type="string", example="active")
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
    public function get_user($id)
    {
        $obj = new \Kapps\Model\Users\Get();
        return $obj->get_user($id);
    }

    /**
     * @OA\Get(
     *     path="/users/company/get/{id}",
     *     tags={"Users"},
     *     summary="Get users by company ID",
     *     description="Retrieves a list of users for the specified company ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="companyId"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="firstname", type="string", example="John"),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="initials", type="string", example="JD"),
     *                 @OA\Property(property="displayname", type="string", example="John Doe"),
     *                 @OA\Property(property="mail", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="mobile", type="string", example="+1234567890"),
     *                 @OA\Property(property="photo", type="string", example="http://example.com/photo.jpg"),
     *                 @OA\Property(property="company_role", type="string", example="Manager"),
     *                 @OA\Property(property="color", type="string", example="#FFFFFF")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_company_users($id)
    {
        $obj = new \Kapps\Model\Users\Get();
        return $obj->get_company_users($id);
    }
	

	/* public function get_my_profile()
	{
		$obj = new \Kapps\Model\Users\Get();
		return $obj->get_my_profile();
	} */

}