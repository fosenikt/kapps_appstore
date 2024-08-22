<?php
namespace Kapps\Controller\Apps;

use \Kapps\Model\Auth\User as AuthUser;

class Create {

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
     *     path="/app/new",
     *     tags={"Apps"},
     *     summary="Add a new app",
     *     description="Adds a new app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="type_id", type="string", description="The ID of the app type (See /types/get)"),
     *                 @OA\Property(property="title", type="string", description="The title of the app"),
     *                 @OA\Property(property="description", type="string", description="The description of the app"),
     *                 @OA\Property(property="installation", type="string", description="The installation instructions"),
     *                 @OA\Property(property="license_id", type="string", description="The ID of the license (See /licenses/get)"),
     *                 @OA\Property(property="link_source_code", type="string", description="The link to the source code")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="app_id", type="integer", example=45)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not added"
     *     )
     * )
     */
	public function add()
	{		
		$obj = new \Kapps\Model\Apps\Create();
		return $obj->add($_POST);
	}

}