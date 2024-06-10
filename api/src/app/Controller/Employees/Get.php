<?php
namespace Kapps\Controller\Employees;

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
     *     path="/company/employees/{company_id}",
     *     tags={"Employees"},
     *     summary="Get employees by company ID",
     *     description="Retrieves a list of employees for the specified company ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="company_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="12345"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employees retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="firstname", type="string", example="John"),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="initials", type="string", example="JD"),
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function get_employees($company_id)
    {
        $obj = new \Kapps\Model\Employees\Get();
        return $obj->get_employees($company_id);
    }
}
?>
