<?php
namespace Kapps\Controller\Companies;

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
     *     path="/companies",
     *     tags={"Companies"},
     *     summary="Get all companies",
     *     description="Retrieves a list of all companies",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="8charId"),
     *                 @OA\Property(property="title", type="string", example="Example Company"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.example.com"),
     *                 @OA\Property(property="domain", type="string", example="example.com"),
     *                 @OA\Property(property="type", type="string", example="LLC"),
     *                 @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_companies()
    {
        $obj = new \Kapps\Model\Companies\Get();
        return $obj->get_companies();
    }

    /**
     * @OA\Get(
     *     path="/companies/simple",
     *     tags={"Companies"},
     *     summary="Get all companies (simple list)",
     *     description="Retrieves a simple list of all companies",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Example Company")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_companies_simple()
    {
        $obj = new \Kapps\Model\Companies\Get();
        return $obj->get_companies_simple();
    }

    /**
     * @OA\Get(
     *     path="/company/get/{id}",
     *     tags={"Companies"},
     *     summary="Get a company by ID",
     *     description="Retrieves a company by its ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="8charId"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="public_id", type="string", example="8charId"),
     *             @OA\Property(property="title", type="string", example="Example Company"),
     *             @OA\Property(property="county", type="string", example="County Name"),
     *             @OA\Property(property="type_id", type="integer", example=1),
     *             @OA\Property(property="org_numb", type="string", example="123456789"),
     *             @OA\Property(property="website", type="string", example="http://www.example.com"),
     *             @OA\Property(property="domain", type="string", example="example.com"),
     *             @OA\Property(property="type", type="string", example="LLC"),
     *             @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
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
    public function get_company($id)
    {
        $obj = new \Kapps\Model\Companies\Get();
        return $obj->get_company($id);
    }

    /**
     * @OA\Get(
     *     path="/companies/counties",
     *     tags={"Companies"},
     *     summary="Get all counties",
     *     description="Retrieves a list of all counties",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Counties retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="8charId"),
     *                 @OA\Property(property="title", type="string", example="County Name"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.example.com"),
     *                 @OA\Property(property="domain", type="string", example="example.com"),
     *                 @OA\Property(property="type", type="string", example="County"),
     *                 @OA\Property(property="logo", type="string", example="http://example.com/logo.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function get_counties()
    {
        $obj = new \Kapps\Model\Companies\Get();
        return $obj->get_counties();
    }
}
?>
