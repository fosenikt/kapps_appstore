<?php
namespace Kapps\Controller\Apps;

use \Kapps\Model\Auth\User as AuthUser;


/**
 * @OA\Schema(
 *   schema="AppPublic",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="1"),
 *   @OA\Property(property="title", type="string", example="Helpdesk"),
 *   @OA\Property(property="short_description", type="string", example="Web-basert helpdesk"),
 *   @OA\Property(property="primary_image", type="object",
 *     @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
 *     @OA\Property(property="thumb", type="string", example="http://example.com/thumb.jpg")
 *   ),
 *   @OA\Property(property="type", type="object",
 *     @OA\Property(property="id", type="string", example="1"),
 *     @OA\Property(property="title", type="string", example="Support"),
 *     @OA\Property(property="icon", type="string", example="support_icon")
 *   ),
 *   @OA\Property(property="tags", type="object",
 *     @OA\Property(property="array", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="string", type="string", example="tag1,tag2")
 *   ),
 *   @OA\Property(property="status", type="string", example="published")
 * )
 * 
 * @OA\Schema(
 *   schema="AppFull",
 *   type="object",
 *   allOf={@OA\Schema(ref="#/components/schemas/AppPublic")},
 *   @OA\Property(property="description", type="string", example="Denne helpdesken..."),
 *   @OA\Property(property="time_created", type="string", format="date-time", example="2024-05-21T14:56:29Z"),
 *   @OA\Property(property="time_edited", type="string", format="date-time", example="2024-05-21T14:56:29Z"),
 *   @OA\Property(property="created_by", type="object",
 *     @OA\Property(property="id", type="string", example="1"),
 *     @OA\Property(property="firstname", type="string", example="John"),
 *     @OA\Property(property="lastname", type="string", example="Doe"),
 *     @OA\Property(property="mail", type="string", example="john.doe@example.com")
 *   ),
 *   @OA\Property(property="updated_by", type="object",
 *     @OA\Property(property="id", type="string", example="2"),
 *     @OA\Property(property="firstname", type="string", example="Jane"),
 *     @OA\Property(property="lastname", type="string", example="Smith"),
 *     @OA\Property(property="mail", type="string", example="jane.smith@example.com")
 *   ),
 *   @OA\Property(property="company", type="object",
 *     @OA\Property(property="id", type="string", example="1"),
 *     @OA\Property(property="name", type="string", example="Acme Corp")
 *   ),
 *   @OA\Property(property="installation", type="string", example="Installation instructions..."),
 *   @OA\Property(property="delivery_id", type="string", example="1"),
 *   @OA\Property(property="license", type="object",
 *     @OA\Property(property="id", type="string", example="1"),
 *     @OA\Property(property="title", type="string", example="MIT License"),
 *     @OA\Property(property="link", type="string", example="http://example.com/license")
 *   ),
 *   @OA\Property(property="link_source_code", type="string", example="http://example.com/source_code"),
 *   @OA\Property(property="files", type="array", @OA\Items(type="object")),
 *   @OA\Property(property="edit_access", type="boolean", example=true)
 * )
 */


class Get {

	private $AuthUser;



	/**
     * @OA\Get(
     *     path="/app/get/{id}",
     *     tags={"Apps"},
     *     summary="Get single app",
     *     description="Retrieves single published app or draft if owner",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the app to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 oneOf={
     *                     @OA\Schema(ref="#/components/schemas/AppPublic"),
     *                     @OA\Schema(ref="#/components/schemas/AppFull")
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apps not found"
     *     )
     * )
     */
	public function get_app($id)
	{
		$obj = new \Kapps\Model\Apps\Get();
		return $obj->get_app($id);
	}




	/**
     * @OA\Get(
     *     path="/apps/get",
     *     tags={"Apps"},
     *     summary="Get all apps",
     *     description="Retrieves all apps.",
	 *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="App retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 oneOf={
     *                     @OA\Schema(ref="#/components/schemas/AppPublic"),
     *                     @OA\Schema(ref="#/components/schemas/AppFull")
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apps not found"
     *     )
     * )
     */
	public function get_apps()
	{
		$obj = new \Kapps\Model\Apps\Get();
		return $obj->get_apps($_GET);
	}




	/**
     * @OA\Get(
     *     path="/company/app/{id}",
     *     tags={"Apps"},
     *     summary="Get company app",
     *     description="Retrieves a single app by its ID for a company. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the app to retrieve",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="App retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AppFull")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="App not found"
     *     )
     * )
     */
	public function get_company_app($id)
	{
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }

		$obj = new \Kapps\Model\Apps\Get();
		return $obj->get_company_app($id);
	}




	/**
     * @OA\Get(
     *     path="/company/apps",
     *     tags={"Apps"},
     *     summary="Get company apps",
     *     description="Retrieves all apps for a company. Requires user to be logged in with a bearer token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AppFull")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apps not found"
     *     )
     * )
     */
	public function get_company_apps()
	{
		$this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }
		
		$obj = new \Kapps\Model\Apps\Get();
		return $obj->get_company_apps();
	}





	/**
     * @OA\Get(
     *     path="/company/published/apps/{id}",
     *     tags={"Apps"},
     *     summary="Get published company apps",
     *     description="Retrieves all published apps for a company.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the company",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Published apps retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AppFull")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apps not found"
     *     )
     * )
     */
	public function get_company_published_apps($id)
	{		
		$obj = new \Kapps\Model\Apps\Get();
		return $obj->get_company_published_apps($id);
	}

}