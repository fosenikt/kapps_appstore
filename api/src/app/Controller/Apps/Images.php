<?php
namespace Kapps\Controller\Apps;

use \Kapps\Model\Auth\User as AuthUser;



class Images {
	
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
     *     path="/app/images/upload",
     *     tags={"Apps"},
     *     summary="Upload images for an app",
     *     description="Uploads images for a specific app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="app_id", type="string", description="The ID of the app"),
     *                 @OA\Property(property="images", type="array", @OA\Items(type="string", format="binary"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Images uploaded successfully",
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
	public function upload()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->upload($_POST, $_FILES['images']);
	}




	/**
     * @OA\Post(
     *     path="/app/image/primary/set",
     *     tags={"Apps"},
     *     summary="Set primary image for an app",
     *     description="Sets the primary image for a specific app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", description="The ID of the app"),
     *             @OA\Property(property="image", type="string", description="The URL of the image to set as primary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Primary image set successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App or image not found"
     *     )
     * )
     */
	public function set_primary_image()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->set_primary_image($_POST['id'], $_POST['image']);
	}




	/**
     * @OA\Post(
     *     path="/app/images/delete",
     *     tags={"Apps"},
     *     summary="Delete an image from an app",
     *     description="Deletes a specific image from an app. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="app_id", type="string", description="The ID of the app"),
     *             @OA\Property(property="filename", type="string", description="The filename of the image to delete")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App or image not found"
     *     )
     * )
     */
	public function delete_image()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->delete_image($_POST['app_id'], $_POST['filename']);
	}

}