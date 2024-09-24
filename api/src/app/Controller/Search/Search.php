<?php
namespace Kapps\Controller\Search;

use \Kapps\Model\Auth\User as AuthUser;

class Search {

    private $AuthUser;

    /**
     * @OA\Post(
     *     path="/search/all",
     *     tags={"Search"},
     *     summary="Search all",
     *     description="Searches for both apps and companies with the given query",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="q", type="string", example="search query")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="apps", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="App Title"),
     *                     @OA\Property(property="short_description", type="string", example="Short description of the app"),
     *                     @OA\Property(property="primary_image", type="object",
     *                         @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
     *                         @OA\Property(property="thumb", type="string", example="http://example.com/thumb.jpg")
     *                     ),
     *                     @OA\Property(property="company", type="object",
     *                         @OA\Property(property="public_id", type="string", example="companyId"),
     *                         @OA\Property(property="name", type="string", example="Company Name"),
     *                         @OA\Property(property="logo", type="object",
     *                             @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                             @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="companies", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="public_id", type="string", example="companyId"),
     *                     @OA\Property(property="title", type="string", example="Company Title"),
     *                     @OA\Property(property="county", type="string", example="County Name"),
     *                     @OA\Property(property="type_id", type="integer", example=1),
     *                     @OA\Property(property="org_numb", type="string", example="123456789"),
     *                     @OA\Property(property="website", type="string", example="http://www.company.com"),
     *                     @OA\Property(property="domain", type="string", example="company.com"),
     *                     @OA\Property(property="type", type="string", example="LLC"),
     *                     @OA\Property(property="logo", type="object",
     *                         @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                         @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function all()
    {
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }

        $obj = new \Kapps\Model\Search\Search();
        return $obj->all($_POST['q']);
    }

    /**
     * @OA\Post(
     *     path="/search/apps",
     *     tags={"Search"},
     *     summary="Search apps",
     *     description="Searches for apps with the given query",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="q", type="string", example="search query")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="App Title"),
     *                 @OA\Property(property="short_description", type="string", example="Short description of the app"),
     *                 @OA\Property(property="primary_image", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/thumb.jpg")
     *                 ),
     *                 @OA\Property(property="company", type="object",
     *                     @OA\Property(property="public_id", type="string", example="companyId"),
     *                     @OA\Property(property="name", type="string", example="Company Name"),
     *                     @OA\Property(property="logo", type="object",
     *                         @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                         @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function apps()
    {
        $obj = new \Kapps\Model\Search\Search();
        return $obj->apps($_POST['q']);
    }

    /**
     * @OA\Post(
     *     path="/search/companies",
     *     tags={"Search"},
     *     summary="Search companies",
     *     description="Searches for companies with the given query",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="q", type="string", example="search query")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="companyId"),
     *                 @OA\Property(property="title", type="string", example="Company Title"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.company.com"),
     *                 @OA\Property(property="domain", type="string", example="company.com"),
     *                 @OA\Property(property="type", type="string", example="LLC"),
     *                 @OA\Property(property="logo", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function companies()
    {
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }

        $obj = new \Kapps\Model\Search\Search();
        return $obj->companies($_POST['q']);
    }

    /**
     * @OA\Get(
     *     path="/companies/search/{q}",
     *     tags={"Search"},
     *     summary="Search companies (GET)",
     *     description="Searches for companies with the given query",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="search query"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="companyId"),
     *                 @OA\Property(property="title", type="string", example="Company Title"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.company.com"),
     *                 @OA\Property(property="domain", type="string", example="company.com"),
     *                 @OA\Property(property="type", type="string", example="LLC"),
     *                 @OA\Property(property="logo", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function companies_get($q)
    {
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }

        $obj = new \Kapps\Model\Search\Search();
        return $obj->companies($q);
    }




	/**
     * @OA\Get(
     *     path="/search/users/{q}",
     *     tags={"Search"},
     *     summary="Search users (GET)",
     *     description="Searches for companies with the given query",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="search query"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="public_id", type="string", example="companyId"),
     *                 @OA\Property(property="title", type="string", example="Company Title"),
     *                 @OA\Property(property="county", type="string", example="County Name"),
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="org_numb", type="string", example="123456789"),
     *                 @OA\Property(property="website", type="string", example="http://www.company.com"),
     *                 @OA\Property(property="domain", type="string", example="company.com"),
     *                 @OA\Property(property="type", type="string", example="LLC"),
     *                 @OA\Property(property="logo", type="object",
     *                     @OA\Property(property="image", type="string", example="http://example.com/company_logo.jpg"),
     *                     @OA\Property(property="thumb", type="string", example="http://example.com/company_logo_thumb.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function users($q)
    {
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }

        $obj = new \Kapps\Model\Search\Search();
        return $obj->users($q);
    }
}
?>
