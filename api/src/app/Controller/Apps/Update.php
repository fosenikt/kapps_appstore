<?php
namespace Kapps\Controller\Apps;

use \Kapps\Model\Auth\User as AuthUser;

class Update {

    public function __construct()
    {
        if (!(new AuthUser())->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }
    }



	/**
     * @OA\Post(
     *     path="/app/update/all",
     *     tags={"Apps"},
     *     summary="Update app page",
     *     description="Updates most fields for the app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="string", description="The ID of the app to update"),
     *                 @OA\Property(property="title", type="string", description="Title"),
     *                 @OA\Property(property="description", type="string", description="Long the description of the app"),
     *                 @OA\Property(property="short_description", type="string", description="Short description (e.g. one line/sentence)"),
     *                 @OA\Property(property="installation", type="string", description="Installation description"),
     *                 @OA\Property(property="license_id", type="int", description="License ID"),
     *                 @OA\Property(property="tags", type="string", description="Comma seperated tags (e.g. tag1, tag2, tag3)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App description updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function update_app()
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->update_app($_POST);
	}



	/**
     * @OA\Post(
     *     path="/app/update/desc",
     *     tags={"Apps"},
     *     summary="Update app description",
     *     description="Updates the description of an app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="description", type="string", description="The new description of the app"),
     *                 @OA\Property(property="id", type="string", description="The ID of the app to update")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App description updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function update_description()
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->update_description($_POST);
	}




	/**
     * @OA\Post(
     *     path="/app/update/install",
     *     tags={"Apps"},
     *     summary="Update app installation instructions",
     *     description="Updates the installation instructions of an app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="installation", type="string", description="The new installation instructions"),
     *                 @OA\Property(property="id", type="string", description="The ID of the app to update")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App installation instructions updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function update_installation()
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->update_installation($_POST);
	}




	/**
     * @OA\Post(
     *     path="/app/update/details",
     *     tags={"Apps"},
     *     summary="Update app details",
     *     description="Updates the details of an app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", description="The new title of the app"),
     *                 @OA\Property(property="short_description", type="string", description="The new short description of the app"),
     *                 @OA\Property(property="license_id", type="string", description="The ID of the new license"),
     *                 @OA\Property(property="tags", type="string", description="The new tags for the app"),
     *                 @OA\Property(property="link_source_code", type="string", description="The new source code link"),
     *                 @OA\Property(property="id", type="string", description="The ID of the app to update")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App details updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function update_details()
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->update_details($_POST);
	}






	/**
     * @OA\Post(
     *     path="/app/publish/{id}",
     *     tags={"Apps"},
     *     summary="Publish an app",
     *     description="Publishes an app by its ID. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the app to publish",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App published successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function publish($id)
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->publish($id);
	}





	/**
     * @OA\Post(
     *     path="/app/unpublish/{id}",
     *     tags={"Apps"},
     *     summary="Unpublish an app",
     *     description="Unpublishes an app by its ID. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the app to unpublish",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App unpublished successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function unpublish($id)
	{		
		$obj = new \Kapps\Model\Apps\Update();
		return $obj->unpublish($id);
	}

}